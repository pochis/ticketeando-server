<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table ='user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    /**
     * Role for user.
     *
     * @method role
     */
    public function role(){
        return $this->hasOne('App\Type', 'id', 'role_id');
    }
    /**
     * Role for user.
     *
     * @method role
     */
    public function projects(){
        return $this->belongsToMany('App\Project','user_has_project');
    }
    /**
     * user tickets.
     *
     * @method tickets
     */
    public function tickets(){
        return $this->hasMany('App\Ticket','user_id','id');
    }
}
