<?php

namespace App\Console\Commands\Ams\Keyword\SP;

use App\Models\AMSModel;
use Artisan;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class getKeywordList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keywordBid:keywordBidSP';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get keyword Bid value for Ad Type SP.';

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
        Log::info("filePath:App\Console\Commands\Ams\Keyword\SP\getKeywordList. Start Cron.");
        Log::info($this->description);
        $obaccess_token = new AMSModel();
        $dataaccess_token['accessToken'] = $obaccess_token->getAMSToken();
        if ($dataaccess_token['accessToken'] != FALSE) {
            $obClientId = new AMSModel();
            $dataClientId['ClientId'] = $obClientId->getParameter();
            if ($dataClientId['ClientId'] != FALSE) {
                $getData = AMSModel::getSPCampaignList();
                if (!empty($getData)) {
                    $DataArray = array();
                    foreach ($getData as $single) {
                        $url = Config::get('constants.amsApiUrl') . '/' . Config::get('constants.apiVersion') . '/' . Config::get('constants.spKeywordList') . '?startIndex=0&campaignType=sponsoredProducts&campaignIdFilter=' . $single->campaignId;
                        b:
                        $client = new Client();
                        $body = array();
                        try {
                            $response = $client->request('GET', $url, [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . $dataaccess_token['accessToken']->access_token,
                                    'Content-Type' => 'application/json',
                                    'Amazon-Advertising-API-ClientId' => $dataClientId['ClientId']->client_id,
                                    'Amazon-Advertising-API-Scope' => $single->profileId],
                                'delay' => Config::get('constants.delayTimeInApi'),
                                'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                                'timeout' => Config::get('constants.timeoutInApi'),
                            ]);
                            $body = json_decode($response->getBody()->getContents());
                            if (!empty($body)) {
                                AMSModel::updateKeywordBidProfileStatus($single->profileId, $single->campaignId);
                                $DataArray = array();
                                Log::info("Make Array For Data Insertion");
                                for ($i = 0; $i < count($body); $i++) {
                                    $storeArray = [];
                                    $storeArray['profileId'] = $single->profileId;
                                    $storeArray['fkAccountId'] = $single->fkAccountId;
                                    $storeArray['adtype'] = 'sponsoredProducts';
                                    $storeArray['type'] = 'SP';
                                    $storeArray['keywordId'] = $body[$i]->keywordId;
                                    $storeArray['adGroupId'] = $body[$i]->adGroupId;
                                    $storeArray['campaignId'] = $body[$i]->campaignId;
                                    $storeArray['keywordText'] = $body[$i]->keywordText;
                                    $storeArray['matchType'] = $body[$i]->matchType;
                                    $storeArray['state'] = $body[$i]->state;
                                    $storeArray['bid'] = isset($body[$i]->bid) ? $body[$i]->bid : '0.00';
                                    $storeArray['servingStatus'] = isset($body[$i]->servingStatus) ? $body[$i]->servingStatus : 'NA';
                                    $storeArray['creationDate'] = isset($body[$i]->creationDate) ? $body[$i]->creationDate : 'NA';
                                    $storeArray['lastUpdatedDate'] = isset($body[$i]->lastUpdatedDate) ? $body[$i]->lastUpdatedDate : 'NA';
                                    $storeArray['reportDate'] = date('Ymd', strtotime('-1 day', time()));
                                    $storeArray['createdAt'] = date('Y-m-d H:i:s');
                                    $storeArray['updatedAt'] = date('Y-m-d H:i:s');
                                    array_push($DataArray, $storeArray);
                                }// end for loop
                                if (!empty($DataArray)) {
                                    // store data
                                    AMSModel::storeKeywordBidData($DataArray);
                                }
                            } else {
                                // if body is empty
                            }
                        } catch (\Exception $ex) {
                            if ($ex->getCode() == 401) {
                                if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                    Log::error('Refresh Access token. In file filePath:App\Console\Commands\Ams\Keyword\SP\getKeywordList');
                                    Artisan::call('getaccesstoken:amsauth');
                                    $obaccess_token = new AMSModel();
                                    $dataaccess_token['accessToken'] = $obaccess_token->getAMSToken();
                                    goto b;
                                } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                    // store profile list not valid
                                    BiddingRule::inValidProfile($single->profileId, $single->campaignId);
                                }
                            } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                sleep(Config::get('constants.sleepTime') + 2);
                                goto b;
                            } else if ($ex->getCode() == 502) {
                                sleep(Config::get('constants.sleepTime') + 2);
                                goto b;
                            }
                            Log::error($ex->getMessage());
                        }// end catch
                    }// end foreach
                } else {
                    Log::info("campaign SP data not found.");
                }
            } else {
                Log::info("Client Id not found.");
            }
        } else {
            Log::info("AMS access token not found.");
        }
        Log::info("filePath:App\Console\Commands\Ams\Keyword\SP\getKeywordList. End Cron.");
    }
}
