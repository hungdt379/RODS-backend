<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Entities\Order;
use App\Domain\Repositories\DishInOrderRepository;
use App\Domain\Repositories\OrderRepository;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;


class OrderService
{
    private $orderRepository;
    private $dishInOrderRepository;
    private $userService;

    /**
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     * @param DishInOrderRepository $dishInOrderRepository
     * @param UserService $userService
     */
    public function __construct(OrderRepository $orderRepository, DishInOrderRepository $dishInOrderRepository, UserService $userService)
    {
        $this->orderRepository = $orderRepository;
        $this->dishInOrderRepository = $dishInOrderRepository;
        $this->userService = $userService;
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
        if (!str_contains($order['table_id'], '--')) {
            $this->dishInOrderRepository->deleteMany($order['item_id'], $order['table_id']);
            $order->status = Order::ORDER_STATUS_COMPLETED;
            $this->orderRepository->update($order);
            $this->userService->closeTable($this->userService->getUserById($order['table_id']));
        } else {
            $arrOrder = $this->orderRepository->getOrderOfMatchingOrder($order['arr_order_id']);
            foreach ($arrOrder as $value) {
                $value->status = Order::ORDER_STATUS_COMPLETED;
                $this->dishInOrderRepository->deleteMany($value['item_id'], $value['table_id']);
                $this->orderRepository->update($value);
                $this->userService->closeTable($this->userService->getUserById($value['table_id']));
            }
        }

        $tempPdf = new Dompdf();
        $html = '
                <div align="center"><img src="http://165.227.99.160/image/Logo.png" width="100px" height="100px" alt=""></div>
                <br>
                <div align="center" style="font-size: 12px; font-family: DejaVu Sans;"><b>HÓA ĐƠN THANH TOÁN</b></div>
                <div align="center" style="font-size: 10px; font-family: DejaVu Sans;"><b>' . $order['table_name'] . '</b></div>
                <br>
                <div style="font-size: 10px; font-family: DejaVu Sans">Thời gian: ' . date('d-m-Y H:i:s', time()) . '</div>
                <div style="font-size: 10px; font-family: DejaVu Sans">Số lượng khách: ' . $order['number_of_customer'] . '</div>
                <br>
                <table style="font-size: 10px; width: 290px; font-family: DejaVu Sans; border: 1px solid black;">
                    <tr>
                        <th style="border-bottom: 1px solid black">Tên món</th>
                        <th style="border-bottom: 1px solid black">SL</th>
                        <th style="border-bottom: 1px solid black">Đ.Giá</th>
                        <th style="border-bottom: 1px solid black">T.Tiền</th>
                    </tr>
                    ';
        for ($i = 0; $i < sizeof($order['item']); $i++) {
            $html = $html . '<tr>
                                <td style="border-bottom: 1px solid black">' . $order['item'][$i]['detail_item']['name'] . '</td>
                                <td align="center" style="border-bottom: 1px solid black">' . $order['item'][$i]['quantity'] . '</td>
                                <td align="right" style="border-bottom: 1px solid black">' . number_format($order['item'][$i]['detail_item']['cost']) . '</td>
                                <td align="right" style="border-bottom: 1px solid black">' . number_format($order['item'][$i]['total_cost']) . '</td>
                            </tr>';
        }
        $html = $html . '
                            <tr>
                                <td colspan="3" align="right" style="font-size: 12px; font-family: DejaVu Sans; width: 60%;">Tồng cộng: </td>
                                <td colspan="1" align="right" style="font-size: 12px; font-family: DejaVu Sans; width: 40%">' . number_format($order['total_cost']) . '</td>
                            </tr>
                            <tr>
                                <td colspan="3" align="right" style="font-size: 12px; font-family: DejaVu Sans;">Giảm giá: </td>
                                <td colspan="1" align="right" style="font-size: 12px; font-family: DejaVu Sans;">' . (isset($order['voucher']) ? $order['voucher'] : 0) . '%</td>
                            </tr>
                            <tr>
                                <td colspan="3" align="right" style="font-size: 12px; font-family: DejaVu Sans;">Thành tiền: </td>
                                <td colspan="1" align="right" style="font-size: 12px; font-family: DejaVu Sans; width: 30%"><b>' . (isset($order['new_total_cost']) ? number_format($order['new_total_cost']) : number_format($order['total_cost'])) . '</b></td>
                            </tr>
                         </table>
                         <br>
                         <hr>
                    <div align="center" style="font-size: 10px; font-family: DejaVu Sans;">Tất cả các đơn giá đều tính theo đơn vị VND</div>
                    <div align="center" style="font-size: 10px; font-family: DejaVu Sans;">Cảm ơn quý khách! Hẹn gặp lại</div>
                         ';
        $tempPdf->loadHtml($html);
        $tempPdf->setPaper(array(0, 0, 150, 100 * 2.838), 'landscape');
        $tempPdf->render();
        $pageCount = $tempPdf->getCanvas()->get_page_count();
        unset($tempPdf);

        $option = new Options();
        $option->setIsRemoteEnabled(true);
        $pdf = new Dompdf($option);
        $customPaper = array(0, 0, 150 * $pageCount / 1.7, 100 * 2.838);
        $pdf->loadHtml($html);
        $pdf->setPaper($customPaper, 'landscape');
        $pdf->render();
        $nameFile = 'eb_' . time() . '.pdf';
        Storage::disk('export-bill')->put($nameFile, $pdf->output());
        return asset('export-bill/' . $nameFile);
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

        return $this->getMatchingOrder($newConfirmOrder->_id);
    }

    public function getMatchingOrder($id)
    {
        return $this->orderRepository->getMatchingOrder($id);
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

        $this->orderRepository->insert($confirmOrder);

        $this->insertToDishInOrder($queueOrder, $confirmOrder->_id);

    }

    public function mergeOrder($queueOrder, $confirmOrder)
    {
        $item = array_merge($confirmOrder['item'], $queueOrder['item']);
        $totalCost = 0;
        $length = count($item);

        $this->insertToDishInOrder($queueOrder, $confirmOrder->_id);

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

    public function insertToDishInOrder($queueOrder, $confirmOrderID)
    {
        foreach ($queueOrder['item'] as $value) {
            $dishInOrder = new DishInOrder();
            $dishInOrder->order_id = $confirmOrderID;
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
                    $dishInOrder->order_id = $confirmOrderID;
                    $dishInOrder->table_id = $queueOrder['table_id'];
                    $dishInOrder->table_name = $queueOrder['table_name'];
                    $dishInOrder->item_id = $value['item_id'];
                    $dishInOrder->item_name = $value['dish_in_combo'][$i];
                    $dishInOrder->quantity = 1;
                    $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_PREPARE;
                    $dishInOrder->category_id = $value['detail_item']['category_id'];
                    $dishInOrder->ts = time();
                    $this->dishInOrderRepository->insert($dishInOrder);
                }
            }
            $this->dishInOrderRepository->insert($dishInOrder);
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
        if ($confirmOrder->voucher >= 0) {
            $confirmOrder->total_cost_of_voucher = (int)$confirmOrder['total_cost'] * $confirmOrder->voucher / 100;
            $confirmOrder->new_total_cost = (int)$confirmOrder['total_cost'] - $confirmOrder->total_cost_of_voucher;
        }

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
        if ($confirmOrder->voucher >= 0) {
            $confirmOrder->total_cost_of_voucher = (int)$confirmOrder['total_cost'] * $confirmOrder->voucher / 100;
            $confirmOrder->new_total_cost = (int)$confirmOrder['total_cost'] - $confirmOrder->total_cost_of_voucher;
        }

        return $this->orderRepository->update($confirmOrder);
    }

}
