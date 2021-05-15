<?php


namespace App\Domain\Services;


use App\Domain\Entities\User;

class UserService
{
    public function login($username, $password){
       return User::where('username', $username)->where('password', (int) $password)->first();
    }
}
