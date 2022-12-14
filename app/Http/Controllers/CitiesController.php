<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( )
    {
        //
    }

    // Logout the user
    public function logout(Request $req){
    }

    // Logout user on next request
    public function logoutUser(Request $req, $message){
    }

    // getCities
    public function getCities(Request $req, User $user, Cities $cities)
    {       
       $cities_res = $cities
        ->orderBy("city_name", "asc")
        ->select("*", "id as action", "id as view_areas")
        ->get()
        ->toArray();

        $res2["data"] = $cities_res;
        return json_encode($res2);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCity(Request $req, Cities $cities, User $user)
    {
        $validated = $req->validate([
            'city_name' => 'required|min:2|unique:cities'
        ]);

        $city_name = $req->input("city_name");
        
        $res = $cities->insert([
            "city_name" => $city_name
        ]);

        if(!$res)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        return redirect("cities")->with(["success" => 'City added successfully']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    // Display cities
    public function cities(User $user, Request $req, Cities $cities){
        return view("content.sidebar.admin.cities_n_areas.cities");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function addCityView(Cities $cities)
    {
        return view("content.sidebar.admin.cities_n_areas.addCity");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function editCity(Cities $cities, User $user, Request $req, $city_id)
    {
        $city = $cities
        ->where("id", $city_id)
        ->get()
        ->toArray();

        if(count($city) == 0) 
            throw ValidationException::withMessages([
                'error' => "You may have deleted the city, that you are trying to edit."
            ]);

        return view("content.sidebar.admin.cities_n_areas.updatecity")->with('city', $city[0]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function updateCity(Request $req, Cities $cities, User $user)
    {
        $req->validate([
            "city_id" => "required",
            "city_name" => "required|min:2"
        ]);
        
        $id = $req->input('city_id');
        $city_name = $req->input('city_name');

        $isDuplicateName = $cities
        ->select("*")
        ->where("city_name", "=", $city_name)
        ->where("id", "!=", $id)
        ->get()
        ->toArray();

        
        if(count($isDuplicateName) != 0) {
            throw ValidationException::withMessages(['error' => "A city with name provided already exists."]);
        }
        
        $updated = $cities->where("id", $id)->update([
            'city_name' => $city_name,
        ]);

        if($updated)
            return redirect('cities')->with('success', 'City updated successfully');
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function deleteCity(Request $req, User $user, Cities $cities)
    {
        $req->validate(["city_id"=> "required"]);
        $id = $req->input('city_id');
        $deleted = $cities->where('id', $id)->delete();
        if($deleted)
            return redirect()->back()->with('success', 'City deleted successfully');
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }
}
