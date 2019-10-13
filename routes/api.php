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

Route::group(['prefix' => 'user'], function () {
    Auth::routes();
});

Route::post('user/password/resetlink', 'Auth\ResetPasswordController@sendResetLink');
Route::post('user/password/reset', 'Auth\ResetPasswordController@reset');



Route::post('ad/create', 'AdController@create');
Route::post('ad/list', 'AdController@index');

Route::post('hashtag/create', 'HashtagController@create');
Route::post('hashtag/list', 'HashtagController@index');
Route::post('hashtag/list', 'HashtagController@index');




Route::middleware('auth:jwt')->get('/user', function (Request $request) {;
    return $request->user();
});
