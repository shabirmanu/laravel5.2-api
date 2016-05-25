<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all API routes are defined.
|
*/


Route::resource('tasks', 'TaskAPIController');

Route::resource('articles', 'ArticleAPIController');