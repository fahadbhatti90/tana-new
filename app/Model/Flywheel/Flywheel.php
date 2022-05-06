<?php

namespace App\Model\Flywheel;

use Illuminate\Database\Eloquent\Model;
use DB;

class Flywheel extends Model
{
    /**
     * Get the Facts Of Flywheel ordered revenue and sp adsales values for line graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function orderedRevenueSpAdSales($granularity, $vendor, $startDate, $endDate, $product, $category, $asin)
    {
        return DB::connection('mysql2')->select('call sp_fly_wheel_report_ordered_revenue_and_sp_ad_sale(?,?,?,?,?,?,?)', array($granularity, $vendor, $startDate, $endDate, $product, $category, $asin));
    }

    /**
     * Get the Facts Flywheel conversion and asp value for line graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function conversionsAspTotal($granularity, $vendor, $startDate, $endDate, $product, $category, $asin)
    {
        return DB::connection('mysql2')->select('call sp_fly_wheel_report_conversion_and_asp(?,?,?,?,?,?,?)', array($granularity, $vendor, $startDate, $endDate, $product, $category, $asin));
    }
    /**
     * Get the Flywheel glance views and sp impressions values for line graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function glanceViewSpImpressionTotal($granularity, $vendor, $startDate, $endDate, $product, $category, $asin)
    {
        return DB::connection('mysql2')->select('call sp_fly_wheel_report_sp_impressions_and_glance_views(?,?,?,?,?,?,?)', array($granularity, $vendor, $startDate, $endDate, $product, $category, $asin));
    }
    /**
     * Get the Flywheel inventory and ordered units values for line graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function inventoryOrderedUnitTotal($granularity, $vendor, $startDate, $endDate, $product, $category, $asin)
    {
        return DB::connection('mysql2')->select('call sp_fly_wheel_report_inv_units_and_ordered_units(?,?,?,?,?,?,?)', array($granularity, $vendor, $startDate, $endDate, $product, $category, $asin));
    }
    /**
     * Get the Flywheel sp,sb,sbv,sd values for donut graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function salesAdType($granularity, $vendor, $startDate, $endDate, $product, $category, $asin)
    {
        return DB::connection('mysql2')->select('call sp_fly_wheel_sales_by_type(?,?,?,?,?,?,?)', array($granularity, $vendor, $startDate, $endDate, $product, $category, $asin));
    }
    /*
     * Get the Flywheel sp,sb,sbv,sd values for donut graph.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function spendAdType($granularity, $vendor, $startDate, $endDate, $product, $category, $asin)
    {
        return DB::connection('mysql2')->select('call sp_fly_wheel_spend_by_type(?,?,?,?,?,?,?)', array($granularity, $vendor, $startDate, $endDate, $product, $category, $asin));
    }
    /**
     *  Get the Flywheel values for category detail Table.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function categoryDetailDataTable($granularity, $vendor, $startDate, $endDate, $product, $category, $asin)
    {
        return DB::connection('mysql2')->select('call sp_fly_wheel_report_category_kpi(?,?,?,?,?,?,?)', array($granularity, $vendor, $startDate, $endDate, $product, $category, $asin));
    }
    /**
     *  Get the Flywheel values for product select2 input.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getproduct($vendor_mix, $startDate, $endDate, $term)
    {
        return DB::connection('mysql2')->table('dim_product as u')->select(['u.product_title AS text', 'u.product_title AS id'])
            ->whereRaw("u.product_id in (SELECT fk_product_id FROM `fact_inventory_daily` WHERE fk_vendor_id IN (SELECT `vendor_id` FROM `dim_vendor` WHERE FIND_IN_SET(rdm_vendor_id,'$vendor_mix')  GROUP BY 1) AND received_date BETWEEN '$startDate' AND '$endDate' GROUP BY 1) AND product_title LIKE '%$term%'")->paginate(10);
    }
    /**
     *  Get the Flywheel values for asin select2 input.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getAsins($vendor_mix, $startDate, $endDate, $term)
    {
        return DB::connection('mysql2')->table('dim_product as u')->select(['u.asin AS text', 'u.asin AS id'])
            ->whereRaw("u.product_id in (SELECT fk_product_id FROM `fact_inventory_daily` WHERE fk_vendor_id IN (SELECT `vendor_id` FROM `dim_vendor` WHERE FIND_IN_SET(rdm_vendor_id,'$vendor_mix')  GROUP BY 1) AND received_date BETWEEN '$startDate' AND '$endDate' GROUP BY 1) AND asin LIKE '%$term%'")->paginate(10);
    }
    /**
     *  Get the Flywheel values for category select2 input.
     * @param $granularity
     * @param $brand
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getCategory($vendor_mix, $startDate, $endDate, $term)
    {
        return DB::connection('mysql2')->table('dim_category as u')->select(['u.subcategory AS text', 'u.subcategory AS id'])
            ->whereRaw("u.category_id in (SELECT fk_category_id FROM `fact_inventory_daily` WHERE fk_vendor_id IN (SELECT `vendor_id` FROM `dim_vendor` WHERE FIND_IN_SET(rdm_vendor_id,'$vendor_mix')  GROUP BY 1) AND received_date BETWEEN '$startDate' AND '$endDate' GROUP BY 1) AND subcategory LIKE '%$term%'")->paginate(10);
    }
}
