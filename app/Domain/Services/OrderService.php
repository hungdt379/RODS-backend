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

    public function checkExistQueueOrderInTable($tableID)
    {
        $order = $this->orderRepository->getQueueOrderByTableID($tableID)->toArray();
        if ($order == []) {
            return false;
        }
        return true;
    }

    public function insertToQueueOrder($param)
    {
        $queueOrder = new QueueOrder();

        $queueOrder->number_of_customer = $param['number_of_customer'];
        $queueOrder->table_id = $param['table_id'];
        $queueOrder->table_name = $param['table_name'];
        $queueOrder->status = $param['status'];
        $queueOrder->combo = json_decode($param['combo']);
        $queueOrder->side_dish_drink = json_decode($param['side_dish_drink']);
        $queueOrder->total_cost = $param['total_cost'];
        $queueOrder->note = $param['note'];
        $queueOrder->ts = time();

        return $this->orderRepository->insert($queueOrder);
    }

    public function getQueueOrderByTableID($tableID)
    {
        return $this->orderRepository->getQueueOrderByTableID($tableID);
    }

}
