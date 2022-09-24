<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $req, User $user){

        $messages = [
            'required' => ':attribute is a required field',
            'email' => ':attribute not valid',
            'min:6' => ':attribute must be of at least 6 characters'
        ];

        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], $messages);

        $email = $req->input("email");
        $password = $req->input("password");

        if ($validator->fails()) {

            $errors = $validator->errors();
            $error;

            if($errors->has("email"))
                $error = $errors->first('email');
            else if($errors->has("password"))
                $error = $errors->first('password');

            return response([
                "status" => false,
                "message" => $error,
            ], 401);
        }

        $result = $user
        ->select("*")
        ->where("email", $email)
        ->first();

        if(!$result) 
            return response([
                "status" => false,
                "message" => "Employee with this id doesn't exist"
            ], 401);

        if(!Hash::check($password, $result->password))
            return response([
                "status" => false,
                "message" => "Invalid credentials"
            ], 401);

        if($result->status == 0)
            return response([
                "status" => false,
                "message" => "Your account has been deactived by the admin"
            ], 401);

        $token = $result->createToken('survey_token')->plainTextToken;

        $response = [
            'id' => $result->id,
            'name' => $result->name,
            'email' => $email,
            'token' => $token
        ];

        return response([
            "status" => true,
            "data" => $response
        ], 200);

    }

    public function logout(Request $req, User $user){
        $res = $req->user()->currentAccessToken()->delete();
        if($res)
            return response([
                "status" => true,
                "message" => "Logged out successfully"
            ], 200);

        return response([
            "status" => false,
            "message" => "Something went wrong"
        ], 500);
    }

    public function getUser(Request $req){

    }
}
