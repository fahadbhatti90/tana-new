<?php

namespace App\Model\ExecutiveDashboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfirmPO extends Model
{
    /**
     * @param $type
     * @param $startDate
     * @return array
     */
    public static function weeklyPOReport($type)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_ytd(?)', array($type));
    }

    /**
     * @param $type
     * @param $user_id
     * @param $role_id
     * @param $startDate
     * @return array
     */
    public static function weeklyConfirmedPOReport($type, $user_id, $role_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_all_vendor(?,?,?,?)', array($type, $user_id, $role_id, $startDate));
    }

    /**
     * @param $type
     * @param $user_id
     * @param $role_id
     * @param $startDate
     * @return array
     */
    public static function tanaAllVendorsPOConfirmRate($user_id, $role_id, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_horizontal_bar_chart(?,?,?)', array($user_id, $role_id, $startDate));
    }

    /**
     * @param $type
     * @param $startDate
     * @return array
     */
    public static function POConfirmRateByVendor($type, $startDate)
    {
        return DB::connection('mysql2')->select('call sp_ed_view_po_vendor_detail(?,?)', array($type, $startDate));
    }
}
