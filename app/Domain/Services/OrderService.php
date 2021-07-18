<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Entities\Order;
use App\Domain\Repositories\DishInOrderRepository;
use App\Domain\Repositories\OrderRepository;


class OrderService
{
    private $orderRepository;
    private $dishInOrderService;
    private $dishInOrderRepository;

    /**
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     * @param DishInOrderService $dishInOrderService
     * @param DishInOrderRepository $dishInOrderRepository
     */
    public function __construct(OrderRepository $orderRepository, DishInOrderService $dishInOrderService, DishInOrderRepository $dishInOrderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->dishInOrderService = $dishInOrderService;
        $this->dishInOrderRepository = $dishInOrderRepository;
    }

    public function getConfirmOrderByTableID($tableID)
    {
        return $this->orderRepository->getConfirmOrder($tableID);
    }

    public function getAllConfirmOrder($pageSize)
    {
        return $this->orderRepository->getAllConfirmOrder($pageSize);
    }

    public function getAllCompleteOrder($pageSize){
        return $this->orderRepository->getAllCompleteOrder($pageSize);
    }

    public function getConfirmOrderByID($orderID)
    {
        return $this->orderRepository->getConfirmOrderByID($orderID);
    }

    public function getCompletedOrderByID($orderID)
    {
        return $this->orderRepository->getCompletedOrderByID($orderID);
    }

    public function invoiceOrder($confirmOrder)
    {
        $confirmOrder->status = Order::ORDER_STATUS_COMPLETED;
        return $this->orderRepository->update($confirmOrder);
    }

    public function getListConfirmOrderByTableID($tableID)
    {
        return $this->orderRepository->getListConfirmOrderByTableID($tableID);
    }

    public function updateOrderToNewTable($fromOrder, $table)
    {
        $fromOrder->table_id = $table['_id'];
        $fromOrder->table_name = $table['full_name'];
        $fromOrder->number_of_customer = $table['number_of_customer'];

        return $this->orderRepository->update($fromOrder);
    }

    public function matchingConfirmOrder($listConfirmOrder)
    {
        $item = [];
        $totalCost = 0;
        $tableID = [];
        $tableName = [];
        $numberOfCustomer = 0;
        $note = '';
        $orderID = [];
        foreach ($listConfirmOrder as $confirmOrder) {
            if (isset($confirmOrder['note'])) {
                $note = $note . ', ' . $confirmOrder['note'];
            }
            $numberOfCustomer += (int)$confirmOrder['number_of_customer'];
            array_push($tableID, $confirmOrder['table_id']);
            array_push($tableName, $confirmOrder['table_name']);
            array_push($orderID, $confirmOrder['_id']);
            foreach ($confirmOrder['item'] as $value) {
                array_push($item, $value);
            }
        }
        $length = count($item);
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
        $newConfirmOrder = new Order();
        $newConfirmOrder->table_id = $tableID;
        $newConfirmOrder->table_name = $tableName;
        $newConfirmOrder->number_of_customer = $numberOfCustomer;
        $newConfirmOrder->status = Order::ORDER_STATUS_CONFIRMED;
        $newConfirmOrder->item = array_values($item);
        $newConfirmOrder->total_cost = $totalCost;
        $newConfirmOrder->total_cost = $totalCost;
        $newConfirmOrder->note = substr($note, 2);
        $newConfirmOrder->ts = time();

        $this->orderRepository->deleteConfirmOrderByID($orderID);
        return $this->orderRepository->insert($newConfirmOrder);
    }

    public function addNewConfirmOrder($queueOrder)
    {
        $confirmOrder = new Order();
        $item = $queueOrder['item'];

        $confirmOrder->table_id = $queueOrder['table_id'];
        $confirmOrder->table_name = $queueOrder['table_name'];
        $confirmOrder->number_of_customer = $queueOrder['number_of_customer'];
        $confirmOrder->status = Order::ORDER_STATUS_CONFIRMED;
        $confirmOrder->item = $item;
        $confirmOrder->total_cost = $queueOrder['total_cost'];
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
            $dishInOrder->table_id = $queueOrder['table_id'];
            $dishInOrder->table_name = $queueOrder['table_name'];
            $dishInOrder->item_id = $value['item_id'];
            $dishInOrder->item_name = $value['detail_item']['name'];
            $dishInOrder->quantity = $value['quantity'];
            $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_PREPARE;
            $dishInOrder->category_id = $value['detail_item']['category_id'];
            $dishInOrder->ts = time();
            if ($value['dish_in_combo'] != null) {
                $dishInCombo = $value['dish_in_combo'];
                $length = count($dishInCombo);
                for ($i = 0; $i < $length; $i++) {
                    $dishInOrder = new DishInOrder();
                    $dishInOrder->table_id = $queueOrder['table_id'];
                    $dishInOrder->table_name = $queueOrder['table_name'];
                    $dishInOrder->item_id = $value['item_id'];
                    $dishInOrder->item_name = $value['dish_in_combo'][$i];
                    $dishInOrder->quantity = 1;
                    $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_PREPARE;
                    $dishInOrder->category_id = $value['detail_item']['category_id'];
                    $this->dishInOrderService->insert($dishInOrder);
                    $dishInOrder->ts = time();
                }
            }
            $this->dishInOrderService->insert($dishInOrder);
        }
    }

    public function deleteItemInConfirmOrder($confirmOrder, $itemID)
    {
        $item = $confirmOrder['item'];
        $itemDeletedCost = 0;
        for ($i = 0; $i < count($item); $i++) {
            if ($item[$i]['item_id'] == $itemID) {
                $itemDeletedCost = $item[$i]['total_cost'];
                unset($item[$i]);
                $item = array_values($item);
            }
        }
        $confirmOrder['item'] = $item;
        $confirmOrder['total_cost'] -= $itemDeletedCost;

        $this->orderRepository->update($confirmOrder);
        $this->dishInOrderRepository->deleteMany($itemID, $confirmOrder['table_id']);
    }

    public function addNoteForRemainItem($confirmOrder, $note)
    {
        $confirmOrder->note = $note;

        return $this->orderRepository->update($confirmOrder);
    }

    public function addVoucherToConfirmOrder($confirmOrder, $voucher)
    {
        $confirmOrder->voucher = $voucher;
        $confirmOrder->total_cost_of_voucher = (int)$confirmOrder['total_cost'] * $voucher / 100;
        $confirmOrder->new_total_cost = (int)$confirmOrder['total_cost'] - $confirmOrder->total_cost_of_voucher;

        return $this->orderRepository->update($confirmOrder);
    }

    public function increaseQuantity($confirmOrder, $itemID)
    {
        $item = [];
        $totalCost = 0;
        foreach ($confirmOrder['item'] as $value) {
            if ($value['item_id'] == $itemID) {
                $value['quantity'] = (int)($value['quantity']) + 1;
                $value['total_cost'] = $value['quantity'] * (int)$value['detail_item']['cost'];
            }
            array_push($item, $value);
            $totalCost += $value['total_cost'];
        }
        $confirmOrder->item = $item;
        $confirmOrder->total_cost = $totalCost;

        return $this->orderRepository->update($confirmOrder);
    }

    public function decreaseQuantity($confirmOrder, $itemID)
    {
        $item = [];
        $totalCost = 0;
        foreach ($confirmOrder['item'] as $value) {
            if ($value['item_id'] == $itemID) {
                $value['quantity'] = (int)($value['quantity']) - 1;
                $value['total_cost'] = $value['quantity'] * (int)$value['detail_item']['cost'];
            }
            array_push($item, $value);
            $totalCost += $value['total_cost'];
        }
        $confirmOrder->item = $item;
        $confirmOrder->total_cost = $totalCost;

        return $this->orderRepository->update($confirmOrder);
    }

}
