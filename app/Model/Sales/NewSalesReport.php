<?php

namespace App\Model\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewSalesReport extends Model
{
    /**
     * Get the Facts Of Shipped COGS Gauge percentages and value.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function shippedCogsGauge($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_shipped_cogs_value(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Facts Of Shipped COGS trailing 6 months.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function shippedCogsGaugeTrailing($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_shipped_cogs_trailling(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Facts Of Net Receipts Gauge (Percentage and value).
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function netReceiptsGauge($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_net_received_value(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Facts Of Net Receipt Trailing value.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function netReceiptsGaugeTrailing($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_net_received_trailling(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Facts Of PO Plan Gauge percentages and value..
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function poPlanGauge($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_confirmation_rate_value(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Facts Of PO Plan Trailing.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function poPlanGaugeTrailing($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_confirmation_rate_yoy_trailling(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Facts Of Growth chart.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function yoyGrowthChart($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_confirmation_rate_graph(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Facts Of Shipped COGS Bar Chart.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function shippedCogsByGranularityChart($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_shipped_cogs_graph(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Subcategory of Vendor.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getVendorSubcategory($brand, $vendor)
    {
        return DB::connection('mysql2')->select('call sp_view_get_all_subcategory(?,?)', array($brand, $vendor));
    }

    /**
     * Get the SIP(Sales, Inventory & PO) Subcategory Value.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function sipSubcategoryValue($granularity, $brand, $vendor, $startDate, $endDate, $subcategory)
    {
        return DB::connection('mysql2')->select('call sp_view_sip_subcategory_value(?,?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate, $subcategory));
    }

    /**
     * Get the Facts Of Shipped COGS by subcategory Chart.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @param $subcategory
     * @return array
     */
    public static function shippedCogsBySubcategoryChart($granularity, $brand, $vendor, $startDate, $endDate, $subcategory)
    {
        return DB::connection('mysql2')->select('call sp_view_shipped_cogs_subcategory_trailling(?,?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate, $subcategory));
    }


    /**
     * Get the Facts Of Net Receipts by subcategory Chart.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @param $subcategory
     * @return array
     */
    public static function netReceiptsBySubcategoryChart($granularity, $brand, $vendor, $startDate, $endDate, $subcategory)
    {
        return DB::connection('mysql2')->select('call sp_view_net_received_subcategory_trailling(?,?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate, $subcategory));
    }

    /**
     * Get the Facts Of PO Confirmed Rate by subcategory Chart.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @param $subcategory
     * @return array
     */
    public static function poConfirmedRateChart($granularity, $brand, $vendor, $startDate, $endDate)
    {
        return DB::connection('mysql2')->select('call sp_view_confirmation_rate_trailling(?,?,?,?,?)', array($granularity, $brand, $vendor, $startDate, $endDate));
    }

    /**
     * Get the Top ASIN Decrease Table data.
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
     * Get the Top ASIN Increase Table data.
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
     * Get the Top ASIN Shipped COGS Table data.
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
}
