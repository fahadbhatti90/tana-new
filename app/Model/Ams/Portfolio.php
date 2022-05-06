<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class Portfolio extends Model
{
    protected $table = 'tbl_ams_portfolios';
    protected $primaryKey = 'id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @param $profile_id
     * @param string $string
     * @param array $DataActiveCampaignIDArray
     */
    public static function updatePortfolioRecords($profile_id, array $DataActivePortfolioIDArray)
    {
        // update all profile status
        DB::table('tbl_ams_portfolios')
            ->where('profile_id', $profile_id)
            ->whereNotIn('campaign_id', $DataActivePortfolioIDArray)
            ->update(['is_active' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getProfile(){
        return $this->belongsTo('App\Model\Ams\Profile','profile_id', 'profile_id')->orderBy('name', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getCampaign(){
        return $this->hasMany('App\Model\Ams\Campaign', 'portfolios_id', 'portfolios_id')->orderBy('name', 'asc');
    }
}
