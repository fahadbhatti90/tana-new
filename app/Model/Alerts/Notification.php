<?php

namespace App\Model\Alerts;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_notification';
    protected $primaryKey = 'alert_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_vendor_id',
        'fk_user_id',
        'alert_name',
        'reported_attribute',
        'sub_reported_attribute',
        'sub_reported_value',
        'reported_value',
        'reported_date',
        'report_range',
        'trigger_date',
        'is_viewed',
        'is_notified',
        'is_disabled',
    ];
}
