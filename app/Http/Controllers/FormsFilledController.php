<?php

namespace App\Http\Controllers;

use App\Models\FormsFilled;
use App\Models\SurveyForm;
use App\Models\Cities;
use App\Models\Areas;
use App\Models\User;
use App\Models\userFormLink;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;

class FormsFilledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("content/sidebar/forms_filled/forms_filled");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req, FormsFilled $formsFilled)
    {

        $req->validate([
            "data_filled" => "required",
            "user_form_link_ref" => "required|numeric"
        ]);

        $data_filled = $req->input("data_filled");
        $user_form_link_ref = $req->input("user_form_link_ref");

        $res = $formsFilled
        ->insert([
            'data_filled' => $data_filled,
            'user_form_link_ref' => $user_form_link_ref
        ]);

        if(!$res)
            throw ValidationException::withMessages([
                'error' => "Something went wrong."
            ]);
        
        return redirect("successpage");

    }

    // Logout
    public function logout(Request $req){
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->flash('error', "Your account may have been inactivated");
        return redirect("login");
    }

    public function success(Request $req){
        $req->session()->flash('success', 'Form submitted successfully');
        return view("public.fill_up_form.successpage");
    }

    // Validating the users is active or not
    public function isUserStatusActive(User $user)
    {   
        return Auth::user()->status;
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

     // Redirects user to the report view page
     public function viewReport(Request $req, FormsFilled $formsFilled, $share_id)
     {
        return view("content.sidebar.form.viewreport", [
            "share_id" => $share_id
        ]);
    }

    public function viewReportAdmin(Request $req, FormsFilled $formsFilled, $form_id)
    {
        $inactive = 0;
        $cities_res = Cities::orderBy("city_name", "asc")
        ->get()
        ->toArray();

       return view("content.sidebar.form.viewreportadmin", [
           "form_id" => $form_id,
           "cities" => $cities_res
       ]);

   }


    public function getReport(FormsFilled $formsFilled, userFormLink $userFormLink, Request $req, $share_id){     

        $user_id = Auth::user()->id;
        $reports = $userFormLink
        ->where([
            "user_form_links.id" => $share_id,
            "user_ref" => $user_id
            ])
        ->get()
        ->toArray();
        $form_belongs_to_user = count($reports);
        if(!$form_belongs_to_user){ 
            return response(['error' => true, 'messsage' => 'Something went wrong'], 404);
        }
        
        $reports = $formsFilled
            ->where("user_form_link_ref", $share_id)
            ->get()
            ->toArray();

        $res2["data"] = $reports;
        return json_encode($res2);
    }


    public function getReportAdmin(FormsFilled $formsFilled, userFormLink $userFormLink, Request $req, $form_id){

        $city_id = $req->input("city_id");
        $area_id = $req->input("area_id");
        $report_ids = $userFormLink
        ->select("areas.area_name", "user_form_links.*", "areas.city_ref", "cities.city_name")
        ->where("survey_form_ref", $form_id)
        ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
        ->leftJoin("cities", "cities.id", "=", "areas.city_ref");
        if($city_id) $report_ids = $report_ids->where("cities.id",  $city_id);
        if($area_id) $report_ids = $report_ids->where("areas.id",  $area_id);
        $report_ids = $report_ids->pluck("user_form_links.id")
        ->toArray();

        $reports = $formsFilled
        ->whereIn("user_form_link_ref", $report_ids)
        ->get()
        ->toArray();

        $res2["data"] = $reports;
        return json_encode($res2);

    }

    // public function exportToPdf(Request $req, $form_id, $city_id, $area_id, $user_id){

    //     $header_html = "";

    //     if($user_id){
    //         $array_of_filled_forms = FormsFilled::where("user_form_link_ref", $form_id)->select("user_form_link_ref")
    //         ->get()->toArray();
    //         if(count($array_of_filled_forms)) 
    //             $user_form_link_ref = $array_of_filled_forms[0]["user_form_link_ref"];
    //         $result = userFormLink::where("id", $user_form_link_ref)->get()->toArray();
    //         $form_id = count($result) ? $result[0]["survey_form_ref"] : 0;
    //     }

    //     $report_ids = userFormLink::select("areas.area_name", "user_form_links.*", "areas.city_ref", "cities.city_name")
    //     ->where("survey_form_ref", $form_id)
    //     ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
    //     ->leftJoin("cities", "cities.id", "=", "areas.city_ref");
    //     if($city_id) $report_ids = $report_ids->where("cities.id",  $city_id);
    //     if($area_id) $report_ids = $report_ids->where("areas.id",  $area_id);
    //     $report_ids = $report_ids->pluck("user_form_links.id")
    //     ->toArray();

    //     $form_details = SurveyForm::where("survey_forms.id", $form_id)
    //     ->select("survey_forms.*", "products.prod_name", "products.comp_id", 'companies.comp_name', "companies.comp_care_no", "companies.comp_addr")
    //     ->leftJoin("products", "products.id", "survey_forms.prod_ref")
    //     ->leftJoin("companies", "companies.id", "products.comp_id")
    //     ->get()->toArray();

    //     if(count($form_details)){
    //         $form_details = $form_details[0];
    //         $comp_name = ucfirst($form_details['comp_name']);
    //         $comp_addr = ucfirst($form_details['comp_addr']);
    //         $form_name = ucfirst($form_details['form_name']);
    //         $prod_name = ucfirst($form_details['prod_name']);
    //         $comp_care_no = $form_details['comp_care_no'];
    //         $header_html = "
    //             <h2 align='center' style='margin-bottom:5px !important;'>$comp_name</h2>
    //             <h4 align='center' style='margin-top:0px !important;'>$comp_addr</h4>
    //             <div style='text-align:right;margin:0px auto 20px auto;'>
    //                 <div align='right'>Customer care: $comp_care_no</div>
    //                 <div align='right'>Product name: $prod_name</div>
    //             </div>
    //             <div style='margin:0px auto 20px auto;font-size:18px;font-weight:bold;'>$form_name</div> 
    //             ";
    //     }

    //     if($user_id) $report_ids = [$user_id];

    //     $reports = FormsFilled::whereIn("user_form_link_ref", $report_ids)
    //     ->get()
    //     ->toArray();

    //     // Report
    //     $firstRecored = $reports[0] ?? [];
    //     $heading = "";
    //     $html = "";

    //     if (count($firstRecored) == 0) {
    //         $html = '<div class="report_item">Looks like no one has filled the form yet.</div>';
    //     }else{
    //         $count = count($reports);
    //         $userStr = $count > 1 ? "users" : "user";
    //         $html = "<div class='report_item'>
    //             {$count} {$userStr} have filled the form till now.</div><br>";
    //     }

    //     $heading = $html;
    //     $outerMost = [];

    //     if(count($firstRecored)){
    //         $first_record = json_decode($firstRecored["data_filled"], true);

    //         foreach ($first_record as $sqIndex => $singleQues) {
    //             $values = $singleQues["values"] ?? [];
    //             $optionsHtml = "";
    //             if (count($values)) {
    //                 $inner = [];
    //                 foreach ($values as $oIndex => $option) {
    //                     $sq = $sqIndex + 1;
    //                     $oi = $oIndex + 1;
    //                     $str = "ques{$sq}-op{$oi}";
    //                     $arr_to_push = [
    //                         "id" => $str,
    //                         "result" => 0,
    //                     ];
    //                     array_push($inner,  $arr_to_push);
    //                 }
    //                 $sq = $sqIndex + 1;
    //                 $str = "ques{$sq}";
    //                 $arr_to_push = [
    //                     "id" => $str,
    //                     "result" => $inner,
    //                 ];
    //                 array_push($outerMost, $arr_to_push);
    //             } else {
    //                 array_push($outerMost, []);
    //             };
    //         }

    //         $all_records = [];
    //         foreach($reports as $report){
    //             $all_records[] = $report['data_filled'];
    //         }

    //         $input_types_html_arr = [];
    //         $html_arr_to_push = [];

    //         foreach ($all_records as $key => $singleRecord) {
    //             $singleRecord = json_decode($singleRecord, true);
    //             $html_arr_to_push = [];
    //             foreach ($singleRecord as $sqIndex => $singleQuestion) {
    //                 if (array_key_exists("values", $singleQuestion) && count($singleQuestion["values"])) {
    //                     $timesToLoop = $singleQuestion["userData"] ?? [];
    //                     foreach($timesToLoop as $currAns){
    //                         if(array_key_exists("values", $singleQuestion)){
    //                         foreach($singleQuestion["values"] as $coIndex => $currOption){
    //                             if ($currAns == $currOption["value"]) {
    //                                 $outerMost[$sqIndex]["result"][$coIndex]["result"] =
    //                                     $outerMost[$sqIndex]["result"][$coIndex]["result"] + 1;
    //                             }
    //                         };
    //                     }
    //                     };
    //                 } else {
    //                     $html_ = "";
    //                     $single_html = $singleQuestion["userData"][0];
    //                     if(trim($single_html) != "") $html_arr_to_push[$sqIndex] = $single_html;
    //                 }
    //             };
    //             if(count($html_arr_to_push)) $input_types_html_arr[] = $html_arr_to_push;
    //         };

    //         $totalUsersFilledTheForm = count($all_records);

    //         $html = "";
    //         $i = 1;
    //         foreach ($first_record as $sqIndex => $singleQues) {
    //             $values = $singleQues["values"] ?? [];
    //             $optionsHtml = "";
    //             if (count($values)) {
    //                 $inner = [];
    //                 foreach ($values as $oIndex => $option) {
    //                     $sq = $sqIndex + 1;
    //                     $oi = $oIndex + 1;
    //                     $str = "ques{$sq}-op{$oi}";
    //                     $arr_to_push = [
    //                         "id" => $str,
    //                         "result" => 0,
    //                     ];
    //                     array_push($inner,  $arr_to_push);

    //                     $inner_id = $inner[$oIndex]["id"];
    //                     $label = $option["label"];
    //                     $progbar_id = $inner[$oIndex]["id"];
    //                     $totalFilled = $outerMost[$sqIndex]["result"][$oIndex]["result"]."/".$totalUsersFilledTheForm;
    //                     $optionsHtml = $optionsHtml."<div class='answer_cont col-12 col-md-3 mb-1 mb-md-0' id='".$inner_id."'><div class='ansVal'>{$label}:  $totalFilled</div><div class='ansResults'></div><div id='".$progbar_id."'></div></div>";
    //                 }
    //                 $sq = $sqIndex + 1;
    //                 $str = "ques{$sq}";
    //                 $arr_to_push = [
    //                     "id" => $str,
    //                     "result" => $inner,
    //                 ];
    //                 array_push($outerMost, $arr_to_push);
    //             } else {
    //                 array_push($outerMost, []);
    //             };

    //             $isInputTypeQues = count($values) == 0;
    //             $ques_index = $sqIndex + 1;
    //             $ques_index = "ques{$ques_index}";
    //             $report_item_id = $outerMost[$sqIndex]["id"] ?? $ques_index;
    //             $ques_label = "Q$i) ".ucfirst($singleQues["label"]);
    //             $i++;
    //             $input_type_ques_id = "inputTypeQues{$ques_index}";
    //             $ans_html = "";
    //             foreach ($input_types_html_arr as $qus_key => $value) {
    //                 if($isInputTypeQues){
    //                     if(array_key_exists($sqIndex, $value)){
    //                         $ans_html = $ans_html."<div>".ucfirst($value[$sqIndex])."</div>";
    //                     }
    //                 }
    //             }

    //             $html_to_insert = !$isInputTypeQues ? $optionsHtml : $ans_html;

    //             $html = $html."
    //                     <div class='report_item card p-2' id='{$report_item_id}'><div class='report_ques'>{$ques_label}</div><div class='report_ques_ans row mx-0'>".$html_to_insert."</div></div>"."<br>"; 
    //             }

    //             $html = "<div style='margin:30px auto;max-width:1200px;font-family:arial;'>".$header_html.$heading.$html."<div>";
    //         }

    //         // instantiate and use the dompdf class
    //         $dompdf = new Dompdf();
    //         $dompdf->loadHtml($html);
    //         $dompdf->render();
    //         ob_end_clean();
    //         $dompdf->stream();
    //         $dompdf->output();
    //         return redirect()->back();
    // }

    public function exportToPdf(Request $req, $form_id, $city_id, $area_id, $user_id){

        $header_html = "";

        if($user_id){
            $array_of_filled_forms = FormsFilled::where("user_form_link_ref", $form_id)->select("user_form_link_ref")
            ->get()->toArray();
            if(count($array_of_filled_forms)) 
                $user_form_link_ref = $array_of_filled_forms[0]["user_form_link_ref"];
            $result = userFormLink::where("id", $user_form_link_ref)->get()->toArray();
            $form_id = count($result) ? $result[0]["survey_form_ref"] : 0;
        }

        $report_ids = userFormLink::select("areas.area_name", "user_form_links.*", "areas.city_ref", "cities.city_name")
        ->where("survey_form_ref", $form_id)
        ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
        ->leftJoin("cities", "cities.id", "=", "areas.city_ref");
        if($city_id) $report_ids = $report_ids->where("cities.id",  $city_id);
        if($area_id) $report_ids = $report_ids->where("areas.id",  $area_id);
        $report_ids = $report_ids->pluck("user_form_links.id")
        ->toArray();

        $form_details = SurveyForm::where("survey_forms.id", $form_id)
        ->select("survey_forms.*", "products.prod_name", "products.comp_id", 'companies.comp_name', "companies.comp_care_no", "companies.comp_addr")
        ->leftJoin("products", "products.id", "survey_forms.prod_ref")
        ->leftJoin("companies", "companies.id", "products.comp_id")
        ->get()->toArray();

        if(count($form_details)){
            $form_details = $form_details[0];
            $comp_name = ucfirst($form_details['comp_name']);
            $comp_addr = ucfirst($form_details['comp_addr']);
            $form_name = ucfirst($form_details['form_name']);
            $prod_name = ucfirst($form_details['prod_name']);
            $comp_care_no = $form_details['comp_care_no'];
            $header_html = "Company\t$comp_name\nAddress\t$comp_addr\nCare no\t'".$comp_care_no."'\nProduct\t$prod_name\nForm\t$form_name\n\n\n";
        }

        if($user_id) $report_ids = [$user_id];

        $reports = FormsFilled::whereIn("user_form_link_ref", $report_ids)
        ->get()
        ->toArray();

        // Report
        $firstRecored = $reports[0] ?? [];
        $heading = "";
        $html = "";

        if (count($firstRecored) == 0) {
            $html = 'Looks like no one has filled the form yet.';
        }else{
            $count = count($reports);
            $userStr = $count > 1 ? "users" : "user";
            $html = "{$count} {$userStr} have filled the form till now.\n\n";
        }

        $heading = $html;
        $outerMost = [];

        if(count($firstRecored)){
            $first_record = json_decode($firstRecored["data_filled"], true);

            foreach ($first_record as $sqIndex => $singleQues) {
                $values = $singleQues["values"] ?? [];
                $optionsHtml = "";
                if (count($values)) {
                    $inner = [];
                    foreach ($values as $oIndex => $option) {
                        $sq = $sqIndex + 1;
                        $oi = $oIndex + 1;
                        $str = "ques{$sq}-op{$oi}";
                        $arr_to_push = [
                            "id" => $str,
                            "result" => 0,
                        ];
                        array_push($inner,  $arr_to_push);
                    }
                    $sq = $sqIndex + 1;
                    $str = "ques{$sq}";
                    $arr_to_push = [
                        "id" => $str,
                        "result" => $inner,
                    ];
                    array_push($outerMost, $arr_to_push);
                } else {
                    array_push($outerMost, []);
                };
            }

            $all_records = [];
            foreach($reports as $report){
                $all_records[] = $report['data_filled'];
            }

            $input_types_html_arr = [];
            $html_arr_to_push = [];

            foreach ($all_records as $key => $singleRecord) {
                $singleRecord = json_decode($singleRecord, true);
                $html_arr_to_push = [];
                foreach ($singleRecord as $sqIndex => $singleQuestion) {
                    if (array_key_exists("values", $singleQuestion) && count($singleQuestion["values"])) {
                        $timesToLoop = $singleQuestion["userData"] ?? [];
                        foreach($timesToLoop as $currAns){
                            if(array_key_exists("values", $singleQuestion)){
                            foreach($singleQuestion["values"] as $coIndex => $currOption){
                                if ($currAns == $currOption["value"]) {
                                    $outerMost[$sqIndex]["result"][$coIndex]["result"] =
                                        $outerMost[$sqIndex]["result"][$coIndex]["result"] + 1;
                                }
                            };
                        }
                        };
                    } else {
                        $html_ = "";
                        $single_html = $singleQuestion["userData"][0];
                        if(trim($single_html) != "") $html_arr_to_push[$sqIndex] = $single_html;
                    }
                };
                if(count($html_arr_to_push)) $input_types_html_arr[] = $html_arr_to_push;
            };

            $totalUsersFilledTheForm = count($all_records);

            $html = "";
            $i = 1;
            foreach ($first_record as $sqIndex => $singleQues) {
                $values = $singleQues["values"] ?? [];
                $optionsHtml = "";
                if (count($values)) {
                    $inner = [];
                    foreach ($values as $oIndex => $option) {
                        $sq = $sqIndex + 1;
                        $oi = $oIndex + 1;
                        $str = "ques{$sq}-op{$oi}";
                        $arr_to_push = [
                            "id" => $str,
                            "result" => 0,
                        ];
                        array_push($inner,  $arr_to_push);

                        $inner_id = $inner[$oIndex]["id"];
                        $label = $option["label"];
                        $progbar_id = $inner[$oIndex]["id"];
                        $totalFilled = $outerMost[$sqIndex]["result"][$oIndex]["result"]." out of ".$totalUsersFilledTheForm;
                        $optionsHtml = $optionsHtml."{$label}\t$totalFilled\n";
                    }
                    $sq = $sqIndex + 1;
                    $str = "ques{$sq}";
                    $arr_to_push = [
                        "id" => $str,
                        "result" => $inner,
                    ];
                    array_push($outerMost, $arr_to_push);
                } else {
                    array_push($outerMost, []);
                };

                $isInputTypeQues = count($values) == 0;
                $ques_index = $sqIndex + 1;
                $ques_index = "ques{$ques_index}";
                $report_item_id = $outerMost[$sqIndex]["id"] ?? $ques_index;
                $ques_label = "Q$i) ".ucfirst($singleQues["label"]);
                $i++;
                $input_type_ques_id = "inputTypeQues{$ques_index}";
                $ans_html = "";
                foreach ($input_types_html_arr as $qus_key => $value) {
                    if($isInputTypeQues){
                        if(array_key_exists($sqIndex, $value)){
                            $ans_html = $ans_html.ucfirst($value[$sqIndex])."\n";
                        }
                    }
                }

                $html_to_insert = !$isInputTypeQues ? $optionsHtml : $ans_html;

                $html = $html."{$ques_label}\n$html_to_insert"."\n"; 
                }

                $html = $header_html.$heading.$html."\n";
            }

            $fileName = "survey_report.xls";
            
            // Headers for download 
            header("Content-Type: application/vnd.ms-excel"); 
            header("Content-Disposition: attachment; filename=\"$fileName\""); 
            
            // Render excel data 
            echo $html; 
            exit;
    }

    public function allocationDetails(Request $req, $id){

        $final_data = [];
        $share_id_list = [];
        $forms_allocated = userFormLink::select( "user_form_links.id as action", "user_form_links.reason", "user_form_links.is_admin_completed","user_form_links.id as user_form_link_ref", "user_form_links.id as share_id", "user_form_links.sample_size", "user_form_links.id as responsive_id", "user_form_links.*", "users.name", "users.email", "user_company_links.comp_ref", "users.id as user_id", "companies.comp_name", "areas.area_name", "cities.city_name")
        ->leftJoin("areas", "areas.id", "=", "user_form_links.area_ref")
        ->leftJoin("cities", "cities.id", "=", "areas.city_ref")
        ->leftJoin("users", "users.id", "=", "user_form_links.user_ref")
        ->leftJoin("user_company_links", "user_company_links.user_ref", "=", "users.id")
        ->leftJoin("companies", "companies.id", "=", "user_company_links.comp_ref")
        ->where("user_form_links.id", $id)
        ->get()
        ->toArray();
        
        foreach ($forms_allocated as $curr_form) {
            $share_id_list[] = $curr_form["share_id"];
        }
        
        $forms_filled_arr = FormsFilled::whereIn("user_form_link_ref", $share_id_list)
        ->selectRaw("user_form_link_ref, COUNT(*) AS filled_forms_count")
        ->groupBy("user_form_link_ref")
        ->get()
        ->toArray();

        $filled_count_arr = [];
        foreach ($forms_filled_arr as $curr_form) {
            $filled_forms_count = $curr_form["filled_forms_count"];
            $user_form_link_ref = $curr_form["user_form_link_ref"];
            array_push($filled_count_arr, [$user_form_link_ref => $filled_forms_count]);
        }        

        
        foreach ($forms_allocated as $key => $curr_form) {
            $filled_form_count = $filled_count_arr[$key][''.$curr_form["user_form_link_ref"].''] ?? 0;
            $curr_form['filled_count'] = $filled_form_count;
            $curr_form['remaining_count'] = intval($curr_form["sample_size"]) - $filled_form_count;
            if($curr_form['is_admin_completed']){
                $curr_form['completed_by'] = $curr_form['remaining_count'];
                $curr_form['remaining_count'] = 0;
            }
            $curr_form['is_completed'] = ($curr_form['is_admin_completed'] || intval($curr_form["sample_size"]) == $filled_form_count) ? 1 : 0;
            $final_data[] = $curr_form;
        }

        return view("content.sidebar.form.allocationdetails", [
            "details" => $final_data
        ]);
    }

    public function completeSurvey(Request $req){

        $req->validate([
            "id" => "required",
            "complete" => "required",
        ]);

        $complete = $req->input("complete") ?? 1;

        if($complete  && ($req->input("reason") ?? "") == "")
            throw ValidationException::withMessages([
                'error' => "Reason is a required field."
            ]);

        $reason = $req->input("reason");
        $id = $req->input("id");

        $check = userFormLink::where("id", $id)->update([
            "is_admin_completed" => $complete,
            "reason" => $complete == 1 ? $reason : ""
        ]);

        if(!$check)
            throw ValidationException::withMessages([
                'error' => "Something went wrong."
            ]);
    
        return redirect()->back()->with('success', 'Updated form status successfully');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    public function edit(FormsFilled $formsFilled)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormsFilled $formsFilled)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormsFilled  $formsFilled
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormsFilled $formsFilled)
    {
        //
    }
}
