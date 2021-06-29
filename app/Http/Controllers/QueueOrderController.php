<?php

namespace App\Http\Controllers;

use App\Domain\Services\CartItemService;
use App\Domain\Services\MenuService;
use App\Domain\Services\QueueOrderService;
use App\Traits\ApiResponse;
use JWTAuth;

class QueueOrderController extends Controller
{
    use ApiResponse;

    private $queueOrderService;
    private $menuService;
    private $cartItemService;

    /**
     * QueueOrderController constructor.
     * @param QueueOrderService $queueOrderService
     * @param MenuService $menuService
     * @param CartItemService $cartItemService
     */
    public function __construct(QueueOrderService $queueOrderService, MenuService $menuService, CartItemService $cartItemService)
    {
        $this->queueOrderService = $queueOrderService;
        $this->menuService = $menuService;
        $this->cartItemService = $cartItemService;
    }


    public function sendOrder()
    {
        $tableID = JWTAuth::user()->_id;
        $cartItem = $this->cartItemService->getCartItemByTableID($tableID)->toArray();
        if ($cartItem != []) {
            $check = $this->queueOrderService->checkExistQueueOrderInTable($tableID);
            if (!$check) {
                $data = $this->queueOrderService->insertToQueueOrder();
                $this->cartItemService->deleteAllItemByTableID($tableID);
                return $this->successResponse($data, 'Success');
            }

            return $this->errorResponse('Table exist queue order', null, false, 405);
        } else {
            return $this->errorResponse('Not found item to send', null, false, 404);
        }

    }

    public function getQueueOrderByTableID()
    {
        $param = request()->all();
        $tableID = $param['table_id'];
        $queueOrder = $this->queueOrderService->getQueueOrderByTableID($tableID);
        if ($queueOrder) {
            return $this->successResponse($queueOrder, 'Success');
        } else {
            return $this->errorResponse('Not found queue order', null, false, 404);
        }

    }

    public function cancelQueueOrder()
    {
        $param = request()->all();
        $this->queueOrderService->delete($param['id']);

        return $this->successResponse(null, 'Delete Success');
    }


}
