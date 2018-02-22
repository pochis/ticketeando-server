<?php namespace App\Http\Controllers;

use App\User;
use App\Traits\Files;
use Illuminate\Http\Request;
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

    public function show(Request $request,$id){
        return response(['status' => 'success','user'=>User::find($id)],200);
    } 
}