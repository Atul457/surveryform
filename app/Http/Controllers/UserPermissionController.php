<?php

namespace App\Http\Controllers;

use App\Models\UserPermission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $permissions_assigned = UserPermission::where("user_id", $user_id)
        ->get()->toArray();

        $all_permissions_modules =UserPermission::select("module_name")
        ->groupBy("module_name")
        ->get()
        ->toArray();

        $data["permissions_assigned"] = $permissions_assigned;
        $data["all_permissions_modules"] = $all_permissions_modules;
        $res2["data"] = $data;
        return json_encode($res2);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $user_id)
    {
        $updatedPermissionsArr = $req->input("updatedPermissionsArr") ?? [];
        $deleted = UserPermission::where("user_id", $user_id)->delete();
        $res = UserPermission::where("user_id", $user_id)->get()->toArray();
        foreach($updatedPermissionsArr as $module_name => $new_permissions){
            $module_name = $new_permissions["moduleName"];
            $permissionGranted = $new_permissions["permissionGrantedArrElem"] ?? [];
            $permissionGrantedStr = count($new_permissions) ? '["'.implode('", "', $permissionGranted).'"]' : "[]";
            $user_permission = new UserPermission;
            $user_permission->module_name = $module_name;
            $user_permission->permissions = $permissionGrantedStr;
            $user_permission->user_id = $user_id;
            $user_permission->save();
        }

        return response([
            "status" => true,
            "message" => "Permissions updated successfully."
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
