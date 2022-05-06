<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModule extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_user_module';
    protected $primaryKey = 'module_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_name',
    ];

    /**
     * Get the assigned permission.
     */
    public function roleModulePermission()
    {
        return $this->belongsToMany('App\Model\UserModulePermission');
    }
}
