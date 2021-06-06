<?php


namespace App\Domain\Repositories;

use App\Domain\Entities\User;

class UserRepository
{
    public function appendRememberToken($token, $userID)
    {
        User::where('_id', $userID)->push('remember_token', [$token]);
    }
}
