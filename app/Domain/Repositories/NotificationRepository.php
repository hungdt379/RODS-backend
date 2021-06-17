<?php


namespace App\Domain\Repositories;


class NotificationRepository
{
    protected $database;

    /**
     * NotificationRepository constructor.
     */
    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    public function checkQueueNotification($ref, $user){
        return $this->database->getReference($ref)
            ->orderByChild('user_id_read')
            ->equalTo($user->id.'_false')
            ->getSnapshot()->getValue();
    }

    public function insertMongo($notification){
        $notification->save();
    }

    public function insertFirebase($ref, $data){
        $this->database->getReference($ref)->set($data);
    }

}
