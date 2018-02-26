<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model 
{


    protected $table ='type';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    
}
