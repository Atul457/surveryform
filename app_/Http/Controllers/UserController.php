<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use App\Models\AdminUsers;
use App\Models\UserCompany_link;
use App\Models\Cities;
use Illuminate\Validation\ValidationException;
use Response;

class UserController extends Controller
{
    public function new_line(){
        echo "</br>";
    }

     // Logout
     public function logoutUser(Request $req, $message){
        $req->session()->flush();
        session([
            'message' => $message,
            'inactivated' => true
        ]);
    }

    // Takes the user to login page
    public function login_view(){
        return view("content/auth/login");
    }

    // Takes the user to create user view
    public function create_user_view(Product $prod, User $user, Request $req, Company $comp){

        if(!$this->isAdmin($user, $req))
            throw ValidationException::withMessages(['error' => "You are not an admin."]);

        $inactive = 0;
        $product = $prod->select("*")
        ->where("status", "!=" , $inactive)
        ->get()
        ->toArray();

        $companies = $comp
        ->select("*")
        ->where("status", "!=", $inactive)
        ->get()
        ->toArray();

        return view("content/sidebar/admin/users/createuser", [
            'prod' => $product,
            'comp' => $companies
        ]);
    }
    
    // Takes the user to users grid
    public function users_view(){
        return view("content/sidebar/admin/users/users");
    }

    // Logs the user in
    public function login_user(Request $req, User $user){
        $validated = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $email = $req->input("email");
        $password = $req->input("password");

        $result = $user
        ->select("*")
        ->where("email", $email)
        ->get()
        ->toArray();

        if(count($result) === 0)
            throw ValidationException::withMessages(['not_found' => "Invalid credentials"]);

        if($result[0]["status"] == 0)
            throw ValidationException::withMessages(['not_found' => "You account has been deactived by the admin"]);

        if(!count($result)) 
            throw ValidationException::withMessages(['not_found' => "Employee with this id doesn't exist"]);

        if(!Hash::check($password, $result[0]["password"]))
            throw ValidationException::withMessages(['unauthorized' => "Invalid credentials"]);

        session([
            'email' => $email,
            'name' => $result[0]["name"],
            'id' => $result[0]["id"],
            "is_admin" => $result[0]["is_admin"]
        ]);

        
        return redirect('myforms');
    }

    // getUsers
    public function getUsers(Request $req, User $user, UserCompany_link $link)
    {       

        if(!$this->isAdmin($user, $req)){
            $this->logoutUser($req, "You are not an admin");
            $res = ["data" => []];
            return json_encode($res);
       }

        $data = $link
        ->select("user_company_links.user_ref", "user_company_links.comp_ref", "users.*", "companies.comp_name", "users.id as action", "users.id as action")
        ->leftJoin("users", "users.id", "=", "user_company_links.user_ref")
        ->leftJoin("companies", "companies.id", "=", "user_company_links.comp_ref")
        ->where("users.is_admin", 0)
        ->get()
        ->toArray();

        $res2["data"] = $data;

        // echo "<pre>";
        return json_encode($res2);
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

    // Creates the user
    public function createuser(User $user, Request $req, UserCompany_link $links){
        $validated = $req->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone_no' => 'required',
            'emp_code'=> 'required|unique:users',
            'comp_ref'=> 'required'
        ]);

        $name = $req->input("name");
        $email = $req->input("email");
        $status = $req->input("status");
        $password = $req->input("password");
        $emp_code = $req->input("emp_code");
        $phone_no = $req->input("phone_no");
        $comp_ref = $req->input("comp_ref");

        if(!$this->isAdmin($user, $req))
            throw ValidationException::withMessages(['error' => "You are not an admin."]);
        
        $id = User::insertGetId([
            "name"=>$name,
            "email"=>$email,
            "status"=>$status,
            "password"=>Hash::make($password),
            "emp_code"=>$emp_code,
            "phone_no"=>$phone_no
        ]);
        
        if(!$id) throw ValidationException::withMessages(['error' => "Something went wrong"]);

        $res = $links
        ->insert([
            'user_ref' => $id,
            'comp_ref' => $comp_ref
        ]);

        if(!$res) throw ValidationException::withMessages(['error' => "Something went wrong"]);
            
        $req->session()->flash('success', 'Employee created successfully');
        return redirect("users");
    }

    // Logout the user
    public function logout(Request $req){
        $req->session()->flush();
        $req->session()->flash('success', 'Logged out successfully');
        return redirect("login");
    }

    // Update password
    function updatepass(Request $req){
        $password = $req->input('password');

        if($password === "" || strlen($password) < 6)
            return response(("Password's length should be 6 mininum"), 401);
        
        $res = DB::update(
            'update users set password = ? where email = ?',
            [Hash::make($password), session("email")]
        );

        if($res === 0)
            return response(("something went wrong"), 500);
            
        return response(("Password updated successfully"), 200);
    }

    // Edit user
    public function edit(User $user, Request $req, Company $comp, Product $prod, UserCompany_link $link, $id)
    {
        if(!$this->isAdmin($user, $req)) return $this->logout($req);

        $single_user = $user->select("*")->where("id", $id)
        ->where("id", "=",  $id)
        ->get()
        ->toArray();

        $inactive = 0;
        $product = $prod->select("*")
        ->where("status", "!=" , $inactive)
        ->get()
        ->toArray();

        $companies = $comp
        ->select("*")
        ->where("status", "!=", $inactive)
        ->get()
        ->toArray();

        $getSelectedComp = UserCompany_link::where("user_ref", $id)
        ->first()
        ->toArray();

        if(count($getSelectedComp) == 0){
            $req->session()->flash('error', 'Something went wrong');
             return view("content.sidebar.admin.users.updateuser");
        };

        if(count($single_user) == 0) {
            $req->session()->flash('error', "Employee with this id doesn't exist.");
            return view("content.sidebar.admin.users.updateuser");
        }

        return view("content.sidebar.admin.users.updateuser",
        [
            'prod' => $product,
            'user' => $single_user[0],
            'comp' => $companies,
            'comp_id' => $getSelectedComp["comp_ref"]
        ]);
    }

    // Display cities and areas view
    public function citiesNAreas(User $user, Request $req, Cities $cities){
        if(!$this->isAdmin($user, $req)) return $this->logout($req);
        $cities_res = $cities
        ->orderBy("city_name", "asc")
        ->get()
        ->toArray();
        return view("content.sidebar.admin.cities_n_areas.citiesNAreas", 
        ["cities" => $cities_res ]);
    }

    // Updates the user
    public function updateUser(Request $req, User $user, AdminUsers $admin_users, UserCompany_link $link)
    {
        if(!$this->isAdmin($user, $req))
            throw ValidationException::withMessages(['error' => "You are not an admin."]);

        $validated = $req->validate([
            'name' => 'required|min:2',
            'emp_code' => 'required',
            'email' => 'required|email',
            'comp_ref'=> 'required',
            'phone_no' => 'required|digits:10'
        ]);

        
        $name = $req->input("name");
        $id = $req->input("users_id");
        $email = $req->input("email");
        $status = $req->input("status");
        $password = $req->input("password");
        $emp_code = $req->input("emp_code");
        $phone_no = $req->input("phone_no");
        $comp_ref = $req->input("comp_ref");

        $data_to_update = [
            'name' => $name,
            'status' => $status,
            'email' => $email,
            'emp_code' => $emp_code,
            'phone_no' => $phone_no
        ];


        if(strlen($password) < 6 && strlen($password) > 0){
            return redirect()->back()->with('other', "User password is given, and it's length should be minimum of 6.");
        }else if(strlen($password) >= 6){
            $password =  Hash::make($password);
            $data_to_update['password'] = $password;
        }

        $single_user = $user->select("*")->where("id", $id)
        ->get()
        ->toArray();

        $isDuplicateEmail = $user
        ->select("*")
        ->where("email", "=", $email)
        ->where("id", "!=", $id)
        ->get()
        ->toArray();

        
        if(count($isDuplicateEmail) != 0) {
            return redirect()->back()->with('other', "A employee with email id provided already exists.");
        }
        
        if(count($single_user) === 0) {
            return redirect()->back()->with('other', "Employee with this id doesn't exist.");   
        }
        
        $updated = $user->where("id", $id)->update($data_to_update);

        if(!$updated)
            throw ValidationException::withMessages([
                'error' => "Something went wrong."
            ]);
        
        $res = $link
        ->where("user_ref", $id)
        ->update([
            "comp_ref" => $comp_ref
        ]);

        if(!$updated)
            throw ValidationException::withMessages([
                'error' => "Something went wrong."
            ]);

        return redirect('users')->with('success', 'Employee updated successfully');
        
    }

    public function destroy(Request $req, User $user, AdminUsers $admin_users){
        if(!$this->isAdmin($user, $req))
            throw ValidationException::withMessages(['error' => "You are not an admin."]);
        $id = $req->input('user_id');

        $single_user = $user->select("*")->where("id", $id)
        ->get()
        ->toArray();

        if(count($single_user) === 0) {
            throw ValidationException::withMessages(['error' => "Employee with this id doesn't exist."]);   
        }

        $deleted = $user->where('id', $id)->delete();
        if($deleted)
            return redirect()->back()->with('success', 'Employee deleted successfully');
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }

    
}
