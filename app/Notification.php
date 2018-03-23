<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{


    protected $table ='notification';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    
}
