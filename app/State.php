<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model 
{


    protected $table ='state';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];

    
    public $timestamps=false;
    
    
    public function cities(){
        return $this->hasMany('App\City');
    }
}
