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


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('login/table', 'AuthController@loginForTable');
    Route::group([
        'middleware' => 'auth.jwt'
    ], function () {
        Route::get('logout', 'AuthController@logout')->name('logout');
        Route::post('password/change', 'AuthController@changePassword');
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
        Route::post('cart/item/delete', 'CartController@deleteItemInCart')->name('delete-item-in-cart');


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
        Route::post('table/order/queue/confirm', 'QueueOrderController@confirmQueueOrder')->name('confirm-queue-order');
        Route::post('table/order/confirm/item/delete', 'OrderController@deleteItemInConfirmOrder');
        Route::post('table/order/queue/item/delete', 'QueueOrderController@deleteItemInQueueOrder');
        Route::get('table/active/false', 'UserController@getTableNotActive');
        Route::post('table/change', 'UserController@changeTable');
        Route::get('order/drink', 'DishInOrderController@getDrinkInOrder');
        Route::post('order/queue/item/quantity/update', 'QueueOrderController@updateQuantityOfItem');
        Route::post('table/queue/order/insert', 'QueueOrderController@insert');
        Route::get('table/order/dish', 'DishInOrderController@getDishInOrderByTableID');
        Route::get('table/dish/detail', 'MenuController@getDetailItemWaiter');
    });
});

Route::group([
    'prefix' => 'receptionist'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('table/new', 'UserController@addNewTable');
        Route::post('table/update', 'UserController@updateTable');
        Route::get('table/delete', 'UserController@deleteTable');
        Route::get('table/qrcode', 'UserController@generateNewQrCode');
        Route::get('feedback', 'FeedbackController@getAllFeedback');
        Route::get('notifications', 'NotificationController@getAllNotification');
        Route::get('notifications/read', 'NotificationController@markAsRead');
        Route::post('order/confirm/voucher/add', 'OrderController@addVoucherToConfirmOrder');
        Route::post('order/confirm/item/quantity/update', 'OrderController@updateQuantityOfItem');
        Route::get('order/completed/view', 'OrderController@getCompletedOrderByTableID');
        Route::get('order/completed/list', 'OrderController@getListCompleteOrder');
        Route::post('order/confirm/match', 'OrderController@matchingConfirmOrder');
        Route::post('order/export/bill', 'OrderController@exportBill');
    });
});

Route::group([
    'prefix' => 'kitchen'
], function () {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('notifications', 'NotificationController@getAllNotification');
        Route::get('notifications/read', 'NotificationController@markAsRead');
        Route::get('order/dish', 'DishInOrderController@getDishInOrder');
        Route::get('items/all', 'MenuController@getAllItem')->name('all-item');
        Route::get('items/update/sold', 'MenuController@updateItemSoldOutStatus');
        Route::post('item/export/many', 'DishInOrderController@matchingDishInOrder');
    });
});



Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('order/dish/status/update', 'DishInOrderController@updateStatus');
    Route::post('order/drink/status/update', 'DishInOrderController@updateStatus');
    Route::get('search', 'MenuController@searchItem');
    Route::get('table/order/confirm/detail', 'OrderController@viewDetailConfirmOrder')->name('detail-confirm-order');
    Route::get('waiter/table/order/confirm/detail/waiter', 'OrderController@viewDetailConfirmOrderWaiter');
    Route::get('table/order/detail', 'OrderController@getOrderByID');
    Route::get('order/confirm/list', 'OrderController@getListConfirmOrder');
    Route::post('order/dish/delete', 'DishInOrderController@deleteDishInOrder');
});







