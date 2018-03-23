<?php

namespace App\Providers;

use App\User;
use App\Api;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $bearer = $request->header('authorization');
            $apiKey = $request->header('api-key');
            $token = explode(" ",$bearer);
            $ip_client = getConnectedUserIp();
          
            if ($apiKey && $bearer && isset($token[1])) {
               $grantedApi = Api::where('secret',$apiKey)->where('status',1)->first();
               $decoedToken= explode("~",base64_decode($token[1]));
              
               if ($decoedToken[1] == $ip_client && $grantedApi) {
                   
                 return User::where('api_token', $token[1])->first();
               }
            }
        });
    }
}
