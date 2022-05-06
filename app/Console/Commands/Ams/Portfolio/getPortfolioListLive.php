<?php

namespace App\Console\Commands\AMS\Portfolio;

use App\Models\AMSModel;
use App\Models\DayPartingModels\Portfolios;
use Artisan;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class getPortfolioListLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getPortfolioListLive:portfolio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get portfolio details';

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
        Log::info("filePath:Commands\Ams\Portfolio\getPortfolioListLive. Start Cron.");
        Log::info($this->description);
        Log::info("Auth Access token get from DB Start!");
        $obAccessToken = new AMSModel();
        // Get Access Token
        $dataAccessToken['accessToken'] = $obAccessToken->getAMSToken();
        if ($dataAccessToken['accessToken'] != FALSE) {
            // Get client Id
            $obClientId = new AMSModel();
            $dataClientId['clientId'] = $obClientId->getParameter();
            if ($dataClientId['clientId'] != FALSE) {
                $allProfileIdsObject = new AMSModel();
                $allProfileIds = $allProfileIdsObject->getAllProfiles();
                if (!empty($allProfileIds)) {
                    foreach ($allProfileIds as $single) {
                        $responseBody = [];
                        // Defined Url to get all portfolio against profiles
                        $url = Config::get('constants.amsApiUrl') . '/' . Config::get('constants.apiVersion') . '/' . Config::get('constants.amsPortfolioUrl');
                        b:
                        try {
                            $client = new Client();
                            $response = $client->request('GET', $url, [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . $dataAccessToken['accessToken']->access_token,
                                    'Content-Type' => 'application/json',
                                    'Amazon-Advertising-API-ClientId' => $dataClientId['clientId']->client_id,
                                    'Amazon-Advertising-API-Scope' => $single->profileId
                                ],
                                'delay' => Config::get('constants.delayTimeInApi'),
                                'connect_timeout' => Config::get('constants.connectTimeOutInApi'),
                                'timeout' => Config::get('constants.timeoutInApi'),
                            ]);
                            $responseBody = json_decode($response->getBody()->getContents());
                            if (!empty($responseBody) && !is_null($responseBody)) {
                                $PortfolioDataArray = [];
                                $PortfolioDataInsert = [];
                                Log::info('Portfolio Record Found');
                                foreach ($responseBody as $singleResponseRecord) {
                                    $PortfolioDataArray['profileId'] = $single->profileId;
                                    $PortfolioDataArray['fkProfileId'] = $single->id;
                                    $PortfolioDataArray['portfolioId'] = $singleResponseRecord->portfolioId;
                                    $PortfolioDataArray['name'] = $singleResponseRecord->name;
                                    if (isset($singleResponseRecord->budget)) {
                                        $PortfolioDataArray['amount'] = $singleResponseRecord->budget->amount;
                                        $PortfolioDataArray['currencyCode'] = $singleResponseRecord->budget->currencyCode;
                                        $PortfolioDataArray['policy'] = $singleResponseRecord->budget->policy;
                                    }
                                    $PortfolioDataArray['inBudget'] = $singleResponseRecord->inBudget;
                                    $PortfolioDataArray['state'] = $singleResponseRecord->state;
                                    $PortfolioDataArray['sandBox'] = 0;
                                    $PortfolioDataArray['live'] = 1;
                                    $PortfolioDataArray['createdAt'] = date('Y-m-d H:i:s');
                                    $PortfolioDataArray['updatedAt'] = date('Y-m-d H:i:s');
                                    array_push($PortfolioDataInsert, $PortfolioDataArray);
                                } // End Foreach Loop for making insertion data of portfolis
                                Portfolios::storePortfolios($PortfolioDataInsert);
                                Log::info('Portfolio inserted against Profile Id : ' . $single->profileId);
                            } else {
                                // Portfolios status
                                Log::error('report name : Get Portfolios Against' . ' profile id: ' . $single->profileId . 'not record found . portfolio details');
                                AMSModel::insertTrackRecord('report name : Get Portfolios Against' . ' profile id: ' . $single->profileId, 'not record found');
                            }
                        } catch (\Exception $ex) {
                            if ($ex->getCode() == 401) {
                                if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                    Log::error('Refresh Access token. In file filePath:Commands\Ams\Portfolio\getPortfolioListLive');
                                    Artisan::call('getaccesstoken:amsauth');
                                    $obAccessToken = new AMSModel();
                                    $dataAccessToken['accessToken'] = $obAccessToken->getAMSToken();
                                    goto b;
                                } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                    // store profile list not valid
                                    Log::info("Invalid Profile Id: " . $single->profileId);
                                }
                            } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                sleep(Config::get('constants.sleepTime') + 2);
                                goto b;
                            } else if ($ex->getCode() == 502) {
                                sleep(Config::get('constants.sleepTime') + 2);
                                goto b;
                            }
                            // store report status
                            AMSModel::insertTrackRecord('Profile List Id : Get Portfolios', 'fail');
                            Log::error($ex->getMessage());
                        }// end catch

                    } // End Foreach Loop
                } else {
                    Log::info("Profile List not found.");
                }
            } else {
                Log::info("Client Id not found.");
            } // End if else for checking Client Id

        } else {
            Log::info("Auth Access token not found.");
        } // End if else for checking Access Token

        Log::info("filePath:Commands\Ams\Portfolio\getPortfolioListLive. End Cron.");
    }
}
