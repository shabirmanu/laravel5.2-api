<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all API routes are defined.
|
*/


Route::get('articles/delegate', 'ArticleAPIController@delegateArticles');
Route::get('articles/{user}', 'ArticleAPIController@userArticles');
Route::resource('tasks', 'TaskAPIController');

Route::resource('articles', 'ArticleAPIController');