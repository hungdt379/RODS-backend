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
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('login/table', 'AuthController@loginForTable');
    Route::group([
        'middleware' => 'auth.jwt'
    ], function () {
        Route::get('logout', 'AuthController@logout')->name('logout');
    });
});

Route::group([
    'prefix' => 'customer'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('categories', 'CategoryController@getAllCategory');
        Route::get('menu', 'MenuController@getMenu')->name('menu');
        Route::get('menu/item/detail', 'MenuController@getDetailItem');
        Route::post('feedback', 'FeedbackController@addfeedback');
        Route::post('order/send', 'QueueOrderController@sendOrder');
        Route::post('call/waiter', 'NotificationController@callWaiter')->name('callWaiter');
        Route::post('call/payment', 'NotificationController@callPayment');
        Route::get('cart', 'CartController@show');
        Route::post('cart/item/add', 'CartController@addItems');
        Route::post('cart/item/delete', 'CartController@deleteItemInCart');


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
        Route::get('table/notifications', 'NotificationController@getNotificationOfEachTable');
        Route::get('table/notifications/read', 'NotificationController@markAsReadOfWaiter');
        Route::get('table/id', 'UserController@getTableById');
        Route::get('table/order/queue/view', 'QueueOrderController@getQueueOrderByTableID');
        Route::post('table/order/queue/cancel', 'QueueOrderController@cancelQueueOrder');
        Route::post('table/order/queue/confirm', 'QueueOrderController@confirmQueueOrder');
    });
});

Route::group([
    'prefix' => 'receptionist'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('feedback', 'FeedbackController@getAllFeedback');
        Route::get('notifications', 'NotificationController@getAllNotification');
        Route::get('notifications/read', 'NotificationController@markAsRead');
    });
});

Route::group([
    'prefix' => 'kitchen'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('notifications', 'NotificationController@getAllNotification');
        Route::get('notifications/read', 'NotificationController@markAsRead');
    });
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('search', 'MenuController@searchItem');
    Route::get('table/order/confirm/detail', 'OrderController@viewDetailConfirmOrder');
});

Route::get('testPDF', 'AuthController@hello');







