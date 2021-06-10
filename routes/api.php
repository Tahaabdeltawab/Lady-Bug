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
    'middleware'    => ['api', 'jwt.verify', /* 'role:Admin' */]
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

    // start routes for users and admins

    Route::get('products', [App\Http\Controllers\API\ProductAPIController::class, 'index']);

    Route::group(['middleware'=>['check_farm_role']], function()
    {
        Route::delete('posts/{post}', [App\Http\Controllers\API\PostAPIController::class, 'destroy'])->name('posts.destroy');
    });

    Route::get('farms/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'show'])->name('farms.show');
    // end routes for users and admins

    // // // // //  USER AREA  // // // // //

    Route::group(['middleware'=>['role:app-user']], function()
    {

        // the above resource routes but separated because when using the middleware on the resource I cannot catch the request->id in the middleware wauth. but in this way I can do.
        // also I tried to apply the middleware on the controller itself but the same problem occurred.
        Route::get('farms/relations/index', [App\Http\Controllers\API\FarmAPIController::class, 'relations_index'])->name('farms.relations.index');
        Route::post('farms/', [App\Http\Controllers\API\FarmAPIController::class, 'store'])->name('farms.store');

        // Route::delete('{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'destroy'])->name('destroy')->middleware('check_farm_role');
        Route::group(['middleware'=>['check_farm_role']], function()
        {
            Route::match(['put', 'patch', 'post'], 'farms/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'update'])->name('farms.update');
            Route::get('farms/users/index/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'get_farm_users'])->name('farms.users.index');
            Route::get('farms/app_users/index/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'app_users']);
            Route::get('farms/posts/index/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'get_farm_posts'])->name('farms.posts.index');
            Route::get('farms/toggle_archive/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'toggleArchive'])->name('farms.toggle_archive');
            Route::get('farms/app_roles/index', [App\Http\Controllers\API\FarmAPIController::class, 'app_roles']);

            Route::resource('posts', App\Http\Controllers\API\PostAPIController::class)->except(['index', 'update', 'show', 'destroy']);
            Route::match(['put', 'patch','post'], 'posts/{post}', [App\Http\Controllers\API\PostAPIController::class, 'update'])->name('posts.update');
            Route::resource('service_tables', App\Http\Controllers\API\ServiceTableAPIController::class);
            Route::resource('service_tasks', App\Http\Controllers\API\ServiceTaskAPIController::class);
            Route::get('service_tasks/toggle_finish/{service_task}', [App\Http\Controllers\API\ServiceTaskAPIController::class, 'toggle_finish']);
            Route::get('service_tables/duplicate/{service_table}', [App\Http\Controllers\API\ServiceTableAPIController::class, 'duplicate']);
            Route::get('posts/toggle_solve/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_solve_post']);
            // // // LIKES // // //
            Route::get('posts/toggle_like/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_like']);
            Route::get('posts/toggle_dislike/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_dislike']);
        });
        Route::get('posts/{post}', [App\Http\Controllers\API\PostAPIController::class, 'show'])->name('posts.show');
        Route::post('farms/roles/store', [App\Http\Controllers\API\FarmAPIController::class, 'update_farm_role'])->name('farms.roles.store');
        Route::get('farms/roles/store/{user}/{role}/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'first_attach_farm_role'])->name('farms.roles.first_attach');
        Route::post('farms/user/weather/index', [App\Http\Controllers\API\FarmAPIController::class, 'get_weather'])->name('farms.users.weather.index');
        Route::get('farms/archived/index', [App\Http\Controllers\API\FarmAPIController::class, 'getArchived'])->name('farms.get_archived');


        //get weather and auth interests
        Route::post('users/interests/index', [App\Http\Controllers\API\UserAPIController::class, 'user_interests']);
        //get auth products
        Route::get('users/products/index', [App\Http\Controllers\API\UserAPIController::class, 'user_products']);
        //get weather and auth farms
        Route::post('users/farms/index', [App\Http\Controllers\API\UserAPIController::class, 'user_farms']);
        Route::post('users/today_tasks/index', [App\Http\Controllers\API\UserAPIController::class, 'user_today_tasks']);
        // // // NOTIFICATIONS
        Route::get('users/notifications/index', [App\Http\Controllers\API\UserAPIController::class, 'get_notifications']);
        Route::get('users/notifications/read/{notification}', [App\Http\Controllers\API\UserAPIController::class, 'read_notification']);
        Route::get('users/notifications/unread/{notification}', [App\Http\Controllers\API\UserAPIController::class, 'unread_notification']);
        // // // FOLLOW
        Route::get('users/followers/index', [App\Http\Controllers\API\UserAPIController::class, 'my_followers']);
        Route::get('users/followings/index', [App\Http\Controllers\API\UserAPIController::class, 'my_followings']);
        Route::get('users/toggle_follow/{user}', [App\Http\Controllers\API\UserAPIController::class, 'toggleFollow']);
        // // // RATE
        Route::post('users/rate', [App\Http\Controllers\API\UserAPIController::class, 'rate']);
        // // // NOTIFIABLE
        Route::get('users/toggle_notifiable', [App\Http\Controllers\API\UserAPIController::class, 'toggle_notifiable']);

        Route::get('users/search/{query}', [App\Http\Controllers\API\UserAPIController::class, 'search']);

        Route::get('users/posts/index', [App\Http\Controllers\API\UserAPIController::class, 'user_posts']);
        Route::get('users/liked_posts/index', [App\Http\Controllers\API\UserAPIController::class, 'user_liked_posts']);
        Route::get('users/disliked_posts/index', [App\Http\Controllers\API\UserAPIController::class, 'user_disliked_posts']);
        Route::post('users/favorites', [App\Http\Controllers\API\UserAPIController::class, 'store_favorites'])->name('users.favorites.store');
        Route::get('users/favorites', [App\Http\Controllers\API\UserAPIController::class, 'my_favorites'])->name('users.favorites.index');
        Route::resource('users', App\Http\Controllers\API\UserAPIController::class)->except(['store', 'destroy', 'index']);
        //with put and patch, laravel cannot read the request
        Route::match(['put', 'patch','post'], 'users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'update'])->name('users.update');

        Route::resource('products', App\Http\Controllers\API\ProductAPIController::class)->except(['update', 'index']);
        Route::match(['put', 'patch','post'], 'products/{product}', [App\Http\Controllers\API\ProductAPIController::class, 'update'])->name('products.update');
        Route::get('products/toggle_sell/{product}', [App\Http\Controllers\API\ProductAPIController::class, 'toggle_sell_product']);
        Route::get('products/relations/index', [App\Http\Controllers\API\ProductAPIController::class, 'products_relations']);
        Route::get('products/search/{query}', [App\Http\Controllers\API\ProductAPIController::class, 'search']);


        Route::get('posts/search/{query}', [App\Http\Controllers\API\PostAPIController::class, 'search']);
        Route::get('posts/timeline/index', [App\Http\Controllers\API\PostAPIController::class, 'timeline'])->name('timeline'); // the name must remain timeline because it's checked in UserResource
        Route::get('posts/video_timeline/index', [App\Http\Controllers\API\PostAPIController::class, 'video_timeline'])->name('video_timeline'); // the name must remain timeline because it's checked in UserResource


        Route::get('post_types/posts/{post_type}', [App\Http\Controllers\API\PostAPIController::class, 'get_posts_by_post_type_id']);
        Route::get('farmed_types/posts/{farmed_type}', [App\Http\Controllers\API\PostAPIController::class, 'get_posts_by_farmed_type_id']);
        Route::get('posts/relations/index', [App\Http\Controllers\API\PostAPIController::class, 'posts_relations']);

        Route::resource('comments', App\Http\Controllers\API\CommentAPIController::class);
        Route::match(['put', 'patch','post'], 'comments/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'update'])->name('comments.update');
        // // // LIKES // // //
        Route::get('comments/toggle_like/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'toggle_like']);
        Route::get('comments/toggle_dislike/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'toggle_dislike']);
    });


        // Route::resource('chemical_details', App\Http\Controllers\API\ChemicalDetailAPIController::class);
        // Route::resource('salt_details', App\Http\Controllers\API\SaltDetailAPIController::class);




            // });



    // // // // // //  ADMIN AREA  // // // // // //

    Route::group([
        'prefix'        => 'admin/',
        'as'            => 'admin.',
        'middleware'=>['role:'.config('myconfig.admin_role') ]], function()
    {

        Route::get('farms', [App\Http\Controllers\API\FarmAPIController::class, 'index'])->name('farms.index');


        Route::get('users', [App\Http\Controllers\API\UserAPIController::class, 'admin_index']);
        Route::get('generic_users', [App\Http\Controllers\API\UserAPIController::class, 'index']);

        Route::get('users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'show']);

        Route::get('users/toggle_activate/{user}', [App\Http\Controllers\API\UserAPIController::class, 'toggle_activate_user']);

        Route::delete('users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'destroy']);
        Route::post('users', [App\Http\Controllers\API\UserAPIController::class, 'store']);

        Route::Resource('roles', App\Http\Controllers\API\RoleAPIController::class);
        Route::Resource('permissions', App\Http\Controllers\API\PermissionAPIController::class);

        Route::post('users/roles/save', [App\Http\Controllers\API\UserAPIController::class, 'update_user_roles']);
        Route::post('roles/permissions/save', [App\Http\Controllers\API\RoleAPIController::class, 'update_role_permissions']);


        Route::get('posts', [App\Http\Controllers\API\PostAPIController::class, 'index']);
        Route::get('posts/toggle_activate/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_activate']);

        Route::post('products/rate', [App\Http\Controllers\API\ProductAPIController::class, 'rate']);



        Route::resource('animal_fodder_sources', App\Http\Controllers\API\AnimalFodderSourceAPIController::class);

        Route::resource('farmed_type_stages', App\Http\Controllers\API\FarmedTypeStageAPIController::class);

        // SHOULD BE ID 1 FOR CROPS, 2 FOR TREES, 3 FOR HOMEPLANTS, 4 FOR ANIMALS
        // BECAUSE THERE IS CHECK ON THESE IDs ON FARM_RELATIONS METHOD IN FARMAPICONTROLLER
        // Route::resource('farm_activity_types', App\Http\Controllers\API\FarmActivityTypeAPIController::class);

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
        Route::get('farmed_types/search/{query}', [App\Http\Controllers\API\FarmedTypeAPIController::class, 'search']);

        Route::resource('farmed_type_classes', App\Http\Controllers\API\FarmedTypeClassAPIController::class);

        Route::resource('farmed_type_ginfos', App\Http\Controllers\API\FarmedTypeGinfoAPIController::class)->except(['update']);
        Route::match(['put', 'patch','post'], 'farmed_type_ginfos/{farmed_type_ginfo}', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'update'])->name('farmed_type_ginfos.update');
        Route::get('farmed_type_ginfos/relations/index', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'farmed_type_ginfos_relations']);
        Route::get('farmed_types/farmed_type_ginfos/{farmed_type}/{stage?}', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'farmed_type_ginfos_by_farmed_type_id']);

        Route::resource('cities', App\Http\Controllers\API\CityAPIController::class);

        Route::resource('districts', App\Http\Controllers\API\DistrictAPIController::class);

        Route::resource('task_types', App\Http\Controllers\API\TaskTypeAPIController::class);

        Route::resource('salt_types', App\Http\Controllers\API\SaltTypeAPIController::class);

        Route::resource('locations', App\Http\Controllers\API\LocationAPIController::class);

        Route::resource('acidity_types', App\Http\Controllers\API\AcidityTypeAPIController::class);

        Route::resource('home_plant_pot_sizes', App\Http\Controllers\API\HomePlantPotSizeAPIController::class);

    });


});

// ROUTES DON'T NEED LOGIN AS THEY ARE USED IN REGISTRATION
Route::get('human_jobs', [App\Http\Controllers\API\HumanJobAPIController::class, 'index'])->name('human_jobs.index');
