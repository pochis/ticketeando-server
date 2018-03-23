<?php namespace App\Http\Controllers;

use App\Api;
use App\Comment;
use App\Ticket;
use App\CommentFiles;
use App\Traits\Files;
use App\Notification;
use Illuminate\Http\Request;
use App\Traits\MailNotification;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    
    use Files,MailNotification;
    /**
     * get comments list
     *
     * @method getComments
     */
     public function getComments($ticket,$offset=0,$limit=10){
         
         return response([
             'status'=>'success',
             'comments'=>Comment::with('user','files')->where('ticket_id',$ticket)->offset($offset)->limit($limit)->orderBy('id','desc')->get(),
             'total'=>Comment::where('ticket_id',$ticket)->count()],200);
     }
      /**
     * store new comment
     *
     * @method store
     */
     public function store(Request $request){
         $this->validate($request, [
            'comment'=>'required',  
            'ticket_id'=>'required',
            'user_id'=>'required',
            'attachment.*' => 'mimes:jpeg,jpg,png|max:20000',
          ]);
          
          $comment = new Comment();
          $comment->comment = $request->comment;
          $comment->ticket_id = $request->ticket_id;
          $comment->user_id = $request->user_id;
          if ($comment->save()){
              
            if($request->hasFile('attachment')){
              $pathImage = base_path('public/static/comment/'.$comment->id);
              foreach($request->attachment as $file){
                $filename=$this->singleFileImage($pathImage, $file);
                CommentFiles::create([
                    'comment_id'=>$comment->id,
                    'file'=>$filename
                ]);
              }
            }
            $updatedTicket =Ticket::find($request->ticket_id);
            $apiSettings = Api::where('secret',$request->header('api-key'))->first();
            $mailData =[
              "title"   =>"Nuevo comentario en el ticket (".$updatedTicket->title.")",
              "emails"  =>[$updatedTicket->submitter->email],
              "body"    =>"Notificacion de comentario, puedes verlo en el siguiente link.",
              "comment" => $request->comment,
              "subject" =>"Nuevo comentario sobre el ticket (".$updatedTicket->subject.")",
              "name"    => $updatedTicket->submitter->name.' '.$updatedTicket->submitter->lastname,
              "link"    => $apiSettings->domain.'/tickets/show/'.$request->ticket_id
            ];
            if($updatedTicket->owner()->count()){
                array_push($mailData['emails'],$updatedTicket->owner[0]->email);
                Notification::create([
                    "subject"   =>"Nuevo comentario en el ticket (".$updatedTicket->title.")",
                    "user_id"   =>$updatedTicket->owner[0]->id,
                    "ticket_id" =>$request->ticket_id
                ]);
            }
            /*notificate to user*/
            $this->notfication($mailData);
            Notification::create([
                "subject"   =>"Nuevo comentario en el ticket (".$updatedTicket->title.")",
                "user_id"   =>$updatedTicket->submitter->id,
                "ticket_id" =>$request->ticket_id
            ]);
            return response(['status'=>'success','message'=>'Comentario creado satisfactoriamente','comment'=>Comment::with('user','files')->find($comment->id)],200);
          } else{
             return response(['status'=>'fail','message'=>'Ah ocurrido un error al tratar de crear el comentario, vuelve a intentarlo mas tarde'],500);
          }
     }
    
}