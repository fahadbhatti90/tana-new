<?php

namespace App\Console\Commands\Ams\Profile;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Profile;
use Artisan;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class UpdateProfileCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getprofileid:updateamsprofile {profileId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update profile list';

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
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Execute the console command.
     *
     */
    public function handle()
    {
        Log::info("filePath:Commands\Ams\Profile. Start Cron.");
        Log::info($this->description);
        $profileId = $this->argument('profileId');
        setMemoryLimitAndExeTime();
        $authToken = AuthToken::where('number_of_profiles', '>', 0)
            ->where('expire_flag', 0)
            ->get();
        if (!$authToken->isEmpty()) {
            // Create a client with a base URI
            $url = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.amsProfileUrl') . '/' . $profileId;
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
                    if (!empty($body)) {
                        $isProfileExist = Profile::where('profile_id', $body->profileId)->get()->first();
                        if (!isset($isProfileExist)) {
                            $profile = new Profile();
                            $profile->fk_access_token = $singleToken->id;
                            $profile->profile_id = $body->profileId;
                            $profile->country_code = $body->countryCode;
                            $profile->currency_code = $body->currencyCode;
                            $profile->time_zone = $body->timezone;
                            $profile->market_place_string_id = (isset($body->accountInfo->marketplaceStringId) ? $body->accountInfo->marketplaceStringId : 'NA');
                            $profile->entity_id = (isset($body->accountInfo->id) ? $body->accountInfo->id : 'NA');
                            $profile->type = (isset($body->accountInfo->type) ? $body->accountInfo->type : 'NA');
                            $profile->name = (isset($body->accountInfo->name) ? $body->accountInfo->name : 'NA');
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
                            $profile->is_sandbox_profile = 0;
                            $profile->is_active = 1;
                            $profile->status = 'NA';
                            $profile->save();
                        } else {
                            $isProfileExist->fk_access_token = $singleToken->id;
                            $isProfileExist->profile_id = $body->profileId;
                            $isProfileExist->country_code = $body->countryCode;
                            $isProfileExist->currency_code = $body->currencyCode;
                            $isProfileExist->time_zone = $body->timezone;
                            $isProfileExist->market_place_string_id = (isset($body->accountInfo->marketplaceStringId) ? $body->accountInfo->marketplaceStringId : 'NA');
                            $isProfileExist->entity_id = (isset($body->accountInfo->id) ? $body->accountInfo->id : 'NA');
                            $isProfileExist->type = (isset($body->accountInfo->type) ? $body->accountInfo->type : 'NA');
                            $isProfileExist->name = (isset($body->accountInfo->name) ? $body->accountInfo->name : 'NA');
                            $isProfileExist->is_active = 1;
                            $isProfileExist->status = 'NA';
                            $isProfileExist->save();
                        } // end else statement
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
                    $profileInfo = Profile::where('profile_id', $profileId)->get()->first();
                    if (isset($profileInfo->profile_id)) {
                        $profileInfo->is_active = 0;
                        $profileInfo->save();
                    }
                    Log::error($ex->getMessage());
                }
            }
        } else {
            Log::info("AMS access token not found.");
        }
        Log::info("filePath:Commands\Ams\Profile. End Cron.");
    }
}
