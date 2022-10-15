<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\AreasController;
use \App\Http\Middleware\ProtectedRoute;
// use \App\Http\Middleware\IsAdmin;
use \App\Http\Controllers\CompanyController;
use \App\Http\Controllers\SurveyFormController;
use \App\Http\Controllers\CitiesController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\FormsFilledController;
use \App\Models\User;

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
// Route::get('lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/', function () {
    return redirect('login');
});


// My routes

// Users and admin Auth
Route::middleware("guest:web")->group(function () {
    Route::get('login', [UserController::class, 'login_view'])->name("login");
    Route::post('login_user', [UserController::class, 'login_user']);
    Route::get('forgotpass_page', [UserController::class, 'forgotpass_page'])->name("forgotpass_page");
    Route::post('forgotpass', [UserController::class, 'forgotpass'])->name("forgotpass");
    Route::get('resetpass_page', [UserController::class, 'resetpass_page'])->name("resetpass_page");
    Route::post('resetpass', [UserController::class, 'resetpass'])->name("resetpass");
});

// Auth routes
Route::middleware("auth:web")->group(function () {
    
    // Permission required routes
    Route::middleware("user_permissions")->group(function(){

        // User
        Route::controller(UserController::class)->group(function(){
            Route::get('createuserview', 'create_user_view')->name('createuserview')->module("users", "create");
            Route::get('users', 'users_view')->name('users')->module("users", "view");
            Route::post('createuser', 'createuser')->module("users", "create");
            Route::post("updateuser", 'updateUser')->name('updateuser')->module("users", "update");
            Route::get("edituser/{id}", 'edit')->name('users_view')->module("users", "edit");   
            Route::post("deleteuser", 'destroy')->name('deleteuser')->module("users", "delete");
            Route::get('getUsers', 'getUsers')->name('getUsers')->module("users", "view");
        });

         // Form
         Route::controller(SurveyFormController::class)->group(function(){
            Route::get('myforms', 'index')->name('myforms')->module("forms", "view");
            Route::get('getMyforms', 'myForms')->name("create_form")->module("forms", "view");
            Route::get('create_form_view', 'show')->name('create_form_view')->module("forms", "create");
            Route::post('createform', 'create')->name("create_form")->module("forms", "create");
            Route::post('deleteform', 'destroy')->name("delete_form")->module("forms", "delete");
            Route::get('editform/{id}', 'edit')->name('edit_form')->module("forms", "edit");
            Route::post("updateform", 'update')->name("update_form")->module("forms", "update");
            Route::get("allocateformview", 'allocateFormView')->name("allocateformview")->module("forms", "create");
            Route::get('getusersofcomp/{id}', 'getUsersOfComp')->module("forms", "view");
            Route::get('getformsofprod/{prod_id}', 'getFormsOfProduct')->module("forms", "view");
            Route::post('allocateform', 'allocateForm')->name("allocate_form")->module("forms", "create");
            Route::get('formsallocated/{form_id}', 'formsAllocated')->name("get_forms_allocated")->module("forms", "view");
            Route::get('formsallocatedview/{form_id}', 'formsAllocatedView')->name("forms_allocated_view")->module("forms", "view");
            Route::post('deallocateform', 'deallocateForm')->name("deallocate_form")->module("forms", "delete");
            Route::get('duplicateform/{form_id}', 'duplicateForm')->name("duplicate_form")->module("forms", "create");
        });


        // Company
        Route::controller(CompanyController::class)->group(function(){
            Route::get('mycompanies', 'index')->name('mycompanies')->module("company", "view");
            Route::get('getcompanies', 'getCompanies')->module("company", "view");
           Route::get("create_comp_view", 'createCompanyView')->name('create_comp_view')->module("company", "create");
           Route::post("createcompany", 'create')->module("company", "create");
           Route::get("editcompany/{id}", 'edit')->name('edit_company')->module("company", "edit");
           Route::post("deletecompany", 'destroy')->name('deletecompany')->module("company", "delete");
           Route::post("updatecompany", 'update')->name("update_company")->module("company", "update");
        });

        // Product
        Route::controller(ProductController::class)->group(function(){
            Route::post('createproduct', 'create')->module("products", "create");
            Route::get('createprodview', 'createProductView')->name('createprodview')->module("products", "create");
            Route::get('getproducts', 'show')->module("products", "view");
            Route::post('deleteproduct', 'destroy')->name("delete_product")->module("products", "delete");
            Route::get('myproducts', 'index')->name("myproducts")->module("products", "view");
            Route::get('editprodview/{prod_id}', 'editProductView')->name('editprodview')->module("products", "edit");
            Route::post('update_prod', 'updateProduct')->name("update_prod")->module("products", "update");
        });  
        
        // Filled forms
        Route::controller(FormsFilledController::class)->group(function(){
            Route::post('getreportadmin/{form_id}', 'getReportAdmin')->name("get_report_admin")->module("forms_filled", "view");
            Route::get('view_report_admin/{share_id}', 'viewReportAdmin')->name("view_report_admin")->module("forms_filled", "view");
            Route::get('allocation_details/{id}', 'allocationDetails')->name("allocation_details")->module("forms_filled", "view");
            Route::post('complete_survey', 'completeSurvey')->name("complete_survey")->module("forms_filled", "create");
        }); 
        
        // Cities
        Route::controller(CitiesController::class)->group(function(){
            Route::get('cities', 'cities')->name("cities")->module("cities", "view");
            Route::get('getcities', 'getCities')->name("get_cities")->module("cities", "view");
            Route::get('addcityview', 'addCityView')->name("addcityview")->module("cities", "create");
            Route::post('addcity', 'addCity')->name("create_city")->module("cities", "create");
            Route::post('updatecity', 'updateCity')->name("update_city")->module("cities", "update");
            Route::get('editcity/{cityid}', 'editCity')->name("edit_city")->module("cities", "edit");
            Route::post('deletecity', 'deleteCity')->name("delete_city")->module("delete_city", "delete");
        });
        
        // Areas
        Route::controller(AreasController::class)->group(function(){
            Route::post('addarea', 'addArea')->name("add_area")->module("areas", "create");
            Route::get('areas/{cityid}', 'areas')->name("areas")->module("areas", "view");
            Route::get('addareaview', 'addAreaView')->name("addareaview")->module("areas", "create");
            Route::get('editarea/{area_id}', 'editArea')->name("edit_area")->module("areas", "edit");
            Route::post('updatearea', 'updateArea')->name("update_area")->module("areas", "update");
            Route::post('deletearea', 'deleteArea')->name("delete_area")->module("areas", "delete");
        });
        
        // Permissions
        Route::controller(UserPermissionController::class)->group(function(){
            Route::post('updatepermissions/{user_id}', 'update')->name("update_permissions")->module("permissions", "update");
            Route::get('getpermissions/{user_id}', 'show')->name("view_permissions")->module("permissions", "view");
        });
        
    });
    
    // Filled forms
    Route::get('getuserforms', [FormsFilledController::class, 'getUserForms']);
    Route::get('getreport/{share_id}', [FormsFilledController::class, 'getReport']);
    Route::get('viewreport/{share_id}', [FormsFilledController::class, 'viewReport'])->name('userforms');
    Route::get('forms_filled', [FormsFilledController::class, 'index'])->name('forms_filled');
    Route::post('saveform', [FormsFilledController::class, 'create']);
    Route::get('successpage', [FormsFilledController::class, 'success']);

    // UserRoutes
    Route::get("/",function(){ return redirect("mycompanies"); });
    Route::controller(UserController::class)->group(function(){
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::post('updatepass', [UserController::class, 'updatepass']);
    });

    // Forms
    Route::get('userforms', [SurveyFormController::class, 'userview'])->name('userforms');
    Route::get('getuserforms', [SurveyFormController::class, 'getUserForms']);      
    Route::post('share_form', [SurveyFormController::class, 'share_form']);      
    
    // Products
    Route::get('getprodofcomp/{id}', [ProductController::class, 'getProdOfComp'])->module("products", "view");
    Route::get('getareas/{cityid}', [AreasController::class, 'getAreas'])->module("create_area", "create");
    Route::get('export', [FormsFilledController::class, 'exportToPdf'])->name("export");

});


Route::get('share/{share_id}', [SurveyFormController::class, 'shareForm']);