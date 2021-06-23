<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Notification;

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

    public function checkQueueNotification($ref)
    {
        return $this->database->getReference($ref)
            ->getSnapshot()->getValue();
    }

    public function insertMongo($notification)
    {
        $notification->save();
    }

    public function insertFirebase($ref, $data)
    {
        $this->database->getReference($ref)->set($data);
    }

    public function getNotificationByReceiver($receiver, $pageSize)
    {
        return Notification::where('receiver', $receiver)->paginate((int)$pageSize);
    }

    public function removeReferenceAfterRead($ref)
    {
        return $this->database->getReference($ref)->remove();
    }

    public function getNotificationByTableId($tableID, $pageSize)
    {
        return Notification::where('user_id', $tableID)
            ->where('receiver', Notification::RECEIVER_WAITER)
            ->paginate((int) $pageSize);
    }

}
