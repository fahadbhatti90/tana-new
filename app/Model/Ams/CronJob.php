<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CronJob extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'tbl_ams_cron_jobs';
    protected $primaryKey = 'cron_id';
    protected $connection = 'mysql';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cron_name', 'cron_slag', 'cron_type', 'recover', 'recover_back_from_date', 'is_running', 'cron_time', 'cron_status', 'last_run', 'modified_date', 'run_status', 'next_run',
    ];

    public static function getAllEnabledCronList()
    {
        $response = CronJob::where('cron_status', 'enable')
            ->get();
        if (!$response->isEmpty()) {
            return $response;
        }
        return FALSE;
    }

    public static function getRecoverCronList()
    {
        $response = CronJob::where('recover', '>=', '1')
            ->get();
        if (!$response->isEmpty()) {
            return $response;
        }
        return FALSE;
    }

    /**
     * changes Cron Job Status
     *
     * @param $type
     * @param $updateArray
     */
    public static function updateCronRunStatus($type, $updateArray)
    {
        Log::info('AMS Model file methods name : updateCronRunStatus.');

        // tracker code
        Tracker::insertTrackRecord('change enable crons status : 0', 'record found');

        CronJob::where('cron_slag', $type)->update($updateArray);
        Log::info('End AMS Model file methods name : updateCronRunStatus.');
    }

    /**
     * changes Cron Job Status
     *
     * @param $type
     * @param $updateArray
     */
    public static function spIdentifire($type, $subtype)
    {
        //return DB::select('call sp_cron_job_ams_error_identifier(?,?)', array($type, $subtype));
    }
    /**
     * changes Cron Job Status
     *
     * @param $type
     * @param $updateArray
     */
    public static function spEtlCaller()
    {
        //return DB::select('call sp_cron_job_ams_reporting_and_etl_caller()');
    }

    public static function updateEmailStatus()
    {
        return DB::select("UPDATE `error_log_ams` SET sent = 1 WHERE DATE(captured_at) = CURRENT_DATE");
    }
    public static function getEmails()
    {
        return DB::select("SELECT mgmt_user.`email` FROM mgmt_user_role,mgmt_user WHERE mgmt_user_role.`fk_user_id` = mgmt_user.`user_id` AND mgmt_user_role.`fk_role_id` = '1'");
    }
}
