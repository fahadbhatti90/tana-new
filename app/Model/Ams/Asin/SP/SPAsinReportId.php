<?php

namespace App\Model\Ams\Asin\SP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SPAsinReportId extends Model
{
    protected $table = 'tbl_ams_asin_sp_report_id';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * @param $storeArray
     */
    public function addReportId($storeArray)
    {
        DB::transaction(function () use ($storeArray) {
            foreach ($storeArray as $row) {
                $existData = DB::table($this->table)->where([
                    ['profile_id', $row['profile_id']],
                    ['record_type', $row['record_type']],
                    ['report_date', $row['report_date']]
                ])->get();
                if ($existData->isEmpty()) {
                    try {
                        DB::table($this->table)->insertGetId($row);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        Log::error($ex->getMessage());
                    }
                } else {
                    DB::table($this->table)->where([
                        ['profile_id', $row['profile_id']],
                        ['record_type', $row['record_type']],
                        ['report_date', $row['report_date']]
                    ])->delete();
                    DB::table('tbl_ams_asin_sp_report_link')->where([
                        ['profile_id', $row['profile_id']],
                        ['report_date', $row['report_date']]
                    ])->delete();
                    try {
                        DB::table($this->table)->insertGetId($row);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        Log::error($ex->getMessage());
                    }
                }
            }
        }, 3);
    }
}
