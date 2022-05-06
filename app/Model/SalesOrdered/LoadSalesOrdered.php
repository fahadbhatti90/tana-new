<?php

namespace App\Model\SalesOrdered;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoadSalesOrdered extends Model
{
    /**
     * Get the Load Daily Sales Ordered Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailySalesOrdered($startDate, $endDate)
    {
        return DB::select('call sp_master_load_sale_manufacturing_ordered_rev(?,?)', array($startDate, $endDate));
    }


    /**
     * Get the Load Weekly Sales Ordered Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklySalesOrdered($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_sale_manufacuting_ordered_rev_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Sales Ordered Records to SDM Tables.
     * sp_load_fact_inventory_monthly    old sp
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlySalesOrdered($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_sale_manufacuting_ordered_rev_monthly(?,?)', array($startDate, $endDate));
    }
}
