<?php


namespace App\Domain\Repositories;

use App\Domain\Entities\User;

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

    public function getUserById($userID)
    {
        return User::where('_id', $userID)->first();
    }

    public function update($user)
    {
        return $user->save();
    }

    public function checkExistedTable($username)
    {
        return User::where('username', $username)->first();
    }

    public function checkExistedTableForUpdate($username, $currentUsername)
    {
        return User::where('username', $username)->where('username', 'not like', $currentUsername)->first();
    }

    public function deleteTable($tableId)
    {
        User::where('_id', $tableId)->delete();
    }

    public function getTableNotActive()
    {
        return User::where('role', 't')->where('is_active', false)->get();
    }
}
