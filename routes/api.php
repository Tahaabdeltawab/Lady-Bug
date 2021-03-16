<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers'

], function ($router) {

    Route::post('login', 'AuthController@login')->middleware('jwt.guest');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
});


// Route::group(['middleware' => 'auth:api'], function(){
    Route::group(['middleware' => 'jwt.verify'], function(){

        Route::resource('assets', App\Http\Controllers\API\AssetAPIController::class);
    
    });

/*
|--------------------------------------------------------------------------
| LARAVEL USER MANAGEMENT ROUTE
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'namespace'     => 'App\Http\Controllers\API\UserManagement',
    'prefix'        => 'admin/user-management',
    'as'            => 'admin.user_management.',
    'middleware'    => ['api', 'jwt.verify', 'role:Admin']
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




//farm routes

// Route::resource('farms', App\Http\Controllers\API\FarmAPIController::class)->middleware(['auth', 'wauth.role:admin|editor']);

Route::group(['middleware'=>['auth:api']], function()
{

    
    // // // // //  USER AREA  // // // // //
    
    Route::group(['middleware'=>['role:app-user']], function()
    {

        Route::group(['as'=>'farms.', 'prefix'=>'farms', 'middleware'=>[]], function()
        {
            // the above resource routes but separated because when using the middleware on the resource I cannot catch the request->id in the middleware wauth. but in this way I can do.
            // also I tried to apply the middleware on the controller itself but the same problem occurred.
            Route::get('/relations', [App\Http\Controllers\API\FarmAPIController::class, 'relations_index'])->name('relations.index');
            Route::post('/', [App\Http\Controllers\API\FarmAPIController::class, 'store'])->name('store');

            Route::match(['put', 'patch', 'post'], '{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'update'])->name('update');
            Route::get('{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'show'])->name('show');
            Route::delete('{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'destroy'])->name('destroy');
            
            Route::post('roles/store', [App\Http\Controllers\API\FarmAPIController::class, 'update_farm_role'])->name('roles.store');
            Route::get('app_users/app_roles', [App\Http\Controllers\API\FarmAPIController::class, 'app_users_and_roles']);
            Route::get('roles/store/{user_id}/{role_id}/{farm_id}', [App\Http\Controllers\API\FarmAPIController::class, 'first_attach_farm_role'])->name('roles.first_attach');
            Route::get('users/index/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'get_farm_users'])->name('users.index');
            Route::get('posts/index/{farm_id}', [App\Http\Controllers\API\FarmAPIController::class, 'get_farm_posts'])->name('posts.index');
            Route::post('user/weather/index', [App\Http\Controllers\API\FarmAPIController::class, 'get_weather'])->name('users.weather.index');
            
        });

        //get auth farms
        Route::get('users/farms/index', [App\Http\Controllers\API\UserAPIController::class, 'user_farms']);
        Route::get('users/liked_posts/index', [App\Http\Controllers\API\UserAPIController::class, 'user_liked_posts']);
        Route::get('users/disliked_posts/index', [App\Http\Controllers\API\UserAPIController::class, 'user_disliked_posts']);
        Route::post('users/favorites', [App\Http\Controllers\API\UserAPIController::class, 'store_favorites'])->name('users.favorites.store');
        Route::get('users/favorites', [App\Http\Controllers\API\UserAPIController::class, 'my_favorites'])->name('users.favorites.index');
        Route::resource('users', App\Http\Controllers\API\UserAPIController::class)->except(['store', 'destroy']);
        //with put and patch, laravel cannot read the request 
        Route::match(['put', 'patch','post'], 'users/{id}', [App\Http\Controllers\API\UserAPIController::class, 'update'])->name('users.update');

        Route::resource('products', App\Http\Controllers\API\ProductAPIController::class);
        
        Route::resource('posts', App\Http\Controllers\API\PostAPIController::class)->except(['update']);
        Route::match(['put', 'patch','post'], 'posts/{post}', [App\Http\Controllers\API\PostAPIController::class, 'update'])->name('posts.update');
        // // // LIKES // // //
        Route::get('posts/toggle_like/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_like']);
        Route::get('posts/toggle_dislike/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_dislike']);
        
        Route::resource('comments', App\Http\Controllers\API\CommentAPIController::class);
        Route::match(['put', 'patch','post'], 'comments/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'update'])->name('comments.update');
        // // // LIKES // // //
        Route::get('comments/toggle_like/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'toggle_like']);
        Route::get('comments/toggle_dislike/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'toggle_dislike']);

        Route::resource('service_tables', App\Http\Controllers\API\ServiceTableAPIController::class);

        Route::resource('service_tasks', App\Http\Controllers\API\ServiceTaskAPIController::class);

        // Route::resource('chemical_details', App\Http\Controllers\API\ChemicalDetailAPIController::class);
        // Route::resource('salt_details', App\Http\Controllers\API\SaltDetailAPIController::class);
    });
    


    // // // // // //  ADMIN AREA  // // // // // //
    
    Route::group(['middleware'=>['role:app-admin']], function()
    {

        Route::get('farms', [App\Http\Controllers\API\FarmAPIController::class, 'index'])->name('farms.index');

        Route::resource('animal_fodder_sources', App\Http\Controllers\API\AnimalFodderSourceAPIController::class);

        Route::resource('farmed_type_stages', App\Http\Controllers\API\FarmedTypeStageAPIController::class);

        Route::resource('farm_activity_types', App\Http\Controllers\API\FarmActivityTypeAPIController::class);

        Route::resource('chemical_fertilizer_sources', App\Http\Controllers\API\ChemicalFertilizerSourceAPIController::class);

        Route::resource('animal_breeding_purposes', App\Http\Controllers\API\AnimalBreedingPurposeAPIController::class);

        Route::resource('home_plant_illuminating_sources', App\Http\Controllers\API\HomePlantIlluminatingSourceAPIController::class);

        Route::resource('farming_methods', App\Http\Controllers\API\FarmingMethodAPIController::class);

        Route::resource('animal_fodder_types', App\Http\Controllers\API\AnimalFodderTypeAPIController::class);

        Route::resource('animal_medicine_sources', App\Http\Controllers\API\AnimalMedicineSourceAPIController::class);

        Route::resource('human_jobs', App\Http\Controllers\API\HumanJobAPIController::class)->except(['index']);

        Route::resource('post_types', App\Http\Controllers\API\PostTypeAPIController::class);

        Route::resource('seedling_sources', App\Http\Controllers\API\SeedlingSourceAPIController::class);

        Route::resource('measuring_units', App\Http\Controllers\API\MeasuringUnitAPIController::class);

        Route::resource('buying_notes', App\Http\Controllers\API\BuyingNoteAPIController::class);

        Route::resource('information', App\Http\Controllers\API\InformationAPIController::class);

        Route::resource('weather_notes', App\Http\Controllers\API\WeatherNoteAPIController::class);

        Route::resource('soil_types', App\Http\Controllers\API\SoilTypeAPIController::class);

        Route::resource('irrigation_ways', App\Http\Controllers\API\IrrigationWayAPIController::class);

        Route::resource('farming_ways', App\Http\Controllers\API\FarmingWayAPIController::class);

        Route::resource('farmed_types', App\Http\Controllers\API\FarmedTypeAPIController::class);
        Route::match(['put', 'patch','post'], 'farmed_types/{farmed_type}', [App\Http\Controllers\API\FarmedTypeAPIController::class, 'update'])->name('farmed_types.update');
        
        Route::resource('farmed_type_classes', App\Http\Controllers\API\FarmedTypeClassAPIController::class);
        
        Route::resource('farmed_type_ginfos', App\Http\Controllers\API\FarmedTypeGinfoAPIController::class);

        Route::resource('cities', App\Http\Controllers\API\CityAPIController::class);

        Route::resource('districts', App\Http\Controllers\API\DistrictAPIController::class);

        Route::resource('task_types', App\Http\Controllers\API\TaskTypeAPIController::class);

        Route::resource('salt_types', App\Http\Controllers\API\SaltTypeAPIController::class);

        Route::resource('locations', App\Http\Controllers\API\LocationAPIController::class);

        Route::resource('acidity_types', App\Http\Controllers\API\AcidityTypeAPIController::class);

    });


});

// ROUTES DON'T NEED LOGIN AS THEY ARE USED IN REGISTRATION
Route::get('human_jobs', [App\Http\Controllers\API\HumanJobAPIController::class, 'index'])->name('human_jobs.index');
