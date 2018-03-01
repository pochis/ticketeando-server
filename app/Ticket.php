<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model 
{


    protected $table ='ticket';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];

    /**
     * proiority relationship one to one
     *
     * @method priority
     */
    public function priority(){
        return $this->hasOne('App\Type','id','priority_id');
    }
    /**
     * proiority relationship one to one
     *
     * @method category
     */
    public function category(){
        return $this->hasOne('App\Category','id','category_id');
    }
    /**
     * project relationship one to one
     *
     * @method project
     */
    public function project(){
        return $this->hasOne('App\Project','id','project_id');
    }
    /**
     * proiority relationship one to one submitter 
     *
     * @method submitter
     */
    public function submitter(){
        return $this->hasOne('App\User','id','user_id');
    }
    /**
     * proiority relationship many to many submitter 
     *
     * @method owner
     */
    public function owner(){
       return $this->belongsToMany('App\User', 'ticket_take',  'ticket_id','user_id');
    }
    
    /**
     * status relationship one to one submitter 
     *
     * @method status
     */
    public function status(){
        return $this->hasOne('App\Type','id','current_status');
    }
    
    /**
     * proiority relationship one to many
     *
     * @method files
     */
    public function files(){
        return $this->hasMany('App\TicketFiles','ticket_id','id');
    }
}
