<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    const ORDER_STATUS_CONFIRMED = 'confirmed';
    const ORDER_STATUS_COMPLETED = 'completed';
    const ORDER_STATUS_MATCHING = 'matching';

    protected $connection = 'mongodb';
    protected $collection = 'order';

    protected $fillable = [
        '_id', 'arr_order_id', 'numerical_order', 'order_code',
        'number_of_customer', 'table_id',
        'table_name', 'status',
        'item',
        'total_cost',
        'note',
        'voucher',
        'total_cost_of_voucher',
        'new_total_cost',
        'ts',
        'done_dish'
    ];

    protected $hidden = ['arr_order_id', 'updated_at', 'created_at'];
}
