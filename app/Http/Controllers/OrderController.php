<?php


namespace App\Http\Controllers;


use App\Domain\Services\OrderService;
use App\Traits\ApiResponse;
use Validator;

class OrderController extends Controller
{
    use ApiResponse;

    private $orderService;

    /**
     * OrderController constructor.
     * @param $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
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

    public function getAllItemInConfirmOrder(){
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
        $tableID = $param['table_id'];

        $confirmOrder = $this->orderService->getConfirmOrderByTableID($tableID);
        $data = $this->orderService->getAllItemInConfirmOrder($confirmOrder);

//        return
    }
}
