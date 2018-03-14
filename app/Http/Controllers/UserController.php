<?php namespace App\Http\Controllers;

use App\User;
use App\UserHasProject;
use App\Traits\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    
    use Files;
    
    /**
     * get users
     *
     * @method getTickets
     */
     public function getUsers(Request $request,$offset=0,$limit=10){
         
         $users= User::with('role')->offset($offset)->limit($limit);
         
         /*sorting  by*/
         if($request->has('sortBy') && $request->has('sortType')){
            if($request->sortBy=='role'){
               $users->whereHas($request->sortBy,function($q) use($request){
                  return $q->orderBY('name',$request->sortType);
               });
            }else{
               $users->orderBy($request->sortBy,$request->sortType);
            }
         }
         /*search filter*/
         if($request->has('search')){
             $users->where('name','like', '%'.$request->search.'%')
             ->orWhere('lastname','like','%'.$request->search.'%')
             ->orWhere('email','like','%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereHas('role',function($q) use($request){
                 return $q->where('name', 'like', '%'.$request->search.'%');
             });
         }
         
         return response([
           'status'=>'success',
           'users'=>$users->get(),
           'total'=>User::count()
         ],200);
     }
    /**
     * show user by id 
     *
     * @method show
     */
    public function show(Request $request,$id){
        $user = User::with('projects')->findOrFail($id);
        return response(['status' => 'success','user'=>$user],200);
    } 
    /**
     * update user by id 
     *
     * @method update
     */
    public function update(Request $request,$id){
        $this->validate($request, [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'nullable|numeric',
            'cellphone' => 'nullable|numeric',
            'birthday' => 'nullable|date_format:"Y-m-d"',
            'country_id' => 'required',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->cellphone = $request->cellphone;
        $user->birthday = $request->birthday;
        $user->genre = $request->genre;
        $user->role_id = $request->role_id;
        $user->country_id = $request->country_id;
        $user->state_id = $request->state_id;
        $user->city_id = $request->city_id;
        $user->status = ($request->has('status')) ? $request->status :$user->status;
        if ($user->save()) {
            //add project if has some
            if($request->has('projects') && count($request->projects)){
                UserHasProject::where('user_id',$user->id)->delete();
                foreach($request->projects as $project){
                    UserHasProject::updateOrCreate([
                        'user_id'=>$user->id,
                        'project_id'=>$project
                    ]);
                }
            }
            return response(['status'=>'success','message'=>'Perfil actualizado'],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de actualizar el usuario,vuelve a intentarlo mas tarde'],500);
        }
    
    }
    /**
     * store user 
     *
     * @method store
     */
     public function store(Request $request){
         $this->validate($request, [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:user',
            'password' => 'required|confirmed',
            'password_confirmation'=>'required',
            'phone' => 'nullable|numeric',
            'cellphone' => 'nullable|numeric',
            'birthday' => 'nullable|date_format:"Y-m-d"',
            'country_id' => 'required',
            'status' => 'required',
        ],[
            'email.unique'=>'El correo electronico ya ha sido tomado'
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = strtolower(trim($request->email));
        $user->password = app('hash')->make($request->password);
        $user->phone = ($request->has('phone'))? $request->phone :null;
        $user->cellphone = ($request->has('cellphone')) ? $request->cellphone : null;
        $user->birthday = ($request->has('birthday')) ? $request->birthday :null;
        $user->genre = $request->genre;
        $user->role_id = $request->role_id;
        $user->country_id = $request->country_id;
        $user->state_id = ($request->has('state_id')) ? $request->state_id : 0;
        $user->city_id = ($request->has('city_id')) ? $request->city_id : 0;
        $user->status = ($request->has('status')) ? $request->status : 0;
        if ($user->save()) {
            if($request->hasFile('image')){
                $path = base_path('public/static/user/'.$user->id);
                $filename=$this->singleFileImage($path, $request->image);
                $user->image =$filename;
                $user->save();
            }
            //add project if has some
            if($request->has('projects')){
                foreach($request->projects as $project){
                    UserHasProject::updateOrCreate([
                        'user_id'=>$user->id,
                        'project_id'=>$project
                    ]);
                }
            }
            return response(['status'=>'success','message'=>'Usuario creado satisfactorimanete!!'],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de crear el usuario,vuelve a intentarlo mas tarde'],500);
        }
     }
    /**
     * user projects
     *
     * @method projects
     */ 
     public function projects(Request $request,$id,$offset=0,$limit=10){
         
       $user= User::where('id',$id)->first();
       $projects = $user->projects()->offset($offset)->limit($limit);
        
        /*search filter*/
         if($request->has('search')){
             
             $projects->where('name', 'like', '%'.$request->search.'%')
                 ->orWhere('email','like','%'.$request->search.'%')
                 ->orWhere('website','like','%'.$request->search.'%')
                 ->orWhere('address','like','%'.$request->search.'%')
                 ->orWhere('contact_phone','like','%'.$request->search.'%')
                 ->orWhere('contact_cellphone','like','%'.$request->search.'%')
                 ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
                 ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"]);
         }
        
        return response([
            'status' => 'success',
            'total'=>$user->projects()->count(),
            'projects'=>$projects->get() 
        ],200);
     }
     
     
    /**
     * upload user image
     *
     * @method uploadImage
     */
    public function uploadImage(Request $request){
    
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png|max:20000',
        ]);
        
        if($request->hasFile('image')){
            $user = User::findOrFail($request->id);
            $path = base_path('public/static/user/'.$user->id);
            if($user->image){
               File::delete($path."/".$user->image,$path."/medium/".$user->image,$path."/small/".$user->image);
            }
            $filename=$this->singleFileImage($path, $request->image);
            $user->image = $filename;
            if($user->save()){
                return response(['status'=>'success','image'=>$filename],200);
            }
            return response(['status'=>'fail','message'=>'An error ocurred trying to storage the image'],500);
        }else{
            return response(['status'=>'fail','message'=>'image no found'],401);
        }
        
    }
    /**
     * change user password
     *
     * @method changePassword
     */
     public function changePassword(Request $request,$id){
         $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation'=>'required'
         ]);
       
         $user = User::findOrFail($id);
         if (!Hash::check($request->current_password, $user->password)) {

            return response(['status' => 'fail', "message" => "La contraseña actual no es valida"], 401);
        }
        if ($request->password != $request->password_confirmation) {
            return response(['status' => 'fail', "message" => "las contraseñas no coinciden"], 401);
        }
        $user->password = app('hash')->make($request->password);
        if ($user->save()) {
            return response(['status' => 'success', "message" => "Contraseña actualizada"], 200);
        }
     }
    /**
     * total users
     *
     * @method total
     */
     public function total(){
         return response(['status' => 'success', "total" => User::count()], 200);
     }
    /**
     * total users projects
     *
     * @method totalRelations
     */
     public function totalRelations ($id,$relation){
         $user= User::findOrFail($id);
         $total=0;
         if($relation=='projects'){
             $total=$user->projects()->count();
         }else if($relation=='tickets'){
             $total =$user->tickets()->count();
         }
         return response(['status' => 'success', "total" => $total], 200);
     }
    
}