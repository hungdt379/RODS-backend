<?php


namespace App\Domain\Services;


use App\Domain\Repositories\UserRepository;

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

    public function getListTable($pageSize)
    {
        return $this->userRepository->getListTable($pageSize);
    }

    public function getUserById($userID)
    {
        return $this->userRepository->getUserById($userID);
    }

    public function openTable($userID, $numberOfCustomer)
    {
        $updateUser = $this->getUserById($userID);
        $updateUser->is_active = true;
        $updateUser->number_of_customer = (int)$numberOfCustomer;
        $this->userRepository->update($updateUser);

        return $updateUser;
    }

    public function deleteRememberToken($user)
    {
        $user['remember_token'] = [];
        $user->is_active = false;
        return $this->userRepository->update($user);
    }

    public function updateNumberOfCustomer($userID, $numberOfCustomer)
    {
        $updateUser = $this->getUserById($userID);
        $updateUser->number_of_customer = (int) $numberOfCustomer;

        return $this->userRepository->update($updateUser);
    }
}
