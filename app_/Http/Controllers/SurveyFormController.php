<?php

namespace App\Http\Controllers;

use App\Models\SurveyForm;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use App\Models\Cities;
use App\Models\UserCompany_link;
use App\Models\userFormLink;
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
    public function myForms(SurveyForm $survey, User $user, Request $req){

        // Validating the admin
        if(!$this->isAdmin($user)){
            $this->unAuthenticatedUser($req, "You are not a admin");
            $res = ["data" => []];
             return json_encode($res);
        }
        
        $data = $survey
        ->select("*", "survey_forms.id as action", "survey_forms.id as forms_allocated", "survey_forms.id as share", "survey_forms.id as responsive_id", "companies.comp_name",  "survey_forms.status as status", "survey_forms.id as id", "survey_forms.id as view_report", "survey_forms.start_date", "survey_forms.end_date")
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

    public function allocateForm(Request $req, userFormLink $userFormLink){

        $req->validate([
            "user_ref" => "required|numeric",
            "area_ref" => "required|numeric",
            "form_ids" => "required"
        ]);

        $user_ref = $req->input("user_ref");
        $form_ids = $req->input("form_ids");
        $area_ref = $req->input("area_ref");
        $alreadyAllocatedFormList = [];
        $loop_count = 1;

        foreach ($form_ids as $form_id) {
            $elem = [
                "user_ref" => $user_ref,
                "survey_form_ref" => $form_id,
                // "area_ref" => $area_ref
            ];
            $elemToInsert = [
                "user_ref" => $user_ref,
                "survey_form_ref" => $form_id,
                "area_ref" => $area_ref
            ];
            $doesExists = $userFormLink
            ->where($elem)
            ->get()
            ->toArray();

            if(count($doesExists) === 0){
                $res = $userFormLink
                ->insert($elemToInsert);
                print_r($res);
            }else{
                $alreadyAllocatedFormList[] = $loop_count;
            }
            $loop_count++;
        }

        $message = '';
        if(count($alreadyAllocatedFormList) > 0){
            $message = implode(",", $alreadyAllocatedFormList);
            $message = "form/forms at number [".$message."]"." could not be allocated, because they were already allocated to the employee.";
            throw ValidationException::withMessages(['error' => $message]);
        }
        
        $message = "Forms allocated successfully";
        $req->session()->flash('success', $message);
        return redirect("myforms");
    }

    // User view 
    public function userView(){
        return view("content/sidebar/form/userforms");
    }

    // Form allocation view
    public function allocateFormView(SurveyForm $survey, User $user, Company $comp, Request $req, Cities $cities){
        $inactive = 0;
        $companies = $comp
        ->select("*")
        ->where("status", "!=", $inactive)
        ->get()
        ->toArray();

        $cities_res = $cities
        ->orderBy("city_name", "asc")
        ->get()
        ->toArray();

        return view("content.sidebar.admin.form_allocation.formAllocation", [
            'comp' => $companies,
            "cities" => $cities_res
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

    // Get users against a company
    public function getUsersOfComp(Product $product, Request $req, User $user, UserCompany_link $user_comp_link, $id)
    {
        if(!$this->isAdmin($user)){
            $this->unAuthenticatedUser($req, "You are not a admin");
            $res = ["data" => []];
             return json_encode($res);
        }

        $products = UserCompany_link::select("user_company_links.*", "companies.comp_name", "users.name as user_name", "users.id as user_id")
        ->leftJoin("companies", "companies.id", "=", "user_company_links.comp_ref")
        ->leftJoin("users", "users.id", "=", "user_company_links.user_ref")
        ->where('comp_ref', $id)
        ->get()
        ->toArray();

        $res2["data"] = $products;
        return json_encode($res2);

    }

    public function formsAllocatedView(Request $req, SurveyForm $survey, $form_id){
        $form_name = $survey
        ->where("id", $form_id)
        ->pluck("form_name")
        ->toArray();

        if(count($form_name) == 0)
            throw ValidationException::withMessages(['error' => "Survey form not found"]);

        return view("content/sidebar/admin/form_allocation/formsAllocated", [
            'form_id' => $form_id,
            'form_name' => $form_name[0]
        ]);
    }

    public function formsAllocated(Request $req, userFormLink $userFormLink, User $user, $form_id){

        if(!$this->isAdmin($user)){
            $this->unAuthenticatedUser($req, "You are not a admin");
            $res = ["data" => []];
            return json_encode($res);
        }
       
        $forms_allocated = userFormLink::select( "user_form_links.id as action", "user_form_links.id as share_id", "user_form_links.id as responsive_id", "user_form_links.*", "users.name", "users.email", "user_company_links.comp_ref", "users.id as user_id", "companies.comp_name", "areas.area_name", "cities.city_name")
        ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
        ->leftJoin("cities", "cities.id", "=", "areas.city_ref")
        ->leftJoin("users", "users.id", "=", "user_form_links.user_ref")
        ->leftJoin("user_company_links", "user_company_links.user_ref", "=", "users.id")
        ->leftJoin("companies", "companies.id", "=", "user_company_links.comp_ref")
        ->where("user_form_links.survey_form_ref", $form_id)
        ->get()
        ->toArray();

        $res2["data"] = $forms_allocated;
        return json_encode($res2);
    }
    
    // Get forms against a company
    public function getFormsOfProduct(Request $req, SurveyForm $survey, User $user, $prod_id)
    {
        if(!$this->isAdmin($user)){
            $this->unAuthenticatedUser($req, "You are not a admin");
            $res = ["data" => []];
             return json_encode($res);
        }

        $products = SurveyForm::select("id as form_id", "form_name")
        ->where('prod_ref', $prod_id)
        ->get()
        ->toArray();

        $res2["data"] = $products;
        return json_encode($res2);

    }

    public function shareForm(userFormLink $userFormLink, Request $req, $share_id){

        $active = 1;
        $fill_up_form = $userFormLink
        ->select("user_form_links.*", "users.name", "users.phone_no", "products.batch_no", "survey_forms.form_name", "survey_forms.form_json", "companies.comp_name", "companies.comp_care_no", "companies.comp_addr", "survey_forms.start_date", "survey_forms.end_date")
        ->leftJoin("users", "users.id", "=", "user_form_links.user_ref")
        ->leftJoin("survey_forms", "survey_forms.id", "=", "user_form_links.survey_form_ref")
        ->leftJoin("products", "products.id", "=", "survey_forms.prod_ref")
        ->leftJoin("companies", "companies.id", "=", "products.comp_id")
        ->where("user_form_links.id", $share_id)
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req, Product $product, User $user, SurveyForm $surveyForm)
    {

        $validated = $req->validate([
            'form_name' => 'required|min:2',
            'prod_ref' => 'required',
            'form_json' => 'required',
            'status' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $form_json = $req->input("form_json");
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

        $companies = $comp
        ->select("*")
        ->where("status", "!=", $inactive)
        ->get()
        ->toArray();

        return view("content.sidebar.form.updateform", [
            "form" => $form[0],
            'comp' => $companies
        ]);
    }

    // Deallocates the form
    public function deallocateForm(Request $req,  userFormLink $userFormLink, User $user){

        $req->validate([
            "share_id" => "required"
        ]);

        if(!$this->isAdmin($user))  {
            $this->unAuthenticatedUser($req, "You are not an admin");
            throw ValidationException::withMessages(['error' => "You are not an admin"]);;
        }

        $share_id = $req->input("share_id");
        $deleted = $userFormLink->where('id', $share_id)->delete();

        if($deleted)
            return redirect()->back()->with('success', 'Form deallocated successfully');
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }

    // Validating the users is active or not
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
        $start_date = $req->input("start_date");
        $end_date = $req->input("end_date");

        if(!$this->isUserStatusActive()) return $this->logout($req);
        
        $updated = $surveyForm->where("id", $form_id)->update([
            'form_json' => $form_json,
            'form_name' => $form_name,
            'status' => $status,
            'prod_ref' => $prod_ref,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
       
        if(!$updated) return throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
        
        return redirect('myforms')->with('success', 'Form updated successfully');
        
    }

    public function getUserForms(userFormLink $userFormLink, User $user, Request $req)
    {
         // Validating the user
         if(!$this->isUserStatusActive()){
            $this->unAuthenticatedUser($req);
            $res = ["data" => []];
            return json_encode($res);
        }

        $data = $userFormLink
        ->select("user_form_links.created_at", "user_form_links.id as share_id", "user_form_links.updated_at", "areas.area_name", "cities.city_name", "survey_forms.form_name", "products.prod_name as form_prod_name", "companies.comp_name as form_comp_name", "user_form_links.id as view_report")
        ->where("user_ref", session("id"))
        ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
        ->leftJoin("cities", "cities.id", "=", "areas.city_ref")
        ->leftJoin("survey_forms", "survey_forms.id", "=", "user_form_links.survey_form_ref")
        ->leftJoin("products", "products.id", "=", "survey_forms.prod_ref")
        ->leftJoin("companies", "companies.id", "=", "products.comp_id")
        ->get()
        ->toArray();

       $res2["data"] = $data;
       return json_encode($res2);
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
