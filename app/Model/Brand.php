<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Brand extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_brand';
    protected $primaryKey = 'brand_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_name','is_active',
    ];

    /**
     * Get the user for this Brand.
     */
    public function users()
    {
        return $this->belongsToMany('App\Model\User', 'mgmt_account', 'fk_brand_id', 'fk_user_id');
    }

    /**
     * Get the Assigned Vendors for this Brand.
     */
    public function vendors()
    {
        return $this->belongsToMany('App\Model\Vendors', 'mgmt_brand_vendors', 'fk_brand_id', 'fk_vendor_id');
    }

    public static function updateSDM(){
        DB::select('call sp_load_dim_brand()');
        DB::select('call sp_load_dim_vendor()');
        DB::select('call sp_load_dim_br_brand_vendor()');
    }
}
