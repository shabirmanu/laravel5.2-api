<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//use Zizaco\Entrust\Entrust;

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::resource('users', 'UserAPIController');
        Route::get('rlogin', 'UserAPIController@rlogin');
        Route::get('rregister', 'UserAPIController@rregister');
    });
});





Route::group(['middleware' =>['has_perm:administer-permissions']], function() {
    Route::resource('permissions', 'PermissionController');
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::get('people/permission', 'PermissionController@assignPermission');
    Route::post('people/permission', 'PermissionController@storePerm');
});







//Entrust::routeNeedsPermission('people/permission', 'create-users');


