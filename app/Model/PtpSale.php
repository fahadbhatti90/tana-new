<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class PtpSale extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_sale_ptp';

    /**
     * insert Data for src_sales Table.
     */
    public static function insertPtp($dbData)
    {
        return DB::table('src_sale_ptp')->insert($dbData);
    }
}
