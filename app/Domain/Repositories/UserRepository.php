<?php


namespace App\Domain\Repositories;


class UserRepository
{


    public function append($user)
    {
        $user->save();
    }
}
