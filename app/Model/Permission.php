<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_permission';
    protected $primaryKey = 'permission_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_name',
    ];


    /**
     * Get the RoleModulePermission.
     */
    public function roleModulePermission()
    {
        return $this->belongsToMany('App\Model\RoleModulePermission');
    }

    /**
     * Get the RoleModulePermission.
     */
    public function userModulePermission()
    {
        return $this->belongsToMany('App\Model\UserModulePermission');
    }
}
