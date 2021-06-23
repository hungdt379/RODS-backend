<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Notification extends Model
{
    const TITLE_CALL_WAITER = 'Gọi phục vụ';
    const TITLE_CALL_PAYMENT = 'Gọi thanh toán';
    const TITLE_CONFIRMED_ORDER = 'Nhân viên đã xác nhận món mới';
    const CUSTOMER_TITLE = [
        self::TITLE_CALL_WAITER,
        self::TITLE_CALL_PAYMENT,
    ];

    const RECEIVER_WAITER = 'waiter';
    const RECEIVER_RECEPTIONIST = 'receptionist';
    const RECEIVER_KITCHEN_MANAGER = 'kitchen manager';
    const RECEIVER_ALL_NOTIFICATION = [
        self::RECEIVER_RECEPTIONIST,
        self::RECEIVER_KITCHEN_MANAGER
    ];

    const REFERENCE_CALL_WAITER = 'call_waiter/';
    const REFERENCE_CALL_PAYMENT = 'call_payment/';
    const REFERENCE_CONFIRMED_ORDER = 'confirmed_order/';

    protected $connection = 'mongodb';
    protected $collection = 'notification';

    protected $fillable = [
        '_id', 'user_id','user_fullname', 'read', 'title', 'content', 'receiver','ts'
    ];
}
