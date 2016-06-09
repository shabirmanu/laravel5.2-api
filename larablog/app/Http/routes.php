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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('rlogin', 'Rauth\AuthController@authenticate');



Route::get('about', 'PagesController@about');
Route::resource('articles', 'ArticlesController');

Route::group(['middleware' => 'custom_auth'], function () {
    Route::auth();
});

Route::get('/home', 'HomeController@index');


/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {

        require('api_routes.php');
    });
});


Route::resource('tasks', 'TaskController');