<?php

namespace App\Model\Dropship;

use DB;

use Illuminate\Database\Eloquent\Model;

class LoadDropship extends Model
{
    /**
     * Get the Load Daily Dropship Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyDropship($startDate, $endDate)
    {
        return DB::select('call sp_master_load_dropship(?,?)', array($startDate, $endDate));
    }


    /**
     * Get the Load Weekly Dropship Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklyDropship($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_dropship_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Dropship Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlyDropship($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_dropship_monthly(?,?)', array($startDate, $endDate));
    }
}
