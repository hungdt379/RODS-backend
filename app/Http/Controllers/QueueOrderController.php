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
use \Illuminate\Http\Response as Res;

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
                return $this->errorResponse('Table exist queue order', null, false, Res::HTTP_CREATED);
            }

        } else {
            return $this->errorResponse('Not found item to send', null, false, Res::HTTP_NO_CONTENT);
        }

    }

    public function getQueueOrderByTableID()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $tableID = $param['table_id'];
        $queueOrder = $this->queueOrderService->getQueueOrderByTableID($tableID);
        if ($queueOrder) {
            return $this->successResponse($queueOrder, 'Success');
        } else {
            return $this->errorResponse('Not found queue order', null, false, Res::HTTP_NO_CONTENT);
        }

    }

    public function cancelQueueOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $queueOrder = $this->queueOrderService->getQueueOrderByID($param['_id']);
        if ($queueOrder) {
            $this->queueOrderService->delete($param['_id']);
            $this->notificationService->removeReferenceAfterRead('waiter/' . $queueOrder['table_id'] . '/send-order');
            return $this->successResponse(null, 'Delete Success');
        } else {
            return $this->errorResponse('Not found queue order', null, false, Res::HTTP_NO_CONTENT);
        }

    }

    public function confirmQueueOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $tableID = $param['table_id'];
        $queueOrder = $this->queueOrderService->getQueueOrderByTableID($tableID);
        if ($queueOrder) {
            $confirmOrder = $this->orderService->getConfirmOrderByTableID($tableID);
            if (!$confirmOrder) {
                $this->orderService->addNewConfirmOrder($queueOrder);
                $this->queueOrderService->delete($queueOrder['_id']);
                $this->notificationService->removeReferenceAfterRead('waiter/' . $tableID . '/send-order');
                return $this->successResponse(null, 'Confirm Success');
            } else {
                $tempConfirmCombo = '';
                $tempQueueCombo = '';
                foreach ($confirmOrder['item'] as $value) {
                    if (strpos($value['detail_item']['name'], 'Combo') !== false) {
                        $tempConfirmCombo = $value['detail_item']['name'];
                    }
                }

                foreach ($queueOrder['item'] as $value) {
                    if (strpos($value['detail_item']['name'], 'Combo') !== false) {
                        $tempQueueCombo = $value['detail_item']['name'];
                    }
                }

                if (($tempConfirmCombo == $tempQueueCombo) || ($tempQueueCombo == '' && $tempConfirmCombo != '') || ($tempQueueCombo != '' && $tempConfirmCombo == '')) {
                    $this->orderService->mergeOrder($queueOrder, $confirmOrder);
                    $this->queueOrderService->delete($queueOrder['_id']);
                    $this->notificationService->removeReferenceAfterRead('waiter/' . $tableID . '/send-order');
                    return $this->successResponse(null, 'Confirm Success');
                } else if ($tempQueueCombo != $tempConfirmCombo) {
                    $this->notificationService->removeReferenceAfterRead('waiter/' . $tableID . '/send-order');
                    return $this->errorResponse('Exist a combo in order', null, false, Res::HTTP_ACCEPTED);
                }

            }

        } else {
            return $this->errorResponse('Not found queue order', null, false, Res::HTTP_ACCEPTED);
        }

    }

    public function deleteItemInQueueOrder()
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

        $queueOrder = $this->queueOrderService->getQueueOrderByTableID($tableID);
        if($queueOrder){
            $this->queueOrderService->deleteItemInQueueOrder($queueOrder, $itemID);

            return $this->successResponse(null, 'Delete Success');
        }else{
            return $this->errorResponse('Not found queue order', null, false, Res::HTTP_ACCEPTED);
        }
    }

}
