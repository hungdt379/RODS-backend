<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Order;


class OrderRepository
{
    public function getConfirmOrderByID($orderID)
    {
        return Order::where('status', Order::ORDER_STATUS_CONFIRMED)
            ->where('_id', $orderID)->first();
    }

    public function getOrderByID($id)
    {
        return Order::where('_id', $id)->first();
    }

    public function getConfirmOrder($tableID)
    {
        return Order::where('status', Order::ORDER_STATUS_CONFIRMED)
            ->where('table_id', $tableID)->first();
    }

    public function getCompletedOrderByID($orderID)
    {
        return Order::where('status', Order::ORDER_STATUS_COMPLETED)
            ->where('_id', $orderID)->first();
    }

    public function getListConfirmOrderByTableID($tableID)
    {
        return Order::where('status', Order::ORDER_STATUS_CONFIRMED)
            ->whereIn('table_id', $tableID)->get();
    }

    public function getAllConfirmOrder($pageSize)
    {
        return Order::whereIn('status', [Order::ORDER_STATUS_CONFIRMED, Order::ORDER_STATUS_UNPAID])
            ->orderBy('ts', 'DESC')
            ->paginate((int)$pageSize);
    }

    public function getAllCompleteOrder($pageSize)
    {
        return Order::where('status', Order::ORDER_STATUS_COMPLETED)
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

    public function getMatchingOrder($id)
    {
        return Order::where('status', Order::ORDER_STATUS_MATCHING)->where('_id', $id)->first();
    }

    public function getOrderOfMatchingOrder($arrOrderID)
    {
        return Order::whereIn('_id', $arrOrderID)->get();
    }

    public function getMaxOrderCode()
    {
        return Order::max('numerical_order');
    }

    public function deleteConfirmOrder($id)
    {
        return Order::where('_id', $id)->delete();
    }
}
