<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\api\auth\UserController;
use \App\Http\Controllers\api\SurveyFormController;
use \App\Http\Controllers\api\FormsFilledController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function(){
    Route::get('myforms', [SurveyFormController::class, 'getUserForms']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('getreport/{share_id}', [FormsFilledController::class, 'getReport']);
});


Route::post('login', [UserController::class, 'login']);