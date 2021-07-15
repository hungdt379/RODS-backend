<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Repositories\DishInOrderRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class DishInOrderService
{
    private $dishInOrderRepository;

    /**
     * DishInOrderService constructor.
     * @param $dishInOrderRepository
     */
    public function __construct(DishInOrderRepository $dishInOrderRepository)
    {
        $this->dishInOrderRepository = $dishInOrderRepository;
    }

    public function insert($dishInOrder)
    {
        return $this->dishInOrderRepository->insert($dishInOrder);
    }

    public function getDishInOrder($categoryID, $pageSize)
    {
        return $this->dishInOrderRepository->getDishInOrder($categoryID, $pageSize);
    }

    public function getAllDishInOrderByTableID($tableID)
    {
        return $this->dishInOrderRepository->getAllDishInOrderByTableID($tableID);
    }

    public function updateDishInOrderToNewTable($dishInOrder, $toTable)
    {
        foreach ($dishInOrder as $value){
            $value->table_id = $toTable['_id'];
            $value->table_name = $toTable['full_name'];
            $this->dishInOrderRepository->update($value);
        }
    }

    public function getDishInOrderByID($dishInOrderID){
        return $this->dishInOrderRepository->getDishInOrderByID($dishInOrderID);
    }
    public function updateStatus($dishInOrder)
    {
        $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_COMPLETED;
        return $this->dishInOrderRepository->update($dishInOrder);
    }
}
