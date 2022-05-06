<?php

namespace App\Console\Commands\AMS\Campaign\SD;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Campaign\SD\SdCampaignReportId;
use App\Model\Ams\Profile;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class getReportIdCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getcampaignreportid:sdcampaign {daysBack=1} {recoverBackFromDate=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get Campaign SD ReportId.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("filePath:Commands\Ams\Campaign\SD\getReportIdCron. Start Cron.");
        Log::info($this->description);
        $daysBack = $this->argument('daysBack');

        $recoverBack = $this->argument('recoverBackFromDate');
        $recoverBackFromDate = ($recoverBack == 0) ? time() : $recoverBack;

        setMemoryLimitAndExeTime();

        $profileList = Profile::with(['getTokenDetail', 'getAllSdCampaignReportId', 'getAllSdCampaignReportLink'])
            ->where('is_active', '1')
            ->where('is_sandbox_profile', '0')
            ->where('type', '<>', 'agency')
            ->where('country_code', '<>', 'MX')
            ->get();

        $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.SDCampaignReport');

        foreach ($profileList as $profile) {
            $daysBackForProfile = $daysBack;
            if ($profile->sponsored_display_campaigns_sixty_days == 0 && $recoverBack == 0) {
                $daysBack = 60;
                $profile->sponsored_display_campaigns_sixty_days = 1;
                $profile->save();
            }
            $reportDays = array();
            for ($i = $daysBackForProfile; $i >= 1; $i--) {
                array_push($reportDays, date('Ymd', strtotime('-' . $i . ' day', $recoverBackFromDate)));
            }
            if ($daysBackForProfile == 1 && $recoverBack == 0) {
                array_push($reportDays, date('Ymd', strtotime('-' . 14 . ' day', $recoverBackFromDate)));
            }
            for ($i = 0; $i < sizeof($reportDays); $i++) {
                $reportDateSixtyDays = $reportDays[$i];
                $existReportId = $profile->getAllSdCampaignReportId->where('report_date', $reportDateSixtyDays)->first();
                $existReportLink = $profile->getAllSdCampaignReportLink->where('report_date', $reportDateSixtyDays)->where('is_done', 3)->first();
                if (!isset($existReportId) || isset($existReportLink)) {
                    $reTry = 3;
                    a:
                    if ($reTry > 0) {
                        $reTry--;
                        $client = new Client();
                        $profileInfo = Profile::where('profile_id', $profile->profile_id)->get()->first();
                        if ($profileInfo->is_active == 1) {
                            try {
                                $token = AuthToken::find($profile->fk_access_token);
                                // get account id from
                                $response = $client->request('POST', $url, [
                                    'headers' => [
                                        'Authorization' => 'Bearer ' . $token->access_token,
                                        'Content-Type' => 'application/json',
                                        'Amazon-Advertising-API-ClientId' => $token->client_id,
                                        'Amazon-Advertising-API-Scope' => $profile->profile_id
                                    ],
                                    'json' => [
                                        'tactic' => 'T00020',
                                        'reportDate' => $reportDateSixtyDays,
                                        'metrics' => Config::get('amsconstants.sdCampaignMetrics'),
                                    ],
                                    'delay' => Config::get('amsconstants.delayTimeInApi'),
                                    'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                                    'timeout' => Config::get('amsconstants.timeoutInApi'),
                                ]);

                                $body = json_decode($response->getBody()->getContents());
                                $DataArray = array();
                                if (!empty($body) && $body != null) {
                                    $storeArray = [];
                                    $storeArray['fk_access_token'] = $profile->getTokenDetail->id;
                                    $storeArray['fk_profile_id'] = $profile->id;
                                    $storeArray['profile_id'] = $profile->profile_id;
                                    $storeArray['report_id'] = $body->reportId;
                                    $storeArray['record_type'] = $body->recordType;
                                    $storeArray['status'] = $body->status;
                                    $storeArray['status_details'] = $body->statusDetails;
                                    $storeArray['report_date'] = $reportDateSixtyDays;
                                    $storeArray['creation_date'] = date('Y-m-d');
                                    array_push($DataArray, $storeArray);
                                    // store report status
                                    Tracker::insertTrackRecord('Report name : Campaign SD Report Id' . ', Profile id: ' . $profile->id . ', Report Date: ' . $reportDateSixtyDays, 'record found');
                                } else {
                                    // store report status
                                    Tracker::insertTrackRecord('Report name : Campaign SD Report Id' . ', Profile id: ' . $profile->id . ', Report Date: ' . $reportDateSixtyDays, 'not record found');
                                } //end else
                                if (!empty($DataArray)) {
                                    $reportId = new SdCampaignReportId();
                                    $reportId->addReportId($DataArray);
                                } // end if
                            } catch (\Exception $ex) {
                                if ($ex->getCode() == 401) {
                                    if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                        Log::error('Not authorized to access this advertiser');
                                    } else if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                        Log::error('Refresh Access token. In file filePath:Commands\Ams\Campaign\SD\getReportIdCron');
                                        Artisan::call('updateGetAccessToken:amsAuth ' . $profile->fk_access_token);
                                        Artisan::call('getprofileid:updateamsprofile ' . $profile->profile_id);
                                        goto a;
                                    } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                        // store profile list not valid
                                        Log::info("Invalid Profile Id: " . $profile->profile_id);
                                    }
                                } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                    sleep(Config::get('amsconstants.sleepTime') + 2);
                                    goto a;
                                } else if ($ex->getCode() == 502) {
                                    sleep(Config::get('amsconstants.sleepTime') + 2);
                                    goto a;
                                }
                                // store report status
                                Log::error($ex->getMessage());
                            }// end catch
                        } else {
                            Log::info('Profile ' . $profile->profile_id . ' is Inactive');
                        } // end else
                    } // end if
                } else {
                    Log::info('ReportID Already exist in DB. Report name : Campaign SD Report Id' . ', Profile id: ' . $profile->id . ', Report Date: ' . $reportDateSixtyDays);
                } // end else
            } //end for
        } // end foreach
        Log::info("filePath:Commands\Ams\Campaign\SD\getReportIdCron. End Cron.");
    } //end handle
}
