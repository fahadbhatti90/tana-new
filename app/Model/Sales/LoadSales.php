<?php

namespace App\Model\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoadSales extends Model
{
    /**
     * Get the Load Daily Sales Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailySales($startDate, $endDate)
    {
        return DB::select('call sp_master_load_sale(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Weekly Sales Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklySales($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_sale_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Sales Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlySales($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_sale_monthly(?,?)', array($startDate, $endDate));
    }
}
