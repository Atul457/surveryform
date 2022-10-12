<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Otp;
use App\Models\Company;
use App\Models\Product;
use App\Models\AdminUsers;
use App\Models\UserCompany_link;
use App\Models\Cities;
use Illuminate\Validation\ValidationException;
use Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $test_numbers = ["8837684275"];

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

        $credentials = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {

            if(Auth::user()->status == 0)
                throw ValidationException::withMessages(['not_found' => "You account has been deactived by the admin"]);

            $req->session()->regenerate();

            if(Auth::user()->is_admin) return redirect('myforms');
            return redirect('userforms');
        }
        
        throw ValidationException::withMessages(['not_found' => "Invalid credentials"]);

    }

    // getUsers
    public function getUsers(Request $req, User $user, UserCompany_link $link)
    { 
        $data = $link
        ->select("user_company_links.user_ref", "user_company_links.comp_ref", "users.*", "companies.comp_name", "users.id as action", "users.id as action", "users.id as viewpermissions")
        ->leftJoin("users", "users.id", "=", "user_company_links.user_ref")
        ->leftJoin("companies", "companies.id", "=", "user_company_links.comp_ref")
        ->where("users.is_admin", 0)
        ->where("users.id", "!=", Auth::user()->id)
        ->get()
        ->toArray();

        $res2["data"] = $data;
        return json_encode($res2);
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

    // Updates the user
    public function updateUser(Request $req, User $user, AdminUsers $admin_users, UserCompany_link $link)
    {
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
            return redirect()->back()->with('other', "Employee password is given, and it's length should be minimum of 6.");
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

    // Forgot pass

    // Forgot password
    public function forgotpass(Request $req){

        $validated = $req->validate([
            'email' => 'required|email'
        ]);

        $min = 1000;
        $max = 9999;
        $active = 1;
        $mobile = "";

        $email = $req->input("email");    
        $otp = rand($min, $max);
        
        $user = User::select("*")
        ->where("email", $email)
        ->get()
        ->toArray();


        if(count($user)){
            $user =  $user[0];
           
            if($user["status"] != $active)
                throw ValidationException::withMessages(['not_found' => "You account has been deactived by the admin"]);

            $mobile = $user["phone_no"];
            // $mobile =urlencode("9779755869,8837684275");
            if(in_array($mobile, $this->test_numbers)) $otp = '1234';

            $data = [
                "user_ref" =>  $user["id"],
                "otp" => $otp
            ];
            $to_search = ["user_ref" => $user["id"]];
            
            $record = Otp::where($to_search);
            if ($record->exists()) $res = $record->update($data);
            else $res = Otp::insert($data);

            $curl = curl_init();
            $message = "Dear ".$user['name'].",\nOTP to login to eRSPL is ".$otp.". Please do not share with anyone.";
            $message = urlencode($message);
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zapsms.co.in/api/v2/SendSMS?SenderId=eRSPLe&Is_Unicode=false&Is_Flash=false&Message=$message&MobileNumbers=$mobile&ApiKey=lsdzpI6f%2BipF%2BwG1j4iwQ%2FjIQS9PS4VC0uftsQih4hY%3D&ClientId=c814d93d-a836-4f39-8b47-6798657c8072",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            return redirect("resetpass_page")->with('user_id', $user["id"]);
        
        }

        throw ValidationException::withMessages(['not_found' => "Account with the email id entered doesn't exist"]);

    }

    public function resetpass_page(){
        return view("content/auth/resetpass");
    }

    public function forgotpass_page(){
        return view("content/auth/forgotpass");
    }
    
    public function resetpass(Request $req){
        $validated = $req->validate([
            'otp' => 'required'
        ]);

        $otp = $req->input("otp");    
        $user_id = $req->input("user_id") ?? 0; 

        $to_search = [
            "otp" => $otp,
            "user_ref" => $user_id,
        ];

        $record = Otp::where($to_search);
        if (!$record->exists() ||  $user_id == 0) throw ValidationException::withMessages(['error' => "Invalid otp."]);
        $user = User::where('id', $user_id)->first();
        Auth::login($user);
        return redirect("login");
        
    }
    
}
