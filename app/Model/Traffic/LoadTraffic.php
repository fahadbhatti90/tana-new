<?php

namespace App\Model\Traffic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoadTraffic extends Model
{
    /**
     * Get the Load Daily Sales Ordered Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyTraffic($startDate, $endDate)
    {
        return DB::select('call sp_master_load_traffic(?,?)', array($startDate, $endDate));
    }


    /**
     * Get the Load Weekly Sales Ordered Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklyTraffic($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_traffic_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Sales Ordered Records to SDM Tables.
     * sp_load_fact_inventory_monthly    old sp
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlyTraffic($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_traffic_monthly(?,?)', array($startDate, $endDate));
    }
}
