<?php

namespace App\Model\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesReport extends Model
{

    /**
     * Get the Facts Of Sales Summary.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function saleViewSummary($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_sale_view_summary(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Date Of Sales Graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function saleViewGraph($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_sale_view_graph(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Date Of Sales Graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function saleTopAsinDecrease($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_sale_view_top_asin_decrease(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Date Of Sales Graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function saleTopAsinIncrease($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_sale_view_top_asin_increase(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Date Of Sales Graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function salesTopAsinShippedCOGS($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_sale_view_top_asin_shipped_cogs(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Date Of Sales Graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function saleViewCategory($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_sale_view_category(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }
}
