<?php

namespace App\Console\Commands\AMS\Campaign\SB;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Campaign\SB\SBCampaignReport;
use App\Model\Ams\Campaign\SB\SBCampaignReportLink;
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
    protected $signature = 'getcampaignreportlinkdata:sbcampaign';

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
        Log::info("filePath:Commands\Ams\Campaign\SB\getReportLinkDataCron. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        $getSBCampaignReportLink = Profile::with(['getSBCampaignReportLink'])->get();
        if (!empty($getSBCampaignReportLink)) {
            // get Specific Report Type ID
            foreach ($getSBCampaignReportLink as $profile) {
                if ($profile->getSBCampaignReportLink != NULL) {
                    foreach ($profile->getSBCampaignReportLink as $reportID) {
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
                                            'Amazon-Advertising-API-Scope' => $profile->profile_id],
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
                                            $storeArray['campaign_status'] = $body[$i]->campaignStatus;
                                            $storeArray['campaign_budget'] = $body[$i]->campaignBudget;
                                            $storeArray['campaign_budget_type'] = $body[$i]->campaignBudgetType;
                                            $storeArray['campaign_rule_based_budget'] = isset($body[$i]->campaignRuleBasedBudget) ? $body[$i]->campaignRuleBasedBudget : '';
                                            $storeArray['applicable_budget_rule_id'] = isset($body[$i]->applicableBudgetRuleId) ? $body[$i]->applicableBudgetRuleId : '';
                                            $storeArray['applicable_budget_rule_name'] = isset($body[$i]->applicableBudgetRuleName) ? $body[$i]->applicableBudgetRuleName : '';

                                            $storeArray['impressions'] = $body[$i]->impressions;
                                            $storeArray['clicks'] = $body[$i]->clicks;
                                            $storeArray['cost'] = $body[$i]->cost;
                                            $storeArray['attributed_detail_page_views_clicks_14d'] = $body[$i]->attributedDetailPageViewsClicks14d;
                                            $storeArray['attributed_sales_14d'] = $body[$i]->attributedSales14d;
                                            $storeArray['attributed_sales_14d_same_sku'] = $body[$i]->attributedSales14dSameSKU;
                                            $storeArray['attributed_conversions_14d'] = $body[$i]->attributedConversions14d;
                                            $storeArray['attributed_conversions_14d_same_sku'] = $body[$i]->attributedConversions14dSameSKU;
                                            $storeArray['attributed_orders_new_to_brand_14d'] = $body[$i]->attributedOrdersNewToBrand14d;
                                            $storeArray['attributed_orders_new_to_brand_percentage_14d'] = $body[$i]->attributedOrdersNewToBrandPercentage14d;
                                            $storeArray['attributed_order_rate_new_to_brand_14d'] = $body[$i]->attributedOrderRateNewToBrand14d;
                                            $storeArray['attributed_sales_new_to_brand_14d'] = $body[$i]->attributedSalesNewToBrand14d;
                                            $storeArray['attributed_sales_new_to_brand_percentage_14d'] = $body[$i]->attributedSalesNewToBrandPercentage14d;
                                            $storeArray['attributed_units_ordered_new_to_brand_14d'] = $body[$i]->attributedUnitsOrderedNewToBrand14d;
                                            $storeArray['attributed_units_ordered_new_to_brand_percentage_14d'] = $body[$i]->attributedUnitsOrderedNewToBrandPercentage14d;
                                            $storeArray['units_sold_14d'] = $body[$i]->unitsSold14d;
                                            $storeArray['dpv_14d'] = $body[$i]->dpv14d;

                                            $storeArray['report_date'] = $reportID->report_date;
                                            $storeArray['reported_date'] = date('Y-m-d', strtotime($reportID->report_date));
                                            $storeArray['captured_at'] = date('Y-m-d H:i:s');
                                            array_push($DataArray, $storeArray);
                                        }// end for loop
                                        if (!empty($DataArray)) {
                                            foreach (array_chunk($DataArray, 1000) as $data) {
                                                $report_data = new SBCampaignReport();
                                                $report_data->addReport($data);
                                            }
                                            $reportLink = SBCampaignReportLink::find($reportID->id);
                                            $reportLink->is_done = 1;
                                            $reportLink->save();
                                        }
                                        // store report status
                                        Tracker::insertTrackRecord('report name : Campaign SB Report Data' . ', Profile id: ' . $reportID->profile_id . ', Report Date: ' . $reportID->report_date, 'record found');
                                    } else {
                                        // store report status
                                        Tracker::insertTrackRecord('report name : Campaign SB Report Data' . ', Profile id: ' . $reportID->profile_id . ', Report Date: ' . $reportID->report_date, 'not record found');
                                    }
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 401) {
                                        if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                            Log::error('Not authorized to access this advertiser');
                                        } else if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                            Log::error('Refresh Access token. In file filePath:Commands\Ams\Campaign\SB\getReportLinkDataCron');
                                            Artisan::call('updateGetAccessToken:amsAuth ' . $profile->fk_access_token);
                                            Artisan::call('getprofileid:updateamsprofile ' . $profile->profile_id);
                                            goto a;
                                        } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                            // store profile list not valid
                                        }// end else if
                                    } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } else if ($ex->getCode() == 502) {
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    }// end else if
                                    // store report status
                                    Log::error($ex->getMessage());
                                }// end catch
                            } else {
                                Log::info("Profile Id: " . $profile->profile_id . " is Inactive");
                            } /// end else
                        } // end if
                    }// end foreach
                } else {
                    Log::info('report name : Campaign SB Report Data' . ' profile id: ' . $profile->profile_id . ' Links record found');
                }// end else
            }// end foreach
        } else {
            Log::info('Profile id not record found');
        }// end else
        Log::info("filePath:Commands\Ams\Campaign\SB\getReportLinkDataCron. End Cron.");
    }
}
