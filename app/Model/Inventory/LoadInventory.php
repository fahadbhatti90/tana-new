<?php

namespace App\Model\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoadInventory extends Model
{
    /**
     * Get the Load Daily Inventory Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailyInventory($startDate, $endDate)
    {
        return DB::select('call sp_master_load_inventory(?,?)', array($startDate, $endDate));
    }


    /**
     * Get the Load Weekly Inventory Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadWeeklyInventory($startDate, $endDate)
    {
        return DB::select('call sp_load_fact_inventory_weekly(?,?)', array($startDate, $endDate));
    }

    /**
     * Get the Load Monthly Inventory Records to SDM Tables.
     * sp_load_fact_inventory_monthly    old sp 
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadMonthlyInventory($startDate, $endDate)
    {
        return DB::select('call sp_master_load_inventory_monthly(?,?)', array($startDate, $endDate));
    }
}
