<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class AuthToken extends Model
{
    protected $table = 'tbl_ams_authtoken';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * This function is used to get only Profile List Associated with Access Token
     * @return HasMany
     */
    public function getProfileList()
    {
        return $this->hasMany('App\Model\Ams\Profile', 'fk_access_token', 'id')
            ->where('is_active','1')
            ->where('is_sandbox_profile','0')
            ->where('type', '!=', 'agency');
    }

    /**
     * This function is used to get only Sandbox Profile List Associated with Access Token
     * @return HasMany
     */
    public function getSandboxProfileList()
    {
        return $this->hasMany('App\Model\Ams\Profile', 'fk_access_token', 'id')
            ->where('is_active','1')
            ->where('is_sandbox_profile','1')
            ->where('type', '!=', 'agency');
    }

    /**
     * This function is used to get only Agency Profile List Associated with Access Token
     * @return HasMany
     */
    public function getAgencyProfileList()
    {
        return $this->hasMany('App\Model\Ams\Profile', 'fk_access_token', 'id')
            ->where('is_active','1')
            ->where('type', '=', 'agency');
    }

    /**
     * @param $data
     * @param $client_id
     */
    public static function addAMSToken($data, $client_id)
    {
        Log::info('Start Insert data into DB.');
        $record = AuthToken::select('id')->where('client_id' , $client_id)->get();
        if ($record->isEmpty()) {
            try {
                AuthToken::insert($data);
                // store track data
                Tracker::insertTrackRecord('Authentication Data', 'insert record');
            } catch (\Illuminate\Database\QueryException $ex) {
                Log::error($ex->getMessage());
            }
            Log::info('Insert Access Token.');
        } else {
            Log::info('Start Update Access Token.');
            try {
                AuthToken::where('client_id', $client_id)
                    ->update($data);
            } catch (\Illuminate\Database\QueryException $ex) {
                Log::error($ex->getMessage());
            }
            Log::info('End Update Access Token.');
        }
        Log::info('End Insertion query.');
    }

}
