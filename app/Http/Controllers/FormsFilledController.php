<?php

namespace App\Http\Controllers;

use App\Models\FormsFilled;
use App\Models\SurveyForm;
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

    public function success(Request $req){
        $req->session()->flash('success', 'Form submitted successfully');
        return view("public.fill_up_form.successpage");
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
    public function show(FormsFilled $formsFilled, SurveyForm $form, Request $req, $id)
    {
        $active = 1;

        $fill_up_form = $form
        ->select("survey_forms.*", "companies.comp_name", "companies.comp_care_no", "companies.comp_addr", "users.name", "users.phone_no", "products.batch_no")
        ->leftJoin("users", "users.id", "=", "survey_forms.user_ref")
        ->leftJoin("products", "products.id", "=", "prod_ref")
        ->leftJoin("companies", "companies.id", "=", "products.comp_id")
        ->where("survey_forms.id", $id)
        ->where("survey_forms.status", $active)
        ->get()
        ->toArray();

        if(count($fill_up_form) == 0)
        {
            $req->session()->flash('error', "The form you are looking for doesn't exist");
            return view("public.fill_up_form.form");
        }

        return view("public.fill_up_form.form", ["form" => $fill_up_form[0]]);
    }

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
