<?php

namespace App\Model\Alerts;

use Illuminate\Database\Eloquent\Model;

class KpiThreshold extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_kpi_threshold';
    protected $primaryKey = 'threshold_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_user_id', 'fk_vendor_id', 'fk_kpi_id','threshold_type', 'threshold_value',
    ];


    /**
     * Get the superAdmin for the roles.
     */
    public function kpiThreshold()
    {
        return $this->belongsTo('App\Model\KpiInfo');
    }
}
