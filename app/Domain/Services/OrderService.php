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

    public function getConfirmOrderByTableID($tableID){
        return $this->orderRepository->getConfirmOrder($tableID);
    }

    public function AddNewConfirmOrder($queueOrder){
        $confirmOrder = new Order();

        $confirmOrder->table_id = $queueOrder->table_id;
        $confirmOrder->table_name = $queueOrder->table_name;
        $confirmOrder->number_of_customer = $queueOrder->number_of_customer;
        $confirmOrder->status = Order::ORDER_STATUS_CONFIRMED;
        $confirmOrder->item = $queueOrder->item;
        $confirmOrder->total_cost = $queueOrder->total_cost;
        $confirmOrder->ts = time();

        return $this->orderRepository->insert($confirmOrder);
    }

    public function MergeOrder($queueOrder, $confirmOrder){
        $item = array_merge($confirmOrder['item'], $queueOrder['item']);
        $totalCost  = 0;
        $length = count($item);


        for ($i = 0; $i < $length; $i++) {
            for ($j = $i+1; $j < $length; $j++){
                if($item[$i]['item_id'] == $item[$j]['item_id']){
                    $item[$i]['quantity'] += $item[$j]['quantity'];
                    $item[$i]['total_cost'] += $item[$j]['total_cost'];
                    $item[$i]['dish_in_combo'] = $item[$j]['dish_in_combo'];
                    unset($item[$j]);
                    $item=array_values($item);
                    $length--;
                }
            }
        }

        foreach ($item as $value){
            $totalCost += $value['total_cost'];
        }

        $confirmOrder['item'] = array_values($item);
        $confirmOrder['total_cost'] = $totalCost;

        return $this->orderRepository->update($confirmOrder);
    }
}
