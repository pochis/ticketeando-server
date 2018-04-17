<?php namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * notifications 
     *
     * @method getNotifications
     */
    public function getNotifications(Request $request,$offset=0,$limit=10){
         
         $notification= Notification::with('ticket')->offset($offset)->limit($limit);
         $total =Notification::count();
         /*sorting  by*/
         if ($request->has('sortBy') && $request->has('sortType')){
             $notification->orderBy($request->sortBy,$request->sortType);
         }
         if ($request->has('read')) {
             $notification->where('read',$request->read);
             $total =Notification::where('read',$request->read)->count();
         }
         if ($request->has('user')) {
             $notification->where('user_id',$request->user);
             $total =Notification::where('user_id',$request->user)->count();
             if($request->has('read')){
               $total =Notification::where('user_id',$request->user)->where('read',$request->read)->count();
             }
         }
         /*search filter*/
         if ($request->has('search')) {
             $notification->where('subject','like', '%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"]);
              $total =Notification::where('subject','like', '%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"])->count();
         }
         return response([
           'status'=>'success',
           'notifications'=>$notification->get(),
           'total'=>$total
         ],200);
     }
     public function show($id){
         
     }
     public function update(Request $request,$id){
        
        $notification = Notification::findOrFail($id);
        $notification->subject =($request->has('subject')) ? $request->subject : $notification->subject;
        $notification->read =($request->has('read')) ? $request->read : $notification->read;
         if ($notification->save()) {
            return response(['status'=>'success','message'=>'Notificación actualizada correctamente!!'],200);
        }else{
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de actualizar la notificación, vuelve a intentarlo mas tarde'],500);
        }
     }
     /**
     * destroy notification
     *
     * @method destroy
     */
     public function destroy($id){
        if (Notification::find($id)->delete()) {
            return response(['status'=>'success','message'=>'Notificación eliminada correctamente!!'],200);
        }else{
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de eliminar la notificación, vuelve a intentarlo mas tarde'],500);
        }
         
     }
    
}