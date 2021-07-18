<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Entities\Order;
use App\Domain\Repositories\DishInOrderRepository;
use App\Domain\Repositories\OrderRepository;
use Dompdf\Dompdf;


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

    public function getAllCompleteOrder($pageSize)
    {
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

    public function invoiceOrder($order)
    {
        if (!str_contains($order['table_id'], '--')){
            $order->status = Order::ORDER_STATUS_COMPLETED;
            $this->orderRepository->update($order);
        }else{
            $arrOrder = $this->orderRepository->getOrderOfMatchingOrder($order['arr_order_id']);
            foreach ($arrOrder as $order){
                $order->status = Order::ORDER_STATUS_COMPLETED;
                $this->orderRepository->update($order);
            }
        }

        $tempPdf = new Dompdf();
        $tempPdf->setPaper([], 'landscape');
        $tempPdf->setPaper(array(20, 0, 150, 80 * 2.838));

        $html = '
                <img src="public/image/Logo.png" width="50px" height="50px">
                <div align="center" style="font-size: 12px; font-family: DejaVu Sans"><b>HÓA ĐƠN THANH TOÁN</b></div>
                <table style="font-size: 10px; width: 200px; font-family: DejaVu Sans; border: 1px solid black">

                </table>
                ';

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

    public function getOrderByID($id)
    {
        return $this->orderRepository->getOrderByID($id);
    }

    public function matchingConfirmOrder($listConfirmOrder)
    {
        $item = [];
        $totalCost = 0;
        $tableID = '';
        $tableName = '';
        $arrOrderID = [];
        $numberOfCustomer = 0;
        $note = '';
        foreach ($listConfirmOrder as $confirmOrder) {
            if (isset($confirmOrder['note'])) {
                $note = $note . ', ' . $confirmOrder['note'];
            }
            $numberOfCustomer += (int)$confirmOrder['number_of_customer'];
            $tableID = $tableID . '--' . $confirmOrder['table_id'] . '--';
            $tableName = $tableName . '--' . $confirmOrder['table_name'] . '--';
            array_push($arrOrderID, $confirmOrder['_id']);
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
        $newConfirmOrder->arr_order_id = $arrOrderID;
        $newConfirmOrder->table_id = $tableID;
        $newConfirmOrder->table_name = $tableName;
        $newConfirmOrder->number_of_customer = $numberOfCustomer;
        $newConfirmOrder->status = Order::ORDER_STATUS_MATCHING;
        $newConfirmOrder->item = array_values($item);
        $newConfirmOrder->total_cost = $totalCost;
        $newConfirmOrder->total_cost = $totalCost;
        $newConfirmOrder->note = substr($note, 2);
        $newConfirmOrder->ts = time();

        $this->orderRepository->insert($newConfirmOrder);

        return $this->getMatchingOrder($tableID);
    }

    public function getMatchingOrder($tableID)
    {
        return $this->orderRepository->getMatchingOrder($tableID);
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

    public function getMatchingOrderByID($id)
    {
    }


}
