<?php

namespace App\Console\Commands\AMS\Portfolio;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Portfolio;
use App\Model\Ams\Profile;
use App\Model\Ams\Tracker;
use Artisan;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class getPortfolioList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getPortfolioDetailData:portfolio';

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
        Log::info("filePath:Commands\Ams\Portfolio. Start Cron.");
        Log::info($this->description);
        Log::info("Auth Access token get from DB Start!");
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
                        $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.amsPortfolioUrl');
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
                                        $PortfolioDataInsert = [];
                                        foreach ($responseBody as $singleResponseRecord) {
                                            $isPortfolioExist = Portfolio::where('portfolios_id', $singleResponseRecord->portfolioId)->get()->first();
                                            $portfolio = $isPortfolioExist;
                                            // check if campaign is not exist
                                            if (!isset($isPortfolioExist)) {
                                                $portfolio = new Portfolio();
                                            } // end if
                                            $portfolio->profile_id = $profile->profile_id;
                                            $portfolio->fk_profile_id = $profile->id;
                                            $portfolio->fk_access_token = $profile->fk_access_token;
                                            $portfolio->portfolios_id = $singleResponseRecord->portfolioId;
                                            $portfolio->portfolios_name = $singleResponseRecord->name;
                                            $portfolio->amount = isset($singleResponseRecord->budget) ? $singleResponseRecord->budget->amount : 0.00;
                                            $portfolio->currency_code = isset($singleResponseRecord->budget) ? $singleResponseRecord->budget->currencyCode : "NA";
                                            $portfolio->policy = isset($singleResponseRecord->budget) ? $singleResponseRecord->budget->policy : "NA";
                                            $portfolio->in_budget = $singleResponseRecord->inBudget;
                                            $portfolio->state = $singleResponseRecord->state;
                                            $portfolio->is_sandbox = $profile->is_sandbox_profile;
                                            $portfolio->created_at = date('Y-m-d H:i:s');
                                            $portfolio->updated_at = date('Y-m-d H:i:s');
                                            $portfolio->save();
                                        } // end foreach
                                        Tracker::insertTrackRecord('Report Name : Get Portfolios List Against' . ' Profile ID: ' . $profile->profile_id . ' Found', 'record found');
                                    } else {
                                        // Portfolio status
                                        Tracker::insertTrackRecord('Report Name : Get Portfolios List Against' . ' Profile ID: ' . $profile->profile_id . ' Not Found', 'not record found');
                                    } // end else
                                } catch (\Exception $ex) {
                                    if ($ex->getCode() == 401) {
                                        if (strstr($ex->getMessage(), 'Not authorized to access this advertiser')) {
                                            Log::error('Not authorized to access this advertiser');
                                        } else if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                                            Log::error('Refresh Access token. In file filePath:Commands\Ams\Portfolio\getPortfolioDetailData');
                                            Artisan::call('updateGetAccessToken:amsAuth ' . $profile->fk_access_token);
                                            Artisan::call('getprofileid:updateamsprofile ' . $profile->profile_id);
                                            goto a;
                                        } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                                            // store profile list not valid
                                            Log::info("Invalid Profile Id: " . $profile->profile_id);
                                        } // end else if
                                    } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } else if ($ex->getCode() == 502) {
                                        sleep(Config::get('amsconstants.sleepTime') + 2);
                                        goto a;
                                    } // end else if
                                    // store report status
                                    Tracker::insertTrackRecord('Profile List Id : Get Portfolios', 'fail');
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
        Log::info("filePath:Commands\Ams\Portfolio. End Cron.");
    } // end function
} // end class
