<?php

namespace App\Model\Alerts;

use Illuminate\Database\Eloquent\Model;

class KpiInfo extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_kpi_info';
    protected $primaryKey = 'kpi_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kpi_name', 'sub_kpi_name', 'sub_kpi_value','report_name', 'report_range', 'report_graph','report_table'
    ];

    /**
     * Get the superAdmin for the roles.
     */
    public function kpiThreshold()
    {
        return $this->hasMany('App\Model\KpiThreshold', 'fk_kpi_id');
    }
}
