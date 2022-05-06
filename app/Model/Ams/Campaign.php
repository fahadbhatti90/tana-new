<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Throwable;

class Campaign extends Model
{
    protected $table = 'tbl_ams_campaigns';
    protected $primaryKey = 'id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @param $profile_id
     * @param string $string
     * @param array $DataActiveCampaignIDArray
     */
    public static function updateCampaignRecords($profile_id, string $type, array $DataActiveCampaignIDArray)
    {
        // update all profile status
        DB::table('tbl_ams_campaigns')
            ->where('profile_id', $profile_id)
            ->where('type', $type)
            ->whereNotIn('campaign_id', $DataActiveCampaignIDArray)
            ->update(['is_active' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * @return BelongsTo
     */
    public function getProfile(){
        return $this->belongsTo('App\Model\Ams\Portfolio','profile_id', 'profile_id')->orderBy('portfolios_name', 'asc');
    }

    /**
     * @return BelongsTo
     */
    public function getPortfolio(){
        return $this->belongsTo('App\Model\Ams\Portfolio','portfolios_id', 'portfolios_id')->orderBy('name', 'asc');
    }
}
