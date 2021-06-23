<?php

namespace App\Http\Controllers;


use App\Domain\Services\UserService;
use App\Traits\ApiResponse;
use JWTAuth;

class UserController extends Controller
{
    use ApiResponse;

    private $userService;
    private $cartController;

    /**
     * UserController constructor.
     * @param $userService
     * @param $cartController
     */
    public function __construct(UserService $userService,CartController $cartController)
    {
        $this->userService = $userService;
        $this->cartController = $cartController;
    }


    public function getListTable()
    {
        $param = request()->all();
        $data = $this->userService->getListTable($param['pageSize']);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $param['pageSize'], $data->total());
    }

    public function openTable()
    {
        $param = request()->all();
        $table = $this->userService->openTable($param['table_id'], $param['number_of_customer']);
        $cart = $this->cartController->store($param['table_id']);
        $data =['table' => $table, 'cart' => $cart];
        return $this->successResponse($data, 'Open table successfully');
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
        $this->cartController->destroy();
        $this->userService->deleteRememberToken($user);
        return $this->successResponse(null, 'Close table successful');
    }

    public function updateNumberOfCustomer()
    {
        $param = request()->all();
        $this->userService->updateNumberOfCustomer($param['table_id'], $param['number_of_customer']);
        return $this->successResponse('', 'Update successfully');
    }
}
