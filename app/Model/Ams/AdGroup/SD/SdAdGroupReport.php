<?php

namespace App\Model\Ams\AdGroup\SD;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SdAdGroupReport extends Model
{
    protected $table = 'tbl_ams_ad_group_sd_report_data';
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
