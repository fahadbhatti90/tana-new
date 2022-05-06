<?php

namespace App\Model\Ams\Asin\SP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SPAsinReport extends Model
{
    protected $table = 'tbl_ams_asin_sp_report_data';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * @param $storeArray
     * @throws Throwable
     */
    public function addReport($storeArray)
    {
        DB::beginTransaction();
        try {
            DB::table($this->table)->insert($storeArray);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
