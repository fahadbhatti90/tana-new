<?php

namespace App\Model\ExecutiveDashboard;

use Illuminate\Database\Eloquent\Model;

class POPlan extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_po_plan';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','value',
    ];
}
