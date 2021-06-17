<?php


namespace App\Domain\Services;


use App\Domain\Entities\Notification;
use App\Domain\Repositories\NotificationRepository;

class NotificationService
{
    private $notificationRepository;

    /**
     * NotificationService constructor.
     * @param $notificationRepository
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function callWaiterNotification($param, $user)
    {
        $callWaiterNotifcation = new Notification();
        $callWaiterNotifcation->user_id = $user->_id;
        $callWaiterNotifcation->user_fulname = $user->full_name;
        $callWaiterNotifcation->read = false;
        $callWaiterNotifcation->title = $param['title'];
        $callWaiterNotifcation->content = $param['content'];
        $callWaiterNotifcation->ts = time();
        $this->notificationRepository->insertMongo($callWaiterNotifcation);

        $data = [
            'user_id' => $user->_id,
            'user_fullname' => $user->full_name,
            'read' => false,
            'title' => $param['title'],
            'content' => $param['content'],
            'ts' => time(),
            'user_id_read' => $user->_id . '_false'
        ];
        $this->notificationRepository->insertFirebase('call_waiter/' . $callWaiterNotifcation->_id, $data);
    }

    public function checkQueueNotification($user)
    {
        $check = $this->notificationRepository->checkQueueNotification('call_waiter/', $user);
        if ($check != null) {
            return true;
        } else {
            return false;
        }
    }

}
