<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Tracker extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'tbl_ams_tracker';
    protected $primaryKey = 'track_id';
    protected $connection = 'mysql';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_name', 'status', 'tracked_at',
    ];

    /**
     * Inserts Recorded Data into Database
     *
     * @param $reportName
     * @param $status
     */
    public static function insertTrackRecord($reportName, $status)
    {
        $data = array(
            'report_name' => $reportName,
            'status' => $status,
            'tracked_at' => date('Y-m-d H:i:s'),
        );
        Tracker::insert($data);
        Log::info('Insert Record AMS Tracker = ' . $reportName);
    }
}
