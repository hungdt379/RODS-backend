<?php


namespace App\Http\Controllers;


use App\Domain\Services\OrderService;

class OrderController extends Controller
{
    private $orderService;

    /**
     * OrderController constructor.
     * @param $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

}
