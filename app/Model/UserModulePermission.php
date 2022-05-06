<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModulePermission extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_user_module_permission';
    protected $primaryKey = 'auth_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'fk_user_id', 'fk_permission_id', 'fk_module_id',
    ];

    /**
     * Get the permission information.
     */
    public function permission()
    {
        return $this->hasOne('App\Model\Permission', 'permission_id', 'fk_permission_id');
    }

    /**
     * Get the role information.
     */
    public function user()
    {
        return $this->hasOne('App\Model\User', 'user_id', 'fk_user_id');
    }

    /**
     * Get the module information.
     */
    public function module()
    {
        return $this->hasOne('App\Model\UserModule', 'module_id', 'fk_module_id');
    }
}
