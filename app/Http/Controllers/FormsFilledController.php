<?php

namespace App\Http\Controllers;

use App\Models\FormsFilled;
use App\Models\SurveyForm;
use App\Models\Cities;
use App\Models\Areas;
use App\Models\User;
use App\Models\userFormLink;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class FormsFilledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("content/sidebar/forms_filled/forms_filled");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req, FormsFilled $formsFilled)
    {

        $req->validate([
            "data_filled" => "required",
            "user_form_link_ref" => "required|numeric"
        ]);

        $data_filled = $req->input("data_filled");
        $user_form_link_ref = $req->input("user_form_link_ref");

        $res = $formsFilled
        ->insert([
            'data_filled' => $data_filled,
            'user_form_link_ref' => $user_form_link_ref
        ]);

        if(!$res)
            throw ValidationException::withMessages([
                'error' => "Something went wrong."
            ]);
        
        return redirect("successpage");

    }

    // Logout
    public function logout(Request $req){
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->flash('error', "Your account may have been inactivated");
        return redirect("login");
    }

    public function success(Request $req){
        $req->session()->flash('success', 'Form submitted successfully');
        return view("public.fill_up_form.successpage");
    }

    // Validating the users is active or not
    public function isUserStatusActive(User $user)
    {   
        return Auth::user()->status;
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

     // Redirects user to the report view page
     public function viewReport(Request $req, FormsFilled $formsFilled, $share_id)
     {
        return view("content.sidebar.form.viewreport", [
            "share_id" => $share_id
        ]);
    }

    public function viewReportAdmin(Request $req, FormsFilled $formsFilled, $form_id)
    {
        $inactive = 0;
        $cities_res = Cities::orderBy("city_name", "asc")
        ->get()
        ->toArray();

       return view("content.sidebar.form.viewreportadmin", [
           "form_id" => $form_id,
           "cities" => $cities_res
       ]);

   }


    public function getReport(FormsFilled $formsFilled, userFormLink $userFormLink, Request $req, $share_id){     

        $user_id = Auth::user()->id;
        $reports = $userFormLink
        ->where([
            "user_form_links.id" => $share_id,
            "user_ref" => $user_id
            ])
        ->get()
        ->toArray();
        $form_belongs_to_user = count($reports);
        if(!$form_belongs_to_user){ 
            return response(['error' => true, 'messsage' => 'Something went wrong'], 404);
        }
        
        $reports = $formsFilled
            ->where("user_form_link_ref", $share_id)
            ->get()
            ->toArray();

        $res2["data"] = $reports;
        return json_encode($res2);
    }


    public function getReportAdmin(FormsFilled $formsFilled, userFormLink $userFormLink, Request $req, $form_id){

        $city_id = $req->input("city_id");
        $area_id = $req->input("area_id");
        $report_ids = $userFormLink
        ->select("areas.area_name", "user_form_links.*", "areas.city_ref", "cities.city_name")
        ->where("survey_form_ref", $form_id)
        ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
        ->leftJoin("cities", "cities.id", "=", "areas.city_ref");
        if($city_id) $report_ids = $report_ids->where("cities.id",  $city_id);
        if($area_id) $report_ids = $report_ids->where("areas.id",  $area_id);
        $report_ids = $report_ids->pluck("user_form_links.id")
        ->toArray();

        $reports = $formsFilled
        ->whereIn("user_form_link_ref", $report_ids)
        ->get()
        ->toArray();

        $res2["data"] = $reports;
        return json_encode($res2);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    public function edit(FormsFilled $formsFilled)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormsFilled $formsFilled)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormsFilled $formsFilled)
    {
        //
    }
}
