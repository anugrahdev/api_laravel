<?php

use Illuminate\Http\Request;

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

Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');
Route::post('logout', 'Api\AuthController@logout');
Route::post('save_user_info', 'Api\AuthController@saveUserInfo')->middleware('jwtAuth');


Route::group(['prefix' => 'post'], function () {

    //POST
    Route::get('posts', 'Api\PostController@index')->middleware('jwtAuth');
    Route::get('/{post}', 'Api\PostController@show')->middleware('jwtAuth');
    Route::post('/create', 'Api\PostController@create')->middleware('jwtAuth');
    Route::delete('/delete/{post}', 'Api\PostController@delete')->middleware('jwtAuth');
    Route::put('/update/{post}', 'Api\PostController@update')->middleware('jwtAuth');
});


//COMMENT
Route::get('comments', 'Api\CommentController@index')->middleware('jwtAuth');
// Route::get('post/{post}', 'Api\PostController@show')->middleware('jwtAuth');
Route::post('comment/create', 'Api\CommentController@store')->middleware('jwtAuth');
Route::delete('comment/delete/{comment}', 'Api\CommentController@destroy')->middleware('jwtAuth');
Route::put('comment/update/{comment}', 'Api\CommentController@update')->middleware('jwtAuth');

//Like
Route::post('post/like', 'Api\LikeController@like')->middleware('jwtAuth');

Route::get('images/{type}/{fileName}', 'Api\AuthController@getImageProfile');
