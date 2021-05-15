<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Domain\Services\UserService;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
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

    public function hello(){
        return response()->json('hello bae');
    }
}
