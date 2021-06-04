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

    public function append($token)
    {
        $user = JWTAuth::user();
        User::where('_id', $user->_id)->push('remember_token', [$token]);

        $this->userRepository->append($user);

    }
}
