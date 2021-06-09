<?php


namespace App\Domain\Entities;


class QueueOrder
{
    const QUEUE_ORDER_STATUS_QUEUED = 'queued';
    const QUEUE_ORDER_STATUS_MERGE = 'merge';

    protected $connection = 'mongodb';
    protected $collection = 'queue_order';

    protected $fillable = [
        '_id',
        'number_of_customer',
        'table_name', 'status',
        'combo',
        'extra_combo',
        'side_dish_drink',
        'total_cost',
        'note',
        'ts'
    ];
}
