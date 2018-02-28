<?php namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * get projects list
     *
     * @method getProjects
     */
     public function getProjects(){
         
         return response(['status'=>'success','projects'=>Project::all()],200);
     }
    
}