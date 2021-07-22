<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\DishInOrder;

class DishInOrderRepository
{
    public function insert($dishInOrder)
    {
        return $dishInOrder->save();
    }

    public function getDishInOrder($categoryID, $page, $pageSize)
    {
        return DishInOrder::raw(function ($collection) use ($categoryID, $page, $pageSize) {
            return $collection->aggregate([
                [
                    '$addFields' => [
                        'cat_id' => ['$toObjectId' => '$category_id']
                    ]
                ],
                [
                    '$match' => [
                        'category_id' => ['$in' => $categoryID]
                    ]
                ],
                [
                    '$skip' => ((int)$page - 1) * (int)$pageSize
                ],
                [
                    '$limit' => (int)$pageSize
                ],
                [
                    '$lookup' => [
                        'as' => 'category',
                        'from' => 'category',
                        'foreignField' => '_id',
                        'localField' => 'cat_id'
                    ]
                ],
                [
                    '$sort' => ['ts' => -1]
                ]

            ]);
        });

    }

    public function getTotalDishInOrder($categoryID)
    {
        return DishInOrder::raw(function ($collection) use ($categoryID) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'category_id' => ['$in' => $categoryID]
                    ]
                ],
            ]);
        });
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
