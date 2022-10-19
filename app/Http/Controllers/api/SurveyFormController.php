<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShareHistory;
use Illuminate\Support\Carbon;
use App\Models\userFormLink;
use App\Models\User;

class SurveyFormController extends Controller
{

    // Validating the users is active or not
    public function isUserStatusActive(Request $req)
    {   
        $email = $req->user()->email;

        $user_id = User::where('email', $email)
        ->where('status', 1)
        ->pluck("id")
        ->toArray();

        return count($user_id) > 0 ? true : false;
    }

    public function getUserForms(userFormLink $userFormLink, User $user, Request $req)
    {

        $id = $req->user()->id;

         // Validating the user
         if(!$this->isUserStatusActive($req)){
            return response([
                "status" => false,
                "message" => "Your account may have been deactived by the admin",
                "logout" => true
            ], 401);
        }

        $data = $userFormLink
        ->select("user_form_links.created_at", "user_form_links.id as share_id", "user_form_links.updated_at", "areas.area_name", "cities.city_name", "survey_forms.form_name", "products.prod_name as form_prod_name", "companies.comp_name as form_comp_name", "user_form_links.id as view_report")
        ->where("user_ref", $id)
        ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
        ->leftJoin("cities", "cities.id", "=", "areas.city_ref")
        ->leftJoin("survey_forms", "survey_forms.id", "=", "user_form_links.survey_form_ref")
        ->leftJoin("products", "products.id", "=", "survey_forms.prod_ref")
        ->leftJoin("companies", "companies.id", "=", "products.comp_id")
        ->get()
        ->toArray();
        
        foreach($data as $key=>$form)
        {
            $data[$key]["share_link"] = url('')."/share/".$form["share_id"];
        }

        $res2 = [
            "status" => true,
            "data" => $data
        ];
        
       return json_encode($res2);
    }

    public function share_form(Request $req){

        $id = $req->user()->id;

         // Validating the user
         if(!$this->isUserStatusActive($req)){
            return response([
                "status" => false,
                "message" => "Your account may have been deactived by the admin",
                "logout" => true
            ], 401);
        }

        $consumersArr = $req->input("consumersArr") ?? [];
        if(count($consumersArr) === 0)  
            return response([
                "status" => false,
                "message" => "Please enter at least one consumer details"
            ], 400);
        $modifiedArr = [];
        $error_count = 0;
        foreach($consumersArr as $key => $value){
            $inner = [];
            $name = $value["name"] ?? "";
            $phone = $value["phone"] ?? "";
            if(trim($name) == "" || trim($phone) == ""){
                $error_count++;
            }
            $inner["name"] = $name;
            $inner["phone"] = $phone;
            $phoneNumbers[] = $phone;
            $inner["user_ref"] = $id;
            $inner["location"] = $value["location"] ?? "";
            $inner["created_at"] = Carbon::now();
            $inner["updated_at"] = Carbon::now();
            array_push($modifiedArr, $inner);
        }

        if($error_count > 0){
            return response([
                "status" => false,
                "message" => "Invalid consumer details"
            ], 400);
        }

        // send messages
        foreach ($modifiedArr as $user_details) {
            $curl = curl_init();
            $mobile = $user_details["phone"];
            $otp = $user_details["phone"];
            $message = "Dear ".$user_details['name'].",\nOTP to login to eRSPL is ".$otp.". Please do not share with anyone.";
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
        }
        // send messages      
        
        ShareHistory::insert($modifiedArr);
        
        return response([
            "status" => true,
            "message" => "Messages sent successfully."
        ], 200);

    }

}
