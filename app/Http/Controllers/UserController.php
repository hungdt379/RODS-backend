<?php

namespace App\Http\Controllers;


use App\Domain\Services\CartService;
use App\Domain\Services\DishInOrderService;
use App\Domain\Services\OrderService;
use App\Domain\Services\QueueOrderService;
use App\Domain\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use \Illuminate\Http\Response as Res;

class UserController extends Controller
{
    use ApiResponse;

    private $userService;
    private $cartService;
    private $cartController;
    private $orderService;
    private $dishInOrderService;
    private $queueOrderService;

    /**
     * UserController constructor.
     * @param UserService $userService
     * @param CartService $cartService
     * @param CartController $cartController
     * @param OrderService $orderService
     * @param DishInOrderService $dishInOrderService
     * @param QueueOrderService $queueOrderService
     */
    public function __construct(UserService $userService, CartService $cartService, CartController $cartController,
                                OrderService $orderService, DishInOrderService $dishInOrderService, QueueOrderService $queueOrderService)
    {
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->cartController = $cartController;
        $this->orderService = $orderService;
        $this->dishInOrderService = $dishInOrderService;
        $this->queueOrderService = $queueOrderService;
    }


    public function getListTable()
    {
        $param = request()->all();
        $data = $this->userService->getListTable($param['pageSize']);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $param['pageSize'], $data->total());
    }

    public function getTableById()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required|alpha_num|max:30'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $data = $this->userService->getUserById($param['table_id']);

        if ($data == null) {
            return $this->errorResponse('Table is not exist', '', false, Res::HTTP_NO_CONTENT);
        }

        return $this->successResponse($data, 'Success');

    }

    public function openTable()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required|alpha_num|max:30',
            'number_of_customer' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid params', null, false, 400);
        }

        $table = $this->userService->getUserById($param['table_id']);

        if ($param['number_of_customer'] > $table->max_customer || $param['number_of_customer'] < 1) {
            return $this->errorResponse('Number of customer must be greater or equal 1 and less or equal ' . $table->max_customer, null, false, 400);
        }

        $this->userService->openTable($param['table_id'], $param['number_of_customer']);
        $this->cartService->addNewCart($param['table_id']);
        return $this->successResponse(null, 'Open table successfully');
    }

    public function closeTable()
    {
        $param = request()->all();
        $user = $this->userService->getUserById($param['table_id']);
        $confirmOrder = $this->orderService->getConfirmOrderByTableID($param['table_id']);
        $queueOrder = $this->queueOrderService->getQueueOrderByTableID($param['table_id']);
        if ($confirmOrder || $queueOrder) {
            return $this->errorResponse('Can not close table', null, false, Res::HTTP_ACCEPTED);
        }

        $this->userService->closeTable($user);
        return $this->successResponse(null, 'Close table successful');
    }

    public function updateNumberOfCustomer()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required',
            'number_of_customer' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid params', null, false, Res::HTTP_BAD_REQUEST);
        }

        $table = $this->userService->getUserById($param['table_id']);
        if ((int)$param['number_of_customer'] > $table['max_customer'] || (int)$param['number_of_customer'] < 1) {
            return $this->errorResponse('Số khách phải lớn hơn 1 và nhỏ hơn ' . $table['max_customer'], null, false, Res::HTTP_ACCEPTED);
        }

        if ((int)$param['number_of_customer'] == $table['number_of_customer']) {
            return $this->successResponse(null, 'Success');
        }

        $this->userService->updateNumberOfCustomer($param['table_id'], (int)$param['number_of_customer']);
        return $this->successResponse('', 'Update successfully');
    }

    public function addNewTable()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_number' => 'required|integer|min:1',
            'max_customer' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid params', null, false, 400);
        }

        if ($param['table_number'] < 10) {
            $username = 'MB0' . (int)$param['table_number'];
            $fullname = 'Bàn 0' . (int)$param['table_number'];
        } else {
            $username = 'MB' . (int)$param['table_number'];
            $fullname = 'Bàn ' . (int)$param['table_number'];
        }

        $check = $this->userService->checkExistedTable($username);
        if (!$check) {
            return $this->errorResponse('Table existed', null, false, 409);
        }

        $this->userService->addNewTable($username, $fullname, (int)$param['max_customer']);

        return $this->successResponse('', 'Insert successful');
    }

    public function updateTable()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_number' => 'required|integer|min:1',
            'max_customer' => 'required|integer|min:1',
            'table_id' => 'required|alpha_num'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid params', null, false, 400);
        }

        if ($param['table_number'] < 10) {
            $username = 'MB0' . (int)$param['table_number'];
            $fullname = 'Bàn 0' . (int)$param['table_number'];
        } else {
            $username = 'MB' . (int)$param['table_number'];
            $fullname = 'Bàn ' . (int)$param['table_number'];
        }

        $table = $this->userService->getUserById($param['table_id']);

        if ($table != null) {
            $check = $this->userService->checkExistedTableForUpdate($username, $table->username);
        } else {
            return $this->errorResponse('Table does not exist', null, false, 400);
        }

        if (!$check) {
            return $this->errorResponse('Table existed', null, false, 409);
        }

        $this->userService->updateTable($param['table_id'], $username, $fullname, (int)$param['max_customer']);
        return $this->successResponse('', 'Update successful');
    }

    public function deleteTable()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required|alpha_num'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $table = $this->userService->getUserById($param['table_id']);
        if ($table == null) {
            return $this->errorResponse('Table does not exist', null, false, 400);
        }

        $this->userService->deleteTable($param['table_id']);
        return $this->successResponse('', 'Delete successful');
    }

    public function generateNewQrCode()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required|alpha_num'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $table = $this->userService->getUserById($param['table_id']);
        if ($table == null) return $this->errorResponse('Not found table', null, false, Res::HTTP_NO_CONTENT);

        //$this->userService->closeTable($table);
        $data = $this->userService->generateNewQrCode($table);
        return $this->successResponse($data, 'Success');
    }

    public function getTableNotActive()
    {
        $data = $this->userService->getTableNotActive();
        return $this->successResponse($data, 'Success');
    }

    public function changeTable()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'from_table_id' => 'required|alpha_num',
            'to_table_id' => 'required|alpha_num'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $fromTableID = $param['from_table_id'];
        $toTableID = $param['to_table_id'];

        $toTable = $this->userService->getUserById($toTableID);
        if ($toTable && $toTable['is_active'] == false) {
            $fromTable = $this->userService->getUserById($fromTableID);
            $this->userService->openTable($toTableID, $fromTable['number_of_customer']);
            $this->cartService->addNewCart($toTableID);
            $this->userService->closeTable($fromTable);
            $this->cartService->delete($fromTableID);
            $toTable = $this->userService->getUserById($toTableID);
            $fromOrder = $this->orderService->getConfirmOrderByTableID($fromTableID);
            if ($fromOrder) {
                $this->orderService->updateOrderToNewTable($fromOrder, $toTable);
                $dishInOrder = $this->dishInOrderService->getAllDishInOrderByTableID($fromTableID);
                $dishInOrderCompleted = $this->dishInOrderService->getAllDishInOrderByTableIDCompleted($fromTableID);
                if ($dishInOrder->toArray() != []) {
                    $this->dishInOrderService->updateDishInOrderToNewTable($dishInOrder, $toTable);
                }
                if ($dishInOrderCompleted->toArray() != []) {
                    $this->dishInOrderService->updateDishInOrderCompletedToNewTable($dishInOrderCompleted, $toTable);
                }
            }

            $fromQueueOrder = $this->queueOrderService->getQueueOrderByTableID($fromTableID);
            if ($fromQueueOrder) {
                $this->queueOrderService->updateQueueOrderToNewTable($fromQueueOrder, $toTable);
            }

            return $this->successResponse(null, 'Success');
        } else {
            return $this->errorResponse('Table not found or not empty', null, false, Res::HTTP_NO_CONTENT);
        }

    }
}
