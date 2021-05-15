<?php


namespace App\Http\Controllers;

use App\Domain\Services\UserService;
use App\Traits\ApiResponse;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;

class UserController
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

    public function dataWithToken($token, $firstLogin = false)
    {
        return $data = [
            'status' => true,
            'first_login' => $firstLogin,
            'token' => $token,
            'token_type' => 'Bearer'
//            'xxx'=>  JWTAuth::factory()->getTTL() * 70,
//            'user' => [
//                'user_id' => JWTAuth::user()->_id,
//                'user_name' => JWTAuth::user()->name,
//                'avatar' => JWTAuth::user()->avatar,
//            ]
        ];
    }

    public function login(Request $request)
    {
        //$input = $request->only('username', 'password');
        $params = $request->all();
        $token = null;
        $existUser = $this->userService->login($params['username'], (int) $params['password']);
        //dd([$existUser['username'], $existUser['password']]);
        if ($existUser){
            //JWTAuth::login($existUser, true);
            try{
                $token = JWTAuth::attempt([$params['username'], (int) $params['password']]);
                $data = $this->dataWithToken($token, false);
                return $this->successResponse($data, 'kakak');
            }catch (\Exception $ex){
                dd($ex);
            }
            //dd($token);

        }
//        if (!$token = JWTAuth::attempt([$params['username'], (int) $params['password']])) {
//            return response()->json([
//                'status' => false,
//                'message' => 'Invalid Email or Password',
//            ], 401);
//        }
//
//        return response()->json([
//            'status' => true,
//            'token' => $token,
//        ]);
    }

    public function hello(){
        return $this->successResponse('', 'hello');
    }

}
