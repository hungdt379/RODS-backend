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

    public function viewDetailConfirmOrder(){
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
        $tableID = $param['table_id'];

        $data = $this->orderService->getConfirmOrderByTableID($tableID);
        if($data){
            return $this->successResponse($data, 'Success');
        }else{
            return $this->errorResponse('Not found confirm order', null, false, 404);
        }
    }
}
