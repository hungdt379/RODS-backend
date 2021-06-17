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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('test', 'NotificationController@testSaveDataToFireBase');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('login/table', 'AuthController@loginForTable');
    Route::group([
        'middleware' => 'auth.jwt'
    ], function () {
        Route::get('logout', 'AuthController@logout');
    });
});

Route::group([
    'prefix' => 'customer'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('categories', 'CategoryController@getAllCategory');
        Route::get('menu', 'MenuController@getMenu');
        Route::get('menu/item/detail', 'MenuController@getDetailItem');
        Route::post('feedback', 'FeedbackController@addfeedback');
        Route::post('call/waiter', 'NotificationController@callWaiterNotification');
    });

});

Route::group([
    'prefix' => 'waiter'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('tables', 'UserController@getlistTable');
        Route::post('table/open', 'UserController@openTable');
        Route::post('table/close', 'UserController@closeTable');
        Route::post('table/update', 'UserController@updateNumberOfCustomer');
    });
});

Route::group([
    'prefix' => 'receptionist'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('feedback', 'FeedbackController@getAllFeedback');
    });
});


Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('search', 'MenuController@searchItem');
});







