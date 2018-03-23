<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::with('role')->where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)){
               $ip_client=  getConnectedUserIp();
               $apitoken = base64_encode(str_random(40).'~'.$ip_client);
               $user->api_token =$apitoken;
                  
               if ($user->save()) {
                  return response(['status' => 'success','token' => $apitoken,'user'=>$user],200);
               }else {
                  return response(['status'=>'fail','message'=>'Ah ocurrido un error al tratar de crear el token, vuelve a intentarlo mas tarde'],401);
               }
            }else {
              return response(['status' => 'fail','message'=>'La contraseña es incorrecta'],401);
            } 
        }
        return response(['status' => 'fail','message'=>'Ah ocurrido un error insesperado, vuelve a intentarlo mas tarde'],500);
         
    }
    public function logout(Request $request){
        $this->validate($request, [
            'user' => 'required|numeric',
        ]);
        $user = User::findOrFail($request->user);
        if ($user) {
           $user->api_token=null;
           if ($user->save()) {
              return response(['status' => 'success'],200);
            } else {
              return response(['status' => 'fail','message'=>"Ah ocurrido un error al tratar de borrar el token, vuelve a intentarlo mas tarde"],401);
            }
        }
        return response(['status' => 'fail','message'=>'Ah ocurrido un error insesperado, vuelve a intentarlo mas tarde'],500);
    }
    
 
}