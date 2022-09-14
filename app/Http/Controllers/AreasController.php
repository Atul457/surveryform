<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Cities;
use App\Models\User;
use Illuminate\Http\Request;

class AreasController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addArea(Request $req, User $user, Areas $areas)
    {
        $validated = $req->validate([
            'area_name' => 'required|min:2|unique:areas',
            'city_ref' => 'required'
        ]);

        $area_name = $req->input("area_name");
        $city_ref = $req->input("city_ref");

        if(!$this->isAdmin($user, $req)) return $this->logout($req);
        
        $res = $areas->insert([
            "area_name" => $area_name,
            "city_ref" => $city_ref
        ]);

        if(!$res)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        return redirect("citynareas")->with(["success" => 'Area added successfully']);
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
     * Display the specified resource.
     *
     * @param  \App\Models\Areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function addAreaView(Areas $areas, Cities $cities, User $user, Request $req)
    {
        if(!$this->isAdmin($user, $req)) return $this->logout($req);
        $cities_res = $cities
        ->orderBy("city_name", "asc")
        ->get()
        ->toArray();
        return view("content.sidebar.admin.cities_n_areas.addArea", 
        ["cities" => $cities_res ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function edit(Areas $areas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Areas $areas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Areas $areas)
    {
        //
    }

    public function getAreas(Request $req, Areas $areas, $cityid){
        $data = $areas
        ->select("*")
        ->where("city_ref", $cityid)
        ->get()
        ->toArray();
       $res2["data"] = $data;
       return json_encode($res2);
    }
}
