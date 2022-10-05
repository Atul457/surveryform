<?php

namespace App\Http\Controllers;

use App\Models\AdminUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    // Takes the user to login page
    public function login_view(){
        return view("content/admin/auth/login");
    }

    // Logs the user in
    public function login_user(Request $req, AdminUsers $adminUsers){
        $validated = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $email = $req->input("email");
        $password = $req->input("password");

        $result = $adminUsers
        ->select("*")
        ->where("email", $email)
        ->get()
        ->toArray();

        if(count($result) == 0)
            throw ValidationException::withMessages(['not_found' => "Invalid credentials"]);

        if(!count($result)) 
            throw ValidationException::withMessages(['not_found' => "Admin with this id doesn't exist"]);

        if(!Hash::check($password, $result[0]["password"]))
            throw ValidationException::withMessages(['unauthorized' => "Invalid credentials"]);

        session([
            'email' => $email,
            'name' => $result[0]["name"],
            'id' => $result[0]["id"],
            'is_admin' => 1
        ]);

        return redirect('admin/mycompanies');
    }

     // Update password
     function updatepass(Request $req){

        $password = $req->input('password');

        if($password === "" || strlen($password) < 6)
            return response(("Password's length should be 6 mininum"), 401);
        
        $res = DB::update(
            'update admin_users set password = ? where email = ?',
            [Hash::make($password), session("email")]
        );

        if($res === 0)
            return response(("something went wrong4"), 500);
            
        return response(("Password updated successfully"), 200);
    }    

    // Logout the user
    public function logout(Request $req){
        $req->session()->flush();
        $req->session()->flash('success', 'Logged out successfully');
        return redirect("admin/login");
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminUsers  $adminUsers
     * @return \Illuminate\Http\Response
     */
    public function show(AdminUsers $adminUsers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminUsers  $adminUsers
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminUsers $adminUsers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminUsers  $adminUsers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminUsers $adminUsers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminUsers  $adminUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminUsers $adminUsers)
    {
        //
    }
}
