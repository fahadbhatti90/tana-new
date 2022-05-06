<?php

namespace App\Console\Commands\AMS\Campaign\SD\Audiences;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Campaign\SD\Audiences\SdAudiencesCampaignReport;
use App\Model\Ams\Campaign\SD\Audiences\SdAudiencesCampaignReportLink;
use App\Model\Ams\Profile;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class getReportLinkDataCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getCampaignReportLinkData:sdAudiencesCampaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get Report Location Data.';

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
        Log::info("filePath:Commands\Ams\Campaign\SD\getReportLinkDataCron. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        $getSdCampaignReportLink = Profile::with(['getSdAudiencesCampaignReportLink'])->get();
        if (!empty($getSdCampaignReportLink)) {
            // get Specific Report Type ID
            foreach ($getSdCampaignReportLink as $profile) {
                if ($profile->getSdAudiencesCampaignReportLink != NULL) {
                    foreach ($profile->getSdAudiencesCampaignReportLink as $reportID) {
                        $reTry = 3;
                        a:
                        if ($reTry > 0) {
                            $reTry--;
                            $profileInfo = Profile::where('profile_id', $profile->profile_id)->get()->first();
                            if ($profileInfo->is_active == 1) {
                                // Create a client with a base URI
                                $url = $reportID->location;
                                try {
                                    $client = new Client();
                                    $token = AuthToken::find($profile->fk_access_token);
                                    $response = $client->request('GET', $url, [
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $token->access_token,
                                            'Amazon-Advertising-API-ClientId' => $token->client_id,
                                            'Amazon-Advertising-API-Scope' => $profile->profile_id
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
                                            $storeArray['profile_id'] = $reportID->profile_id;
                                            $storeArray['entity_id'] = $profile->entity_id;

                                            $storeArray['campaign_name'] = $body[$i]->campaignName;
                                            $storeArray['campaign_id'] = $body[$i]->campaignId;
                                            $storeArray['campaign_budget'] = $body[$i]->campaignBudget;
                                            $storeArray['campaign_status'] = $body[$i]->campaignStatus;

                                            $storeArray['attributed_dpv_14d'] = isset($body[$i]->attributedDPV14d) ? $body[$i]->attributedDPV14d : "";
                                            $storeArray['attributed_units_sold_14d'] = isset($body[$i]->attributedUnitsSold14d) ? $body[$i]->attributedUnitsSold14d : "";

                                            $storeArray['impressions'] = $body[$i]->impressions;
                                            $storeArray['clicks'] = $body[$i]->clicks;
                                            $storeArray['cost'] = $body[$i]->cost;
                                            $storeArray['currency'] = isset($body[$i]->currency) ? $body[$i]->currency : "";

                                            $storeArray['attributed_conversions_1d'] = $body[$i]->attributedConversions1d;
                                            $storeArray['attributed_conversions_7d'] = $body[$i]->attributedConversions7d;
                                            $storeArray['attributed_conversions_14d'] = $body[$i]->attributedConversions14d;
                                            $storeArray['attributed_conversions_30d'] = $body[$i]->attributedConversions30d;

                                            $storeArray['attributed_conversions_1d_same_sku'] = $body[$i]->attributedConversions1dSameSKU;
                                            $storeArray['attributed_conversions_7d_same_sku'] = $body[$i]->attributedConversions7dSameSKU;
                                            $storeArray['attributed_conversions_14d_same_sku'] = $body[$i]->attributedConversions14dSameSKU;
                                            $storeArray['attributed_conversions_30d_same_sku'] = $body[$i]->attributedConversions30dSameSKU;

                                            $storeArray['attributed_units_ordered_1d'] = $body[$i]->attributedUnitsOrdered1d;
                                            $storeArray['attributed_units_ordered_7d'] = $body[$i]->attributedUnitsOrdered7d;
                                            $storeArray['attributed_units_ordered_14d'] = $body[$i]->attributedUnitsOrdered14d;
                                            $storeArray['attributed_units_ordered_30d'] = $body[$i]->attributedUnitsOrdered30d;

                                            $storeArray['attributed_sales_1d'] = $body[$i]->attributedSales1d;
                                            $storeArray['attributed_sales_7d'] = $body[$i]->attributedSales7d;
                                            $storeArray['attributed_sales_14d'] = $body[$i]->attributedSales14d;
                                            $storeArray['attributed_sales_30d'] = $body[$i]->attributedSales30d;

                                            $storeArray['attributed_sales_1d_same_sku'] = $body[$i]->attributedSales1dSameSKU;
                                            $storeArray['attributed_sales_7d_same_sku'] = $body[$i]->attributedSales7dSameSKU;
                                            $storeArray['attributed_sales_14d_same_sku'] = $body[$i]->attributedSales14dSameSKU;
                                            $storeArray['attributed_sales_30d_same_sku'] = $body[$i]->attributedSales30dSameSKU;

                                            $storeArray['view_attributed_conversions_14d'] = $body[$i]->viewAttributedConversions14d;
                                            $storeArray['view_attributed_detail_page_view_14d'] = $body[$i]->viewAttributedDetailPageView14d;
                                            $storeArray['view_attributed_sales_14d'] = $body[$i]->viewAttributedSales14d;
                                            $storeArray['view_attributed_units_ordered_14d'] = $body[$i]->viewAttributedUnitsOrdered14d;
                                            $storeArray['view_impressions'] = $body[$i]->viewImpressions;

                                            $storeArray['report_date'] = $reportID->report_date;
                                            $storeArray['reported_date'] = date('Y-m-d', strtotime($reportID->report_date));
                                            $storeArray['captured_at'] = date('Y-m-d H:i:s');
                                            array_push($DataArray, $storeArray);
                                        } // end for loop
                                        if (!empty($DataArray)) {
                                            foreach (array_chunk($DataArray, 1000) as $data) {
                                                $report_data = new SdAudiencesCampaignReport();
                                                $report_data->addReport($data);
                                            }
                                            $reportLink = SdAudiencesCampaignReportLink::find($reportID->id);
                                            $reportLink->is_done = 1;
                                            $reportLink->save();
                                        }
                                        // store report status
                                        Tracker::insertTrackRecord('report name : Campaign SD Report Data' . ', Profile id: ' . $reportID->profile_id . ', Report Date: ' . $reportID->report_date, 'record found');
                                    } else {
                                        // store report status
                                        Tracker::insertTrackRecord('report name : Campaign SD Report Data' . ', Profile id: ' . $reportID->profile_id . ', Report Date: ' . $reportID->report_date, 'not record found');
                                    }
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 401) {
                                        if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                            Log::error('Not authorized to access this advertiser');
                                        } else if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                            Log::error('Refresh Access token. In file filePath:Commands\Ams\Campaign\SD\getReportLinkDataCron');
                                            Artisan::call('updateGetAccessToken:amsAuth ' . $profile->fk_access_token);
                                            Artisan::call('getprofileid:updateamsprofile ' . $profile->profile_id);
                                            goto a;
                                        } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                            // store profile list not valid
                                        } // end else if
                                    } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } else if ($ex->getCode() == 502) {
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } // end else if
                                    // store report status
                                    Log::error($ex->getMessage());
                                } // end catch
                            } else {
                                Log::info("Profile Id: " . $profile->profile_id . " is Inactive");
                            }
                        } // end if
                    } // end foreach
                } else {
                    Log::info('report name : Campaign SD Report Data' . ' profile id: ' . $profile->profile_id . ' Links record found');
                } // end else
            } // end foreach
        } else {
            Log::info('Profile id not record found');
        } // end else
        Log::info("filePath:Commands\Ams\Campaign\SD\getReportLinkDataCron. End Cron.");
    }
}
