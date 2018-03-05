<?php namespace App\Http\Controllers;

use App\Comment;
use App\CommentFiles;
use App\Traits\Files;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    
    use Files;
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
          if($comment->save()){
              
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
              return response(['status'=>'success','message'=>'Comentario creado satisfactoriamente','comment'=>Comment::with('user','files')->find($comment->id)],200);
          }else{
              return response(['status'=>'fail','message'=>'Ah ocurrido un error al tratar de crear el comentario, vuelve a intentarlo mas tarde'],500);
          }
     }
    
}