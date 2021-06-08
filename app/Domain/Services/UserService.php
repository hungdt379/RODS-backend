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

    public function appendRememberToken($token, $userID)
    {
        $this->userRepository->appendRememberToken($token, $userID);
    }

    public function getListTable($param)
    {
        return $this->userRepository->getListTable($param['pageSize']);
    }

    public function getUserById($userID){
        return $this->userRepository->getUserById($userID);
    }

    public function openTable($param)
    {
        $updateUser = $this->getUserById($param['id']);
        $updateUser->is_active = true;
        $updateUser->number_of_customer=(int)$param['number_of_customer'];

        return $this->userRepository->update($updateUser);
    }
}
