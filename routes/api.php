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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('login/table', 'AuthController@loginForTable');
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group([
    'prefix' => 'customer'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('categories', 'CategoryController@getAllCategory');
    });

});

Route::group([
    'prefix' => 'waiter'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('tables', 'UserController@getlistTable');
        Route::post('table/open', 'UserController@openTable');
    });

});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('hello', 'AuthController@hello');
});


