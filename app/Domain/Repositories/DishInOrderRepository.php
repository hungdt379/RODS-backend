<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\DishInOrder;

class DishInOrderRepository
{
    public function insert($dishInOrder)
    {
        return $dishInOrder->save();
    }

    public function getDishInOrder($tableID, $categoryID, $pageSize)
    {
        return DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)
            ->where('table_id', $tableID)
            ->whereIn('category_id', $categoryID)
            ->paginate((int)$pageSize);
    }

    public function getDishInOrderByID($id)
    {
        return DishInOrder::where('_id', $id)->first();
    }

    public function getAllDishInOrderByTableID($tableID)
    {
        return DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)
            ->where('table_id', $tableID)->get();
    }

    public function update($dishInOrder)
    {
        return $dishInOrder->update();
    }

}
