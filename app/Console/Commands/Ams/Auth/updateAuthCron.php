<?php

namespace App\Console\Commands\Ams\Auth;

use App\Model\Ams\AuthToken;
use App\Model\Ams\Tracker;
use Artisan;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class UpdateAuthCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateGetAccessToken:amsAuth {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to get and update Auth Access Token Flag';

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
     * @throws \Exception
     * Execute the console command.
     *
     */
    public function handle()
    {
        $id = $this->argument('id');
        Log::info("filePath:Commands\Ams\Auth\UpdateAuthCron. Start Cron.");
        Log::info($this->description);
        setMemoryLimitAndExeTime();
        // check AMS client ID and Client secret key Founded or not
        $authToken = AuthToken::where('id', $id)->get();
        if ($authToken !== null) {
            foreach ($authToken as $data) {
                if (isset($data) && !empty($data)) {
                    $client = new Client();
                    //Create a client with a base URI
                    $url = Config::get('amsconstants.amsAuthUrl');
                    $post_data = [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $data->refresh_token,
                        'client_id' => Config::get('amsconstants.client_id'),
                        'client_secret' => Config::get('amsconstants.client_secret')
                    ];
                    try {
                        // Get Response CURL call
                        $response = $client->request('POST', $url, [
                            'headers' => [
                                'Content-Type' => 'application/x-www-form-urlencoded',
                                'charset' => 'UTF-8',
                            ],
                            'form_params' => $post_data,
                            'delay' => Config::get('amsconstants.delayTimeInApi'),
                            'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                            'timeout' => Config::get('amsconstants.timeoutInApi'),
                        ]);
                        $body = json_decode($response->getBody()->getContents());
                        if (!empty($body)) {
                            // store track data
                            Tracker::insertTrackRecord('Authentication Data', 'record found');
                            $array_data = array();
                            $array_data['access_token'] = $body->access_token;
                            $array_data['refresh_token'] = $body->refresh_token;
                            $array_data['token_type'] = $body->token_type;
                            $array_data['expires_in'] = $body->expires_in;
                            $array_data['last_update'] = date('Y-m-d H:i:s');
                            $array_data['expire_flag'] = 0;
                            AuthToken::addAMSToken($array_data, $data->client_id);
                        } else {
                            // store track data
                            Tracker::insertTrackRecord('Authentication Data', 'not record found');
                        }
                    } catch (\Exception $ex) {
                        // store report status
                        Tracker::insertTrackRecord('UpdateAuthCron Token', 'fail');
                        Log::error($ex->getMessage());
                    }
                } else {
                    Log::info("AMS client id and secret key not found.");
                }
            }
        }
        Log::info("filePath:Commands\Ams\Auth\UpdateAuthCron. End Cron.");
    }
}
