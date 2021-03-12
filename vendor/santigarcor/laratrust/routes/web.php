<?php

use Illuminate\Support\Facades\Route;

Route::resource('/permissions', 'PermissionsController', ['as' => 'laratrust']);

Route::resource('/teams', 'TeamsController', ['as' => 'laratrust']);

Route::resource('/roles', 'RolesController', ['as' => 'laratrust']);

Route::resource('/roles-assignment', 'RolesAssignmentController', ['as' => 'laratrust'])
->only(['index', 'edit', 'update']);