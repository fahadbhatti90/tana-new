<?php

namespace App\Model\Ams\AdGroup\SP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SPAdGroupReport extends Model
{
    protected $table = 'tbl_ams_ad_group_sp_report_data';
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
