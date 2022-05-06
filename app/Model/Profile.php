<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'mgmt_user_profile';
    protected $primaryKey = 'profile_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_mode',
    ];

    public function user(){
        return $this->belongsTo('App\Model\User','fk_user_id');
    }

}
