<?php namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    
    
    /**
     * get type by group_type id
     *
     * @method getTypeByGroup
     */
     public function getTypeByGroup($id){
         
         return response(['status'=>'success','types'=>Type::where('group_type_id',$id)->get()],200);
     }
    
}