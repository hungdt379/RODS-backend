<?php


namespace App\Domain\Services;


use App\Domain\Repositories\QueueOrderRepository;
use App\Domain\Entities\QueueOrder;
use JWTAuth;

class QueueOrderService
{
    private $queueOrderRepository;
    private $cartService;
    private $cartItemService;
    private $menuService;

    /**
     * QueueOrderService constructor.
     * @param QueueOrderRepository $queueOrderRepository
     * @param CartService $cartService
     * @param CartItemService $cartItemService
     * @param MenuService $menuService
     */
    public function __construct(QueueOrderRepository $queueOrderRepository, CartService $cartService, CartItemService $cartItemService, MenuService $menuService)
    {
        $this->queueOrderRepository = $queueOrderRepository;
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
        $this->menuService = $menuService;
    }


    public function checkExistQueueOrderInTable($tableID)
    {
        $queueOrder = $this->getQueueOrderByTableID($tableID);
        if (!$queueOrder) {
            return false;
        }
        return true;
    }

    public function insertToQueueOrder()
    {
        $table = JWTAuth::user();
        $tableID = $table->_id;
        $tableName = $table->full_name;
        $numberOfCustomer = $table->number_of_customer;

        $cart = $this->cartService->getCartByTableID($tableID);
        $cartItem = $this->cartItemService->getCartItemByTableID($tableID)->toArray();

        $item = [];
        foreach ($cartItem as $value) {
            $detailItem = $this->menuService->getItemByID($value['item_id']);
            $value['detail_item'] = $detailItem->toArray();
            if ($value['total_cost'] == 0) {
                $value['quantity'] = 0;
            }
            array_push($item, $value);
        }
        $queueOrder = new QueueOrder();
        $queueOrder->table_id = $tableID;
        $queueOrder->table_name = $tableName;
        $queueOrder->number_of_customer = $numberOfCustomer;
        $queueOrder->status = QueueOrder::QUEUE_ORDER_STATUS_QUEUED;
        $queueOrder->item = $item;
        $queueOrder->total_cost = $cart['total_cost'];
        $queueOrder->ts = time();

        return $this->queueOrderRepository->insert($queueOrder);
    }

    public function updateQueueOrderToNewTable($fromQueueOrder, $toTable)
    {
        $fromQueueOrder->table_id = $toTable['_id'];
        $fromQueueOrder->table_name = $toTable['full_name'];
        $fromQueueOrder->number_of_customer = $toTable['number_of_customer'];

        return $this->queueOrderRepository->update($fromQueueOrder);
    }

    public function getQueueOrderByTableID($tableID)
    {
        return $this->queueOrderRepository->getQueueOrderByTableID($tableID);
    }

    public function getQueueOrderByID($queueOrderID)
    {
        return $this->queueOrderRepository->getQueueOrderByID($queueOrderID);
    }

    public function delete($id)
    {
        return $this->queueOrderRepository->delete($id);
    }

    public function update($queueOrder)
    {
        return $this->queueOrderRepository->update($queueOrder);
    }

    public function insert($queueOrder)
    {
        return $this->queueOrderRepository->insert($queueOrder);
    }

    public function deleteItemInQueueOrder($queueOrder, $itemID)
    {
        $item = $queueOrder['item'];
        $itemDeletedCost = 0;
        for ($i = 0; $i < count($item); $i++) {
            if ($item[$i]['item_id'] == $itemID) {
                $itemDeletedCost = $item[$i]['total_cost'];
                unset($item[$i]);
                $item = array_values($item);
            }
        }
        $queueOrder['item'] = $item;
        $queueOrder['total_cost'] -= $itemDeletedCost;
        if ($item == null) {
            $this->queueOrderRepository->delete($queueOrder->_id);
        } else {
            $this->queueOrderRepository->update($queueOrder);
        }
    }

    public function increaseQuantity($queueOrder, $itemID)
    {
        $item = [];
        $totalCost = 0;
        foreach ($queueOrder['item'] as $value) {
            if ($value['item_id'] == $itemID) {
                $value['quantity'] = (int)($value['quantity']) + 1;
                $value['total_cost'] = $value['quantity'] * (int)$value['detail_item']['cost'];
            }
            array_push($item, $value);
            $totalCost += $value['total_cost'];
        }
        $queueOrder->item = $item;
        $queueOrder->total_cost = $totalCost;

        return $this->queueOrderRepository->update($queueOrder);
    }

    public function decreaseQuantity($queueOrder, $itemID)
    {
        $item = [];
        $totalCost = 0;
        foreach ($queueOrder['item'] as $value) {
            if ($value['item_id'] == $itemID) {
                $value['quantity'] = (int)($value['quantity']) - 1;
                $value['total_cost'] = $value['quantity'] * (int)$value['detail_item']['cost'];
            }
            array_push($item, $value);
            $totalCost += $value['total_cost'];
        }
        $queueOrder->item = $item;
        $queueOrder->total_cost = $totalCost;

        return $this->queueOrderRepository->update($queueOrder);
    }

    public function updateNormalItemExistedInQueueOrder($queueOrderArray, $item, $quantity, $cost)
    {
        $itemArray = [];
        foreach ($queueOrderArray['item'] as $value) {

            if ($value['item_id'] = $item['_id']) {
                $value['quantity'] += $quantity;
                $value['total_cost'] += $quantity * $cost;
                $queueOrderArray['total_cost'] += $quantity * $cost;
            }
            array_push($itemArray, $value);
        }
        $queueOrderArray['item'] = $itemArray;
        $this->queueOrderRepository->update($queueOrderArray);
    }

    public function insertNewItemInQueueOrder($queueOrderArray, $tableID, $item, $quantity, $cost, $dishInCombo, $note)
    {
        $newQueueOrderItem['table_id'] = $tableID;
        $newQueueOrderItem['item_id'] = $item['_id'];
        $newQueueOrderItem['quantity'] = $quantity;
        $newQueueOrderItem['note'] = $note;
        $newQueueOrderItem['dish_in_combo'] = $dishInCombo;
        $newQueueOrderItem['total_cost'] = $quantity * $cost;
        $newQueueOrderItem['detail_item']['_id'] = $item['_id'];
        $newQueueOrderItem['detail_item']['name'] = $item['name'];
        $newQueueOrderItem['detail_item']['cost'] = $item['cost'];
        $newQueueOrderItem['detail_item']['image'] = $item['image'];
        $newQueueOrderItem['detail_item']['category_id'] = $item['category_id'];
        $newQueueOrderItem['detail_item']['is_sold_out'] = $item['is_sold_out'];

        $arrayItem = [];
        foreach ($queueOrderArray['item'] as $value) {
            array_push($arrayItem, $value);
        }
        array_push($arrayItem, $newQueueOrderItem);
        $queueOrderArray['item'] = $arrayItem;
        $queueOrderArray['total_cost'] += $quantity * $cost;
        $this->queueOrderRepository->update($queueOrderArray);
    }

    public function updateComboItemInQueueOrder($queueOrderArray, $item, $dishInCombo, $note)
    {
        $itemArray = [];
        foreach ($queueOrderArray['item'] as $value) {
            if ($value['item_id'] = $item['_id']) {
                $value['dish_in_combo'] = $dishInCombo;
                $value['note'] = $note;
            }
            array_push($itemArray, $value);
        }
        $queueOrderArray['item'] = $itemArray;
        $this->queueOrderRepository->update($queueOrderArray);
    }

    public function insertNewQueueOrder($tableID, $table, $item, $dishInCombo, $quantity, $cost, $note)
    {
        $newQueueOrder = new QueueOrder();
        $newQueueOrder->table_id = $tableID;
        $newQueueOrder->table_name = $table['full_name'];
        $newQueueOrder->number_of_customer = $table['number_of_customer'];
        $newQueueOrder->status = QueueOrder::QUEUE_ORDER_STATUS_QUEUED;

        $newQueueOrderItemArray = [];
        $newQueueOrderItem['table_id'] = $tableID;
        $newQueueOrderItem['item_id'] = $item['_id'];
        $newQueueOrderItem['quantity'] = $quantity;
        $newQueueOrderItem['note'] = $note;
        $newQueueOrderItem['dish_in_combo'] = $dishInCombo;
        $newQueueOrderItem['total_cost'] = $quantity * $cost;
        $newQueueOrderItem['detail_item']['_id'] = $item['_id'];
        $newQueueOrderItem['detail_item']['name'] = $item['name'];
        $newQueueOrderItem['detail_item']['cost'] = $item['cost'];
        $newQueueOrderItem['detail_item']['image'] = $item['image'];
        $newQueueOrderItem['detail_item']['category_id'] = $item['category_id'];
        $newQueueOrderItem['detail_item']['is_sold_out'] = $item['is_sold_out'];

        array_push($newQueueOrderItemArray, $newQueueOrderItem);
        $newQueueOrder->item = $newQueueOrderItemArray;
        $newQueueOrder->total_cost = $quantity * $cost;
        $newQueueOrder->ts = time();
        $this->queueOrderRepository->insert($newQueueOrder);
    }
}
