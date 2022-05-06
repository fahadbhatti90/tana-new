<?php

namespace App\Model\ptp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PtpVerify extends Model
{
    /**
     * Get the Date Of Sales Graph.
     * @param $granularity
     * @param $vendor
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function ptpView()
    {
        return DB::select('call sp_etl_core_sale_ptp()');
    }
    /**
     * Get the Data for ptp Table.
     */
    public static function ptpViewTable()
    {
        return DB::table('src_sale_ptp')->get();
    }
    /**
     * Get the Data for ptp Table.
     */
    public static function vendorsList()
    {
        return  DB::select("Select DISTINCT fk_vendor_name from src_sale_ptp ORDER BY 1", [1]);
        //DB::table('mgmt_vendor')->where('is_active', '=', 1)->get();
    }
    /**
     * Delete Data for ptp Table.
     */
    public static function deleteVendor($vendorName)
    {
        return DB::select("DELETE FROM src_sale_ptp WHERE `fk_vendor_name` = '$vendorName'", [1]);
    }
}
