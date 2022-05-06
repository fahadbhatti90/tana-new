<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmsProductAdsLoad extends Model
{
    /**
     * Get the Load Daily Product Ads Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyProductAds($startDate, $endDate)
    {
        return DB::select('call sp_master_load_ams_product_ads(?,?)', array($startDate, $endDate));
    }


    /**
     * Get the Load Weekly Product Ads Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklyProductAds($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_ams_product_ads_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Product Ads Records to SDM Tables.
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlyProductAds($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_ams_product_ads_monthly(?,?)', array($startDate, $endDate));
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
        FROM `metadata_fact_ams_product_ads`");
    }
}
