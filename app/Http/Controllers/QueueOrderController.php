<?php

namespace App\Http\Controllers;

use App\Domain\Services\QueueOrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QueueOrderController extends Controller
{
    use ApiResponse;

    private $queueOrderService;

    /**
     * QueueOrderController constructor.
     * @param $queueOrderService
     */
    public function __construct(QueueOrderService $queueOrderService)
    {
        $this->queueOrderService = $queueOrderService;
    }

    public function sendOrder()
    {
        $param = request()->all();
        $check = $this->queueOrderService->checkExistQueueOrderInTable($param['table_id']);
        if (!$check) {
            $data = $this->queueOrderService->insertToQueueOrder($param);
            return $this->successResponse($data, 'Success');
        }

        return $this->errorResponse('Table exist queue order', null, false, 405);
    }

    public function getQueueOrderByTableID()
    {
        $param = request()->all();
        $data = $this->queueOrderService->getQueueOrderByTableID($param['table_id']);

        return $this->successResponse($data, 'Success');
    }

}
