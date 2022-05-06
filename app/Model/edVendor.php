<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class edVendor extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_ed_user_vendor';
    protected $primaryKey = 'row_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'fk_user_id', 'fk_vendor_id_ed', 'fk_vendor1_id_confirm_po', 'fk_vendor2_id_confirm_po', 'fk_vendor3_id_confirm_po'
    ];

    public function user(){
        return $this->belongsTo('App\Model\User','fk_user_id');
    }
}
