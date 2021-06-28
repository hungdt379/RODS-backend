<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Notification extends Model
{
    const TITLE_CALL_WAITER_VN = 'Gọi phục vụ';
    const TITLE_CALL_PAYMENT_VN = 'Gọi thanh toán';
    const TITLE_SEND_ORDER_VN = 'Đặt món';
    const TITLE_CONFIRMED_ORDER_VN = 'Nhân viên đã xác nhận món mới';

    const TITLE_CALL_WAITER_EN = 'call-waiter';
    const TITLE_CALL_PAYMENT_EN = 'call-payment';
    const TITLE_SEND_ORDER_EN = 'send-order';
    const TITLE_CONFIRMED_ORDER_EN = 'confirmed-order';

    const RECEIVER_WAITER = 'waiter';
    const RECEIVER_RECEPTIONIST = 'receptionist';
    const RECEIVER_KITCHEN_MANAGER = 'kitchen manager';
    const RECEIVER_ALL_NOTIFICATION = [
        self::RECEIVER_RECEPTIONIST,
        self::RECEIVER_KITCHEN_MANAGER
    ];

    protected $connection = 'mongodb';
    protected $collection = 'notification';

    protected $fillable = [
        '_id', 'user_id','user_fullname', 'read', 'title', 'content', 'receiver','ts'
    ];
}
