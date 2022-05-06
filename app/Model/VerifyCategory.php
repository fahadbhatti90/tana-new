<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VerifyCategory extends Model
{
    /**
     * Get the Date Of Sales Graph.
     * @param $granularity
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function categoryViewSp()
    {
        return DB::select('call sp_etl_core_sale_category()');
    }
    /**
     * Get the Data for ptp Table.
     */
    public static function categoryViewTable()
    {
        return  DB::table('src_sale_category')->get();
    }
    /**
     * Get the Data for ptp Table.
     */
    public static function vendorsList()
    {
        return  DB::select("Select DISTINCT fk_vendor_name from src_sale_category ORDER BY 1", [1]);
        //DB::table('mgmt_vendor')->where('is_active', '=', 1)->get();
    }
    /**
     * Delete Data for ptp Table.
     */
    public static function deleteCategoryVendor($vendorName)
    {
        return DB::select("DELETE FROM src_sale_category WHERE `fk_vendor_name` = '$vendorName'", [1]);
    }
}
