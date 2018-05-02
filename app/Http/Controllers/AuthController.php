<?php

namespace App\Http\Controllers;

use App\Api;
use App\User;
use Illuminate\Http\Request;
use App\Traits\MailNotification;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use MailNotification;
    
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
    
    public function resetPassword(Request $request) {
        
        $this->validate($request, [
            'recoveryemail' => 'required|email',
            ], [
            'recoveryemail.required' =>'Correo electronico es requerido',
            'recoveryemail.email' => 'El correo electronico no es valido',
        ]);
        
        $user = User::where('email',strtolower($request->recoveryemail))->first();
    
        if($user) {
            $generatedPass = str_random(8);
            $updatePass = User::where('email',strtolower($request->recoveryemail))->update(['password'=>app('hash')->make($generatedPass)]);
            if($updatePass) {
                
                $apiSettings = Api::where('secret',$request->header('api-key'))->first();
        
                $this->notfication([
                  "sitename"=> "Ticketeando",
                  "title"   => "Recuperación de contraseña",
                  "emails"  => $user->email,
                  "body"    => "Se ha generado una nueva contraseña, recuerda cambiarla una vez accedas a la plataforma, nueva contraseña <strong>".$generatedPass."</strong>. <br> puedes ingresar de nuevo en el siguiente link",
                  "subject" => "Nueva contraseña ticketeando",
                  "name"    => $user->name." ".$user->lastname,
                  "link"    => $apiSettings->domain
                ]);
                return response(['status'=>'success', 'message'=>'Se ha enviado un correo elecontronico al correo ('.$user->email.') con la nueva contraseña'],200);
            
            }else{
                return response(['status'=>'fail', 'message'=>'Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde'],500);
            }
        }else {
            return response(['status'=>'fail', 'message'=>'El correo electronico no se encuentra registrado'],403);
        }
    }
}