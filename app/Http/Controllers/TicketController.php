<?php namespace App\Http\Controllers;

use App\Ticket;
use App\Type;
use App\TicketFiles;
use App\TicketHasStatus;
use App\Traits\Files;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    
    use Files;
    /**
     * store new ticket
     *
     * @method store
     */
     public function store(Request $request){
         
          $this->validate($request, [
            'title'=>'required',  
            'subject'=>'required',
            'description'=>'required',
            'category_id'=>'required',
            'priority_id'=>'required',
            'user_id'=>'required',
            'attachment.*' => 'mimes:jpeg,jpg,png|max:20000',
          ]);
          
         $ticket = new Ticket();
         $ticket->title=$request->title;
         $ticket->subject=$request->subject;
         $ticket->description=$request->description;
         $ticket->category_id=$request->category_id;
         $ticket->priority_id=$request->priority_id;
         $ticket->user_id=$request->user_id;
         if($ticket->save()){
             
             TicketHasStatus::create([
                 'ticket_id'=>$ticket->id,
                 'status_id'=> 7
             ]);
            if($request->hasFile('attachment')){
                $pathImage = base_path('public/static/ticket/'.$ticket->id);
                foreach($request->attachment as $file){
                    
                    $filename=$this->singleFileImage($pathImage, $file);
                    
                    TicketFiles::create([
                        'ticket_id'=>$ticket->id,
                        'file'=>$filename
                    ]);
                }
              
            }
             
            return response(['status'=>'success','message'=>'Ticket creado satisfactoriamente (TICK:'.$ticket->id.')'],200);
         }else{
            return response(['status'=>'fail','message'=>'Ah ocurrido un error al tratar de crear el ticket, vuelve a intentarlo mas tarde'],500);
         }
         
     }
    
}