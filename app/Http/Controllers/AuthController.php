<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ],
        [
            'email.required'=>'El correo electronico es requerido',
            'email.email'=>'El correo electronico no es valido',
            'password.required'=>'La contraseña es requerida',
        ]);
 
        $user = User::where('email', $request->email)->first();
        
        if($user){
            if(Hash::check($request->password, $user->password)){
 
                  $apitoken = base64_encode(str_random(40));
                  User::where('email', $request->email)->update(['api_token' => $apitoken]);
         
                  return response(['status' => 'success','api_token' => $apitoken,"user"=>$user],200);
         
              }else{
                  return response(['status' => 'fail'],401);
         
              } 
        }
             return response(['status' => 'fail'],401);
         
    }
    public function logout(){
        
    }
}