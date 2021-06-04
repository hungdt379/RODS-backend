<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository
{


    public function append($token)
    {
        $user = JWTAuth::user();
        User::where('_id', $user->_id)->push('remember_token', [$token]);
    }
}
