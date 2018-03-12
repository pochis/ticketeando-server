<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model 
{


    protected $table ='queue';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    
}
