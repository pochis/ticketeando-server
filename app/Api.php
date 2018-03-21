<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Api extends Model 
{


    protected $table ='api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    
}
