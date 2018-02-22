<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return 'API REST TICKETEA V1';
});
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    
    $router->post('auth','AuthController@login');
    
    $router->group(['middleware' => 'auth'], function () use ($router) {
        
         $router->post('logout',  'AuthController@logout');
         
         /*
          *user group paths
          */
         $router->get('user/{id}',  'UserController@show');
         
         /*
          * regions group paths
          */
          $router->get('region/countries',  'RegionController@getCountries');
          $router->get('region/country/states/{id}',  'RegionController@getStateByCountryId');
          $router->get('region/states/cities/{id}',  'RegionController@getCitiesByStateId');
            
    });
   
});