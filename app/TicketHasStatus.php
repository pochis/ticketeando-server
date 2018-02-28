<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketHasStatus extends Model 
{


    protected $table ='ticket_has_status';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    
}
