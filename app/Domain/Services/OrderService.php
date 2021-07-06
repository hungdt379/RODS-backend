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

    public function getCompletedOrderByID($id)
    {
        return $this->orderRepository->getCompletedOrderByID($id);
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

    public function matchingConfirmOrder($listConfirmOrder)
    {
        $item = [];
        $totalCost = 0;
        $tableID = [];
        $tableName = [];
        $numberOfCustomer = 0;
        $note = '';
        $id = [];
        foreach ($listConfirmOrder as $confirmOrder) {
            $note = $note.', '.$confirmOrder['note'];
            $numberOfCustomer += (int)$confirmOrder['number_of_customer'];
            array_push($tableID, $confirmOrder['table_id']);
            array_push($tableName, $confirmOrder['table_name']);
            array_push($id, $confirmOrder['_id']);
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
        $newConfirmOrder->note = substr($note,2);
        $newConfirmOrder->ts = time();

        $this->orderRepository->deleteConfirmOrderByID($id);
        return $this->orderRepository->insert($newConfirmOrder);
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

    public function addNoteForRemainItem($id, $note)
    {
        $confirmOrder = $this->orderRepository->getConfirmOrderByID($id);
        $confirmOrder->note = $note;

        return $this->orderRepository->update($confirmOrder);
    }

    public function addVoucherToConfirmOrder($id, $voucher)
    {
        $confirmOrder = $this->orderRepository->getConfirmOrderByID($id);
        $confirmOrder->voucher = $voucher;
        $confirmOrder->total_cost_of_voucher = (int)$confirmOrder['total_cost'] * $voucher / 100;
        $confirmOrder->total_cost = (int)$confirmOrder['total_cost'] - $confirmOrder->total_cost_of_voucher;

        return $this->orderRepository->update($confirmOrder);
    }

    public function increaseQuantity($id, $itemID)
    {
        $confirmOrder = $this->orderRepository->getConfirmOrderByID($id);
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

    public function decreaseQuantity($id, $itemID)
    {
        $confirmOrder = $this->orderRepository->getConfirmOrderByID($id);
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

    public function deleteConfirmOrderByTableID($tableID)
    {
        return $this->orderRepository->deleteConfirmOrderByID($tableID);
    }
}
