<?php

namespace App\Console\Commands\Ams\Profile;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Profile;
use Artisan;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SandboxProfileCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getprofileid:amssandboxprofile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get all sand box profile list';

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
     */
    public function handle()
    {
        Log::info("filePath:Commands\Ams\SandboxProfile. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        Log::info("AMS Auth token get from DB Start!");
        $authToken = AuthToken::where('number_of_profiles', '>', 0)
            ->where('expire_flag', 0)
            ->get();
        if (!$authToken->isEmpty()) {
            // Create a client with a base URI
            $url = Config::get('constants.testingAmsApiUrl') . '/' . Config::get('constants.apiVersion') . '/' . Config::get('constants.amsProfileUrl');
            foreach ($authToken as $singleToken) {
                a:
                $token = AuthToken::find($singleToken->id);
                try {
                    $client = new Client();
                    $response = $client->request('GET', $url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token->access_token,
                            'Content-Type' => 'application/json',
                            'Amazon-Advertising-API-ClientId' => $token->client_id
                        ],
                        'delay' => Config::get('amsconstants.delayTimeInApi'),
                        'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                        'timeout' => Config::get('amsconstants.timeoutInApi'),
                    ]);
                    $body = json_decode($response->getBody()->getContents());
                    $totalValue = count($body);
                    if (!empty($body) && $totalValue > 0) {
                        $DataActiveProfileIDArray = [];
                        for ($i = 0; $i < $totalValue; $i++) {
                            $isProfileExist = Profile::where('profile_id', $body[$i]->profileId)->get()->first();
                            if (!isset($isProfileExist)) {
                                $profile = new Profile();
                                $profile->fk_access_token = $singleToken->id;
                                $profile->profile_id = $body[$i]->profileId;
                                $profile->country_code = $body[$i]->countryCode;
                                $profile->currency_code = $body[$i]->currencyCode;
                                $profile->time_zone = $body[$i]->timezone;
                                $profile->market_place_string_id = (isset($body[$i]->accountInfo->marketplaceStringId) ? $body[$i]->accountInfo->marketplaceStringId : 'NA');
                                $profile->entity_id = (isset($body[$i]->accountInfo->id) ? $body[$i]->accountInfo->id : 'NA');
                                $profile->type = (isset($body[$i]->accountInfo->type) ? $body[$i]->accountInfo->type : 'NA');
                                $profile->name = (isset($body[$i]->accountInfo->name) ? $body[$i]->accountInfo->name : 'NA');
                                $profile->ad_group_sp_sixty_days = 0; //0
                                $profile->asins_sixty_days = 0; //0
                                $profile->campaign_sp_sixty_days = 0;
                                $profile->keyword_sb_sixty_days = 0;
                                $profile->keyword_sp_sixty_days = 0;
                                $profile->search_term_sp_sixty_days = 0;
                                $profile->product_ads_sixty_days = 0;
                                $profile->product_targeting_sixty_days = 0;
                                $profile->sponsored_brand_campaigns_sixty_days = 0;
                                $profile->sponsored_display_campaigns_sixty_days = 0;
                                $profile->sponsored_display_product_targeting_sixty_days = 0;
                                $profile->sponsored_display_audiences_targeting_sixty_days = 0;
                                $profile->sponsored_display_adgroup_sixty_days = 0;
                                $profile->sponsored_display_productads_sixty_days = 0;
                                $profile->sponsored_brand_adgroup_sixty_days = 0;
                                $profile->sponsored_brand_targeting_sixty_days = 0;
                                $profile->is_sandbox_profile = 1;
                                $profile->is_active = 1;
                                $profile->status = 'NA';
                                $profile->save();
                                array_push($DataActiveProfileIDArray, $profile->id);
                            } else {

                                $isProfileExist->fk_access_token = $singleToken->id;
                                $isProfileExist->profile_id = $body[$i]->profileId;
                                $isProfileExist->country_code = $body[$i]->countryCode;
                                $isProfileExist->currency_code = $body[$i]->currencyCode;
                                $isProfileExist->time_zone = $body[$i]->timezone;
                                $isProfileExist->market_place_string_id = (isset($body[$i]->accountInfo->marketplaceStringId) ? $body[$i]->accountInfo->marketplaceStringId : 'NA');
                                $isProfileExist->entity_id = (isset($body[$i]->accountInfo->id) ? $body[$i]->accountInfo->id : 'NA');
                                $isProfileExist->type = (isset($body[$i]->accountInfo->type) ? $body[$i]->accountInfo->type : 'NA');
                                $isProfileExist->name = (isset($body[$i]->accountInfo->name) ? $body[$i]->accountInfo->name : 'NA');
                                $isProfileExist->is_active = 1;
                                $isProfileExist->last_update = date('Y-m-d H:i:s');
                                $isProfileExist->save();
                                array_push($DataActiveProfileIDArray, $isProfileExist->id);
                            } // end else statement
                        } // end for loop
                        Profile::updateSandboxProfileRecords($DataActiveProfileIDArray);
                    } else {
                        // store status
                        Log::info("Response empty");
                    }
                } catch (\Exception $ex) {
                    if ($ex->getCode() == 401) {
                        if (strstr($ex->getMessage(), '401 Unauthorized')) { // if auth token expire
                            Log::error('Refresh Access token. In file filePath:Commands\Ams\Profile');
                            Artisan::call('updateGetAccessToken:amsAuth ' . $singleToken->id);
                            goto a;
                        } elseif (strstr($ex->getMessage(), 'advertiser found for scope')) {
                            // store profile list not valid
                            Log::info("Invalid Profile Id: ");
                            goto a;
                        }
                    } else if ($ex->getCode() == 429) { //https://advertising.amazon.com/API/docs/v2/guides/developer_notes#Rate-limiting
                        sleep(Config::get('amsconstants.sleepTime'));
                    }
                    // store status
                    Log::error($ex->getMessage());
                }
            }
        } else {
            Log::info("AMS access token not found.");
        }
        Log::info("filePath:Commands\Ams\SandboxProfile. End Cron.");
    }
}
