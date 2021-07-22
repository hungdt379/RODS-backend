<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\DishInOrder;

class DishInOrderRepository
{
    public function insert($dishInOrder)
    {
        return $dishInOrder->save();
    }

    public function getDishInOrder($categoryID, $pageSize)
    {
        return DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)
            ->whereIn('category_id', $categoryID)
            ->orderBy('ts', 'DESC')
            ->paginate((int)$pageSize)
            ->join('category','category._id', '=', 'category_id');

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

    public function deleteMany($itemID, $tableID)
    {
        DishInOrder::where('table_id', $tableID)->where('item_id', $itemID)->delete();
    }

}
