<?php

namespace App\Model\Ams\BiddingRule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Keyword extends Model
{
    protected $table = 'tbl_ams_bidding_rule_keyword';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * @param $storeArray
     * @throws Throwable
     */
    public function addKeywordList($storeArray)
    {
        DB::beginTransaction();
        try {
            foreach ($storeArray as $row) {
                $existData = DB::table($this->table)->where('keyword_id', $row['keyword_id'])->get();
                if ($existData->isEmpty()) {
                    DB::table($this->table)->Insert($row);
                } else {
                    // change keyword status
                    DB::table($this->table)
                        ->where('keyword_id', $row['keyword_id'])
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
