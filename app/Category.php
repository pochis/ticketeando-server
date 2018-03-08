<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{


    protected $table ='category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];


    public function hasParent(){
        return  $this->hasOne('App\Category','id','parent');
    }
    
}
