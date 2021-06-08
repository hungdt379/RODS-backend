<?php

namespace App\Http\Controllers;


use App\Domain\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function index()
    {
        $users = User::all();

        return response()->json($users, 200);
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
        $user = JWTAuth::user();
        var_dump($user['remember_token']);
        $this->userService->closeTable($param);
        try {
            JWTAuth::invalidate($user['remember_token']);

        } catch (JWTException $exception) {
            return $this->errorResponse('Sorry, the user cannot be logged out', null, false, 500);
        }
        return $this->successResponse('', 'Close table successfully');

    }
}
