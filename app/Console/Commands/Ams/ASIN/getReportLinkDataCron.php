<?php

namespace App\Console\Commands\Ams\ASIN;

use App\Model\Ams\Asin\SP\SPAsinReport;
use App\Model\Ams\Asin\SP\SPAsinReportLink;
use App\Model\Ams\AuthToken;
use App\Model\Ams\Profile;
use App\Model\Ams\Tracker;
use App\Models\AMSModel;
use Artisan;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class getReportLinkDataCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getasinreportlinkdata:spasin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get ASIN encoded Data From location';

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        Log::info("filePath:App\Console\Commands\Ams\ASIN\getReportLinkDataCron. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        $getSPAsinReportLink = Profile::with(['getSPAsinReportLink'])->get();
        if (!empty($getSPAsinReportLink)) {
            foreach ($getSPAsinReportLink as $single) {
                if ($single->getSPAsinReportLink != NULL) {
                    foreach ($single->getSPAsinReportLink as $reportID) {
                        $reTry = 3;
                        a:
                        if ($reTry > 0) {
                            $reTry--;
                            $profileInfo = Profile::where('profile_id', $single->profile_id)->get()->first();
                            if ($profileInfo->is_active == 1) {
                                // Create a client with a base URI
                                $url = $reportID->location;
                                try {
                                    $client = new Client();
                                    $token = AuthToken::find($single->fk_access_token);
                                    $response = $client->request('GET', $url, [
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $token->access_token,
                                            'Amazon-Advertising-API-ClientId' => $token->client_id,
                                            'Amazon-Advertising-API-Scope' => $single->profile_id
                                        ],
                                        'delay' => Config::get('amsconstants.delayTimeInApi'),
                                        'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                                        'timeout' => Config::get('amsconstants.timeoutInApi'),
                                    ]);
                                    $body = json_decode(gzdecode($response->getBody()->getContents()));
                                    if (!empty($body) && $body != null) {
                                        $totalNumberOfRecords = count($body);
                                        $DataArray = [];
                                        for ($i = 0; $i < $totalNumberOfRecords; $i++) {
                                            $storeArray = [];
                                            $storeArray['fk_reports_download_linksId'] = $reportID->id;
                                            $storeArray['fk_profile_id'] = $reportID->profile_id;
                                            $storeArray['entity_id'] = $single->entity_id;

                                            $storeArray['campaign_name'] = $body[$i]->campaignName;
                                            $storeArray['campaign_id'] = $body[$i]->campaignId;

                                            $storeArray['adgroup_id'] = $body[$i]->adGroupId;
                                            $storeArray['adgroup_name'] = $body[$i]->adGroupName;

                                            $storeArray['keyword_id'] = $body[$i]->keywordId;
                                            $storeArray['keyword_text'] = $body[$i]->keywordText;

                                            $storeArray['asin'] = isset($body[$i]->asin) ? $body[$i]->asin : "";
                                            $storeArray['sku'] = isset($body[$i]->sku) ? $body[$i]->sku : "";
                                            $storeArray['other_asin'] = $body[$i]->otherAsin;
                                            $storeArray['match_type'] = $body[$i]->matchType;
                                            $storeArray['currency'] = $body[$i]->currency;

                                            $storeArray['attributed_units_ordered_1d'] = $body[$i]->attributedUnitsOrdered1d;
                                            $storeArray['attributed_units_ordered_7d'] = $body[$i]->attributedUnitsOrdered7d;
                                            $storeArray['attributed_units_ordered_14d'] = $body[$i]->attributedUnitsOrdered14d;
                                            $storeArray['attributed_units_ordered_30d'] = $body[$i]->attributedUnitsOrdered30d;

                                            $storeArray['attributed_units_ordered_1d_other_sku'] = $body[$i]->attributedUnitsOrdered1dOtherSKU;
                                            $storeArray['attributed_units_ordered_7d_other_sku'] = $body[$i]->attributedUnitsOrdered7dOtherSKU;
                                            $storeArray['attributed_units_ordered_14d_other_sku'] = $body[$i]->attributedUnitsOrdered14dOtherSKU;
                                            $storeArray['attributed_units_ordered_30d_other_sku'] = $body[$i]->attributedUnitsOrdered30dOtherSKU;

                                            $storeArray['attributed_sales_1d_other_sku'] = $body[$i]->attributedSales1dOtherSKU;
                                            $storeArray['attributed_sales_7d_other_sku'] = $body[$i]->attributedSales7dOtherSKU;
                                            $storeArray['attributed_sales_14d_other_sku'] = $body[$i]->attributedSales14dOtherSKU;
                                            $storeArray['attributed_sales_30d_other_sku'] = $body[$i]->attributedSales30dOtherSKU;

                                            $storeArray['report_date'] = $reportID->report_date;
                                            $storeArray['reported_date'] = date('Y-m-d', strtotime($reportID->report_date));
                                            $storeArray['captured_at'] = date('Y-m-d H:i:s');
                                            array_push($DataArray, $storeArray);
                                        } // end for loop
                                        if (!empty($DataArray)) {
                                            foreach (array_chunk($DataArray, 1000) as $data) {
                                                $report_data = new SPAsinReport();
                                                $report_data->addReport($data);
                                            }
                                            $reportLink = SPAsinReportLink::find($reportID->id);
                                            $reportLink->is_done = 1;
                                            $reportLink->save();
                                        }
                                        // store report status
                                        Tracker::insertTrackRecord('report name : Asin SP Report Data' . ' profile id: ' . $reportID->profile_id . ', Report Date: ' . $reportID->report_date, 'record found');
                                    } else {
                                        // store report status
                                        Tracker::insertTrackRecord('report name : Asin SP Report Data' . ' profile id: ' . $reportID->profile_id . ', Report Date: ' . $reportID->report_date, 'not record found');
                                    }
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 401) {
                                        if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                            Log::error('Not authorized to access this advertiser');
                                        } else if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                            Log::error('Refresh Access token. In file filePath:Commands\Ams\ASIN\SP\getReportLinkDataCron');
                                            Artisan::call('updateGetAccessToken:amsAuth ' . $single->fk_access_token);
                                            Artisan::call('getprofileid:updateamsprofile ' . $single->profile_id);
                                            goto a;
                                        } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                            // store profile list not valid
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
                                } // end catch
                            } else {
                                Log::info("Profile Id: " . $single->profile_id . " is Inactive");
                            }
                        } // end if
                    } // end foreach
                }
            } // end foreach
        } else {
            Log::info('Profile id not record found');
        } // end else
        Log::info("filePath:App\Console\Commands\Ams\ASIN\getReportLinkDataCron. End Cron.");
    }
}
