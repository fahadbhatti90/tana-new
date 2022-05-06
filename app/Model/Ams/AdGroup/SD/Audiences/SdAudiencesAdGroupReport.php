<?php

namespace App\Model\Ams\AdGroup\SD\Audiences;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SdAudiencesAdGroupReport extends Model
{
    protected $table = 'tbl_ams_audiences_ad_group_sd_report_data';
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
