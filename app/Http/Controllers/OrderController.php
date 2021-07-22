<?php


namespace App\Http\Controllers;


use App\Domain\Services\NotificationService;
use App\Domain\Services\OrderService;
use App\Traits\ApiResponse;
use Validator;
use \Illuminate\Http\Response as Res;

class OrderController extends Controller
{
    use ApiResponse;

    private $orderService;
    private $notificationService;

    /**
     * OrderController constructor.
     * @param OrderService $orderService
     * @param NotificationService $notificationService
     */
    public function __construct(OrderService $orderService, NotificationService $notificationService)
    {
        $this->orderService = $orderService;
        $this->notificationService = $notificationService;
    }

    public function viewDetailConfirmOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $tableID = $param['table_id'];

        $data = $this->orderService->getConfirmOrderByTableID($tableID);
        if ($data) {
            return $this->successResponse($data, 'Success');
        } else {
            return $this->errorResponse('Not found confirm order', null, false, Res::HTTP_NO_CONTENT);
        }
    }

    public function deleteItemInConfirmOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required',
            'item_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $tableID = $param['table_id'];
        $itemID = $param['item_id'];

        $confirmOrder = $this->orderService->getConfirmOrderByTableID($tableID);
        if ($confirmOrder) {
            $this->orderService->deleteItemInConfirmOrder($confirmOrder, $itemID);
            return $this->successResponse(null, 'Delete Success');
        } else {
            return $this->errorResponse('Not found confirm order', null, false, Res::HTTP_NO_CONTENT);
        }

    }

    public function getListConfirmOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'pageSize' => 'required|numeric|'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $pageSize = $param['pageSize'];
        $data = $this->orderService->getAllConfirmOrder($pageSize);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $pageSize, $data->total());
    }

    public function getListCompleteOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'pageSize' => 'required|numeric|'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $pageSize = $param['pageSize'];
        $data = $this->orderService->getAllCompleteOrder($pageSize);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $pageSize, $data->total());
    }

    public function addNoteForRemainItem()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'note' => 'required',
            '_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $note = $param['note'];
        $orderID = $param['_id'];
        $confirmOrder = $this->orderService->getConfirmOrderByID($orderID);
        if ($confirmOrder) {
            $this->orderService->addNoteForRemainItem($confirmOrder, $note);
            return $this->successResponse(null, 'Success');
        } else {
            return $this->errorResponse('Not found confirm order', null, false, Res::HTTP_NO_CONTENT);
        }

    }

    public function addVoucherToConfirmOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required',
            'voucher' => 'required|numeric|min:0|max:90'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $orderID = $param['_id'];
        $voucher = (int)$param['voucher'];
        $confirmOrder = $this->orderService->getOrderByID($orderID);
        if ($confirmOrder) {
            $this->orderService->addVoucherToConfirmOrder($confirmOrder, $voucher);
            return $this->successResponse(null, 'Success');
        } else {
            return $this->errorResponse('Not found confirm order', null, false, Res::HTTP_NO_CONTENT);
        }


    }

    public function updateQuantityOfItem()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required',
            'item_id' => 'required',
            'status' => 'required|boolean'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $orderID = $param['_id'];
        $itemID = $param['item_id'];
        $status = $param['status'];
        $confirmOrder = $this->orderService->getOrderByID($orderID);
        if ($confirmOrder) {
            if ($status) {
                $this->orderService->increaseQuantity($confirmOrder, $itemID);
            } else {
                $this->orderService->decreaseQuantity($confirmOrder, $itemID);
            }
            return $this->successResponse(null, 'Success');
        } else {
            return $this->errorResponse('Not found confirm order', null, false, Res::HTTP_NO_CONTENT);
        }


    }

    public function matchingConfirmOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $tableID = $param['table_id'];
        $strTableID = '';
        foreach ($tableID as $value) {
            $strTableID = $strTableID . '--' . $value . '--';
        }
        $listConfirmOrder = $this->orderService->getListConfirmOrderByTableID($tableID)->toArray();
        if (count($tableID) == count($listConfirmOrder)) {
            $data = $this->orderService->matchingConfirmOrder($listConfirmOrder);
            return $this->successResponse($data, 'Success');
        } else {
            return $this->errorResponse('One or more table_id not found', null, false, Res::HTTP_ACCEPTED);
        }

    }

    public function exportBill()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $id = $param['_id'];
        $order = $this->orderService->getOrderByID($id);

        if (!$order){
            return $this->errorResponse('Order not found', null, false, Res::HTTP_ACCEPTED);
        }

        $data = $this->orderService->invoiceOrder($order);

        return $this->successResponse($data, 'Success');
    }

    public function getOrderByID()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $data = $this->orderService->getOrderByID($param['_id']);

        if (!$data){
            $this->errorResponse('Not found Order', null, false, Res::HTTP_ACCEPTED);
        }

        return $this->successResponse($data, 'Success');
    }
}
