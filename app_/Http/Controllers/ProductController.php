<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use App\Models\AdminUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        
        if(!$this->isAdmin($user)) $this->logout($req);

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

    public function isAdmin(User $user){
        $res = $user
        ->where("email", session("email"))
        ->where("is_admin", 1)
        ->get()
        ->toArray();

        return count($res) > 0 ? 1 : 0;
    }

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

    public function createProductView(Company $comp, User $user, Request $req){
        
        if(!$this->isAdmin($user)){
            $this->unAuthenticatedUser($req, "You are not an admin");
            throw ValidationException::withMessages(['error' => "You are not an admin"]);
        }

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
        if(!$this->isAdmin($user)){
            $this->unAuthenticatedUser($req, "You are not a admin");
            $res = ["data" => []];
             return json_encode($res);
        }

        $data = $company
        ->select("comp_name", "products.id as responsive_id", "products.batch_no", "products.city", "companies.id as comp_id", "products.prod_name", "products.id as prod_id", "products.created_at", "products.updated_at", "products.status")
        ->Rightjoin("products", "products.comp_id", "=", "companies.id")
        ->get()
        ->toArray();

        $res2["data"] = $data;
        return json_encode($res2);
    }

    // Get products against a company
    public function getProdOfComp(Product $product, Request $req, User $user, $id)
    {
        if(!$this->isAdmin($user)){
            $this->unAuthenticatedUser($req, "You are not a admin");
            $res = ["data" => []];
             return json_encode($res);
        }

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

        if(!$this->isAdmin($user)) return $this->logout();

        $deleted = $product->where('id', $id)->delete();
        if($deleted)
            return redirect()->back()->with('success', 'Product deleted successfully');
    
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }
}
