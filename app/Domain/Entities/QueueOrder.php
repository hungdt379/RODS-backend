<?php


namespace App\Domain\Entities;
use Jenssegers\Mongodb\Eloquent\Model;


class QueueOrder extends Model
{
    const QUEUE_ORDER_STATUS_QUEUED = 'queue';
    const QUEUE_ORDER_STATUS_MERGE = 'merge';

    protected $connection = 'mongodb';
    protected $collection = 'queue_order';

    protected $fillable = [
        '_id',
        'number_of_customer', 'table_id',
        'table_name', 'status',
        'item',
        'total_cost',
        'ts'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
