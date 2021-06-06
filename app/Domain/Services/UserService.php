<?php


namespace App\Domain\Services;


use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function appendRememberToken($token, $userID){
        $this->userRepository->appendRememberToken($token, $userID);
    }

}
