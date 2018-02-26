<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupType extends Model 
{


    protected $table ='group_type';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    
}
