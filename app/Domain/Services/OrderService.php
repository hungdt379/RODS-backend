<?php


namespace App\Domain\Services;


use App\Domain\Entities\Order;
use App\Domain\Entities\QueueOrder;
use App\Domain\Repositories\OrderRepository;


class OrderService
{
    private $orderRepository;

    /**
     * OrderService constructor.
     * @param $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

}
