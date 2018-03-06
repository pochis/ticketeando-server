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
     public function getProjects(Request $request,$offset=0,$limit=10){
         
         return response(['status'=>'success','projects'=>Project::all()],200);
         $projects= Project::offset($offset)->limit($limit);
         
         /*sorting  by*/
         if($request->has('sortBy') && $request->has('sortType')){
             $projects->orderBy($request->sortBy,$request->sortType);
         }
         /*search filter*/
         if($request->has('search')){
             $projects->where('name','like', '%'.$request->search.'%')
             ->orWhere('email','like','%'.$request->search.'%')
             ->orWhere('website','like','%'.$request->search.'%')
             ->orWhere('address','like','%'.$request->search.'%')
             ->orWhere('contact_phone','like','%'.$request->search.'%')
             ->orWhere('contact_cellphone','like','%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"]);
             
         }
         
         return response([
           'status'=>'success',
           'projects'=>$projects->get(),
           'total'=>Project::count()
         ],200);
     }
    
}