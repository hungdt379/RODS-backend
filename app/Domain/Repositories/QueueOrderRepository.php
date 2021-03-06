<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\QueueOrder;


class QueueOrderRepository
{

    public function insert($queueOrder)
    {
        return $queueOrder->save();
    }

    public function getQueueOrderByTableID($tableID)
    {
        return QueueOrder::where([['status', QueueOrder::QUEUE_ORDER_STATUS_QUEUED], ['table_id', $tableID]])
            ->first();
    }

    public function getQueueOrderByID($queueOrderID){
        return QueueOrder::where([['status', QueueOrder::QUEUE_ORDER_STATUS_QUEUED], ['_id', $queueOrderID]])
            ->first();
}

    public function delete($id)
    {
        return QueueOrder::where('_id', $id)->delete();
    }

    public function update($queueOrder){
        return $queueOrder->update();
    }
}
