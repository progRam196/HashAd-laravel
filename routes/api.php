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

Route::group(['middleware' => ['cors']], function () {
Route::post('settings', 'SettingController@index');
Route::post('user/password/resetlink', 'Auth\ResetPasswordController@sendResetLink');
Route::post('user/password/resetlink/', 'Auth\ResetPasswordController@sendResetLink');
Route::post('user/password/reset', 'Auth\ResetPasswordController@reset');
Route::post('ad/list', 'AdController@index');
Route::post('inquiry', 'InquiryController@create');
});
Route::group(['middleware' => ['jwt.auth','cors']], function () {
Route::post('hashtag/list', 'HashtagController@index');
Route::post('hashtag/trending', 'HashtagController@trending');
Route::post('ad/list/{id}', 'AdController@userBasedList');
Route::post('ad/create', 'AdController@create');
Route::post('ad/edit/{id}', 'AdController@edit');
Route::post('ad/detail/{id}', 'AdController@show');
Route::delete('ad/delete/{id}', 'AdController@destroy');
Route::post('fav/update', 'FavouriteController@create');
Route::post('fav/update-view-count', 'AdController@updateViewCount');
Route::post('user/update-follow', 'FollowerController@create');
Route::post('user/follow-list', 'FollowerController@index');
Route::post('user/subscriptions', 'HashtagSubscriberController@index');
Route::post('fav/subscribe', 'HashtagSubscriberController@create');
Route::post('user/notify-list', 'NotificationController@index');
Route::post('user/notify-update', 'NotificationController@update');
Route::post('message/send', 'MessageController@create');
Route::post('conversation/list', 'MessageController@index');
Route::post('message/list/{id}', 'MessageController@show');
Route::delete('conversation/delete/{id}', 'MessageController@destroy');
Route::post('city-list', 'CityController@index');
Route::post('hashtag/create', 'HashtagController@create');
Route::post('user/profile', 'UserController@index');
Route::post('user/profile/{id}', 'UserController@show');
Route::post('user/update-profile', 'UserController@update');
Route::post('ad/mylist', 'AdController@userAds');
Route::post('user/verify-token', 'UserController@verifyToken');
});



Route::middleware(['auth:jwt','cors'])->get('/user', function (Request $request) {;
    return $request->user();
});
