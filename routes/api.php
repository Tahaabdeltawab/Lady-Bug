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

], function () {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
});


//farm routes

Route::group(['middleware'=>['auth:api', 'checkBlocked']], function()
{

    //  USER AREA

    Route::group(
        ['middleware'=>[/* 'role:app-user' */]],
        function()
    {

        // the above resource routes but separated because when using the middleware on the resource I cannot catch the request->id in the middleware wauth. but in this way I can do.
        // also I tried to apply the middleware on the controller itself but the same problem occurred.
        Route::get('farms/relations/index', [App\Http\Controllers\API\FarmAPIController::class, 'relations_index'])->name('farms.relations.index');
        Route::post('farms/', [App\Http\Controllers\API\FarmAPIController::class, 'store'])->name('farms.store');

        // Route::delete('{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'destroy'])->name('destroy')->middleware('check_business_role');
        Route::group(['middleware'=>[/* 'check_business_role' */]], function()
        {

            Route::match(['put', 'patch', 'post'], 'farms/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'update'])->name('farms.update');
            Route::get('farms/toggle_archive/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'toggleArchive'])->name('farms.toggle_archive');

            Route::resource('posts', App\Http\Controllers\API\PostAPIController::class)->except(['index', 'update', 'show', 'destroy']);
            Route::match(['put', 'patch','post'], 'posts/{post}', [App\Http\Controllers\API\PostAPIController::class, 'update'])->name('posts.update');
            // Route::resource('service_tasks', App\Http\Controllers\API\ServiceTaskAPIController::class);
            // Route::resource('service_tables', App\Http\Controllers\API\ServiceTableAPIController::class);
            // Route::get('service_tables/duplicate/{service_table}', [App\Http\Controllers\API\ServiceTableAPIController::class, 'duplicate']);
            Route::get('posts/toggle_solve/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_solve_post']);
        });

        // LIKES
        Route::get('posts/toggle_like/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_like']);
        Route::get('posts/toggle_dislike/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_dislike']);


        // UPDATE PROFILE
        Route::post('update_profile', [App\Http\Controllers\API\UserAPIController::class, 'update_profile']);
        Route::post('users/update_password/{user}', [App\Http\Controllers\API\UserAPIController::class, 'update_password']);

        //get weather and auth interests
        Route::post('users/interests/index', [App\Http\Controllers\API\UserAPIController::class, 'user_interests']);
        //get auth products
        Route::get('users/products/index', [App\Http\Controllers\API\UserAPIController::class, 'user_products']);
        // NOTIFICATIONS
        Route::get('users/notifications/index', [App\Http\Controllers\API\UserAPIController::class, 'get_notifications']);
        Route::get('users/notifications/read/{notification}', [App\Http\Controllers\API\UserAPIController::class, 'read_notification']);
        Route::get('users/notifications/unread/{notification}', [App\Http\Controllers\API\UserAPIController::class, 'unread_notification']);
        // FOLLOW
        Route::get('users/followers/index', [App\Http\Controllers\API\UserAPIController::class, 'my_followers']);
        Route::get('users/followings/index', [App\Http\Controllers\API\UserAPIController::class, 'my_followings']);
        Route::get('users/toggle_follow/{user}', [App\Http\Controllers\API\UserAPIController::class, 'toggle_follow']);
        // RATE
        Route::post('users/rate', [App\Http\Controllers\API\UserAPIController::class, 'rate']);
        // NOTIFIABLE
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
        Route::get('posts/timeline/posts', [App\Http\Controllers\API\PostAPIController::class, 'paginated_posts'])->name('paginated_posts');
        Route::get('posts/timeline/index', [App\Http\Controllers\API\PostAPIController::class, 'timeline'])->name('timeline'); // the name must remain timeline because it's checked in UserResource
        Route::get('posts/video_timeline/index', [App\Http\Controllers\API\PostAPIController::class, 'video_timeline'])->name('video_timeline'); // the name must remain timeline because it's checked in UserResource


        Route::get('post_types/posts/{post_type}', [App\Http\Controllers\API\PostAPIController::class, 'get_posts_by_post_type_id']);
        Route::get('farmed_types/posts/{farmed_type}', [App\Http\Controllers\API\PostAPIController::class, 'get_posts_by_farmed_type_id']);
        Route::get('posts/relations/index', [App\Http\Controllers\API\PostAPIController::class, 'posts_relations']);

        Route::resource('comments', App\Http\Controllers\API\CommentAPIController::class);
        Route::match(['put', 'patch','post'], 'comments/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'update'])->name('comments.update');
        // LIKES //
        Route::get('comments/toggle_like/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'toggle_like']);
        Route::get('comments/toggle_dislike/{comment}', [App\Http\Controllers\API\CommentAPIController::class, 'toggle_dislike']);

        Route::resource('reports', App\Http\Controllers\API\ReportAPIController::class)->except(['index', 'show', 'update', 'destroy']);

    });


    // //  ADMIN AREA  // //

    Route::group([
        'prefix'        => 'admin/',
        'as'            => 'admin.',
        // 'middleware'=>['role:'.config('myconfig.admin_role') ]
    ], function()
    {

        Route::get('farms', [App\Http\Controllers\API\FarmAPIController::class, 'index'])->name('farms.index');
        Route::get('reports', [App\Http\Controllers\API\ReportAPIController::class, 'index'])->name('reports.index');
        Route::get('reports/{report}', [App\Http\Controllers\API\ReportAPIController::class, 'show'])->name('reports.show');
        Route::match(['put', 'patch','post'], 'reports/{report}', [App\Http\Controllers\API\ReportAPIController::class, 'update'])->name('reports.update');
        Route::delete('reports/{report}', [App\Http\Controllers\API\ReportAPIController::class, 'destroy'])->name('reports.destroy');


        Route::get('users', [App\Http\Controllers\API\UserAPIController::class, 'admin_index']);
        Route::get('generic_users', [App\Http\Controllers\API\UserAPIController::class, 'index']);

        Route::get('users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'admin_show']);
        Route::get('generic_users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'show']);

        Route::get('users/toggle_activate/{user}', [App\Http\Controllers\API\UserAPIController::class, 'toggle_activate_user']);

        Route::delete('users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'destroy']);
        Route::post('users', [App\Http\Controllers\API\UserAPIController::class, 'store']);

        //with put and patch, laravel cannot read the request
        Route::match(['put', 'patch','post'], 'users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'update'])->name('users.update')->middleware('permission:users.update');

        Route::Resource('roles', App\Http\Controllers\API\RoleAPIController::class);
        Route::Resource('permissions', App\Http\Controllers\API\PermissionAPIController::class);

        Route::post('users/roles/save', [App\Http\Controllers\API\UserAPIController::class, 'update_user_roles']);
        Route::post('roles/permissions/save', [App\Http\Controllers\API\RoleAPIController::class, 'update_role_permissions']);


        Route::get('posts', [App\Http\Controllers\API\PostAPIController::class, 'index']);
        Route::get('posts/toggle_activate/{post}', [App\Http\Controllers\API\PostAPIController::class, 'toggle_activate']);

        Route::post('products/rate', [App\Http\Controllers\API\ProductAPIController::class, 'rate']);



        Route::resource('animal_fodder_sources', App\Http\Controllers\API\AnimalFodderSourceAPIController::class)->except(['index']);

        Route::resource('farmed_type_stages', App\Http\Controllers\API\FarmedTypeStageAPIController::class)->except(['index']);

        // SHOULD BE ID 1 FOR CROPS, 2 FOR TREES, 3 FOR HOMEPLANTS, 4 FOR ANIMALS
        // BECAUSE THERE IS CHECK ON THESE IDs ON FARM_RELATIONS METHOD IN FARMAPICONTROLLER
        // Route::resource('farm_activity_types', App\Http\Controllers\API\FarmActivityTypeAPIController::class);

        Route::resource('chemical_fertilizer_sources', App\Http\Controllers\API\ChemicalFertilizerSourceAPIController::class)->except(['index']);

        Route::resource('animal_breeding_purposes', App\Http\Controllers\API\AnimalBreedingPurposeAPIController::class)->except(['index']);

        Route::resource('home_plant_illuminating_sources', App\Http\Controllers\API\HomePlantIlluminatingSourceAPIController::class)->except(['index']);

        Route::resource('farming_methods', App\Http\Controllers\API\FarmingMethodAPIController::class)->except(['index']);

        Route::resource('animal_fodder_types', App\Http\Controllers\API\AnimalFodderTypeAPIController::class)->except(['index']);

        Route::resource('animal_medicine_sources', App\Http\Controllers\API\AnimalMedicineSourceAPIController::class)->except(['index']);

        Route::resource('human_jobs', App\Http\Controllers\API\HumanJobAPIController::class)->except(['index']);//

        Route::resource('post_types', App\Http\Controllers\API\PostTypeAPIController::class)->except(['index']);

        Route::resource('seedling_sources', App\Http\Controllers\API\SeedlingSourceAPIController::class)->except(['index']);

        Route::resource('measuring_units', App\Http\Controllers\API\MeasuringUnitAPIController::class)->except(['index']);

        Route::resource('buying_notes', App\Http\Controllers\API\BuyingNoteAPIController::class)->except(['index']);

        Route::resource('information', App\Http\Controllers\API\InformationAPIController::class)->except(['index']);

        Route::resource('weather_notes', App\Http\Controllers\API\WeatherNoteAPIController::class)->except(['index']);

        Route::resource('soil_types', App\Http\Controllers\API\SoilTypeAPIController::class)->except(['index']);

        Route::resource('irrigation_ways', App\Http\Controllers\API\IrrigationWayAPIController::class)->except(['index']);

        Route::resource('farming_ways', App\Http\Controllers\API\FarmingWayAPIController::class)->except(['index']);

        Route::resource('farmed_types', App\Http\Controllers\API\FarmedTypeAPIController::class)->except(['index']);
        Route::match(['put', 'patch','post'], 'farmed_types/{farmed_type}', [App\Http\Controllers\API\FarmedTypeAPIController::class, 'update'])->name('farmed_types.update');

        Route::resource('farmed_type_classes', App\Http\Controllers\API\FarmedTypeClassAPIController::class)->except(['index']);

        Route::resource('farmed_type_ginfos', App\Http\Controllers\API\FarmedTypeGinfoAPIController::class)->except(['index', 'update']);
        Route::match(['put', 'patch','post'], 'farmed_type_ginfos/{farmed_type_ginfo}', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'update'])->name('farmed_type_ginfos.update');
        Route::get('farmed_type_ginfos/relations/index', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'farmed_type_ginfos_relations']);

        Route::resource('cities', App\Http\Controllers\API\CityAPIController::class)->except(['index']);

        Route::resource('districts', App\Http\Controllers\API\DistrictAPIController::class)->except(['index']);

        Route::resource('task_types', App\Http\Controllers\API\TaskTypeAPIController::class)->except(['index']);

        Route::resource('salt_types', App\Http\Controllers\API\SaltTypeAPIController::class)->except(['index']);

        Route::resource('acidity_types', App\Http\Controllers\API\AcidityTypeAPIController::class)->except(['index']);

        Route::resource('home_plant_pot_sizes', App\Http\Controllers\API\HomePlantPotSizeAPIController::class)->except(['index']);

        Route::resource('report_types', App\Http\Controllers\API\ReportTypeAPIController::class)->except(['index']);

    });

    // start routes for users and admins
    Route::get('farmed_types/search/{query}', [App\Http\Controllers\API\FarmedTypeAPIController::class, 'search']);

    Route::get('farmed_types/farmed_type_ginfos/{farmed_type}/{stage?}', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'farmed_type_ginfos_by_farmed_type_id']);

    Route::get('farms/calculate_compatibility/{id}', [App\Http\Controllers\API\FarmAPIController::class, 'calculate_compatibility']);

    Route::get('products', [App\Http\Controllers\API\ProductAPIController::class, 'index']);

    Route::group(['middleware'=>[/* 'check_business_role' */]], function()
    {
        Route::delete('posts/{post}', [App\Http\Controllers\API\PostAPIController::class, 'destroy'])->name('posts.destroy');
    });

    Route::get('posts/{post}', [App\Http\Controllers\API\PostAPIController::class, 'show'])->name('posts.show');

    Route::get('farms/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'show'])->name('farms.show');

    Route::get('farm_activity_types', [App\Http\Controllers\API\FarmActivityTypeAPIController::class, 'index']);
    // Route::get('farm_activity_types/{farm_activity_type}', [App\Http\Controllers\API\FarmActivityTypeAPIController::class, 'show'])->name('farm_activity_types.show');

    Route::get('animal_fodder_sources', [App\Http\Controllers\API\AnimalFodderSourceAPIController::class, 'index']);
    Route::get('animal_fodder_sources/{animal_fodder_source}', [App\Http\Controllers\API\AnimalFodderSourceAPIController::class, 'show'])->name('animal_fodder_sources.show');

    Route::get('farmed_type_stages', [App\Http\Controllers\API\FarmedTypeStageAPIController::class, 'index']);
    Route::get('farmed_type_stages/{farmed_type_stage}', [App\Http\Controllers\API\FarmedTypeStageAPIController::class, 'show'])->name('farmed_type_stages.show');

    Route::get('chemical_fertilizer_sources', [App\Http\Controllers\API\ChemicalFertilizerSourceAPIController::class, 'index']);
    Route::get('chemical_fertilizer_sources/{chemical_fertilizer_source}', [App\Http\Controllers\API\ChemicalFertilizerSourceAPIController::class, 'show'])->name('chemical_fertilizer_sources.show');

    Route::get('animal_breeding_purposes', [App\Http\Controllers\API\AnimalBreedingPurposeAPIController::class, 'index']);
    Route::get('animal_breeding_purposes/{animal_breeding_purpose}', [App\Http\Controllers\API\AnimalBreedingPurposeAPIController::class, 'show'])->name('animal_breeding_purposes.show');

    Route::get('home_plant_illuminating_sources', [App\Http\Controllers\API\HomePlantIlluminatingSourceAPIController::class, 'index']);
    Route::get('home_plant_illuminating_sources/{home_plant_illuminating_source}', [App\Http\Controllers\API\HomePlantIlluminatingSourceAPIController::class, 'show'])->name('home_plant_illuminating_sources.show');

    Route::get('farming_methods', [App\Http\Controllers\API\FarmingMethodAPIController::class, 'index']);
    Route::get('farming_methods/{farming_method}', [App\Http\Controllers\API\FarmingMethodAPIController::class, 'show'])->name('farming_methods.show');

    Route::get('animal_fodder_types', [App\Http\Controllers\API\AnimalFodderTypeAPIController::class, 'index']);
    Route::get('animal_fodder_types/{animal_fodder_type}', [App\Http\Controllers\API\AnimalFodderTypeAPIController::class, 'show'])->name('animal_fodder_types.show');

    Route::get('animal_medicine_sources', [App\Http\Controllers\API\AnimalMedicineSourceAPIController::class, 'index']);
    Route::get('animal_medicine_sources/{animal_medicine_source}', [App\Http\Controllers\API\AnimalMedicineSourceAPIController::class, 'show'])->name('animal_medicine_sources.show');

    Route::get('post_types', [App\Http\Controllers\API\PostTypeAPIController::class, 'index']);
    Route::get('post_types/{post_type}', [App\Http\Controllers\API\PostTypeAPIController::class, 'show'])->name('post_types.show');

    Route::get('seedling_sources', [App\Http\Controllers\API\SeedlingSourceAPIController::class, 'index']);
    Route::get('seedling_sources/{seedling_source}', [App\Http\Controllers\API\SeedlingSourceAPIController::class, 'show'])->name('seedling_sources.show');

    Route::get('measuring_units', [App\Http\Controllers\API\MeasuringUnitAPIController::class, 'index']);
    Route::get('measuring_units/{measuring_unit}', [App\Http\Controllers\API\MeasuringUnitAPIController::class, 'show'])->name('measuring_units.show');

    Route::get('buying_notes', [App\Http\Controllers\API\BuyingNoteAPIController::class, 'index']);
    Route::get('buying_notes/{buying_note}', [App\Http\Controllers\API\BuyingNoteAPIController::class, 'show'])->name('buying_notes.show');

    Route::get('information', [App\Http\Controllers\API\InformationAPIController::class, 'index']);

    Route::get('weather_notes', [App\Http\Controllers\API\WeatherNoteAPIController::class, 'index']);
    Route::get('weather_notes/{weather_note}', [App\Http\Controllers\API\WeatherNoteAPIController::class, 'show'])->name('weather_notes.show');

    Route::get('soil_types', [App\Http\Controllers\API\SoilTypeAPIController::class, 'index']);
    Route::get('soil_types/{soil_type}', [App\Http\Controllers\API\SoilTypeAPIController::class, 'show'])->name('soil_types.show');

    Route::get('irrigation_ways', [App\Http\Controllers\API\IrrigationWayAPIController::class, 'index']);
    Route::get('irrigation_ways/{irrigation_way}', [App\Http\Controllers\API\IrrigationWayAPIController::class, 'show'])->name('irrigation_ways.show');

    Route::get('farming_ways', [App\Http\Controllers\API\FarmingWayAPIController::class, 'index']);
    Route::get('farming_ways/{farming_way}', [App\Http\Controllers\API\FarmingWayAPIController::class, 'show'])->name('farming_ways.show');

    Route::get('farmed_types', [App\Http\Controllers\API\FarmedTypeAPIController::class, 'index']);
    Route::get('farmed_types/{farmed_type}', [App\Http\Controllers\API\FarmedTypeAPIController::class, 'show'])->name('farmed_types.show');

    Route::get('farmed_type_classes', [App\Http\Controllers\API\FarmedTypeClassAPIController::class, 'index']);
    Route::get('farmed_type_classes/{farmed_type_classe}', [App\Http\Controllers\API\FarmedTypeClassAPIController::class, 'show'])->name('farmed_type_classes.show');

    Route::get('farmed_type_ginfos', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'index']);
    Route::get('farmed_type_ginfos/{farmed_type_ginfo}', [App\Http\Controllers\API\FarmedTypeGinfoAPIController::class, 'show'])->name('farmed_type_ginfos.show');

    Route::get('cities', [App\Http\Controllers\API\CityAPIController::class, 'index']);
    Route::get('cities/{citie}', [App\Http\Controllers\API\CityAPIController::class, 'show'])->name('cities.show');

    Route::get('districts', [App\Http\Controllers\API\DistrictAPIController::class, 'index']);
    Route::get('districts/{district}', [App\Http\Controllers\API\DistrictAPIController::class, 'show'])->name('districts.show');

    Route::get('task_types', [App\Http\Controllers\API\TaskTypeAPIController::class, 'index']);
    Route::get('task_types/{task_type}', [App\Http\Controllers\API\TaskTypeAPIController::class, 'show'])->name('task_types.show');

    Route::get('salt_types', [App\Http\Controllers\API\SaltTypeAPIController::class, 'index']);
    Route::get('salt_types/{salt_type}', [App\Http\Controllers\API\SaltTypeAPIController::class, 'show'])->name('salt_types.show');

    Route::get('locations', [App\Http\Controllers\API\LocationAPIController::class, 'index']);
    Route::get('locations/{location}', [App\Http\Controllers\API\LocationAPIController::class, 'show'])->name('locations.show');

    Route::get('acidity_types', [App\Http\Controllers\API\AcidityTypeAPIController::class, 'index']);
    Route::get('acidity_types/{acidity_type}', [App\Http\Controllers\API\AcidityTypeAPIController::class, 'show'])->name('acidity_types.show');

    Route::get('home_plant_pot_sizes', [App\Http\Controllers\API\HomePlantPotSizeAPIController::class, 'index']);
    Route::get('home_plant_pot_sizes/{home_plant_pot_size}', [App\Http\Controllers\API\HomePlantPotSizeAPIController::class, 'show'])->name('home_plant_pot_sizes.show');

    Route::get('report_types', [App\Http\Controllers\API\ReportTypeAPIController::class, 'index']);
    Route::get('report_types/{report_type}', [App\Http\Controllers\API\ReportTypeAPIController::class, 'show'])->name('report_types.show');
    // end routes for users and admins

    // * new
    // Business
    Route::resource('businesses', App\Http\Controllers\API\BusinessAPIController::class);
    Route::get('businesses/relations/index/{business_field?}', [App\Http\Controllers\API\BusinessAPIController::class, 'getRelations']);
    //get weather and auth farms
    Route::post('users/businesses/index', [App\Http\Controllers\API\BusinessAPIController::class, 'user_businesses']);
    Route::post('users/today_tasks/index', [App\Http\Controllers\API\BusinessAPIController::class, 'user_today_tasks']);
    // BUSIENSS ROLES
    Route::post('businesses/roles/store', [App\Http\Controllers\API\BusinessAPIController::class, 'update_business_role'])->name('businesses.roles.store');
    Route::get('businesses/roles/store/{user}/{role}/{business}/{start_date?}/{end_date?}', [App\Http\Controllers\API\BusinessAPIController::class, 'first_attach_business_role'])->name('businesses.roles.first_attach');
    Route::get('businesses/roles/decline/{user}/{role}/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'decline_business_invitation'])->name('businesses.roles.decline_business_invitation');

    Route::get('businesses/users/index/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'get_business_users'])->name('businesses.users.index');
    Route::get('businesses/app_users/index/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'app_users']);
    Route::get('businesses/app_roles/index', [App\Http\Controllers\API\BusinessAPIController::class, 'app_roles']);

    Route::get('businesses/farms/index/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'get_business_farms'])->name('businesses.farms.index');
    Route::get('businesses/products/index/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'get_business_products'])->name('businesses.products.index');
    Route::get('business_with_posts/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'get_business_with_posts'])->name('businesses.posts.index');
    Route::get('businesses/videos/index/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'get_business_videos'])->name('businesses.videos.index');
    Route::get('businesses/stories/index/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'get_business_stories'])->name('businesses.stories.index');

    // FOLLOW
    Route::get('businesses/toggle_follow/{business}', [App\Http\Controllers\API\BusinessAPIController::class, 'toggle_follow']);

    Route::post('farms/user/weather/index', [App\Http\Controllers\API\FarmAPIController::class, 'get_weather'])->name('farms.users.weather.index');
    Route::get('farms/archived/index', [App\Http\Controllers\API\FarmAPIController::class, 'getArchived'])->name('farms.get_archived');

    // REPORT
    Route::get('farm_reports/relations/index', [App\Http\Controllers\API\FarmReportAPIController::class, 'getRelations'])->name('farm_reports.getRelations');
    Route::resource('farm_reports', App\Http\Controllers\API\FarmReportAPIController::class)->except(['update']);
    Route::match(['put', 'patch','post'], 'farm_reports/{farm_report}', [App\Http\Controllers\API\FarmReportAPIController::class, 'update'])->name('farm_reports.update');
    Route::get('farms/reports/index/{farm}', [App\Http\Controllers\API\FarmAPIController::class, 'farm_with_reports'])->name('farm_with_reports');
    Route::get('report_tasks/{farm_report}', [App\Http\Controllers\API\BusinessAPIController::class, 'report_tasks']);

    // TASKS
    Route::get('tasks/toggle_finish/{task}', [App\Http\Controllers\API\TaskAPIController::class, 'toggle_finish']);
    Route::resource('tasks', App\Http\Controllers\API\TaskAPIController::class);
    Route::get('tasks/relations/index/{task_type?}', [App\Http\Controllers\API\TaskAPIController::class, 'getRelations']);

    // Disease Registration
    Route::resource('disease_registrations', App\Http\Controllers\API\DiseaseRegistrationAPIController::class);
    Route::get('disease_registrations/relations/index', [App\Http\Controllers\API\DiseaseRegistrationAPIController::class, 'getRelations']);
        // admin
        Route::get('disease_registrations/toggle_confirm/{disease_registration}', [App\Http\Controllers\API\DiseaseRegistrationAPIController::class, 'toggle_confirm']);


    // Fertilizers & Insecticides (admin)
    Route::resource('insecticides', App\Http\Controllers\API\InsecticideAPIController::class)->except('update');
    Route::match(['put', 'patch','post'], 'insecticides/{insecticide}', [App\Http\Controllers\API\InsecticideAPIController::class, 'update'])->name('insecticides.update');
    Route::resource('fertilizers', App\Http\Controllers\API\FertilizerAPIController::class)->except('update');
    Route::match(['put', 'patch','post'], 'fertilizers/{fertilizer}', [App\Http\Controllers\API\FertilizerAPIController::class, 'update'])->name('fertilizers.update');
    Route::get('insecticides/relations/index', [App\Http\Controllers\API\InsecticideAPIController::class, 'getRelations']);
    Route::get('fertilizers/relations/index', [App\Http\Controllers\API\FertilizerAPIController::class, 'getRelations']);

    // GOALS & STEPS
    Route::resource('business_parts', App\Http\Controllers\API\BusinessPartAPIController::class);
    Route::get('business_parts_by_business_id/{type}/{business}', [App\Http\Controllers\API\BusinessPartAPIController::class, 'business_parts_by_business_id']);
    Route::get('business_parts/toggle_finish/{business_part}', [App\Http\Controllers\API\BusinessPartAPIController::class, 'toggle_finish']);

    // CONSULTANCY
    Route::resource('consultancy_profiles', App\Http\Controllers\API\ConsultancyProfileAPIController::class);
    Route::get('my_consultancy_profile', [App\Http\Controllers\API\ConsultancyProfileAPIController::class, 'mine']);
    Route::get('user_consultancy_profile/{user}', [App\Http\Controllers\API\ConsultancyProfileAPIController::class, 'user_consultancy_profile']);
    Route::delete('my_consultancy_profile', [App\Http\Controllers\API\ConsultancyProfileAPIController::class, 'delete_mine']);
    Route::match(['put', 'patch','post'], 'update_my_consultancy_profile', [App\Http\Controllers\API\ConsultancyProfileAPIController::class, 'update_my_consultancy_profile']);
    Route::get('consultancy_profiles/relations/index', [App\Http\Controllers\API\ConsultancyProfileAPIController::class, 'getRelations']);


    Route::resource('business_fields', App\Http\Controllers\API\BusinessFieldAPIController::class);

    Route::resource('business_branches', App\Http\Controllers\API\BusinessBranchAPIController::class);

    Route::resource('rating_questions', App\Http\Controllers\API\RatingQuestionAPIController::class);

    Route::resource('offline_consultancy_plans', App\Http\Controllers\API\OfflineConsultancyPlanAPIController::class);

    Route::resource('work_fields', App\Http\Controllers\API\WorkFieldAPIController::class);

    Route::resource('transactions', App\Http\Controllers\API\TransactionAPIController::class);

    Route::resource('education', App\Http\Controllers\API\EducationAPIController::class);

    Route::resource('careers', App\Http\Controllers\API\CareerAPIController::class);

    Route::resource('residences', App\Http\Controllers\API\ResidenceAPIController::class);

    Route::resource('visiteds', App\Http\Controllers\API\VisitedAPIController::class);

    Route::resource('irrigation_rates', App\Http\Controllers\API\IrrigationRateAPIController::class);

    Route::resource('infection_rates', App\Http\Controllers\API\InfectionRateAPIController::class);

    Route::resource('pathogen_growth_stages', App\Http\Controllers\API\PathogenGrowthStageAPIController::class);

    Route::resource('pathogen_types', App\Http\Controllers\API\PathogenTypeAPIController::class);

    Route::resource('countries', App\Http\Controllers\API\CountryAPIController::class);

    Route::resource('nut_elem_values', App\Http\Controllers\API\NutElemValueAPIController::class);

    Route::resource('farmed_type_nut_vals', App\Http\Controllers\API\FarmedTypeNutValAPIController::class);

    Route::resource('farmed_type_fertilization_needs', App\Http\Controllers\API\FarmedTypeFertilizationNeedAPIController::class);

    Route::resource('farmed_type_extras', App\Http\Controllers\API\FarmedTypeExtrasAPIController::class);

    Route::resource('marketing_datas', App\Http\Controllers\API\MarketingDataAPIController::class);

    Route::resource('taxonomies', App\Http\Controllers\API\TaxonomyAPIController::class);

    Route::resource('acs', App\Http\Controllers\API\AcAPIController::class);

    Route::resource('diseases', App\Http\Controllers\API\DiseaseAPIController::class);

    Route::resource('disease_causatives', App\Http\Controllers\API\DiseaseCausativeAPIController::class);

    Route::resource('pathogens', App\Http\Controllers\API\PathogenAPIController::class);

    Route::resource('product_types', App\Http\Controllers\API\ProductTypeAPIController::class);

    Route::resource('product_ads', App\Http\Controllers\API\ProductAdAPIController::class);
});

// ROUTES DON'T NEED LOGIN AS THEY ARE USED IN REGISTRATION
Route::get('human_jobs', [App\Http\Controllers\API\HumanJobAPIController::class, 'index'])->name('human_jobs.index');
Route::get('information/{information}', [App\Http\Controllers\API\InformationAPIController::class, 'show'])->name('information.show');
// Route::post('forget_password', [App\Http\Controllers\API\UserAPIController::class, 'forgetPassword'])->name('auth.forgetPassword');
// Route::post('reset_password', [App\Http\Controllers\API\UserAPIController::class, 'resetPassword'])->name('auth.resetPassword');
Route::post('forget_password', [App\Http\Controllers\AuthController2::class, 'forgetPassword'])->name('auth.forgetPassword');
Route::post('reset_password', [App\Http\Controllers\AuthController2::class, 'resetPassword'])->name('auth.resetPassword');


Route::resource('settings', App\Http\Controllers\API\SettingAPIController::class);
