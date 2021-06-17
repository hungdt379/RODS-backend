<?php


namespace App\Http\Controllers;


use App\Domain\Services\OrderService;
use App\Traits\ApiResponse;

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

    public function sendOrder()
    {
        $param = request()->all();
        $data = $this->orderService->insertToOrder($param);

        return $this->successResponse($data, 'Success');
    }


}
