<?php

namespace App\Console\Commands\BiddingRule;

use App\Model\Ams\AuthToken;
use App\Model\Ams\BiddingRule\Cron;
use App\Model\Ams\BiddingRule\InValidProfile;
use App\Model\Ams\BiddingRule\Keyword;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Throwable;

class listofkeyword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keywordlist:amsKeywordlist {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get keyword list of specific rule.';

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
     * @throws GuzzleException
     * @throws Throwable
     */
    public function handle()
    {
        $id = $this->argument('id');
        Log::info("filePath:App\Console\Commands\Ams\Keyword\Data\listofkeyword. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        $authToken = AuthToken::where('number_of_profiles', '>', 0)->get();
        if (!$authToken->isEmpty()) {
            foreach ($authToken as $singleToken) {

                $allBiddingRuleIds = Cron::where('id', $id)->where('is_active', 1)->with('getCampaignsInfo')->get();

                if (!empty($allBiddingRuleIds)) {
                    foreach ($allBiddingRuleIds as $rule) {
                        $campaigns = $rule->getCampaignsInfo()->where('fk_access_token', $singleToken->id)->where('rule_ad_type', '!=', "SD")->get();
                        if (!empty($campaigns)) {
                            foreach ($campaigns as $campaign) {
                                $url = ''; // Create a client with a base URI
                                $reportType = $campaign->rule_ad_type;
                                if ($reportType == 'SP') {
                                    $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.spKeywordList') . '?campaignType=sponsoredProducts&campaignIdFilter=' . $campaign->campaign_id;
                                } else if ($reportType == 'SB') {
                                    $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.sbKeywordList') . '?campaignType=sponsoredBrands&campaignIdFilter=' . $campaign->campaign_id;;
                                } // end else if
                                $client = new Client();
                                a:
                                try {
                                    $token = AuthToken::find($singleToken->id);

                                    $response = $client->request('GET', $url, [
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $token->access_token,
                                            'Content-Type' => 'application/json',
                                            'Amazon-Advertising-API-ClientId' => Config::get('amsconstants.client_id'),
                                            'Amazon-Advertising-API-Scope' => $campaign->profile_id
                                        ],
                                        'delay' => Config::get('amsconstants.delayTimeInApi'),
                                        'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                                        'timeout' => Config::get('amsconstants.timeoutInApi'),
                                    ]);
                                    $body = json_decode($response->getBody()->getContents());

                                    if (!empty($body) && !is_null($body)) {
                                        $DataArray = [];
                                        for ($i = 0; $i < count($body); $i++) {
                                            $storeArray = [];

                                            $storeArray['fk_rule_cron_id'] = $rule->id;
                                            $storeArray['fk_bidding_rule_id'] = $rule->fk_bidding_rule_id;
                                            $storeArray['ad_type'] = $reportType;

                                            $storeArray['profile_id'] = $campaign->profile_id;
                                            $storeArray['ad_group_id'] = $body[$i]->adGroupId;
                                            $storeArray['campaign_id'] = $body[$i]->campaignId;
                                            $storeArray['keyword_id'] = $body[$i]->keywordId;

                                            $storeArray['keyword_text'] = $body[$i]->keywordText;
                                            $storeArray['match_type'] = $body[$i]->matchType;
                                            $storeArray['state'] = $body[$i]->state;
                                            $storeArray['bid'] = isset($body[$i]->bid) ? $body[$i]->bid : '0.00';
                                            $storeArray['serving_status'] = isset($body[$i]->servingStatus) ? $body[$i]->servingStatus : 'NA';
                                            $storeArray['creation_date'] = isset($body[$i]->creationDate) ? $body[$i]->creationDate : 'NA';
                                            $storeArray['last_updated_date'] = isset($body[$i]->lastUpdatedDate) ? $body[$i]->lastUpdatedDate : 'NA';

                                            $storeArray['created_at'] = date('Y-m-d H:i:s');
                                            $storeArray['updated_at'] = date('Y-m-d H:i:s');
                                            array_push($DataArray, $storeArray);
                                        } // end foreach Loop for making insertion data of campaign

                                        if (!empty($DataArray)) {
                                            $keyword = new Keyword();
                                            $keyword->addKeywordList($DataArray);
                                        } // end if
                                        unset($storeArray);
                                        unset($DataArray);
                                        Tracker::insertTrackRecord('Report Name : Keyword List Against ' . $reportType . ' Campaign ID: ' . $campaign->campaign_id . ' Found', 'Record found');
                                    } else {
                                        // campaign status
                                        Tracker::insertTrackRecord('Report Name : Keyword List Against ' . $reportType . ' Campaign ID: ' . $campaign->campaign_id . ' Not Found', 'No record found');
                                    } // end else
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 401) {
                                        if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                            Log::error('Not authorized to access this advertiser');
                                        } else if (strstr($ex->getMessage(), '401 Unauthorized')) {
                                            Log::error('Refresh Access token. In file filePath:App\Console\Commands\Ams\Keyword\ListData\listofkeyword');
                                            Artisan::call('updateGetAccessToken:amsAuth ' . $singleToken->id);
                                            goto a;
                                        } else if (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                            // store profile list not valid
                                            Log::error("In Valid Profile");
                                            $inValidProfile = new InValidProfile();
                                            $inValidProfile->addInValidProfile($rule->id, $campaign->profile_id, $campaign->campaign_id);
                                        } // end else if
                                    } else if ($ex->getCode() == 429) {
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } else if ($ex->getCode() == 502) {
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } // end else if
                                    Log::error($ex->getMessage());
                                }// end catch
                            } // end foreach
                        } else {
                            Log::info("Campaign list not found.");
                        } // end else
                    } // end foreach
                } else {
                    Log::info("Rule not found.");
                } // end else
            } // end foreach
        } else {
            Log::info("AMS access token not found.");
        } // end else
        Log::info("filePath:App\Console\Commands\Ams\Keyword\Data\listofkeyword. End Cron.");
    } // end function
} // end class
