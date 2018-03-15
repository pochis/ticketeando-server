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
         $router->get('users[/{offset:[0-9]+?}/{limit:[0-9]+?}]','UserController@getUsers');
         $router->get('user/{id:[0-9]+}/projects[/{offset:[0-9]+?}/{limit:[0-9]+?}]','UserController@projects');
         $router->get('user/{id:[0-9]+}','UserController@show');
         $router->get('users/total','UserController@total');
         $router->get('user/{id:[0-9]+}/{relation}/total','UserController@totalRelations');
         $router->put('user/{id:[0-9]+}','UserController@update');
         $router->put('user/password/update/{id}','UserController@changePassword');
         $router->post('user','UserController@store');
         $router->post('user/image/upload',  'UserController@uploadImage');
         $router->delete('user/{id:[0-9]+}',  'UserController@destroy');
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
         $router->get('categories[/{offset:[0-9]+?}/{limit:[0-9]+?}]','CategoryController@getCategories');
         $router->get('category/{id:[0-9]+}','CategoryController@show');
         $router->put('category/{id:[0-9]+}',  'CategoryController@update');
         $router->post('category','CategoryController@store');
         /*
          * project group paths
          */
         $router->get('projects[/{offset:[0-9]+?}/{limit:[0-9]+?}]','ProjectController@getProjects');
         $router->get('project/{id:[0-9]+}','ProjectController@show');
         $router->get('projects/total','ProjectController@total');
         $router->put('project/{id:[0-9]+}',  'ProjectController@update');
         $router->post('project','ProjectController@store');
         $router->post('project/image/upload',  'ProjectController@uploadImage');
         /*
          * tickets group paths
          */
         $router->get('ticket/{id:[0-9]+}',  'TicketController@show');
         $router->get('tickets[/{offset:[0-9]+?}/{limit:[0-9]+?}]','TicketController@getTickets');
         $router->get('tickets/total','TicketController@total');
         $router->put('ticket/{id:[0-9]+}','TicketController@update');
         $router->post('ticket','TicketController@store');
         $router->post('ticket/state','TicketController@state');
         /*
          * comments group paths
          */
         $router->get('comments/{ticket}[/{offset:[0-9]+?}/{limit:[0-9]+?}]','CommentController@getComments');
         $router->post('comment','CommentController@store');
    });
   
});