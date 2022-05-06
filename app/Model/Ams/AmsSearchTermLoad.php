<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmsSearchTermLoad extends Model
{
    /**
     * Get the Load Daily Search Term Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailySearchTerm($startDate, $endDate)
    {
        return DB::select('call sp_master_load_ams_keyword_search_term(?,?)', array($startDate, $endDate));
    }


    /**
     * Get the Load Weekly Search Term Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklySearchTerm($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_ams_keyword_search_term_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Search Term Records to SDM Tables.
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlySearchTerm($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_ams_keyword_search_term_monthly(?,?)', array($startDate, $endDate));
    }

    /**
     * Move Data from src_sales To core by sp.
     */
    public static function getDashboardRecord()
    {
        return DB::select("SELECT
        `profile_id`
        ,`profile_name`
        ,`report_type`
        ,`daily_max_date`
        ,`weekly_max_date`
        ,`monthly_max_date`
        ,`inserted_at`
        FROM `metadata_fact_ams_keyword_search_term`");
    }
}
