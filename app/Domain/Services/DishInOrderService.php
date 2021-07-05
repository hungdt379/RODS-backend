<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Repositories\DishInOrderRepository;

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

    public function getDishInOrder($tableID, $categoryID, $pageSize){
        return $this->dishInOrderRepository->getDishInOrder($tableID, $categoryID, $pageSize);
    }

    public function updateStatus($id){
        $dishInOrder = $this->dishInOrderRepository->getDishInOrderByID($id);
        $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_COMPLETED;
        return $this->dishInOrderRepository->update($dishInOrder);
    }
}
