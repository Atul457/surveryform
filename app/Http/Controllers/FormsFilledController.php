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
    public function create(Request $req)
    {
        print_r($req->all());
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
        ->where("id", $id)
        ->select("form_json", "id", "form_name")
        ->where("status", $active)
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
