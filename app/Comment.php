<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model 
{


    protected $table ='comment';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
 
    /**
     * user relationship
     *
     * @method user
     */
    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
    /**
     * files relationship one to many
     *
     * @method files
     */
    public function files(){
        return $this->hasMany('App\CommentFiles','comment_id','id');
    }
}
