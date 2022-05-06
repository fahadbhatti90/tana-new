<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoleModulePermission extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_role_module_permission';
    protected $primaryKey = 'auth_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'fk_role_id', 'fk_permission_id', 'fk_module_id',
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
    public function role()
    {
        return $this->hasOne('App\Model\Role', 'role_id', 'fk_role_id');
    }

    /**
     * Get the module information.
     */
    public function module()
    {
        return $this->hasOne('App\Model\Module', 'module_id', 'fk_module_id');
    }
}
