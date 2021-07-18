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
        '_id',
        'number_of_customer', 'table_id',
        'table_name', 'status',
        'item',
        'total_cost',
        'note',
        'voucher',
        'total_cost_of_voucher',
        'ts'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
