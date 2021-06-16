<?php

namespace App\Http\Controllers;


use App\Domain\Services\UserService;
use App\Traits\ApiResponse;
use JWTAuth;

class UserController extends Controller
{
    use ApiResponse;

    private $userService;

    /**
     * UserController constructor.
     * @param $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        $this->userService->openTable($param['table_id'], $param['number_of_customer']);
        return $this->successResponse('', 'Open table successfully');
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
