<?php


namespace App\Domain\Services;


use App\Domain\Entities\Order;
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

    public function insertToOrder($param)
    {
        $order = new Order();

        $order->number_of_customer = $param['number_of_customer'];
        $order->table_id = $param['table_id'];
        $order->table_name = $param['table_name'];
        $order->status = $param['status'];
        $order->combo = json_decode($param['combo']);
        $order->side_dish_drink = json_decode($param['side_dish_drink']);
        $order->total_cost = $param['total_cost'];
        $order->note = $param['note'];
        $order->ts = time();

        return $this->orderRepository->insert($order);
    }


}
