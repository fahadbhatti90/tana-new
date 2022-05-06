<?php

namespace App\Model;

use DB;

use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_sale';

    /**
     * insert Data for src_sales Table.
     */
    public static function Insertion($data)
    {
        DB::beginTransaction();

        try {
            $qry = DB::table('src_sale')->insert($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return $qry;
    }
}
