<?php namespace App\Http\Controllers;

use App\Country;
use App\State;
use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    /**
     * countries 
     *
     * @method getCountries
     */
    public function getCountries(){
        return response(['status'=>'success','countries'=>Country::all()],200);   
    }
    /**
     * get cities by state id 
     *
     * @method getCitiesByStateId
     */
    public function getCitiesByStateId($id){
        return response(['status'=>'success','cities'=>City::where('state_id',$id)->get()],200);
    }
    /**
     * get states by country id 
     *
     * @method getCitiesByStateId
     */
    public function getStateByCountryId($id){
        return response(['status'=>'success','states'=>State::where('country_id',$id)->get()],200);
    }
}