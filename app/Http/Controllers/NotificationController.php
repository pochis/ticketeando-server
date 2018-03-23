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
         
         $notification= Notification::offset($offset)->limit($limit);
         
         /*sorting  by*/
         if ($request->has('sortBy') && $request->has('sortType')){
             $notification->orderBy($request->sortBy,$request->sortType);
         }
         if ($request->has('read')) {
             $notification->where('read',$request->read);
         }
         if ($request->has('user')) {
             $notification->where('user_id',$request->user);
         }
         /*search filter*/
         if ($request->has('search')) {
             $notification->where('subject','like', '%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"]);
         }
         return response([
           'status'=>'success',
           'notifications'=>$notification->get(),
           'total'=>$notification->count()
         ],200);
     }
     public function show($id){
         
     }
     public function update(){
         
     }
     /**
     * destroy notification
     *
     * @method destroy
     */
     public function destroy($id){
        if (Notification::find($id)->delete()) {
            return response(['status'=>'success','message'=>'Notificción eliminada correctamente!!'],200);
        }else{
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de eliminar la notificación, vuelve a intentarlo mas tarde'],500);
        }
         
     }
    
}