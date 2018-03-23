<?php namespace App\Http\Controllers;

use App\Project;
use App\Traits\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    use Files;
    /**
     * get projects list
     *
     * @method getProjects
     */
     public function getProjects(Request $request,$offset=0,$limit=10){
         
         $projects= Project::offset($offset)->limit($limit);
         
         /*sorting  by*/
         if ($request->has('sortBy') && $request->has('sortType')){
             $projects->orderBy($request->sortBy,$request->sortType);
         }
         if ($request->has('status')) {
             $projects->where('status',$request->status);
         }
         /*search filter*/
         if ($request->has('search')) {
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
           'total'=>$projects->count()
         ],200);
     }
     /**
     * show project by id 
     *
     * @method show
     */
     public function show(Request $request,$id){
        $project =Project::findOrFail($id);
        return response(['status' => 'success','project'=>$project],200);
     } 
     /**
     * update project by id 
     *
     * @method update
     */
    public function update(Request $request,$id){
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'contact_phone' => 'required|numeric',
        ]);
        $project = Project::findOrFail($id);
        $project->name = $request->name;
        $project->address = $request->address;
        $project->website = $request->website;
        $project->contact_phone = $request->contact_phone;
        $project->contact_cellphone = ($request->has('contact_cellphone')) ? $request->contact_cellphone : null;
        $project->status = ($request->has('status') && !is_null($request->status)) ? $request->status : 0;
        if ($project->save()) {
            
            return response(['status'=>'success','message'=>'Proyecto actualizado!!'],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de actualizar el proyecto, vuelve a intentarlo mas tarde'],500);
        }
    
    }
     /**
     * store project 
     *
     * @method store
     */
     public function store(Request $request){
         $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:project',
            'address' => 'required',
            'contact_phone' => 'required|numeric',
        ],[
            'email.unique'=>'El correo electronico ya ha sido tomado'
        ]);
        
        $project = new Project();
        $project->name = $request->name;
        $project->email = strtolower(trim($request->email));
        $project->website = ($request->has('website'))? $request->website : null;
        $project->address = $request->address;
        $project->contact_phone = $request->contact_phone;
        $project->contact_cellphone = ($request->has('contact_cellphone')) ? $request->contact_cellphone : null;
        $project->status = ($request->has('status') && !is_null($request->status)) ? $request->status : 0;
        if ($project->save()) {
            
            if($request->hasFile('image')){
                $path = base_path('public/static/project/'.$project->id);
                $filename=$this->singleFileImage($path, $request->image);
                $project->image =$filename;
                $project->save();
            }
            return response(['status'=>'success','message'=>'Proyecto creado!!'],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de crear el proyecto, vuelve a intentarlo mas tarde'],500);
        }
     }
     /**
     * destroy project
     *
     * @method destroy
     */
     public function destroy($id){
        $project = Project::findOrFail($id);
        
        $projectIntickets =\App\Ticket::where('project_id',$project->id)->get();
        
        if($projectIntickets->count()){
            return response(['status'=>'fail','message'=>'El proyecto no puede ser eliminado porque tiene relacion con tickets'],500);
        }
        /*delete project image*/
        if($project->image){
            $path = base_path('public/static/project/'.$project->id);
            File::deleteDirectory($path);
        }
        /*delete user projects*/
        \App\UserHasProject::where('project_id',$project->id)->delete();
        
        if($project->delete()){
            return response(['status'=>'success','message'=>'Proyecto eliminado correctamente!!'],200);
        }else{
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de eliminar el proyecto, vuelve a intentarlo mas tarde'],500);
        }
        
     }
     /**
     * upload project image
     *
     * @method uploadImage
     */
    public function uploadImage(Request $request){
    
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png|max:20000',
        ]);
        
        if($request->hasFile('image')){
            $project = Project::findOrFail($request->id);
            $path = base_path('public/static/project/'.$project->id);
            if($project->image){
               File::delete($path."/".$project->image,$path."/medium/".$project->image,$path."/small/".$project->image);
            }
            $filename=$this->singleFileImage($path, $request->image);
            $project->image = $filename;
            if($project->save()){
                return response(['status'=>'success','image'=>$filename],200);
            }
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de guardar la image'],500);
        }else{
            return response(['status'=>'fail','message'=>'image no found'],401);
        }
        
    }
    /**
     * total projects
     *
     * @method total
     */
     public function total(){
         return response(['status' => 'success', "total" => Project::count()], 200);
     }
    
}