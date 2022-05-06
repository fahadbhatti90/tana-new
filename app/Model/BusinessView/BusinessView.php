<?php

namespace App\Model\BusinessView;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BusinessView extends Model
{
    /**
     * Get Business KPI  value.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function businessReviewKPI($marketplace, $granularity,  $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_top_all_cards(?,?,?,?,?)', array($marketplace, $granularity, $vendor, $startDate, $endDate));
    } // end function
    /**
     * Get total Ad Sales By Type percentages.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function totalAdSalesByType($granularity, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_campaign_ad_sale_by_type(?,?,?,?)', array($granularity, $vendor, $startDate, $endDate));
    } // end function

    /**
     * Get campaign Spend By Type percentages.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function campaignSpendByType($granularity,  $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_campaign_spend_by_type(?,?,?,?)', array($granularity, $vendor, $startDate, $endDate));
    } // end function

    /**
     * Get the Top ASIN Sales Table data.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function salesTopAsinSales($granularity, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_top_10_asins(?,?,?,?)', array($granularity,  $vendor, $startDate, $endDate));
    } // end function
    /**
     * Get the Top ASIN Sales Table data.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function portfolioKpiData($granularity, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_portfolio_kpi(?,?,?,?)', array($granularity, $vendor, $startDate, $endDate));
    } // end function

    /**
     * Get the Top ASIN Decrease Table data.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function saleTopAsinDecrease($granularity, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_top_5_decrease(?,?,?,?)', array($granularity, $vendor, $startDate, $endDate));
    } // end function

    /**
     * Get the Top ASIN Increase Table data.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function saleTopAsinIncrease($granularity, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_top_5_increase(?,?,?,?)', array($granularity, $vendor, $startDate, $endDate));
    } // end function

    /**
     * Get Business Line Graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getLineGraphByType($marketplace, $granularity, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_choice_graph(?,?,?,?,?)', array($marketplace, $granularity, $vendor, $startDate, $endDate));
    } // end function
    /**
     * Get Search term by sp Table data.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getSearchTermSpData($granularity, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_business_review_search_term_table(?,?,?,?)', array($granularity,  $vendor, $startDate, $endDate));
    } // end function
} // end class
