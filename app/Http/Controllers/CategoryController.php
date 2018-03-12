<?php namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    
    /**
     * get categories
     *
     * @method getCategories
     */
     public function getCategories(Request $request,$offset=0,$limit=10){
         
         $categories= Category::with('hasParent')->offset($offset)->limit($limit);
         
         /*sorting  by*/
         if($request->has('sortBy') && $request->has('sortType')){
             if($request->sortBy=='parent'){
                 $categories->whereHas($request->sortBy,function($q) use($request){
                      return $q->orderBY('name',$request->sortType);
                 });
                     
             }else{
                $categories->orderBy($request->sortBy,$request->sortType);
                 
             }
         }
         /*search filter*/
         if($request->has('search')){
             $categories->where('name','like', '%'.$request->search.'%')
             ->orWhereRaw("DATE_FORMAT(created_at,'%Y/%m/%d') like ?", ["%$request->search%"])
             ->orWhereRaw("DATE_FORMAT(updated_at,'%Y/%m/%d') like ?", ["%$request->search%"]);
             
         }
         return response([
           'status'=>'success',
           'categories'=>$categories->get(),
           'total'=>Category::count()
         ],200);
         
     }
     /**
     * show category by id 
     *
     * @method show
     */
     public function show(Request $request,$id){
        $category=Category::findOrFail($id);
        return response(['status' => 'success','category'=>$category],200);
     } 
     /**
     * update category by id 
     *
     * @method update
     */
    public function update(Request $request,$id){
        $this->validate($request, [
            'name' => 'required',
         ]);
        $category = Category::findOrFail($id);
        $category->name = trim($request->name);
        $category->parent = ($request->has('parent')) ? $request->parent : 0;
        if ($category->save()) {
            
            return response(['status'=>'success','message'=>'Categoria actualizada!!','category'=>["id"=>$category->id,"name"=>$category->name]],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de actualizar la categoria, vuelve a intentarlo mas tarde'],500);
        }
    
    }
    /**
     * store category 
     *
     * @method store
     */
     public function store(Request $request){
         $this->validate($request, [
            'name' => 'required',
         ]);
        
        $category = new Category();
        $category->name = trim($request->name);
        $category->parent = ($request->has('parent')) ? $request->parent : 0;
        if ($category->save()) {
            
            return response(['status'=>'success','message'=>'Categoria creada!!','category'=>["id"=>$category->id,"name"=>$category->name]],200);
        } else {
            return response(['status'=>'fail','message'=>'Ha ocurrido un error al tratar de crear la categoria, vuelve a intentarlo mas tarde'],500);
        }
     }
}