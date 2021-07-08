<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Order;


class OrderRepository
{
    public function getConfirmOrderByID($id)
    {
        return Order::where('_id', $id)->first();
    }

    public function getConfirmOrder($tableID)
    {
        return Order::where('status', Order::ORDER_STATUS_CONFIRMED)
            ->where('table_id', $tableID)->first();
    }

    public function getCompletedOrderByID($id)
    {
        return Order::where('status', Order::ORDER_STATUS_COMPLETED)
                ->where('_id', $id) ->first();
    }

    public function checkExistingOrderInTable($tableID)
    {
        return Order::where('status', Order::ORDER_STATUS_CONFIRMED)
            ->where('table_id', $tableID)->get();
    }

    public function getAllConfirmOrder($pageSize)
    {
        return Order::where('status', Order::ORDER_STATUS_CONFIRMED)
            ->orderBy('ts', 'DESC')
            ->paginate((int)$pageSize);
    }

    public function insert($confirmOrder)
    {
        return $confirmOrder->save();
    }

    public function update($confirmOrder)
    {
        return $confirmOrder->update();
    }
}
