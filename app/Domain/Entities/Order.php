<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    const ORDER_STATUS_CONFIRMED = 'confirmed';
    const ORDER_STATUS_COMPLETED = 'completed';

    protected $connection = 'mongodb';
    protected $collection = 'order';

    protected $fillable = [
        '_id',
        'number_of_customer', 'table_id',
        'table_name', 'status',
        'item',
        'total_cost',
        'ts'
    ];
}
