<?php

namespace App\Console\Commands\Ams\ProductsAds\SP;

use App\Model\Ams\AuthToken;
use App\Model\Ams\ProductAds\SP\SPProductAdsReportId;
use App\Model\Ams\ProductAds\SP\SPProductAdsReportLink;
use App\Model\Ams\Profile;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class getReportLinkCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getproductsadsreportlink:productsads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get Product Ads Report Link location.';

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
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("filePath:Commands\Ams\ProductAds\SP\getReportLinkCron. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        $getProfileReportIdList = Profile::with(['getSPProductAdsReportId'])->get();
        if (!empty($getProfileReportIdList)) {
            // get Specific Report Type ID
            foreach ($getProfileReportIdList as $profile) {
                if ($profile->getSPProductAdsReportId != NULL) {
                    foreach ($profile->getSPProductAdsReportId as $reportID) {
                        $reTry = 3;
                        a:
                        if ($reTry > 0) {
                            $reTry--;
                            $profileInfo = Profile::where('profile_id', $profile->profile_id)->get()->first();
                            if ($profileInfo->is_active == 1) {
                                // Create a client with a base URI
                                $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.downloadReport');
                                try {
                                    $client = new Client();
                                    $token = AuthToken::find($profile->fk_access_token);
                                    $response = $client->request('GET', $url . '/' . $reportID->report_id, [
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $token->access_token,
                                            'Content-Type' => 'application/json',
                                            'Amazon-Advertising-API-ClientId' => $token->client_id,
                                            'Amazon-Advertising-API-Scope' => $profile->profile_id
                                        ],
                                        'delay' => Config::get('amsconstants.delayTimeInApi'),
                                        'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                                        'timeout' => Config::get('amsconstants.timeoutInApi'),
                                    ]);
                                    $body = json_decode($response->getBody()->getContents());
                                    if (!empty($body) && $body != null) {
                                        $reportDate = $reportID->report_date; // ams report date
                                        if ($body->status != 'IN_PROGRESS') {
                                            $SPProductAdsReportId = SPProductAdsReportId::find($reportID->id);
                                            if ($SPProductAdsReportId->is_done == 0) {
                                                $ReportData = new SPProductAdsReportLink();
                                                $ReportData->profile_id = $profile->profile_id;
                                                $ReportData->report_id = $body->reportId;
                                                $ReportData->status = $body->status;
                                                $ReportData->status_details = $body->statusDetails;
                                                $ReportData->is_done = 0;
                                                if ($body->status == 'FAILURE') {
                                                    $ReportData->location = 'not available';
                                                    $ReportData->file_size = 'not available';
                                                    $ReportData->is_done = 3; // not find URL
                                                } else {
                                                    if (isset($body->location)) {
                                                        $ReportData->location = $body->location;
                                                    }
                                                    $ReportData->file_size = $body->fileSize;
                                                    if ($ReportData->file_size == 22) {
                                                        $ReportData->is_done = 2; // FILE SIZE is 22 because its empty not record found
                                                    }
                                                }
                                                $ReportData->report_date = $reportDate;
                                                $ReportData->creation_date = date('Y-m-d');
                                                $ReportData->save();

                                                $SPProductAdsReportId->is_done = 1;
                                                $SPProductAdsReportId->save();
                                            } // end if
                                        } // end if
                                        // store report status
                                        Tracker::insertTrackRecord('Report name : ProductAds SP Report Link' . ', Profile id: ' . $profile->id . ', Report Date: ' . $reportID->report_date, 'record found');
                                    } else {
                                        // store report status
                                        Tracker::insertTrackRecord('Report name : ProductAds SP Report Link' . ', Profile id: ' . $profile->id . ', Report Date: ' . $reportID->report_date, 'not record found');
                                    }
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 401) {
                                        if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                            Log::error('Not authorized to access this advertiser');
                                        } else if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                            Log::error('Refresh Access token. In file filePath:Commands\Ams\ProductAds\SP\getReportLinkCron');
                                            Artisan::call('updateGetAccessToken:amsAuth ' . $profile->fk_access_token);
                                            Artisan::call('getprofileid:updateamsprofile ' . $profile->profile_id);
                                            goto a;
                                        } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                            // store profile list not valid
                                            Log::info("Invalid Profile Id: " . $profile->profile_id);
                                        }
                                    } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                    } else if ($ex->getCode() == 502) {
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                    }
                                    // store report status
                                    // store report status
                                    Tracker::insertTrackRecord('ProductAds SP Report Link', 'fail');
                                    Log::error($ex->getMessage());
                                }
                            } else {
                                Log::info("Profile Id: " . $profile->profile_id . " is Inactive");
                            } // end else
                        } // end if
                    } // end foreach
                } // end if
            }// end foreach
        } else {
            Log::info("Client Id not found.");
        }
        Log::info("filePath:Commands\Ams\ProductAds\SP\getReportLinkCron. End Cron.");
    }
}
