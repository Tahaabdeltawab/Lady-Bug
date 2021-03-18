<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// infyom generator builder
Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder')->name('io_generator_builder');

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate')->name('io_field_template');

Route::get('relation_field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@relationFieldTemplate')->name('io_relation_field_template');

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate')->name('io_generator_builder_generate');

Route::post('generator_builder/rollback', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback')->name('io_generator_builder_rollback');

Route::post('generator_builder/generate-from-file', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generateFromFile')->name('io_generator_builder_generate_from_file');


/*
|--------------------------------------------------------------------------
| LARAVEL USER MANAGEMENT ROUTE
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'namespace'     => 'App\Http\Controllers\UserManagement',
    'prefix'        => 'admin/user-management',
    'as'            => 'admin.user_management.',
    'middleware'    => ['web', 'auth:web', 'role:Admin']
], 
function () {

    ////    USER ROUTES
    ///////////////////////////////////////////////////////////////////
    Route::group([
        'prefix' => 'user',
        'as'     => 'user.'
    ], 
    function () {

        // admin.user_management.user.index
        route::get('/', 'UsersController@index')->name('index');

        // admin.user_management.user.create
        route::get('/create', 'UsersController@create')->name('create');

        // admin.user_management.user.store
        route::post('/store', 'UsersController@store')->name('store');

        // admin.user_management.user.edit
        route::get('/edit/{ID}', 'UsersController@edit')->name('edit');

        // admin.user_management.user.update
        route::put('/update/{ID}', 'UsersController@update')->name('update');

        // admin.user_management.user.delete
        route::delete('/delete/{ID}', 'UsersController@delete')->name('delete');

        // admin.user_management.user.restore
        route::put('/restore/{ID}', 'UsersController@restoreBackUser')->name('restore');
    });

    ////    ROLE ROUTES
    ///////////////////////////////////////////////////////////////////
    Route::group([
        'prefix' => 'role',
        'as'     => 'role.'
    ], 
    function () {

        // admin.user_management.role.index
        route::get('/', 'RolesController@index')->name('index');

        // admin.user_management.role.create
        route::get('/create', 'RolesController@create')->name('create');

        // admin.user_management.role.store
        route::post('/store', 'RolesController@store')->name('store');

        // admin.user_management.role.edit
        route::get('/edit/{ID}', 'RolesController@edit')->name('edit');

        // admin.user_management.role.update
        route::put('/update/{ID}', 'RolesController@update')->name('update');

        // admin.user_management.role.delete
        route::delete('/delete/{ID}', 'RolesController@delete')->name('delete');
    });

    ////    PERMISSION ROUTES
    ///////////////////////////////////////////////////////////////////
    Route::group([
        'prefix' => 'permission',
        'as'     => 'permission.'
    ], 
    function () {

        // admin.user_management.permission.index
        route::get('/', 'PermissionsController@index')->name('index');

        // admin.user_management.permission.create
        route::get('/create', 'PermissionsController@create')->name('create');

        // admin.user_management.permission.store
        route::post('/store', 'PermissionsController@store')->name('store');

        // admin.user_management.permission.edit
        route::get('/edit/{ID}', 'PermissionsController@edit')->name('edit');

        // admin.user_management.permission.update
        route::put('/update/{ID}', 'PermissionsController@update')->name('update');

        // admin.user_management.permission.delete
        route::delete('/delete/{ID}', 'PermissionsController@delete')->name('delete');
    });

    ////    DEPARTMENT ROUTES
    ///////////////////////////////////////////////////////////////////
    Route::group([
        'prefix' => 'department',
        'as'     => 'department.'
    ], 
    function () {

        // admin.user_management.department.index
        route::get('/', 'DepartmentsController@index')->name('index');

        // admin.user_management.department.create
        route::get('/create', 'DepartmentsController@create')->name('create');

        // admin.user_management.department.store
        route::post('/store', 'DepartmentsController@store')->name('store');

        // admin.user_management.department.edit
        route::get('/edit/{ID}', 'DepartmentsController@edit')->name('edit');

        // admin.user_management.department.update
        route::put('/update/{ID}', 'DepartmentsController@update')->name('update');

        // admin.user_management.department.delete
        route::delete('/delete/{ID}', 'DepartmentsController@delete')->name('delete');
    });


});



/*
|--------------------------------------------------------------------------
| IF THE CONFIG USER AUTH ENABLED THIS ROUTE WILL BE AVAILABLE
|--------------------------------------------------------------------------
|
|
*/    

if(config('laravel_user_management.auth.enable'))
{
    /// USER AUTH
    Route::group([
        'namespace'     => 'App\Http\Controllers\UserManagement\Auth',
        'as'            => 'auth.user.',
        'middleware'    => ['web', 'guest']
    ], 
    function () {

        // auth.user.login
        Route::get(config('laravel_user_management.auth.login_url'), 'AuthController@loginForm')
            ->name('login');

        // auth.user.login
        Route::post(config('laravel_user_management.auth.login_url'), 'AuthController@login')
            ->name('login');

        // auth.user.register
        Route::get(config('laravel_user_management.auth.register_url'), 'AuthController@registerForm')
            ->name('register');

        // auth.user.register
        Route::post(config('laravel_user_management.auth.register_url'), 'AuthController@register')
            ->name('register');
            
    });
    

    ///////////////////
    Route::group([
        'namespace'     => 'App\Http\Controllers\UserManagement\Auth',
        'as'            => 'auth.user.',
        'middleware'    => ['web', 'auth']
    ],
    function(){

        // auth.user.logout
        Route::get(config('laravel_user_management.auth.logout_url'), 'AuthController@logout')
            ->name('logout');

    });
        
}

/*
|--------------------------------------------------------------------------
| WE USE THIS SECTION FOR VUE.JS 
|--------------------------------------------------------------------------
|
|
*/    

if(config('laravel_user_management.vue_theme'))
{
    Route::get('/laravel-user-management', function () {
        return view('mekaeils-package.vue.master');
    });
}




//farm routes
// Route::resource('farms', App\Http\Controllers\FarmController::class)->middleware(['auth', 'wauth.role:admin|editor']);
Route::group(['as'=>'farms.', 'prefix'=>'farms', 'middleware'=>['auth'/* ,'wauth.role:admin|editor' */]], function(){
    // the above resource routes but separated because when using the middleware on the resource I cannot catch the request->id in the middleware wauth. but in this way I can do.
    // also I tried to apply the middleware on the controller itself but the same problem occurred.
    // the coming 3 permissions are not affected by the wauth middlewares as the existance ot id parameter is mandatory for the middleware to be applied (see the middlewares classes)
    Route::get('/', [App\Http\Controllers\FarmController::class, 'index'])->name('index');
    Route::get('create', [App\Http\Controllers\FarmController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\FarmController::class, 'store'])->name('store');

    // the coming 4 permissions are affected by the wauth middlewares as they have id parameter.
    Route::get('{id}/edit', [App\Http\Controllers\FarmController::class, 'edit'])->name('edit')->middleware('wauth.permission:edit');
    Route::match(['put', 'patch'], '{id}', [App\Http\Controllers\FarmController::class, 'update'])->name('update')->middleware('wauth.permission:update');
    Route::get('{id}', [App\Http\Controllers\FarmController::class, 'show'])->name('show')->middleware('wauth.permission:show');
    Route::delete('{id}', [App\Http\Controllers\FarmController::class, 'destroy'])->name('destroy')->middleware('wauth.permission:delete');

    // additionla routes but commented as they were included the above edit and update routes
    Route::get('edit-roles/{id}', [App\Http\Controllers\FarmController::class, 'edit_roles'])->name('roles.edit');
    Route::post('update-roles/{id}', [App\Http\Controllers\FarmController::class, 'update_roles'])->name('roles.update');

    Route::post('permByWtype/', [App\Http\Controllers\WorkableRoleController::class, 'permissions_by_workable_type'])->name('permByWtype');
});



Route::resource('workableRoles', App\Http\Controllers\WorkableRoleController::class);

Route::resource('workablePermissions', App\Http\Controllers\WorkablePermissionController::class);

Route::resource('users', App\Http\Controllers\UserController::class);

Route::resource('workableTypes', App\Http\Controllers\WorkableTypeController::class);

Route::resource('workables', App\Http\Controllers\WorkableController::class);


Route::resource('animalFodderSources', App\Http\Controllers\AnimalFodderSourceController::class);

Route::resource('animalFodderSources', App\Http\Controllers\AnimalFodderSourceController::class);

Route::resource('farmedTypeStages', App\Http\Controllers\FarmedTypeStageController::class);

Route::resource('farmActivityTypes', App\Http\Controllers\FarmActivityTypeController::class);

Route::resource('chemicalFertilizerSources', App\Http\Controllers\ChemicalFertilizerSourceController::class);

Route::resource('animalBreedingPurposes', App\Http\Controllers\AnimalBreedingPurposeController::class);

Route::resource('homePlantIlluminatingSources', App\Http\Controllers\HomePlantIlluminatingSourceController::class);

Route::resource('farmingMethods', App\Http\Controllers\FarmingMethodController::class);

Route::resource('saltDetails', App\Http\Controllers\SaltDetailController::class);

Route::resource('animalFodderTypes', App\Http\Controllers\AnimalFodderTypeController::class);

Route::resource('animalMedicineSources', App\Http\Controllers\AnimalMedicineSourceController::class);

Route::resource('jobs', App\Http\Controllers\JobController::class);

Route::resource('postTypes', App\Http\Controllers\PostTypeController::class);

Route::resource('seedlingSources', App\Http\Controllers\SeedlingSourceController::class);





Route::resource('measuringUnits', App\Http\Controllers\MeasuringUnitController::class);

Route::resource('buyingNotes', App\Http\Controllers\BuyingNoteController::class);

Route::resource('information', App\Http\Controllers\InformationController::class);

Route::resource('weatherNotes', App\Http\Controllers\WeatherNoteController::class);

Route::resource('soilTypes', App\Http\Controllers\SoilTypeController::class);

Route::resource('irrigationWays', App\Http\Controllers\IrrigationWayController::class);

Route::resource('farmingWays', App\Http\Controllers\FarmingWayController::class);

Route::resource('farmedTypes', App\Http\Controllers\FarmedTypeController::class);

Route::resource('products', App\Http\Controllers\ProductController::class);

Route::resource('posts', App\Http\Controllers\PostController::class);

Route::resource('comments', App\Http\Controllers\CommentController::class);

Route::resource('serviceTables', App\Http\Controllers\ServiceTableController::class);

Route::resource('serviceTasks', App\Http\Controllers\ServiceTaskController::class);

Route::resource('farmedTypeClasses', App\Http\Controllers\FarmedTypeClassController::class);

Route::resource('farmedTypeGinfos', App\Http\Controllers\FarmedTypeGinfoController::class);

Route::resource('chemicalDetails', App\Http\Controllers\ChemicalDetailController::class);



Route::resource('cities', App\Http\Controllers\CityController::class);

Route::resource('districts', App\Http\Controllers\DistrictController::class);

Route::resource('taskTypes', App\Http\Controllers\TaskTypeController::class);

Route::resource('saltTypes', App\Http\Controllers\SaltTypeController::class);

Route::resource('locations', App\Http\Controllers\LocationController::class);

Route::resource('acidityTypes', App\Http\Controllers\AcidityTypeController::class);

Route::resource('homePlantPotSizes', App\Http\Controllers\HomePlantPotSizeController::class);