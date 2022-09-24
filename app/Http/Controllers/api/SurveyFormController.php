<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\userFormLink;
use App\Models\User;

class SurveyFormController extends Controller
{

    // Validating the users is active or not
    public function isUserStatusActive(User $user, Request $req)
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
         if(!$this->isUserStatusActive($user, $req)){
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

        $res2 = [
            "status" => true,
            "data" => $data
        ];
        
       return json_encode($res2);
    }
}
