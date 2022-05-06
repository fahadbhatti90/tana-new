<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmsTargetLoad extends Model
{
    /**
     * Get the Load Daily Campaign Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyKeyword()
    {
        return DB::select('call sp_etl_core_ams_bidding_rule_keyword()');
    }
    /**
     * Get the Load Daily Campaign Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyTarget()
    {
        return DB::select('call sp_etl_core_ams_bidding_rule_targets()');
    }
}
