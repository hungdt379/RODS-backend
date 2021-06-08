<?php

namespace App\Http\Controllers;


use App\Domain\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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
        $data = $this->userService->getListTable($param);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $param['pageSize'], $data->total());
    }

    public function openTable()
    {
        $param = request()->all();
        $this->userService->openTable($param);
        return $this->successResponse('', 'Open table successfully');

    }

    public function closeTable()
    {
        $param = request()->all();
        $this->userService->closeTable($param);
        return $this->successResponse('', 'Close table successfully');

    }
}
