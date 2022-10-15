<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use App\Models\FormsFilled;
use App\Models\userFormLink;
use App\Models\AdminUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("content/sidebar/product/products");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req, Product $product, Company $comp, User  $user)
    {
        $validated = $req->validate([
            'prod_name' => 'required|min:2',
            'comp_id' => 'required',
            'city' => 'required',
            'batch_no' => 'required',
            'sample_size' => 'required|numeric|min:0|not_in:0'
        ]);

        $comp_id = $req->input("comp_id");
        $prod_name = $req->input("prod_name");
        $status = $req->input("status");
        $batch_no = $req->input("batch_no");
        $city = $req->input("city");
        $sample_size = $req->input("sample_size");

        $comp_exists = $comp->where("id", $comp_id)->get()->toArray();

        if(count($comp_exists) === 0)
            throw ValidationException::withMessages(['error' => "Company selected doesn't exist."]);

        $create_prod_res = $product->insert([
            "prod_name" => $prod_name,
            "comp_id" => $comp_id,
            "batch_no" => $batch_no,
            "city" => $city,
            "status" => $status,
            "sample_size" => $sample_size
        ]);

        if(!$create_prod_res)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        return redirect("myproducts")->with(["success" => 'Product created successfully']);
    }

    public function editProductView(Company $company, $prod_id){
        $data = $company
        ->select("comp_name", "products.id as responsive_id", "products.batch_no",  "products.sample_size", "products.city", "companies.id as comp_id", "products.prod_name", "products.id as prod_id", "products.created_at", "products.updated_at", "products.status")
        ->where("products.id", $prod_id)
        ->Rightjoin("products", "products.comp_id", "=", "companies.id")
        ->get()
        ->toArray();

        $inactive = 0;
        $companies = $company->select("*")
        ->where("status", "!=" , $inactive)
        ->get()
        ->toArray();

        if(count($data) == 0 )
            throw ValidationException::withMessages(['error' => "The product for you trying to edit doesn't exist"]);

        return view('content.sidebar.product.editproduct', ["comp" => $companies, "product" => $data[0]]);

    }

    public function updateProduct(Request $req){

        $share_id_list = [];
        $final_data = [];
        $validated = $req->validate([
            'prod_name' => 'required|min:2',
            'prod_id' => 'required',
            'comp_id' => 'required',
            'city' => 'required',
            'batch_no' => 'required',
            'sample_size' => 'required|numeric|min:0|not_in:0'
        ]);

        $comp_id = $req->input("comp_id");
        $prod_name = $req->input("prod_name");
        $status = $req->input("status");
        $batch_no = $req->input("batch_no");
        $city = $req->input("city");
        $sample_size = $req->input("sample_size");
        $prod_id = $req->input("prod_id");

        $comp_exists = Company::where("id", $comp_id)->get()->toArray();

        if(count($comp_exists) === 0)
            throw ValidationException::withMessages(['error' => "Company selected doesn't exist."]);

        $dataToUpdate = [
            "prod_name" => $prod_name,
            "comp_id" => $comp_id,
            "batch_no" => $batch_no,
            "city" => $city,
            "status" => $status,
            "sample_size" => $sample_size
        ];

        $data = userFormLink::select("user_form_links.sample_size", "user_form_links.survey_form_ref", "user_form_links.sample_size")
        ->selectRaw("user_form_links.sample_size, SUM(user_form_links.sample_size) AS total_assigned")
        ->groupBy("user_form_links.survey_form_ref")
        ->join("survey_forms", "survey_forms.id", "user_form_links.survey_form_ref")
        ->join("products", "products.id", "survey_forms.prod_ref")
        ->where("products.id", $prod_id)
        ->get()
        ->toArray();

        if(count($data) > 0){
            if($sample_size < $data[0]["total_assigned"]){
                throw ValidationException::withMessages(['error' => "The product size can be minimum ".$data[0]["total_assigned"].", since ".$data[0]["total_assigned"]." has already been assigned to the user, you may deallocate the form to decrease the product size"]);
            }
        }
        
        $check = Product::where("id", $prod_id)
        ->update($dataToUpdate);

        if(!$check)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        return redirect("myproducts")->with(["success" => 'Product updated successfully']);
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

    // Logout
    public function logout(Request $req){
        $req->session()->flush();
        session([
            'inactivated' => true
        ]);
    }

    public function unAuthenticatedUser(Request $req, $message = 0){

        Auth::logout();
        $req->session()->invalidate();        
        if($message != 0)
            return session([
                'inactivated' => true,
                'message' => $message
            ]);
        
        return session([
            'inactivated' => true
        ]);
        
    }

    public function createProductView(Company $comp, User $user, Request $req)
    {
        $inactive = 0;
        $companies = $comp->select("*")
        ->where("status", "!=" , $inactive)
        ->get()
        ->toArray();

        return view('content.sidebar.product.createproduct')
        ->with('comp',  $companies);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Company $company, Request $req, User $user)
    {
        $data = $company
        ->select("comp_name", "products.id as responsive_id", "products.batch_no",  "products.sample_size", "products.city", "companies.id as comp_id", "products.prod_name", "products.id as prod_id", "products.created_at", "products.updated_at", "products.status")
        ->Rightjoin("products", "products.comp_id", "=", "companies.id")
        ->get()
        ->toArray();

        $res2["data"] = $data;
        return json_encode($res2);
    }

    // Get products against a company
    public function getProdOfComp(Product $product, Request $req, User $user, $id)
    {
        $inactive = 0;
        $products = Product::where('comp_id', $id)
        ->where("status", "!=", $inactive)
        ->get()
        ->toArray();

        $res2["data"] = $products;
        return json_encode($res2);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, User $user, Request $req)
    {
        $id = $req->input('product_id');

        $deleted = $product->where('id', $id)->delete();
        if($deleted)
            return redirect()->back()->with('success', 'Product deleted successfully');
    
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }
}
