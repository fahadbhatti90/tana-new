<?php

namespace App\Console\Commands\BiddingRule;

use App\Model\Ams\AuthToken;
use App\Model\Ams\BiddingRule\Cron;
use App\Model\Ams\BiddingRule\InValidProfile;
use App\Model\Ams\BiddingRule\Target;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Throwable;

class listoftarget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'targetlist:amsTargetlist {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get target list of specific rule.';

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
        Log::info("filePath:App\Console\Commands\Ams\Target\Data\listoftarget. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        $authToken = AuthToken::where('number_of_profiles', '>', 0)
            ->where('expire_flag', 0)
            ->get();
        if (!$authToken->isEmpty()) {
            foreach ($authToken as $singleToken) {

                $allBiddingRuleIds = Cron::where('id', $id)->where('is_active', 1)->with('getCampaignsInfo')->get();

                if (!empty($allBiddingRuleIds)) {
                    foreach ($allBiddingRuleIds as $rule) {

                        $campaigns = $rule->getCampaignsInfo()->where('fk_access_token', $singleToken->id)->get();

                        if (!empty($campaigns)) {
                            foreach ($campaigns as $campaign) {
                                $reportType = $campaign->rule_ad_type;
                                $url = "";
                                $jsonArray = [];
                                if ($reportType == 'SB') {
                                    $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.sbTargetsList');
                                    $jsonObj = (object)[
                                        'maxResults' => 5000,
                                        'filters' => [
                                            [
                                                'filterType' => 'TARGETING_STATE',
                                                'values' => [
                                                    'archived', 'paused', 'pending', 'enabled'
                                                ],
                                            ],
                                            [
                                                'filterType' => 'CAMPAIGN_ID',
                                                'values' => [
                                                    $campaign->campaign_id,
                                                ],
                                            ],
                                        ],
                                    ];
                                    a:
                                    try {
                                        $client = new Client();
                                        $token = AuthToken::find($singleToken->id);
                                        $response = $client->request('POST', $url, [
                                            'headers' => [
                                                'Authorization' => 'Bearer ' . $token->access_token,
                                                'Content-Type' => 'application/json',
                                                'Amazon-Advertising-API-ClientId' => Config::get('amsconstants.client_id'),
                                                'Amazon-Advertising-API-Scope' => $campaign->profile_id
                                            ],
                                            'json' => $jsonObj,
                                            'delay' => Config::get('amsconstants.delayTimeInApi'),
                                            'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                                            'timeout' => Config::get('amsconstants.timeoutInApi'),
                                        ]);
                                        $body = json_decode($response->getBody()->getContents());
                                        if (!empty($body->targets) && !is_null($body->targets)) {
                                            $DataArray = [];
                                            $body_data = $body->targets;
                                            for ($i = 0; $i < count($body_data); $i++) {

                                                $storeArray = [];

                                                $storeArray['fk_rule_cron_id'] = $rule->id;
                                                $storeArray['fk_bidding_rule_id'] = $rule->fk_bidding_rule_id;
                                                $storeArray['ad_type'] = $reportType;

                                                $storeArray['profile_id'] = $campaign->profile_id;
                                                $storeArray['ad_group_id'] = $body_data[$i]->adGroupId;
                                                $storeArray['campaign_id'] = $campaign->campaign_id;
                                                $storeArray['target_id'] = $body_data[$i]->targetId;

                                                $storeArray['state'] = $body_data[$i]->state;
                                                $storeArray['bid'] = isset($body_data[$i]->bid) ? $body_data[$i]->bid : '0.00';

                                                if (isset($body_data[$i]->expressions[0]->type)) {
                                                    $storeArray['expressions_type'] = $body_data[$i]->expressions[0]->type;
                                                    $storeArray['expressions_value'] = $body_data[$i]->expressions[0]->value;
                                                } // end if

                                                if (isset($body_data[$i]->resolvedExpressions[0]->type)) {
                                                    $storeArray['resolved_expression_type'] = $body_data[$i]->resolvedExpressions[0]->type;
                                                    $storeArray['resolved_expression_value'] = $body_data[$i]->resolvedExpressions[0]->value;
                                                } // end if

                                                $storeArray['created_at'] = date('Y-m-d H:i:s');
                                                $storeArray['updated_at'] = date('Y-m-d H:i:s');
                                                array_push($DataArray, $storeArray);
                                            } // end foreach Loop for making insertion data of targets

                                            if (!empty($DataArray)) {
                                                $target = new Target();
                                                $target->addTargetList($DataArray);
                                            } // end if
                                            unset($storeArray);
                                            unset($DataArray);
                                            Tracker::insertTrackRecord('Report Name : Target List Against ' . $reportType . ' Campaign ID: ' . $campaign->campaign_id . ' Found', 'Record found');
                                        } else {
                                            // campaign status
                                            Tracker::insertTrackRecord('Report Name : Target List Against ' . $reportType . ' Campaign ID: ' . $campaign->campaign_id . ' Not Found', 'No record found');
                                        } // end else
                                    } catch (\Exception $ex) {
                                        if ($ex->getCode() == 401) {
                                            if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                                Log::error('Not authorized to access this advertiser');
                                            } else if (strstr($ex->getMessage(), '401 Unauthorized')) {
                                                Log::error('Refresh Access token. In file filePath:App\Console\Commands\Ams\Keyword\ListData\listoftarget');
                                                Artisan::call('updateGetAccessToken:amsAuth ' . $singleToken->id);
                                                goto a;
                                            } else if (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                                // store profile list not valid
                                                Log::error("In Valid Profile");
                                                $inValidProfile = new InValidProfile();
                                                $inValidProfile->addInValidProfile($rule->id, $campaign->profile_id, $campaign->campaign_id);
                                            } // end else if
                                        } else if ($ex->getCode() == 429) {
                                            sleep(Config::get('constants.sleepTime') + 2);
                                            goto a;
                                        } else if ($ex->getCode() == 502) {
                                            sleep(Config::get('constants.sleepTime') + 2);
                                            goto a;
                                        } // end else if
                                        dd($ex);
                                        Log::error($ex->getMessage());
                                    }// end catch
                                } else {
                                    if ($reportType == 'SP') {
                                        $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.spTargetsList') . '?campaignIdFilter=' . $campaign->campaign_id;
                                    } elseif ($reportType == 'SD') {
                                        $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.sdTargetsList') . '?campaignIdFilter=' . $campaign->campaign_id;
                                    } // end else if
                                    $client = new Client();
                                    b:
                                    try {
                                        $token = AuthToken::find($singleToken->id);

                                        $response = $client->request('GET', $url, [
                                            'headers' => [
                                                'Authorization' => 'Bearer ' . $token->access_token,
                                                'Content-Type' => 'application/json',
                                                'Amazon-Advertising-API-ClientId' => Config::get('amsconstants.client_id'),
                                                'Amazon-Advertising-API-Scope' => $campaign->profile_id
                                            ],
                                            'delay' => Config::get('constants.delayTimeInApi'),
                                            'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                                            'timeout' => Config::get('constants.timeoutInApi'),
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
                                                $storeArray['campaign_id'] = $campaign->campaign_id;
                                                $storeArray['target_id'] = $body[$i]->targetId;

                                                $storeArray['state'] = $body[$i]->state;
                                                $storeArray['bid'] = isset($body[$i]->bid) ? $body[$i]->bid : '0.00';

                                                $storeArray['expression'] = isset($body[$i]->expressionType) ? $body[$i]->expressionType : '';

                                                if (isset($body[$i]->expression[0]->type)) {

                                                    $storeArray['expressions_type'] = $body[$i]->expression[0]->type;

                                                    if (isset($body[$i]->expression[0]->value[0]->type)) {
                                                        $expression_data_type = "";
                                                        $expression_data_value = "";
                                                        foreach ($body[$i]->expression[0]->value as $expression_value) {
                                                            $expression_data_type .= (isset($expression_value->type) ? $expression_value->type : "") . ',';
                                                            $expression_data_value .= (isset($expression_value->value) ? $expression_value->value : "") . ',';
                                                        }
                                                        $storeArray['expressions_type'] = $expression_data_type;
                                                        $storeArray['expressions_value'] = $expression_data_value;
                                                    } else {
                                                        $storeArray['expressions_value'] = isset($body[$i]->expression[0]->value) ? $body[$i]->expression[0]->value : '';
                                                    } // end else
                                                } // end if

                                                if (isset($body[$i]->resolvedExpression[0]->type)) {
                                                    $storeArray['resolved_expression_type'] = $body[$i]->resolvedExpression[0]->type;

                                                    if (isset($body[$i]->resolvedExpression[0]->value[0]->type)) {
                                                        $resolvedExpression_data_type = "";
                                                        $resolvedExpression_data_value = "";
                                                        foreach ($body[$i]->resolvedExpression[0]->value as $resolvedExpression_value) {
                                                            $resolvedExpression_data_type .= (isset($resolvedExpression_value->type) ? $resolvedExpression_value->type : "") . ',';
                                                            $resolvedExpression_data_value .= (isset($resolvedExpression_value->value) ? $resolvedExpression_value->value : "") . ',';
                                                        }
                                                        $storeArray['resolved_expression_type'] = $resolvedExpression_data_type;
                                                        $storeArray['resolved_expression_value'] = $resolvedExpression_data_value;
                                                    } else {
                                                        $storeArray['resolved_expression_value'] = isset($body[$i]->resolvedExpression[0]->value) ? $body[$i]->resolvedExpression[0]->value : '';
                                                    } // end else
                                                } // end if
                                                $storeArray['serving_status'] = isset($body[$i]->expressionType) ? $body[$i]->expressionType : '';
                                                $storeArray['creation_date'] = isset($body[$i]->creationDate) ? $body[$i]->creationDate : '';
                                                $storeArray['last_updated_date'] = isset($body[$i]->lastUpdatedDate) ? $body[$i]->lastUpdatedDate : '';

                                                $storeArray['created_at'] = date('Y-m-d H:i:s');
                                                $storeArray['updated_at'] = date('Y-m-d H:i:s');
                                                array_push($DataArray, $storeArray);
                                            } // end foreach Loop for making insertion data of targets

                                            if (!empty($DataArray)) {
                                                $target = new Target();
                                                $target->addTargetList($DataArray);
                                            } // end if
                                            unset($storeArray);
                                            unset($DataArray);
                                            Tracker::insertTrackRecord('Report Name : Target List Against ' . $reportType . ' Campaign ID: ' . $campaign->campaign_id . ' Found', 'Record found');
                                        } else {
                                            // campaign status
                                            Tracker::insertTrackRecord('Report Name : Target List Against ' . $reportType . ' Campaign ID: ' . $campaign->campaign_id . ' Not Found', 'No record found');
                                        } // end else
                                    } catch (\Exception $ex) {
                                        if ($ex->getCode() == 401) {
                                            if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                                Log::error('Not authorized to access this advertiser');
                                            } else if (strstr($ex->getMessage(), '401 Unauthorized')) {
                                                Log::error('Refresh Access token. In file filePath:App\Console\Commands\Ams\Keyword\ListData\listoftarget');
                                                Artisan::call('updateGetAccessToken:amsAuth ' . $singleToken->id);
                                                goto b;
                                            } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                                // store profile list not valid
                                                Log::error("In Valid Profile");
                                                $inValidProfile = new InValidProfile();
                                                $inValidProfile->addInValidProfile($rule->id, $campaign->profile_id, $campaign->campaign_id);
                                            } // end else if
                                        } else if ($ex->getCode() == 429) {
                                            sleep(Config::get('constants.sleepTime') + 2);
                                            goto b;
                                        } else if ($ex->getCode() == 502) {
                                            sleep(Config::get('constants.sleepTime') + 2);
                                            goto b;
                                        } // end else if
                                        Log::error($ex->getMessage());
                                    }// end catch
                                } // end else
                            }  // end foreach
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
        Log::info("filePath:App\Console\Commands\Ams\Target\Data\listoftarget. End Cron.");
    } // end function
} // end class
