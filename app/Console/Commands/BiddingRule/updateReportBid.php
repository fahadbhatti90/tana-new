<?php

namespace App\Console\Commands\BiddingRule;

use App\Model\Ams\AuthToken;
use App\Model\Ams\BiddingRule\InValidProfile;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Throwable;

class updateReportBid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateReportBid:updateBid {token_id} {campaign_type} {--bid_data=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update keyword bid of specific keyword.';

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
        $token_id = $this->argument('token_id');
        $campaign_type = $this->argument('campaign_type');
        $bid_data = $this->option('bid_data');
        Log::info("filePath:App\Console\Commands\Ams\Keyword\Data\updateReportBid. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();


        $profile_id = $bid_data['profile_id'];
        $campaign_id = $bid_data['campaign_id'];
        $ad_group_id = $bid_data['ad_group_id'];
        $state = $bid_data['state'];
        $ad_type = $bid_data['ad_type'];
        $old_bid = $bid_data['old_bid'];
        $new_bid = $bid_data['new_bid'];

        $url = '';
        $jsonArray = array();
        $jsonData = null;

        if ($ad_type == "SP" && $campaign_type == "keyword") {
            // Url and Json Data configuration for SP Keyword type value
            $keyword_id = $bid_data['keyword_id'];
            $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.spKeywordUpdate');
            $jsonObj = (object)[
                'keywordId' => $keyword_id,
                'state' => $state,
                'bid' => $new_bid,
            ];
            array_push($jsonArray, $jsonObj);
            $jsonData = $jsonArray;
        } else if ($ad_type == "SP" && $campaign_type == "target") {
            // Url and Json Data configuration for SP target type value
            $target_id = $bid_data['target_id'];
            $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.spTargetsUpdate');
            $jsonObj = (object)[
                'targetId' => $target_id,
                'state' => $state,
                'bid' => $new_bid
            ];
            array_push($jsonArray, $jsonObj);
            $jsonData = $jsonArray;
        } else if ($ad_type == "SB" && $campaign_type == "keyword") {
            // Url and Json Data configuration for SB Keyword type value
            $keyword_id = $bid_data['keyword_id'];
            $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.sbKeywordUpdate');
            $jsonObj = (object)[
                'keywordId' => $keyword_id,
                'adGroupId' => $ad_group_id,
                'campaignId' => $campaign_id,
                'state' => $state,
                'bid' => $new_bid,
            ];
            array_push($jsonArray, $jsonObj);
            $jsonData = $jsonArray;
        } else if ($ad_type == "SB" && $campaign_type == "target") {
            // Url and Json Data configuration for SB target type value
            $target_id = $bid_data['target_id'];
            $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.sbTargetsUpdate');
            $jsonObj = (object)[
                'targets' => [
                    [
                        'targetId' => $target_id,
                        'adGroupId' => $ad_group_id,
                        'campaignId' => $campaign_id,
                        'state' => $state,
                        'bid' => $new_bid
                    ]
                ]
            ];
            $jsonData = $jsonObj;
        } else if ($ad_type == "SD" && $campaign_type == "target") {
            // Url and Json Data configuration for SD target type value
            $target_id = $bid_data['target_id'];
            $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.sdTargetsUpdate');
            $jsonObj = (object)[
                'targetId' => $target_id,
                'state' => $state,
                'bid' => $new_bid,
            ];
            array_push($jsonArray, $jsonObj);
            $jsonData = $jsonArray;
        } // end else if
        if ($url != '' && !empty($jsonData)) {
            $client = new Client();
            a:
            try {
                $token = AuthToken::find($token_id);
                $response = $client->request('PUT', $url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token->access_token,
                        'Content-Type' => 'application/json',
                        'Amazon-Advertising-API-ClientId' => Config::get('amsconstants.client_id'),
                        'Amazon-Advertising-API-Scope' => $profile_id
                    ],
                    'json' => $jsonData,
                    'delay' => Config::get('amsconstants.delayTimeInApi'),
                    'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                    'timeout' => Config::get('amsconstants.timeoutInApi'),
                ]);
                $body = json_decode($response->getBody()->getContents());
                if (!empty($body) && $body != null) {
                    Tracker::insertTrackRecord('Bidding Rule Bid Data is updated', 'Record found');
                } // end if
            } catch (\Exception $ex) {
                if ($ex->getCode() == 401) {
                    if (strstr($ex->getMessage(), '401 Unauthorized')) {
                        Log::error('Refresh Access token. In file filePath:App\Console\Commands\Ams\Keyword\Data\updateReportBid');
                        Artisan::call('updateGetAccessToken:amsAuth ' . $token_id);
                        goto a;
                    } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                        // store profile list not valid
                        Log::error("In Valid Profile");
                        $inValidProfile = new InValidProfile();
                        $inValidProfile->addInValidProfile($token_id, $profile_id, $campaign_id);
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
        } // end if
        Log::info("filePath:App\Console\Commands\Ams\Keyword\Data\updateReportBid. End Cron.");
    } // end function
} // end class
