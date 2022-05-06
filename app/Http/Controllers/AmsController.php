<?php

namespace App\Http\Controllers;

use App\Model\Ams\AuthToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class AmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = AuthToken::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('client_id', function($data){
                    return substr($data->client_id, 0, 20)." . . .";
                })
                ->addColumn('client_secret', function($data){
                    return substr($data->client_secret, 0, 20)." . . .";
                })
                ->addColumn('number_of_profiles', function($data){
                    return $data->number_of_profiles;
                })
                ->addColumn('creation_date', function($data){
                    return date('M d, Y', strtotime($data->creation_date));
                })
                ->addColumn('status', function($data){
                    if($data->expire_flag == 1){
                        return '<span class="badge badge-danger badge-pill mr-2 test ">Expire</span>';
                    }else{
                        return '<span class="badge badge-info badge-pill mr-2 test ">Active</span>';
                    }
                })
                ->rawColumns(['status'])
                ->make(true);
        }
        return view('ams.login-with-amazon');
    }

    /**
     * This function is used to get code of ams for authorization
     * @param Request $request
     * @return void
     * @throws GuzzleException
     */
    public function code(Request $request)
    {
        $codeValues = $request->code;
        if ($codeValues != NULL) {
            try {
                $client_id = Config::get('amsconstants.client_id');
                $client_secret = Config::get('amsconstants.client_secret');;
                $post_data = [
                    'grant_type' => 'authorization_code',
                    'code' => $codeValues,
                    'redirect_uri' => route('ams.code'),
                    'client_id' => $client_id,
                    'client_secret' => $client_secret
                ];
                // Get Response CURL call
                $tokenUrl = Config::get('amsconstants.amsAuthUrl');
                $client = new Client();
                $response = $client->request('POST', $tokenUrl, [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'charset' => 'UTF-8'
                    ],
                    'form_params' => $post_data,
                    'delay' => Config::get('amsconstants.delayTimeInApi'),
                    'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                    'timeout' => Config::get('amsconstants.timeoutInApi'),
                ]);
                $authBody = json_decode($response->getBody()->getContents());
                Log::info('Login With Amazon');
                Log::info(json_encode($authBody));
                if(!empty($authBody)){
                    $profileUrl = Config::get('amsconstants.amsApiUrl') . '/' . Config::get('amsconstants.apiVersion') . '/' . Config::get('amsconstants.amsProfileUrl');
                    // Get Response CURL call
                    $client = new Client();
                    $responseProfile = $client->request('GET', $profileUrl, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $authBody->access_token,
                            'Content-Type' => 'application/json',
                            'Amazon-Advertising-API-ClientId' => $client_id
                        ],
                        'delay' => Config::get('amsconstants.delayTimeInApi'),
                        'connect_timeout' => Config::get('amsconstants.connectTimeOutInApi'),
                        'timeout' => Config::get('amsconstants.timeoutInApi'),
                    ]);
                    $bodyProfile = json_decode($responseProfile->getBody()->getContents());

                    $array_data = array();
                    $array_data['code'] = $codeValues;
                    $array_data['client_id'] = $client_id;
                    $array_data['client_secret'] = $authBody->access_token;
                    $array_data['refresh_token'] = $authBody->refresh_token;
                    $array_data['number_of_profiles'] = (!empty($bodyProfile)) ? count($bodyProfile) : 0;
                    $array_data['token_type'] = $authBody->token_type;
                    $array_data['expires_in'] = $authBody->expires_in;
                    $array_data['expire_flag'] = 0;
                    $array_data['creation_date'] = date('Y-m-d H:i:s');
                    AuthToken::addAMSToken($array_data, $client_id);
                    Log::info(json_encode($bodyProfile));
                }
            } catch (\Exception $ex) {
                dd($ex);
            }
        }
    }
}
