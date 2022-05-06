<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimVendor extends Model
{

    /**
     * The database table and primary Key used by the model.
     */
    protected $connection = 'mysql2';
    protected $table = 'dim_vendor';
    protected $primaryKey = 'vendor_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'vendor_name', 'vendor_alias', 'region', 'marketplace', 'tier',
    ];

    /**
     * Get the Dimension Vendor of this Vendor.
     */
    public function getVendor()
    {
        return $this->belongsTo('App\Model\Vendors');
    }
}
