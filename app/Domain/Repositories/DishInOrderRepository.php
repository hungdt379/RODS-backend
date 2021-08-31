<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\DishInOrder;

class DishInOrderRepository
{
    public function insert($dishInOrder)
    {
        return $dishInOrder->save();
    }

    public function getDishInOrder($categoryID, $page, $pageSize, $status)
    {
        return DishInOrder::raw(function ($collection) use ($categoryID, $page, $pageSize, $status) {
            return $collection->aggregate([
                [
                    '$addFields' => [
                        'cat_id' => ['$toObjectId' => '$category_id']
                    ]
                ],
                [
                    '$match' => [
                        'category_id' => ['$in' => $categoryID],
                        'status' => $status
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
                    '$sort' => ['ts' => 1]
                ]

            ]);
        });

    }

    public function getTotalDishInOrder($categoryID, $status)
    {
        return DishInOrder::raw(function ($collection) use ($categoryID, $status) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'category_id' => ['$in' => $categoryID],
                        'status' => $status
                    ]
                ],
            ]);
        });
    }

    public function getDishInOrderByID($id)
    {
        return DishInOrder::where('_id', $id)->first();
    }

    public function getListDishInOrderByID($id)
    {
        return DishInOrder::whereIn('_id', $id)->get();
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

    public function delete($id)
    {
        DishInOrder::where('_id', $id)->delete();
    }

    public function getDishInOrderByTableID($categoryID, $status, $tableID)
    {
        return DishInOrder::raw(function ($collection) use ($categoryID, $status, $tableID) {
            return $collection->aggregate([
                [
                    '$addFields' => [
                        'cat_id' => ['$toObjectId' => '$category_id']
                    ]
                ],
                [
                    '$match' => [
                        'category_id' => ['$in' => $categoryID],
                        'status' => $status,
                        'table_id' => $tableID
                    ]
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
                    '$sort' => ['ts' => 1]
                ]

            ]);
        });

    }

    public function getAllDishInOrderByTableIDCompleted($tableID)
    {
        return DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_COMPLETED)
            ->where('table_id', $tableID)->get();
    }

}
