<?php

namespace App\Http\Controllers;

use App\Models\SurveyForm;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SurveyFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Request $req)
    {
        if(!$this->isAdmin($user))  {
            $this->unAuthenticatedUser($req, "You are not an admin");
            throw ValidationException::withMessages(['error' => "You are not an admin"]);;
        }
        return view("content/sidebar/form/myforms");
    }

    // Checks whether user is admin or not
    public function isAdmin(User $user){
        if(session("is_admin") != 1) return 0;

        $res = $user
        ->where("email", session("email"))
        ->where("is_admin", 1)
        ->get()
        ->toArray();

        return count($res) > 0 ? 1 : 0;
    }

    // Show my forms
    public function myForms(SurveyForm $survey, User $user, Company $comp, Request $req){

        // Validating the user
        if(!$this->isUserStatusActive()){
            $this->unAuthenticatedUser($req);
            $res = ["data" => []];
            return json_encode($res);
        }

        $data = $survey
        ->select("*", "survey_forms.id as action", "survey_forms.id as share", "survey_forms.id as responsive_id", "companies.comp_name",  "survey_forms.status as status", "survey_forms.id as id", "survey_forms.start_date", "survey_forms.end_date")
        ->leftJoin("products", "products.id", "=", "prod_ref")
        ->leftJoin("companies", "companies.id", "=", "products.comp_id")
        ->get()
        ->toArray();
       $res2["data"] = $data;
       return json_encode($res2);
    }
    
    // Logout
    public function logout(Request $req){
        $req->session()->flush();
        $req->session()->flash('error', "Your account may have been inactivated");
        return redirect("login");
    }
    

    // Logout on second request to any route
    public function unAuthenticatedUser(Request $req, $message = 0){
        $req->session()->flush();

        if($message != 0)
            return session([
                'inactivated' => true,
                'message' => $message
            ]);
        
        return session([
            'inactivated' => true
        ]);
    }

    public function doesProductExist(Product $product, User $user){

        $productId = $user
        ->where("id", session("id"))
        ->where("status", 1)
        ->pluck("id")
        ->toArray();
        if(count($productId) > 0) return true;
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req, Product $product, User $user, SurveyForm $surveyForm)
    {

        $validated = $req->validate([
            'form_name' => 'required|min:2',
            'prod_ref' => 'required',
            'user_ref' => 'required',
            'form_json' => 'required',
            'status' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $form_json = $req->input("form_json");
        $user_ref = session("id");
        $form_name = $req->input("form_name");
        $status = $req->input("status");
        $prod_ref = $req->input("prod_ref");
        $start_date = $req->input("start_date");
        $end_date = $req->input("end_date");

        if(!$this->isUserStatusActive()) $this->logout($req);
        
        $comp_result = $this->doesProductExist($product, $user);

        if(!$comp_result)
            throw ValidationException::withMessages(['error' => "Product you had selected may have been deleted or inactivated by the admin"]);

        $res = $surveyForm->insert([
            "form_json" => $form_json,
            "user_ref" => $user_ref,
            "status" => $status,
            "form_name" => $form_name,
            "prod_ref" => $prod_ref,
            "start_date" => $start_date,
            "end_date" => $end_date
        ]);

        if(!$res)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        return redirect("myforms")->with(["success" => 'Form created successfully']);

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
     * @param  \App\Models\SurveyForm  $surveyForm
     * @return \Illuminate\Http\Response
     */
    public function show(SurveyForm $surveyForm, Request $req, User $user, Company $comp)
    {
        if(!$this->isUserStatusActive()) return $this->logout($req);

        $inactive = 0;
        $admin = 1;

        $users = $user
        ->select("*")
        ->where("status", "!=", $inactive)
        ->where("is_admin", "!=", $admin)
        ->get()
        ->toArray();

        $companies = $comp
        ->select("*")
        ->where("status", "!=", $inactive)
        ->get()
        ->toArray();

        return view("content/sidebar/form/createform", [
            'users' => $users,
            'comp' => $companies
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SurveyForm  $surveyForm
     * @return \Illuminate\Http\Response
     */
    public function edit(SurveyForm $surveyForm, User $user, Company $comp, $id)
    {
        // Validating the user
        if(!$this->isUserStatusActive()) return $this->logout($req);

        // $form = $surveyForm->select("*")->where("user_ref", session("id"))->get()->toArray();       
        $form = $surveyForm
        ->select("*", "survey_forms.id as action", "survey_forms.id as share", "survey_forms.id as responsive_id", "companies.comp_name", "survey_forms.id as id", "survey_forms.status as status")
        ->leftJoin("products", "products.id", "=", "prod_ref")
        ->leftJoin("companies", "companies.id", "=", "products.comp_id")
        ->where("survey_forms.id", $id)
        ->get()
        ->toArray();

        if(count($form) == 0) 
            throw ValidationException::withMessages([
                'error' => "You may have deleted the form, that you are trying to edit."
            ]);

        $inactive = 0;
        $admin = 1;

        $users = $user
        ->select("*")
        ->where("status", "!=", $inactive)
        ->where("is_admin", "!=", $admin)
        ->get()
        ->toArray();

        $companies = $comp
        ->select("*")
        ->where("status", "!=", $inactive)
        ->get()
        ->toArray();

        return view("content.sidebar.form.updateform", [
            "form" => $form[0],
            'users' => $users,
            'comp' => $companies
        ]);
    }

    // Validating the company whether it exists or not and is this form belongs to him
    public function isUserStatusActive()
    {   
        $user_id = DB::table("users")
        ->where('email', session('email'))
        ->where('status', 1)
        ->pluck("id")
        ->toArray();

        return count($user_id) > 0 ? true : false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SurveyForm  $surveyForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, SurveyForm $surveyForm)
    {
        $validated = $req->validate([
            'form_name' => 'required|min:2',
            'prod_ref' => 'required',
            'user_ref' => 'required',
            'form_json' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
            'form_id' => 'required',
        ]);

        $form_json = $req->input("form_json");
        $form_name = $req->input("form_name");
        $form_id = $req->input("form_id");
        $status = $req->input("status");
        $prod_ref = $req->input("prod_ref");
        $user_ref = $req->input("user_ref");
        $start_date = $req->input("start_date");
        $end_date = $req->input("end_date");

        if(!$this->isUserStatusActive()) return $this->logout($req);
        
        $updated = $surveyForm->where("id", $form_id)->update([
            'form_json' => $form_json,
            'form_name' => $form_name,
            'status' => $status,
            'prod_ref' => $prod_ref,
            'user_ref' => $user_ref,
            'user_ref' => $user_ref,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
       
        if(!$updated) return throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
        
        return redirect('myforms')->with('success', 'Form updated successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SurveyForm  $surveyForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(SurveyForm $surveyForm, Request $req, Company $comp)
    {
        $id = $req->input('form_id');
        $user_id = session("id");

        if(!$this->isUserStatusActive()) return $this->logout($req);

        $deleted = $surveyForm->where('id', $id)->delete();
        if($deleted)
            return redirect()->back()->with('success', 'Form deleted successfully');
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }
}
