<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_role';
    protected $primaryKey = 'role_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'role_name',
    ];

    /**
     * Get the superAdmin for the roles.
     */
    public function users()
    {
        return $this->belongsToMany('App\Model\User', 'mgmt_user_role', 'fk_role_id', 'fk_user_id');
    }

    /**
     * Get the assigned permission.
     */
    public function authorization()
    {
        return $this->hasMany('App\Model\RoleModulePermission','fk_role_id');
    }

}
