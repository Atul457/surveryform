<?php

namespace App\Http\Controllers;

use App\Models\FormsFilled;
use App\Models\SurveyForm;
use App\Models\User;
use App\Models\userFormLink;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $data_filled = $req->input("data_filled");
        $survey_form_ref = $req->input("survey_form_ref");
        $user_ref = $req->input("user_ref");

        $res = $formsFilled
        ->insert([
            'data_filled' => $data_filled,
            'survey_form_ref' => $survey_form_ref,
            'user_ref' => $user_ref
        ]);

        if(!$res)
            throw ValidationException::withMessages([
                'error' => "Something went wrong."
            ]);
        
        return redirect("successpage");

    }

    // Logout
    public function logout(Request $req){
        $req->session()->flush();
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
        $user_id = $user
        ->where('email', session('email'))
        ->where('status', 1)
        ->pluck("id")
        ->toArray();

        return count($user_id) > 0 ? true : false;
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
