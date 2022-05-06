<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmsCampaignLoad extends Model
{
    /**
     * Get the Load Daily Campaign Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyCampaing($startDate, $endDate)
    {
        return DB::select('call sp_master_load_ams_campaign(?,?)', array($startDate, $endDate));
    }


    /**
     * Get the Load Weekly Campaign Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklyCampaing($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_ams_campaign_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Campaign Records to SDM Tables.
     * sp_load_fact_inventory_monthly    old sp 
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlyCampaing($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_ams_campaign_monthly(?,?)', array($startDate, $endDate));
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
        FROM `metadata_fact_ams_campaign`");
    }
}
