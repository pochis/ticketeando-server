<?php namespace App\Http\Controllers;

use App\Ticket;
use App\Type;
use App\TicketFiles;
use App\CommentFiles;
use App\TicketHasStatus;
use App\Queue;
use App\Comment;
use App\Traits\Files;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    
    use Files;
    
    /**
     * get tickets
     *
     * @method getTickets
     */
     public function getTickets(Request $request,$offset=0,$limit=10){
         
         $tickets = Ticket::with('category','priority','submitter','project','status','owner')->offset($offset)->limit($limit);
         
         if($request->has('user')){
            $tickets->where('user_id',$request->user);
         }
         if($request->has('status')){
             $tickets->where('current_status',$request->status);
         }
         
         /*sorting  by*/
         if($request->has('sortBy') && $request->has('sortType')){
            if($request->sortBy=='created_at'||$request->sortBy=='updated_at' || $request->sortBy=='title' || $request->sortBy=='id'){
               $tickets->orderBy($request->sortBy,$request->sortType);
            }else{
               $tickets->whereHas($request->sortBy,function($q) use($request){
                  return $q->orderBY('name',$request->sortType);
               });
            }
         }
         /*search filter*/
         if($request->has('search')){
             $tickets->where('title','like', '%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereHas('owner',function($q) use($request){
                 return $q->where('name', 'like', '%'.$request->search.'%');
             })->orWhereHas('category',function($q) use($request){
                 return $q->where('name', 'like', '%'.$request->search.'%');
             })->orWhereHas('priority',function($q) use($request){
                 return $q->where('name', 'like', '%'.$request->search.'%');
             })->orWhereHas('submitter',function($q) use($request){
                 return $q->where('name', 'like', '%'.$request->search.'%');
             })->orWhereHas('project',function($q) use($request){
                 return $q->where('name', 'like', '%'.$request->search.'%');
             })->orWhereHas('status',function($q) use($request){
                 return $q->where('name', 'like', '%'.$request->search.'%');
             });
         }
         
         return response([
           'status'=>'success',
           'tickets'=>$tickets->get(),
           'total'=>Ticket::count()
         ],200);
     }
    /**
     * update the ticket
     *
     * @method update
     */
    public function update(Request $request,$id){
        $this->validate($request, [
            'title'=>'required',  
            'subject'=>'required',
            'description'=>'required',
            'category_id'=>'required',
            'priority_id'=>'required',
        ]);
        $ticket = Ticket::findOrFail($id);
        $ticket->title=$request->title;
        $ticket->subject=$request->subject;
        $ticket->description=$request->description;
        $ticket->category_id=$request->category_id;
        $ticket->priority_id=$request->priority_id;
        $ticket->project_id=$request->project_id;
        if($ticket->save()){
            return response(['status'=>'success','message'=>'Ticket actualizado satisfactoriamente'],200);
        }else{
            return response(['status'=>'fail','message'=>'Ah ocurrido un error al tratar de actualizar el ticket, vuelve a intentarlo mas tarde'],500);
        }
    }
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
         $current_status =Type::where('group_type_id',3)->limit(1)->first();
         $current_resolution =Type::where('group_type_id',4)->limit(1)->first();
         $ticket->title=$request->title;
         $ticket->subject=$request->subject;
         $ticket->description=$request->description;
         $ticket->category_id=$request->category_id;
         $ticket->priority_id=$request->priority_id;
         $ticket->project_id=$request->project_id;
         $ticket->user_id=$request->user_id;
         $ticket->current_status=$current_status->id;
         $ticket->current_resolution=$current_resolution->id;
         if($ticket->save()){
             
             TicketHasStatus::create([
                 'ticket_id'=>$ticket->id,
                 'status_id'=> $current_status->id,
                 'resolution_id'=> $current_resolution->id
             ]);
             //create thread
             $comment=Comment::create([
                 'comment'=>$request->description,
                 'ticket_id'=>$ticket->id,
                 'user_id'=>$request->user_id
             ]);
            if($request->hasFile('attachment')){
                $pathImage = base_path('public/static/ticket/'.$ticket->id);
                $pathCommentImage = base_path('public/static/comment/'.$comment->id);
                foreach($request->attachment as $file){
                    
                    $filename=$this->singleFileImage($pathImage, $file);
                    $filenameComment=$this->singleFileImage($pathCommentImage, $file);
                    
                    TicketFiles::create([
                        'ticket_id'=>$ticket->id,
                        'file'=>$filename
                    ]);
                    CommentFiles::create([
                        'comment_id'=>$comment->id,
                        'file'=>$filenameComment
                    ]);
                }
              
            }
            return response(['status'=>'success','message'=>'Ticket creado satisfactoriamente (TICK:'.$ticket->id.')'],200);
         }else{
            return response(['status'=>'fail','message'=>'Ah ocurrido un error al tratar de crear el ticket, vuelve a intentarlo mas tarde'],500);
         }
         
     }
     /**
     * show ticket data
     *
     * @method show
     */
     public function show($id){
         $ticket=Ticket::with('category','priority','submitter','project','status','resolution','owner','files')->findOrFail($id);
         return response(['status' => 'success','ticket'=>$ticket],200); 
         
     }
     /**
     * take ticket by user and change status
     *
     * @method state
     */
     public function state(Request $request){
          $this->validate($request, [
            'ticket_id'=>'required|numeric',
            'status_id'=>'required|numeric',
            'resolution_id'=>'required|numeric',
            'user_id'=>'required|numeric',
          ]);
          $relations = Ticket::with('category','priority','submitter','project','status','resolution','owner','files');
          $ticket = $relations->findOrFail($request->ticket_id);
          $ticket->current_status = $request->status_id;
          $ticket->current_resolution =$request->resolution_id;
          if($ticket->save()){
              if($request->status_id==6 || $request->status_id==8){
                  $hasQueue=Queue::where('user_id',$request->user_id)->where('ticket_id',$ticket->id)->first();
                  if($hasQueue){
                      $hasQueue->where('user_id',$request->user_id)->where('ticket_id',$ticket->id)->delete();
                  }
              }else{
                  Queue::updateOrCreate([
                     'user_id'=>$request->user_id,
                     'ticket_id'=>$ticket->id
                  ]);
              }
              TicketHasStatus::create([
                 'ticket_id'=>$ticket->id,
                 'status_id'=> $request->status_id,
                 'resolution_id'=> $request->resolution_id,
                 'user_id'=> $request->user_id,
              ]);
              return response(['status'=>'success','message'=>'Ticket actualizado','ticket'=>$relations->find($ticket->id)],200);
          }else{
              return response(['status'=>'fail','message'=>'Ah ocurrido un error al tratar de cambiar el estado del ticket, vuelve a intentarlo mas tarde'],500);
          }
          
     }
     /**
     * total tickets
     *
     * @method total
     */
     public function total(){
         return response(['status' => 'success', "total" => Ticket::count()], 200);
     }
     
    
}