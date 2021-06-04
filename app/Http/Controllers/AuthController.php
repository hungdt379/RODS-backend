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
    private $userRepository;

    public function __construct(UserService $userService, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;

    }

    public function dataWithToken($token)
    {
        $data = [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'user_id' => JWTAuth::user()->_id,
                'user_name' => JWTAuth::user()->username,
            ]
        ];

        return $this->successResponse($data, 'Login successful');
    }

    public function login(Request $request)
    {
        $input = $request->only('username', 'password');
        $params = $request->all();


        if (!$token = JWTAuth::attempt($params)) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }


        return $this->dataWithToken($token);
    }

    public function loginForTable(Request $request)
    {
        $params = $request->all();

        if (!$token = JWTAuth::attempt($params)) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }


        if (JWTAuth::user()->is_active == 'true') {
            if (JWTAuth::user()->role == 't') {
                $this->userRepository->append($token);
            }
            return $this->dataWithToken($token);
        }

        return response()->json([
            'status' => false,
            'message' => 'Table is closed',
        ], 401);
    }

    public function hello()
    {
        return response()->json('hello bae');
    }
}
