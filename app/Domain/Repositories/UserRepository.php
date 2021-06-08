<?php


namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function appendRememberToken($token, $userID)
    {
        User::where('_id', $userID)->push('remember_token', [$token]);
    }

    public function getListTable($pageSize)
    {
        return User::where('role', 't')
            ->paginate((int)$pageSize);
    }

    public function getUserById($id)
    {
        return User::where('_id', $id)->first();
    }

    public function update($user)
    {
        return $user->save();
    }
}
