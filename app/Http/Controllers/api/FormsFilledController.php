<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormsFilled;
use App\Models\userFormLink;
use App\Models\User;

class FormsFilledController extends Controller
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

    public function getReport(FormsFilled $formsFilled, userFormLink $userFormLink, Request $req, User $user, $share_id){    
        
        $id = $req->user()->id;

        // Validating the user
        if(!$this->isUserStatusActive($user, $req)){
           return response([
               "status" => false,
               "message" => "Your account may have been deactived by the admin",
               "logout" => true
           ], 401);
       }

        $reports = $userFormLink
        ->where([
            "user_form_links.id" => $share_id,
            "user_ref" => $id
            ])
        ->get()
        ->toArray();

        $form_belongs_to_user = count($reports);
        if(!$form_belongs_to_user)
            return response([
                "status" => false,
                "message" => "The form may not belongs to you, whose report you are trying to view",
            ], 401);
        
        $reports = $formsFilled
            ->where("user_form_link_ref", $share_id)
            ->get()
            ->toArray();

        $res2 = [
            "status" => true,
            "data" => $reports
        ];
        
        return json_encode($res2);
    }

}
