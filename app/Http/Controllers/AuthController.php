<?php

namespace App\Http\Controllers;

use App\Domain\Repositories\UserRepository;
use App\Traits\ApiResponse;
use App\Domain\Services\UserService;
use JWTAuth;
use App\Domain\Entities\User;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    use ApiResponse;

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function dataWithToken($token)
    {
        $data = [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'user_id' => JWTAuth::user()->_id,
                'user_name' => JWTAuth::user()->username,
                'role' => JWTAuth::user()->role,
                'number_of_customer' => JWTAuth::user()->number_of_customer
            ]
        ];

        return $this->successResponse($data, 'Login successful');
    }

    public function login(Request $request)
    {
        $params = $request->all();

        if (!$token = JWTAuth::attempt($params)) {
            return $this->errorResponse('Invalid Email or Password', null, false, 401);
        }

        if (JWTAuth::user()->role == 't') {
            return $this->errorResponse('Access denied', null, false, 401);
        }

        return $this->dataWithToken($token);
    }

    public function loginForTable(Request $request)
    {
        $params = $request->all();

        if (!$token = JWTAuth::attempt($params)) {
            return $this->errorResponse('Invalid Email or Password', null, false, 401);
        }

        if (JWTAuth::user()->is_active != 'true' || JWTAuth::user()->role != 't') {
            return $this->errorResponse('Access denied', null, false, 401);
        }

        $user = JWTAuth::user();
        $this->userService->appendRememberToken($token, $user->_id);
        return $this->dataWithToken($token);
    }

    public function hello()
    {
        return response()->json('hello bae');
    }
}
