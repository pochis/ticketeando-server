<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketFiles extends Model 
{


    protected $table ='ticket_files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];

    
}
