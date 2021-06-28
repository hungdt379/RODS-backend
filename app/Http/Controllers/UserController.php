<?php

namespace App\Http\Controllers;


use App\Domain\Services\CartService;
use App\Domain\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class UserController extends Controller
{
    use ApiResponse;

    private $userService;
    private $cartService;
    private $cartController;

    /**
     * UserController constructor.
     * @param $userService
     * @param $cartService
     * @param $cartController
     */
    public function __construct(UserService $userService, CartService $cartService, CartController $cartController)
    {
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->cartController = $cartController;
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
            return $this->errorResponse('Table is not exist', '', false, 404);
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

        if ($param['number_of_customer'] > 8 || $param['number_of_customer'] < 1) {
            return $this->errorResponse('Number of customer must be greater or equal 1 and less or equal 8', null, false, 400);
        }

        $this->userService->openTable($param['table_id'], $param['number_of_customer']);
        $this->cartService->addNewCart($param['table_id']);
        return $this->successResponse(null, 'Open table successfully');
    }

    public function closeTable()
    {
        $param = request()->all();
        $user = $this->userService->getUserById($param['table_id']);
        $ctoken = JWTAuth::getToken();
        foreach ($user['remember_token'] as $token) {
            if ($token != $ctoken) {
                JWTAuth::setToken($token);
                JWTAuth::invalidate();
            }
        }
        JWTAuth::setToken($ctoken);
        $this->userService->closeTable($user);
        $this->cartService->delete($param['table_id']);
        return $this->successResponse(null, 'Close table successful');
    }

    public function updateNumberOfCustomer()
    {
        $param = request()->all();
        $this->userService->updateNumberOfCustomer($param['table_id'], $param['number_of_customer']);
        return $this->successResponse('', 'Update successfully');
    }
}
