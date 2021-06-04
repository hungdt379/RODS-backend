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
    Route::post('login-table', 'AuthController@loginForTable');
    Route::group([
        'middleware' => 'auth:api'
            ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('hello', 'AuthController@hello');
});

Route::group([
    'prefix' => 'group'
        ], function () {
    Route::group([
        'middleware' => 'auth:api'
            ], function() {
        Route::post('/bulkAction', 'GroupController@bulkAction');
        Route::get('', 'GroupController@index');
        Route::get('/{id}', 'GroupController@show');
        Route::post('', 'GroupController@store');
        Route::put('/{id}', 'GroupController@update');
        Route::delete('/{id}', 'GroupController@delete');
    });
});

Route::group([
    'prefix' => 'server'
        ], function () {
    Route::group([
        'middleware' => 'auth:api'
            ], function() {
        Route::post('/bulkAction', 'ServerController@bulkAction');
        Route::get('/getProvider', 'ServerController@getProvider');
        Route::get('', 'ServerController@index');
        Route::get('/{id}', 'ServerController@show');
        Route::post('', 'ServerController@store');
        Route::put('/{id}', 'ServerController@update');
        Route::delete('/{id}', 'ServerController@delete');
    });
});

Route::group([
    'prefix' => 'serverAccount'
        ], function () {
    Route::group([
        'middleware' => 'auth:api'
            ], function() {
        Route::get('', 'ServerAccountController@index');
        Route::post('/create', 'ServerAccountController@create');
        Route::post('/update', 'ServerAccountController@update');
        Route::delete('/delete/{id}', 'ServerAccountController@delete');
        Route::get('/detail/{id}', 'ServerAccountController@detail');
    });
});
