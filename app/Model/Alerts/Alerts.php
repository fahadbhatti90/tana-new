<?php

namespace App\Model\Alerts;

use Illuminate\Database\Eloquent\Model;

class Alerts extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_alert';
    protected $primaryKey = 'alert_id';
    public $timestamps = false;

    /**
     * Get the alerts reported value.
     *
     * @param $fk_vendor_id
     * @param $fk_user_id
     * @param $report_name
     * @param $report_range
     * @param $report_graph
     * @param $start_date
     * @param $end_date
     */
    public static function getReportedAlerts($fk_vendor_id, $fk_user_id, $report_name, $report_range, $report_graph, $start_date, $end_date)
    {
        return Alerts::where('fk_vendor_id', $fk_vendor_id)
                ->where('fk_user_id', $fk_user_id)
                ->where('report_name', $report_name)
                ->where('report_range', $report_range)
                ->where('report_graph', $report_graph)
                ->where('reported_date', '>=', $start_date)
                ->where('reported_date', '<=', $end_date)
                ->get();
    }

}
