<?php


namespace App\Domain\Services;


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
}
