<?php


namespace App\Http\Controllers;


use App\Domain\Entities\Notification;
use App\Domain\Services\NotificationService;
use App\Domain\Services\OrderService;
use App\Traits\ApiResponse;
use Validator;
use JWTAuth;

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
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
        $tableID = $param['table_id'];

        $data = $this->orderService->getConfirmOrderByTableID($tableID);
        if ($data) {
            return $this->successResponse($data, 'Success');
        } else {
            return $this->errorResponse('Not found confirm order', null, false, 404);
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
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
        $tableID = $param['table_id'];
        $itemID = $param['item_id'];

        $confirmOrder = $this->orderService->getConfirmOrderByTableID($tableID);
        $this->orderService->deleteItemInConfirmOrder($confirmOrder, $itemID);

        return $this->successResponse(null, 'Delete Success');
    }

    public function getListConfirmOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'pageSize' => 'required|numeric|'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }

        $pageSize = $param['pageSize'];
        $data = $this->orderService->getAllConfirmOrder($pageSize);
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
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
        $note = $param['note'];
        $id = $param['_id'];
        $this->orderService->addNoteForRemainItem($id, $note);

        return $this->successResponse(null, 'Success');
    }

    public function addVoucherToConfirmOrder(){
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required',
            'voucher' => 'required|numeric|min:5|max:90'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
        $id = $param['_id'];
        $voucher = (int)$param['voucher'];
        $this->orderService->addVoucherToConfirmOrder($id, $voucher);

        return $this->successResponse(null, 'Success');

    }
}
