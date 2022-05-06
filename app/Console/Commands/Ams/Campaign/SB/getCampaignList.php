<?php

namespace App\Console\Commands\Ams\Campaign\SB;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Campaign;
use App\Model\Ams\Profile;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class getCampaignList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getCampaignList:campaignSB';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get sponsored Brand Campaign details';

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
        Log::info("filePath:App\Console\Commands\Ams\Campaign\SB\getCampaignList. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        $authToken = AuthToken::where('number_of_profiles', '>', 0)
            ->where('expire_flag', 0)
            ->get();
        if (!$authToken->isEmpty()) {
            foreach ($authToken as $singleToken) {
                $allProfileIdsObject = AuthToken::with('getProfileList', 'getSandboxProfileList')->find($singleToken->id);
                //$responseForProfile = getNotifyWhichEnvDataToUse(env('APP_ENV'));

                $allProfileIds = $allProfileIdsObject->getProfileList;
//                if (TRUE == TRUE){
//                    $allProfileIds = $allProfileIdsObject->getProfileList;
//                }else if(FALSE == FALSE){
//                    $allProfileIds = $allProfileIdsObject->getSandboxProfileList;
//                }
                if (!empty($allProfileIds)) {
                    foreach ($allProfileIds as $profile) {
                        // Defined Url to get all portfolio against profiles
                        //$apiUrl = getApiUrlForDiffEnv(env('APP_ENV'));
                        $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.sbCampaignUrl');
                        $client = new Client();
                        // Goto Statement used
                        $reTry = 3;
                        a:
                        if ($reTry > 0) {
                            $reTry--;
                            $profileInfo = Profile::where('profile_id', $profile->profile_id)->get()->first();
                            if ($profileInfo->is_active == 1) {
                                try {
                                    $token = AuthToken::find($profile->fk_access_token);
                                    $response = $client->request('GET', $url, [
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $token->access_token,
                                            'Content-Type' => 'application/json',
                                            'Amazon-Advertising-API-ClientId' => Config::get('amsconstants.client_id'),
                                            'Amazon-Advertising-API-Scope' => $profile->profile_id
                                        ],
                                        'delay' => Config::get('amsconstants.delayTimeInApi'),
                                        'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                                        'timeout' => Config::get('amsconstants.timeoutInApi'),
                                    ]);
                                    $responseBody = json_decode($response->getBody()->getContents());

                                    if (!empty($responseBody) && !is_null($responseBody)) {
                                        foreach ($responseBody as $singleResponseRecord) {
                                            $DataActiveCampaignIDArray = [];
                                            $isCampaignExist = Campaign::where('campaign_id', $singleResponseRecord->campaignId)->get()->first();
                                            $campaign = $isCampaignExist;
                                            // check if campaign is not exist
                                            if (!isset($isCampaignExist)) {
                                                $campaign = new Campaign();
                                            } // end if
                                            $campaign->profile_id = $profile->profile_id;
                                            $campaign->fk_profile_id = $profile->id;
                                            $campaign->fk_access_token = $profile->fk_access_token;
                                            $campaign->portfolios_id = (isset($singleResponseRecord->portfolioId) ? $singleResponseRecord->portfolioId : 0);
                                            $campaign->campaign_id = isset($singleResponseRecord->campaignId) ? $singleResponseRecord->campaignId : 0;
                                            $campaign->name = $singleResponseRecord->name;
                                            $campaign->type = 'SB';
                                            $campaign->campaign_type = 'sponsored_brand';

                                            $campaign->strategy = isset($singleResponseRecord->bidding->strategy) ? $singleResponseRecord->bidding->strategy : 'NA';
                                            $campaign->predicate = isset($singleResponseRecord->bidding->adjustments[0]->predicate) ? $singleResponseRecord->bidding->adjustments[0]->predicate : 'NA';;
                                            $campaign->percentage = isset($singleResponseRecord->bidding->adjustments[0]->percentage) ? $singleResponseRecord->bidding->adjustments[0]->percentage : 0;
                                            $campaign->budget = isset($singleResponseRecord->budget) ? $singleResponseRecord->budget : 0.00;
                                            $campaign->daily_budget = isset($singleResponseRecord->dailyBudget) ? $singleResponseRecord->dailyBudget : 0.00;
                                            $campaign->budget_type = isset($singleResponseRecord->budgetType) ? $singleResponseRecord->budgetType : 'NA';

                                            $campaign->state = isset($singleResponseRecord->state) ? $singleResponseRecord->state : 'NA';

                                            $campaign->cost_type = isset($singleResponseRecord->costType) ? $singleResponseRecord->costType : 'NA';
                                            $campaign->tactic = isset($singleResponseRecord->tactic) ? $singleResponseRecord->tactic : 'NA';
                                            $campaign->delivery_profile = isset($singleResponseRecord->deliveryProfile) ? $singleResponseRecord->deliveryProfile : 'NA';

                                            $campaign->targeting_type = isset($singleResponseRecord->targetingType) ? $singleResponseRecord->targetingType : 'NA';
                                            $campaign->premium_bid_adjustment = isset($singleResponseRecord->premiumBidAdjustment) ? $singleResponseRecord->premiumBidAdjustment : 'NA';
                                            $campaign->bid_optimization = isset($singleResponseRecord->bidOptimization) ? $singleResponseRecord->bidOptimization : 'NA';
                                            $campaign->serving_status = isset($singleResponseRecord->servingStatus) ? $singleResponseRecord->servingStatus : 'NA';

                                            $campaign->page_type = isset($singleResponseRecord->landingPage->pageType) ? $singleResponseRecord->landingPage->pageType : 'NA';
                                            $campaign->url = isset($singleResponseRecord->landingPage->url) ? $singleResponseRecord->landingPage->url : 'NA';

                                            $campaign->brand_name = isset($singleResponseRecord->creative->brandName) ? $singleResponseRecord->creative->brandName : 'NA';
                                            $campaign->brand_logo_asset_id = isset($singleResponseRecord->creative->brandLogoAssetID) ? $singleResponseRecord->creative->brandLogoAssetID : 'NA';
                                            $campaign->headline = isset($singleResponseRecord->creative->headline) ? $singleResponseRecord->creative->headline : 'NA';
                                            $campaign->should_optimize_asins = isset($singleResponseRecord->creative->shouldOptimizeAsins) ? $singleResponseRecord->creative->shouldOptimizeAsins : 'NA';
                                            $campaign->brand_logo_url = isset($singleResponseRecord->creative->brandLogoUrl) ? $singleResponseRecord->creative->brandLogoUrl : 'NA';
                                            $campaign->asins = isset($singleResponseRecord->creative->asins) ? implode(',', $singleResponseRecord->creative->asins) : 'NA';

                                            $campaign->start_date = isset($singleResponseRecord->startDate) ? $singleResponseRecord->startDate : 'NA';
                                            $campaign->end_date = isset($singleResponseRecord->endDate) ? $singleResponseRecord->endDate : 'NA';

                                            $campaign->created_at = date('Y-m-d H:i:s');
                                            $campaign->updated_at = date('Y-m-d H:i:s');
                                            $campaign->is_active = 1;
                                            $campaign->save();
                                            array_push($DataActiveCampaignIDArray, $campaign->campaign_id);
                                        } // end foreach
                                        Campaign::updateCampaignRecords($profile->profile_id, 'SB', $DataActiveCampaignIDArray);
                                        unset($DataActiveCampaignIDArray);
                                        Tracker::insertTrackRecord('Report Name : Get SB Campaigns List Against' . ' Profile ID: ' . $profile->profile_id . ' Found', 'record found');
                                    } else {
                                        // campaign status
                                        Tracker::insertTrackRecord('Report Name : Get SB Campaigns List Against' . ' Profile ID: ' . $profile->profile_id . ' Not Found', 'not record found');
                                    } // end else
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 401) {
                                        if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                            Log::error('Not authorized to access this advertiser');
                                        } else if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                            Log::error('Refresh Access token. In file filePath:App\Console\Commands\Ams\Campaign\SB\getCampaignList');
                                            Artisan::call('updateGetAccessToken:amsAuth ' . $profile->fk_access_token);
                                            Artisan::call('getprofileid:updateamsprofile ' . $profile->profile_id);
                                            goto a;
                                        } // end else if
                                    } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } else if ($ex->getCode() == 502) {
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } // end else if
                                    // store report status
                                    Tracker::insertTrackRecord('App\Console\Commands\Ams\Campaign\SB\getCampaignList', 'fail');
                                    Log::error($ex->getMessage());
                                }// end catch
                            } // end if
                        } // end if
                    } // end foreach
                } else {
                    Log::info("Profile List not found.");
                } // end else
            } // end foreach
        } else {
            Log::info("AMS access token not found.");
        } // end else
        Log::info("filePath:App\Console\Commands\Ams\Campaign\SB\getCampaignList. End Cron.");
    } // end function
} // end class
