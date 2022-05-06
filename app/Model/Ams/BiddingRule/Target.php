<?php

namespace App\Model\Ams\BiddingRule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class Target extends Model
{
    protected $table = 'tbl_ams_bidding_rule_target';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * @param $storeArray
     * @throws Throwable
     */
    public function addTargetList($storeArray)
    {
        DB::beginTransaction();
        try {
            foreach ($storeArray as $row) {
                $existData = DB::table($this->table)->where('target_id', $row['target_id'])->get();
                if ($existData->isEmpty()) {
                    DB::table($this->table)->Insert($row);
                } else {
                    // change keyword status
                    DB::table($this->table)
                        ->where('target_id', $row['target_id'])
                        ->update($row);
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
