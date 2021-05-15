<?php

namespace App\Domain\Repositories;

use App\User;

class CustomerRepository extends BaseRepository
{
    /**
    * Associated Repository Model.
    */
    const MODEL = User::class;

    /**
     * Update user info
     *
     * @param User $user
     * @param array $dataUpdate
     *
     * @return bool
     */
    public function updateAdmin(User $user, array $dataUpdate)
    {
        return $user->update($dataUpdate);
    }

    /**
     * Get an User by user Id
     *
     * @param $userId
     * @return mixed
     */
    public function getSingle($userId)
    {
        return $this->query()->where('id', $userId)->lockForUpdate()->first();
    }


}
