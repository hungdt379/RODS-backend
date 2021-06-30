<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Domain\Services\UserService;
use PDF;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
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
            return $this->errorResponse('Invalid Username or Password', null, false, 401);
        }

        if (JWTAuth::user()->role == 't') {
            return $this->errorResponse('Access denied', null, false, 401);
        }

        return $this->dataWithToken($token);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return $this->successResponse('', 'Logout successfully');
    }

    public function loginForTable(Request $request)
    {
        $params = $request->all();

        if (!$token = JWTAuth::attempt($params)) {
            return $this->errorResponse('Invalid Username or Password', null, false, 401);
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
        // 1 milimet = 2.838 point

        $customPaper = array(0,0,567.00,283.80); // (10*20 cm)
        $pdf = PDF::loadHTML('<h1>Test</h1> ')->setPaper($customPaper, 'landscape');
        $nameFile = '_' . time() . '.pdf';
        //return response()->file($pdf);
        Storage::disk('public')->put($nameFile, $pdf->output());
        $url = asset('export/' . $nameFile);
        return response()->json($url);
    }
}
