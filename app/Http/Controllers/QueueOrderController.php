<?php

namespace App\Http\Controllers;

use App\Domain\Entities\Notification;
use App\Domain\Services\CartItemService;
use App\Domain\Services\MenuService;
use App\Domain\Services\NotificationService;
use App\Domain\Services\OrderService;
use App\Domain\Services\QueueOrderService;
use App\Traits\ApiResponse;
use JWTAuth;
use Validator;

class QueueOrderController extends Controller
{
    use ApiResponse;

    private $queueOrderService;
    private $orderService;
    private $menuService;
    private $cartItemService;
    private $notificationService;

    /**
     * QueueOrderController constructor.
     * @param QueueOrderService $queueOrderService
     * @param MenuService $menuService
     * @param CartItemService $cartItemService
     * @param NotificationService $notificationService
     * @param OrderService $orderService
     */
    public function __construct(QueueOrderService $queueOrderService, MenuService $menuService, CartItemService $cartItemService, NotificationService $notificationService, OrderService $orderService)
    {
        $this->queueOrderService = $queueOrderService;
        $this->menuService = $menuService;
        $this->cartItemService = $cartItemService;
        $this->notificationService = $notificationService;
        $this->orderService = $orderService;
    }


    public function sendOrder()
    {
        $user = JWTAuth::user();
        $re = [Notification::RECEIVER_WAITER];
        $tableID = $user->_id;
        $cartItem = $this->cartItemService->getCartItemByTableID($tableID)->toArray();
        if ($cartItem != []) {
            $check = $this->queueOrderService->checkExistQueueOrderInTable($tableID);
            if (!$check) {
                $data = $this->queueOrderService->insertToQueueOrder();
                $this->cartItemService->deleteAllItemByTableID($tableID);
                $this->notificationService->notification(null, Notification::TITLE_SEND_ORDER_VN, Notification::TITLE_SEND_ORDER_EN, $user, $re);
                return $this->successResponse($data, 'Send order success');
            } else {
                return $this->errorResponse('Table exist queue order', null, false, 405);
            }

        } else {
            return $this->errorResponse('Not found item to send', null, false, 404);
        }

    }

    public function getQueueOrderByTableID()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
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
        $validator = Validator::make($param, [
            '_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }
        $queueOrder = $this->queueOrderService->getQueueOrderByID($param['_id']);
        if($queueOrder){
            $this->queueOrderService->delete($param['_id']);
            return $this->successResponse(null, 'Delete Success');
        }else{
            return $this->errorResponse('Not found queue order', null, false, 404);
        }

    }

    public function confirmQueueOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }

        $tableID = $param['table_id'];
        $confirmOrder = $this->orderService->getConfirmOrderByTableID($tableID);
        $queueOrder = $this->queueOrderService->getQueueOrderByTableID($tableID);
        if (!$confirmOrder) {
            $this->orderService->addNewConfirmOrder($queueOrder);
        } else {
            $this->orderService->mergeOrder($queueOrder, $confirmOrder);
        }
        $this->queueOrderService->delete($queueOrder['_id']);

        return $this->successResponse(null, 'Confirm Success');
    }

}
