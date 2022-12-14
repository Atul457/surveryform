<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\AdminUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("content/sidebar/company/mycompanies");
    }

    // Logout
    public function logout(Request $req){
        Auth::logout();
        $req->session()->invalidate();
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

    public function createCompanyView(){
        return view('content.sidebar.company.createcompany');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req, User $user)
    {
        $validated = $req->validate([
            'comp_name' => 'required|min:2',
            'comp_addr' => 'required',
        ]);

        $company_name = $req->input("comp_name");
        $company_addr = $req->input("comp_addr");
        $company_care_no = $req->input("comp_care_no");
        $status = $req->input("status");
        $email = Auth::user()->email;
        
        $res = DB::insert(
            "insert into companies (comp_name, status, comp_addr, comp_care_no) values (?, ?, ?, ?)",
            [$company_name,  $status, $company_addr, $company_care_no]
        );

        if(!$res)
            throw ValidationException::withMessages(['error' => "Something went wrong"]);
        
        $req->session()->flash('success', 'Company created successfully');
        return redirect("mycompanies");
        
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function getCompanies(Request $req, Company $company, User $user)
    {
        $data = $company
            ->select('*', 'id as responsive_id', 'id as action')
            ->get()
            ->toArray();

        $res2["data"] = $data;
        return json_encode($res2);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Request $req, User $user, $id)
    {
        $comp = $company->select("*")->where("id", $id)->get()->toArray();
        if(count($comp) == 0) 
            throw ValidationException::withMessages([
                'error' => "You may have deleted the company, that you are trying to edit."
            ]);

        return view("content.sidebar.company.updatecompany")->with('comp', $comp[0]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Company $company, User $user)
    {
        $id = $req->input('company_id');
        $comp_name = $req->input('comp_name');
        $company_addr = $req->input("comp_addr");
        $company_care_no = $req->input("comp_care_no");
        $status = $req->input('status');
        
        $updated = $company->where("id", $id)->update([
            'comp_name' => $comp_name,
            'status' => $status,
            'comp_addr' => $company_addr,
            'comp_care_no' => $company_care_no
        ]);

        if($updated)
            return redirect('mycompanies')->with('success', 'Company updated successfully');
        
        throw ValidationException::withMessages([
            'error' => "Something went wrong."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, Request $req, User $user)
    {
        $id = $req->input('company_id');
        $deleted = $company->where('id', $id)->delete();
        if($deleted)
            return redirect()->back()->with('success', 'Company deleted successfully');
        
            throw ValidationException::withMessages([
                'error' => "Something went wrong."
            ]);
    }
}
