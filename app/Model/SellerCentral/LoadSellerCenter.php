<?php

namespace App\Model\SellerCentral;

use DB;

use Illuminate\Database\Eloquent\Model;

class LoadSellerCenter extends Model
{
    /**
     * Get the Load Daily SellerCenter Records to SDM Tables.
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function loadDailySellerCenter($startDate, $endDate)
    {
        return DB::select('call sp_master_load_sc_sale(?,?)', array($startDate, $endDate));
    }
}
