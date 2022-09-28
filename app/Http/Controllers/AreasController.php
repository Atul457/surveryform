<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Cities;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

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

    // Display areas of a city
    public function areas(User $user, Request $req, Cities $cities, $cityid){
        if(!$this->isAdmin($user, $req)) return $this->logout($req);
        return view("content.sidebar.admin.cities_n_areas.areasOfCity", ["cityid" => $cityid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addArea(Request $req, User $user, Areas $areas)
    {
        if(!$this->isAdmin($user, $req)) return $this->logout($req);

        $validated = $req->validate([
            'area_name' => 'required|min:2|unique:areas',
            'city_ref' => 'required'
        ]);

        $area_name = $req->input("area_name");
        $city_ref = $req->input("city_ref");

        
        $res = $areas->insert([
            "area_name" => $area_name,
            "city_ref" => $city_ref
        ]);

        if(!$res)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        return redirect("cities")->with(["success" => 'Area added successfully']);
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
        $res = Auth::user()->is_admin;
        return $res ? 1 : 0;
    }

    // Logout the user
    public function logout(Request $req){
        $req->session()->invalidate();
        $req->session()->flash('error', "Your are not an admin");
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
    public function editArea(Areas $areas, User $user, Request $req, Cities $cities, $area_id)
    {
        // Validating the user
        if(!$this->isAdmin($user, $req)) return $this->logout($req);

        $area = $areas
        ->where("id", $area_id)
        ->get()
        ->toArray();

        $cities_res = $cities
        ->orderBy("city_name", "asc")
        ->get()
        ->toArray();

        if(count($area) == 0) 
            throw ValidationException::withMessages([
                'error' => "You may have deleted the city, that you are trying to edit."
            ]);

        return view("content.sidebar.admin.cities_n_areas.editArea")->with([
            'area' => $area[0],
            "cities" => $cities_res 
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function updateArea(Request $req, Areas $areas, User $user)
    {
        // Validating the user
        if(!$this->isAdmin($user, $req)) return $this->logout($req);

        $req->validate([
            "city_ref" => "required",
            "area_name" => "required|min:2",
            "area_id" => "required",
            "old_city_ref" => "required"
        ]);
        
        $id = $req->input('area_id');
        $area_name = $req->input('area_name');
        $city_ref = $req->input('city_ref');
        $old_city_ref = $req->input('old_city_ref');

        $isDuplicateName = $areas
        ->select("*")
        ->where("area_name", "=", $area_name)
        ->where("city_ref", "=", $old_city_ref)
        ->where("id", "!=", $id)
        ->get()
        ->toArray();

        
        if(count($isDuplicateName) != 0) {
            throw ValidationException::withMessages(['error' => "A area with the name provided already exists under the city."]);
        }
        
        $updated = $areas
        ->where("id", $id)
        ->where("city_ref", $old_city_ref)
        ->update([
            'area_name' => $area_name,
            'city_ref' => $city_ref
        ]);

        if($updated){
            $is_city_changed = $old_city_ref != $city_ref;
            $message = 'Area updated successfully';
            $message = $is_city_changed ? $message.", City changed" : $message;
            return redirect('areas/'.$city_ref)->with('success', $message);

        }
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function deleteArea(Request $req, User $user, Areas $areas)
    {
        $req->validate([
            "area_id"=> "required"
        ]);

        $id = $req->input('area_id');
        if(!$this->isAdmin($user, $req)){
            $req->session()->flush();
            $req->session()->flash('error', "Your account may have been inactivated");
            return redirect("login");
        }

        $deleted = $areas->where('id', $id)->delete();
        if($deleted)
            return redirect()->back()->with('success', 'Area deleted successfully');
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }

    public function getAreas(Request $req, Areas $areas, $cityid){
        $data = $areas
        ->select("*", "id as action")
        ->where("city_ref", $cityid)
        ->get()
        ->toArray();
       $res2["data"] = $data;
       return json_encode($res2);
    }
}
