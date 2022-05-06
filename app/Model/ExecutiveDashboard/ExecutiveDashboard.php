<?php

namespace App\Model\ExecutiveDashboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExecutiveDashboard extends Model
{

    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function shippedCogsYtd($type, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_ytd(?,?,?)', array($type,  $brand, $startDate));
    }
    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function netReceivedYtd($type, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_ytd(?,?,?)', array($type,  $brand, $startDate));
    }

    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function shippedCogsMtd($type, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_mtd(?,?,?)', array($type,  $brand, $startDate));
    }

    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function netReceivedMtd($type, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_mtd(?,?,?)', array($type,  $brand, $startDate));
    }

    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function shippedCogsTable($marketplace_value, $type, $toogloe_table, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_table(?,?,?,?,?)', array($marketplace_value, $type, $toogloe_table, $brand, $startDate));
    }

    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function netReceivedTable($type, $toogle_table, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_table(?,?,?,?)', array($type, $toogle_table, $brand, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailSC($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_ytd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailNR($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_ytd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @return array
     */
    public static function vendorDetailROAS($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_roas_campaign_ytd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailSCMTD($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_mtd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailNRMTD($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_mtd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailROASMTD($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_roas_campaign_mtd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }
    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function orderedProductYtd($type, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_ordered_product_ytd(?,?,?)', array($type,  $brand, $startDate));
    }
    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function orderedProductMtd($type, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_ordered_product_mtd(?,?,?)', array($type,  $brand, $startDate));
    }
    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailOrderedProductMtd($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_ordered_product_mtd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }
    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function vendorDetailOrderedProductYtd($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_ordered_product_ytd_vendor(?,?,?)', array($type, $vendor, $startDate));
    }
    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function shippedCogsTrailing($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_trailing(?,?,?)', array($type, $vendor, $startDate));
    }

    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function netReceivedTrailing($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_net_received_trailing(?,?,?)', array($type, $vendor, $startDate));
    }
    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function roasTrailing($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_roas_trailing(?,?,?)', array($type, $vendor, $startDate));
    }
    /**
     * @param $type
     * @param $vendor
     * @param $startDate
     * @return array
     */
    public static function orderedProductTrailing($type, $vendor, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_ordered_product_trailing(?,?,?)', array($type, $vendor, $startDate));
    }
    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function shippedCogsNcTable($marketplace_value, $type, $toogloe_table, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_shipped_cogs_and_net_received_table(?,?,?,?,?)', array($marketplace_value, $type, $toogloe_table, $brand, $startDate));
    }
    /**
     * @param $type
     * @param $brand
     * @param $startDate
     * @return array
     */
    public static function shippedCogsNCGrandTotal($marketplace_value, $type, $toogloe_table, $brand, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_Grand_total_shipped_cogs_and_net_received_table(?,?,?,?,?)', array($marketplace_value, $type, $toogloe_table, $brand, $startDate));
    }
}
