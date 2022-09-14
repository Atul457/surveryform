<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\User;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    // Validates the admin
    public function isAdmin(User $user, Request $req){
        $email = session("email");
        $is_admin_user = 1;
        $is_admin = $user
        ->where("email", $email)
        ->where("is_admin", $is_admin_user)
        ->get();

        return count($is_admin) > 0 ? 1 : 0;
    }

    // Logout the user
    public function logout(Request $req){
        $req->session()->flush();
        $req->session()->flash('success', 'Logged out successfully');
        return redirect("login");
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

        if(!$this->isAdmin($user, $req)) return $this->logout($req);
        
        $res = $cities->insert([
            "city_name" => $city_name
        ]);

        if(!$res)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        return redirect("citynareas")->with(["success" => 'City added successfully']);
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
    public function edit(Cities $cities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cities $cities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cities  $cities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cities $cities)
    {
        //
    }
}
