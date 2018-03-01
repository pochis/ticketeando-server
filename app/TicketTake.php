<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketTake extends Model 
{


    protected $table ='ticket_take';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    
}
