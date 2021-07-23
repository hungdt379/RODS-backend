<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class DishInOrder extends Model
{
    const ORDER_ITEM_STATUS_PREPARE = 'prepare';
    const ORDER_ITEM_STATUS_COMPLETED = 'completed';

    protected $connection = 'mongodb';
    protected $collection = 'dish_in_order';

    protected $fillable = [
        '_id', 'order_id', 'table_id', 'table_name', 'item_id', 'item_name', 'quantity', 'status', 'category_id', 'ts'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
