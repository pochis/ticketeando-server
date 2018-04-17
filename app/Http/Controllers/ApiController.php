<?php namespace App\Http\Controllers;

use App\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * get api list
     *
     * @method getApiList
     */
    public function getApiList(Request $request,$offset=0,$limit=10){
         
         $apis= Api::offset($offset)->limit($limit);
         $total =Api::count();
         /*sorting  by*/
         if($request->has('sortBy') && $request->has('sortType')){
             $apis->orderBy($request->sortBy,$request->sortType);
         }
         /*search filter*/
         if($request->has('search')){
             $apis->where('secret','like', '%'.$request->search.'%')
             ->orWhere('domain','like','%'.$request->search.'%')
             ->orWhere('email','like','%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"]);
              $total =Api::where('secret','like', '%'.$request->search.'%')
             ->orWhere('domain','like','%'.$request->search.'%')
             ->orWhere('email','like','%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"])->count();
         }
         
         return response([
           'status'=>'success',
           'apilist'=>$apis->get(),
           'total'=>$total
         ],200);
     }
      /**
     * show api by id 
     *
     * @method show
     */
     public function show(Request $request,$id){
        $api =Api::findOrFail($id);
        return response(['status' => 'success','api'=>$api],200);
     } 
     /**
     * generate api key
     *
     * @method generateKey
     */
     public function store(Request $request){
         $this->validate($request,[
            'secret' => 'required',
            'domain' => 'required',
            'email' => 'required|email',
         ]);
         $api = new Api();
         $api->secret= $request->secret;
         $api->domain= $request->domain;
         $api->email= strtolower(trim($request->email));
         $api->status= ($request->has('status') && !is_null($request->status)) ? $request->status : 0;
         if ($api->save()) {
            return response(['status'=>'success','message'=>'Api creada satisfactoriamente!!'],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de crear la api, vuelve a intentarlo mas tarde'],500);
        }
     }
     /**
     * update api by id 
     *
     * @method update
     */
    public function update(Request $request,$id){
        $this->validate($request,[
            'secret' => 'required',
            'domain' => 'required',
            'email' => 'required|email',
         ]);
        $api = Api::findOrFail($id);
        $api->secret= $request->secret;
        $api->domain= $request->domain;
        $api->email= strtolower(trim($request->email));
        $api->status= ($request->has('status') && !is_null($request->status)) ? $request->status : 0;
        if ($api->save()) {
            return response(['status'=>'success','message'=>'Api actualizada!!'],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de actualizar el proyecto, vuelve a intentarlo mas tarde'],500);
        }
    
    }
    /**
     * destroy api
     *
     * @method destroy
     */
     public function destroy($id){
        $api = Api::findOrFail($id);
        
        if($id==1){
            return response(['status'=>'fail','message'=>'No puedes eliminar la api por defecto'],500);
        }
        
        if($api->delete()){
            return response(['status'=>'success','message'=>'Api eliminada correctamente!!'],200);
        }else{
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de eliminar la api, vuelve a intentarlo mas tarde'],500);
        }
        
     }
     /**
     * generate api key
     *
     * @method generateKey
     */
     public function generateKey(){
         return response(['status'=>'success',"key"=>str_random(40)],200);
     }
    
}
