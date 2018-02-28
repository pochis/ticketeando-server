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
        return $this->hasOne('App\Category');
    }
    /**
     * proiority relationship one to one submitter 
     *
     * @method submitter
     */
    public function submitter(){
        return $this->hasOne('App\User');
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
