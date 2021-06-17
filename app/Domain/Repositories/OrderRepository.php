<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Order;
use App\Domain\Entities\QueueOrder;



class OrderRepository
{
    public function checkExistingOrderInTable($tableID)
    {
        return Order::where('status', Order::ORDER_STATUS_CONFIRMED)
            ->where('table_id', $tableID)->get();
    }


    public function insert($model)
    {
        return $model->save();
    }

    public function getQueueOrderByTableID($tableID)
    {
        return QueueOrder::where([['status', QueueOrder::QUEUE_ORDER_STATUS_QUEUED], ['table_id', $tableID]])
            ->get();
    }

}
