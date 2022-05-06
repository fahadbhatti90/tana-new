<?php

namespace App\Model;

use DB;

use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_vendor';
    protected $primaryKey = 'vendor_id';
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'vendor_name', 'vendor_alias', 'region', 'tier', 'marketplace'
    ];

    /**
     * Get the Brands for this Vendor.
     */
    public function brands()
    {
        return $this->belongsToMany('App\Model\Brand', 'mgmt_brand_vendors', 'fk_vendor_id', 'fk_brand_id');
    }

    /**
     * Get the Dimension Vendor of this Vendor.
     */
    public function getDimVendor()
    {
        return $this->hasOne('App\Model\DimVendor', 'rdm_vendor_id');
    }
    /**
     * Get the Assigned profile for this vendor.
     */
    public function profile()
    {
        return $this->belongsToMany('App\Model\Ams\Profile', 'mgmt_vendor_entity', 'fk_vendor_id', 'fk_profile_id');
    }

    /**
     * Get the Assigned profile for this vendor.
     */
    public function profileAssign($dbData)
    {
        return DB::table('mgmt_vendor_entity')->insert($dbData);
    }
}
