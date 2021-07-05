<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Entities\Order;
use App\Domain\Repositories\OrderRepository;


class OrderService
{
    private $orderRepository;
    private $dishInOrderService;

    /**
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     * @param DishInOrderService $dishInOrderService
     */
    public function __construct(OrderRepository $orderRepository, DishInOrderService $dishInOrderService)
    {
        $this->orderRepository = $orderRepository;
        $this->dishInOrderService = $dishInOrderService;
    }

    public function getConfirmOrderByTableID($tableID)
    {
        return $this->orderRepository->getConfirmOrder($tableID);
    }

    public function getAllConfirmOrder($pageSize)
    {
        return $this->orderRepository->getAllConfirmOrder($pageSize);
    }

    public function addNewConfirmOrder($queueOrder)
    {
        $confirmOrder = new Order();
        $item = $queueOrder->item;

        $confirmOrder->table_id = $queueOrder->table_id;
        $confirmOrder->table_name = $queueOrder->table_name;
        $confirmOrder->number_of_customer = $queueOrder->number_of_customer;
        $confirmOrder->status = Order::ORDER_STATUS_CONFIRMED;
        $confirmOrder->item = $item;
        $confirmOrder->total_cost = $queueOrder->total_cost;
        $confirmOrder->ts = time();

        $this->insertToDishInOrder($queueOrder);

        return $this->orderRepository->insert($confirmOrder);
    }

    public function mergeOrder($queueOrder, $confirmOrder)
    {
        $item = array_merge($confirmOrder['item'], $queueOrder['item']);
        $totalCost = 0;
        $length = count($item);

        $this->insertToDishInOrder($queueOrder);

        for ($i = 0; $i < $length; $i++) {
            for ($j = $i + 1; $j < $length; $j++) {
                if ($item[$i]['item_id'] == $item[$j]['item_id']) {
                    $item[$i]['quantity'] += $item[$j]['quantity'];
                    $item[$i]['total_cost'] += $item[$j]['total_cost'];
                    $item[$i]['dish_in_combo'] = $item[$j]['dish_in_combo'];
                    unset($item[$j]);
                    $item = array_values($item);
                    $length--;
                }
            }
        }

        foreach ($item as $value) {
            $totalCost += $value['total_cost'];
        }
        $confirmOrder['item'] = array_values($item);
        $confirmOrder['total_cost'] = $totalCost;

        return $this->orderRepository->update($confirmOrder);
    }

    public function insertToDishInOrder($queueOrder)
    {
        foreach ($queueOrder['item'] as $value) {
            $dishInOrder = new DishInOrder();
            $dishInOrder->table_id = $queueOrder->table_id;
            $dishInOrder->table_name = $queueOrder->table_name;
            $dishInOrder->item_name = $value['detail_item']['name'];
            $dishInOrder->quantity = $value['quantity'];
            $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_PREPARE;
            $dishInOrder->category_id = $value['detail_item']['category_id'];
            if ($value['dish_in_combo'] != null) {
                $dishInCombo = $value['dish_in_combo'];
                $length = count($dishInCombo);
                for ($i = 0; $i < $length; $i++) {
                    $dishInOrder = new DishInOrder();
                    $dishInOrder->table_id = $queueOrder->table_id;
                    $dishInOrder->table_name = $queueOrder->table_name;
                    $dishInOrder->item_name = $value['dish_in_combo'][$i];
                    $dishInOrder->quantity = $value['quantity'];
                    $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_PREPARE;
                    $dishInOrder->category_id = $value['detail_item']['category_id'];
                    $this->dishInOrderService->insert($dishInOrder);
                }
            }
            $this->dishInOrderService->insert($dishInOrder);
        }
    }

    public function deleteItemInConfirmOrder($confirmOrder, $itemID)
    {
        $item = $confirmOrder['item'];
        for ($i = 0; $i < count($item); $i++) {
            for ($j = 0; $j < count($itemID); $j++) {
                if ($item[$i]['item_id'] == $itemID[$j]) {
                    unset($item[$i]);
                    $item = array_values($item);
                }
            }
        }
        $confirmOrder['item'] = $item;

        return $this->orderRepository->update($confirmOrder);
    }

    public function addNoteForRemainItem($id, $note){
        $confirmOrder = $this->orderRepository->getConfirmOrderByID($id);
        $confirmOrder->note = $note;

        return $this->orderRepository->update($confirmOrder);
    }
}
