<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHasProject extends Model 
{


    protected $table ='user_has_project';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    public $timestamps=false;
    
    public function projects(){
        return $this->hasMany('App\Project','id','project_id');
    }
}
