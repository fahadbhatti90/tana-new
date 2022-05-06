<?php

namespace App\Model\ExecutiveDashboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfirmPOExtended extends Model
{
    /**
     * @param $type
     * @param $brand_id
     * @return array
     */
    public static function weeklyPOReport($type, $brand_id)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_ytd(?,?)', array($type, $brand_id));
    }

    /**
     * @param $type
     * @param $brand_id
     * @param $startDate
     * @return array
     */
    public static function weeklyConfirmedPOReport($type, $brand_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_all_vendor_weekly(?,?,?)', array($type, $brand_id, $startDate));
    }

    /**
     * @param $type
     * @param $brand_id
     * @param $startDate
     * @return array
     */
    public static function MonthlyConfirmedPOReport($type, $brand_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_all_vendor_monthly(?,?,?)', array($type, $brand_id, $startDate));
    }

    /**
     * @param $type
     * @param $brand_id
     * @param $startDate
     * @return array
     */
    public static function AggregatedMonthlyConfirmedPOReport($type, $brand_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_all_vendor_aggregation_monthly(?,?,?)', array($type, $brand_id, $startDate));
    }

    /**
     * @param $type
     * @param $brand_id
     * @param $startDate
     * @return array
     */
    public static function AggregatedWeeklyConfirmedPOReport($type, $brand_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_all_vendor_aggregation_weekly(?,?,?)', array($type, $brand_id, $startDate));
    }

    /**
     * @param $brand_id
     * @param $startDate
     * @return array
     */
    public static function tanaAllVendorsPOConfirmRateWeekly($brand_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_horizontal_bar_chart_weekly(?,?)', array($brand_id, $startDate));
    }

    /**
     * @param $brand_id
     * @param $startDate
     * @return array
     */
    public static function tanaAllVendorsPOConfirmRateMonthly($brand_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_horizontal_bar_chart_monthly(?,?)', array($brand_id, $startDate));
    }

    /**
     * @param $id
     * @param $startDate
     * @param $type
     * @return array
     */
    public static function POConfirmRateByVendor($id, $startDate, $type)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_vendor_detail_weekly(?,?,?)', array($type, $id, $startDate));
    }

    /**
     * @param $id
     * @param $startDate
     * @param $type
     * @return array
     */
    public static function POConfirmRateByVendorMonthly($id, $startDate, $type)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_vendor_detail_monthly(?,?,?)', array($type, $id, $startDate));
    }
}
