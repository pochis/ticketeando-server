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
    return 'Ticketeando/API v1';
});
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    
    $router->post('auth','AuthController@login');
    
    $router->group(['middleware' => 'auth'], function () use ($router) {
        
         $router->post('logout',  'AuthController@logout');
         
         /*
          *user group paths
          */
         $router->get('user/{id}',  'UserController@show');
         $router->put('user/{id}',  'UserController@update');
         $router->put('user/password/update/{id}',  'UserController@changePassword');
         $router->post('user/image/upload',  'UserController@uploadImage');
         /*
          * regions group paths
          */
         $router->get('region/countries',  'RegionController@getCountries');
         $router->get('region/country/states/{id}',  'RegionController@getStateByCountryId');
         $router->get('region/states/cities/{id}',  'RegionController@getCitiesByStateId');
         /*
          * types group paths
          */
         $router->get('type/group/{id}','TypeController@getTypeByGroup');
         /*
          * categories group paths
          */
         $router->get('categories','CategoryController@getCategories');
         /*
          * project group paths
          */
         $router->get('projects','ProjectController@getProjects');
         /*
          * tickets group paths
          */
         $router->get('tickets/{user}[/{offset:[0-9]+?}/{limit:[0-9]+?}]','TicketController@getTickets');
         $router->post('ticket','TicketController@store');
            
    });
   
});