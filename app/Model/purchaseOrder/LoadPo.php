<?php

namespace App\Model\purchaseOrder;

use DB;
use Illuminate\Database\Eloquent\Model;

class LoadPo extends Model
{
    /**
     * Get the Load Daily PO Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyPo($startDate, $endDate)
    {
        return DB::select('call sp_master_load_purchase_order(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Weekly PO Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklyPo($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_purchase_order_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly PO Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlyPo($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_purchase_order_monthly(?,?)', array($startDate, $endDate));
    }
}
