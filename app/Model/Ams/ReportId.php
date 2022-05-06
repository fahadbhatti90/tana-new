<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportId extends Model
{
    protected $table = 'tbl_ams_reports_id';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * @param $storeArray
     */
    public function addReportId($storeArray)
    {
        DB::transaction(function () use ($storeArray) {
            foreach ($storeArray as $row) {
                // report id information
                $reportIdInfo = DB::table($this->table)->where([
                    ['profile_id', $row['profile_id']],
                    ['report_name', $row['report_name']],
                    ['report_ad_type', $row['report_ad_type']],
                    ['report_date', $row['report_date']]
                ]);

                if ($reportIdInfo->get()->isEmpty()) {
                    try {
                        DB::table($this->table)->insertGetId($row);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        Log::error($ex->getMessage());
                    } // end catch statements
                } else {
                    // report link information
                    $reportLinkInfo = ReportLink::where([
                        ['profile_id', $row['profile_id']],
                        ['report_name', $row['report_name']],
                        ['report_ad_type', $row['report_ad_type']],
                        ['report_date', $row['report_date']]
                    ]);
                    // delete & update only when report link has an error is_done = 3
                    if($reportLinkInfo->first()->is_done > 2) {
                        $reportIdInfo->delete();
                        $reportLinkInfo->delete();
                        try {
                            DB::table($this->table)->insertGetId($row);
                        } catch (\Illuminate\Database\QueryException $ex) {
                            Log::error($ex->getMessage());
                        } // end catch statements
                    } // end if
                } // end else
            } // end foreach
        }, 3);
    }
}
