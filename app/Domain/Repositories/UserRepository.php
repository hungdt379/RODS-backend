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
        return DB::table('user')
            ->where('role', '=', 't')
            ->paginate($pageSize);
    }
}
