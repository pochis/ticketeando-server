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
    /*
     *one to one relationship with tiket
     *
     *@method ticket
     */
    public function ticket(){
        return $this->hasOne('App\Ticket','id','ticket_id');
    }
    
}
