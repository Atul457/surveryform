<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AreasController;
use \App\Http\Middleware\ProtectedRoute;
// use \App\Http\Middleware\AdminProtectedRoutes;
// use \App\Http\Middleware\AdminAuthProtectedRoutes;
use \App\Http\Middleware\AuthProtectedRoute;
use \App\Http\Middleware\AdminRoute;
use \App\Http\Controllers\CompanyController;
use \App\Http\Controllers\SurveyFormController;
use \App\Http\Controllers\CitiesController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\FormsFilledController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/', function () {
    return redirect('login');
});


// My routes

// Users and admin Auth
Route::withoutMiddleware([ProtectedRoute::class, AdminRoute::class])->group(function () {
    Route::get('login', [UserController::class, 'login_view']);
    Route::post('login_user', [UserController::class, 'login_user']);
});

// Admin Routes
Route::withoutMiddleware([AuthProtectedRoute::class])->group(function () {      
    
    // User
    Route::post('logout', [UserController::class, 'logout'])->name('logout')->withoutMiddleware([AdminRoute::class]);
    Route::post('updatepass', [UserController::class, 'updatepass']);
    
    // Form
    Route::get('myforms', [SurveyFormController::class, 'index'])->name('myforms');
    Route::get('getMyforms', [SurveyFormController::class, 'myForms']);
    Route::get('create_form_view', [SurveyFormController::class, 'show'])->name('create_form_view');
    Route::post('createform', [SurveyFormController::class, 'create']);
    Route::post('deleteform', [SurveyFormController::class, 'destroy']);
    Route::get('editform/{id}', [SurveyFormController::class, 'edit'])->name('myforms');
    Route::post("updateform", [SurveyFormController::class, 'update']);
    Route::get("allocateformview", [SurveyFormController::class, 'allocateFormView'])->name("allocateformview");
    Route::get('getusersofcomp/{id}', [SurveyFormController::class, 'getUsersOfComp']);
    Route::get('getformsofprod/{prod_id}', [SurveyFormController::class, 'getFormsOfProduct']);
    Route::post('allocateform', [SurveyFormController::class, 'allocateForm']);
    Route::get('formsallocated/{form_id}', [SurveyFormController::class, 'formsAllocated']);
    Route::get('formsallocatedview/{form_id}', [SurveyFormController::class, 'formsAllocatedView']);
    Route::post('deallocateform', [SurveyFormController::class, 'deallocateForm']);
    Route::get('duplicateform/{form_id}', [SurveyFormController::class, 'duplicateForm']);
    
    // Admin
    Route::get('createuserview', [UserController::class, 'create_user_view'])->name('create_user_view');
    Route::post('createuser', [UserController::class, 'createuser']);
    Route::get('getUsers', [UserController::class, 'getUsers'])->name('getUsers');
    Route::get('users', [UserController::class, 'users_view'])->name('users_view');
    Route::post("updateuser", [UserController::class, 'updateUser'])->name('updateuser');
    Route::get("edituser/{id}", [UserController::class, 'edit'])->name('users_view');
    Route::post("deleteuser", [UserController::class, 'destroy'])->name('deleteuser');
    
    // Company
    Route::get("/",function(){ return redirect("mycompanies"); });
    Route::get('mycompanies', [CompanyController::class, 'index'])->name('mycompanies');
    Route::get("viewcompany", [CompanyController::class, 'createCompanyView'])->name('create_company');
    Route::post("createcompany", [CompanyController::class, 'create']);
    Route::get("editcompany/{id}", [CompanyController::class, 'edit'])->name('mycompanies');
    Route::post("deletecompany", [CompanyController::class, 'destroy'])->name('deletecompany');
    Route::post("updatecompany", [CompanyController::class, 'update']);
    Route::get('getcompanies', [CompanyController::class, 'getCompanies']);
    
    // Product
    Route::post('createproduct', [ProductController::class, 'create']);
    Route::get('createprodview', [ProductController::class, 'createProductView'])->name('createprodview');
    Route::get('getproducts', [ProductController::class, 'show']);
    Route::post('deleteproduct', [ProductController::class, 'destroy']);
    Route::get('myproducts', [ProductController::class, 'index'])->name("myproducts");
    Route::get('getprodofcomp/{id}', [ProductController::class, 'getProdOfComp']);
    
    // Filled forms
    Route::get('getuserforms', [FormsFilledController::class, 'getUserForms']);
    Route::post('getreportadmin/{form_id}', [FormsFilledController::class, 'getReportAdmin']);
    Route::get('view_report_admin/{share_id}', [FormsFilledController::class, 'viewReportAdmin'])->name("myforms");
    
    // Cities
    Route::get('cities', [CitiesController::class, 'cities'])->name("cities");
    Route::get('getcities', [CitiesController::class, 'getCities']);
    Route::get('addcityview', [CitiesController::class, 'addCityView'])->name("addcityview");
    Route::post('addcity', [CitiesController::class, 'addCity']);
    Route::post('updatecity', [CitiesController::class, 'updateCity'])->name("cities");
    Route::get('editcity/{cityid}', [CitiesController::class, 'editCity'])->name("cities");
    Route::post('deletecity', [CitiesController::class, 'deleteCity']);
    
    // Areas
    Route::post('addarea', [AreasController::class, 'addArea']);
    Route::get('getareas/{cityid}', [AreasController::class, 'getAreas']);
    Route::get('areas/{cityid}', [AreasController::class, 'areas'])->name("cities");
    Route::get('addareaview', [AreasController::class, 'addAreaView'])->name("addareaview");
    Route::get('editarea/{area_id}', [AreasController::class, 'editArea'])->name("cities");
    Route::post('updatearea', [AreasController::class, 'updateArea'])->name("cities");
    Route::post('deletearea', [AreasController::class, 'deleteArea']);
    
});

// UserRoutes

Route::withoutMiddleware([AuthProtectedRoute::class, AdminRoute::class])->group(function () {      
    
    // Forms
    Route::get('userforms', [SurveyFormController::class, 'userview'])->name('userforms');
    Route::get('getuserforms', [SurveyFormController::class, 'getUserForms']);
    
    // Forms filled
    Route::get('getreport/{share_id}', [FormsFilledController::class, 'getReport']);
    Route::get('viewreport/{share_id}', [FormsFilledController::class, 'viewReport'])->name('userforms');
    Route::get('forms_filled', [FormsFilledController::class, 'index'])->name('forms_filled');
    Route::post('saveform', [FormsFilledController::class, 'create']);
    Route::get('successpage', [FormsFilledController::class, 'success']);

});


Route::get('share/{share_id}', [SurveyFormController::class, 'shareForm'])->withoutMiddleware([ProtectedRoute::class, AuthProtectedRoute::class, AdminAuthProtectedRoutes::class, AdminProtectedRoutes::class, AdminRoute::class]);