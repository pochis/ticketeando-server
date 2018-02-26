<?php namespace App\Http\Controllers;

use App\User;
use App\Traits\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    
    use Files;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * show user by id 
     *
     * @method show
     */
    public function show(Request $request,$id){
        return response(['status' => 'success','user'=>User::find($id)],200);
    } 
    /**
     * update user by id 
     *
     * @method update
     */
    public function update(Request $request,$id){
        $this->validate($request, [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'nullable|numeric',
            'cellphone' => 'nullable|numeric',
            'birthday' => 'nullable|date_format:"Y-m-d"',
            'country_id' => 'required',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->cellphone = $request->cellphone;
        $user->birthday = $request->birthday;
        $user->genre = $request->genre;
        $user->country_id = $request->country_id;
        $user->state_id = $request->state_id;
        $user->city_id = $request->city_id;
        if ($user->save()) {
            return response(['status'=>'success','message'=>'Perfil actualizado'],200);
        } else {
            return response(['status'=>'fail','message'=>'n error ocurred trying to update the user'],401);
        }
    
    }
    /**
     * upload user image
     *
     * @method uploadImage
     */
    public function uploadImage(Request $request){
    
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png|max:20000',
        ]);
        
        if($request->hasFile('image')){
            $user = User::findOrFail($request->id);
            $path = base_path('public/static/user/'.$user->id);
            if($user->image){
               File::delete($path."/".$user->image,$path."/medium/".$user->image,$path."/small/".$user->image);
            }
            $filename=$this->singleFile($path, $request->image);
            $user->image = $filename;
            if($user->save()){
                return response(['status'=>'success','image'=>$filename],200);
            }
            return response(['status'=>'fail','message'=>'An error ocurred trying to storage the image'],500);
        }else{
            return response(['status'=>'fail','message'=>'image no found'],401);
        }
        
    }
    /**
     * change user password
     *
     * @method changePassword
     */
     public function changePassword(Request $request,$id){
         $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required||confirmed',
            'password_confirmation'=>'required'
         ]);
       
         $user = User::findOrFail($id);
         if (!Hash::check($request->current_password, $user->password)) {

            return response(['status' => 'fail', "message" => "La contraseña actual no es valida"], 401);
        }
        if ($request->password != $request->password_confirmation) {
            return response(['status' => 'fail', "message" => "las contraseñas no coinciden"], 401);
        }
        $user->password = app('hash')->make($request->password);
        if ($user->save()) {
            return response(['status' => 'success', "message" => "Contraseña actualizada"], 200);
        }
     }


}