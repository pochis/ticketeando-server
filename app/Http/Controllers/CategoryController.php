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
     public function getCategories(){
         
         return response(['status'=>'success','categories'=>Category::all()],200);
     }
    
}