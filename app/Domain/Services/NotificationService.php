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

    public function notification($content, $title, $user, $receiver, $ref)
    {
        $notifcation = new Notification();
        $notifcation->user_id = $user->_id;
        $notifcation->user_fulname = $user->full_name;
        $notifcation->read = false;
        $notifcation->title = $title;
        $notifcation->content = $content;
        $notifcation->receiver = $receiver;
        $notifcation->ts = time();
        $this->notificationRepository->insertMongo($notifcation);

        $data = [
            'user_id' => $user->_id,
            'user_fullname' => $user->full_name,
            'read' => false,
            'title' => $title,
            'content' => $content,
            'receiver' => $receiver,
            'ts' => time(),
            'user_id_read' => $user->_id . '_false' // check bàn không cho gửi nhiều thông báo
        ];
        $this->notificationRepository->insertFirebase($ref . $notifcation->_id, $data);
    }

    public function checkQueueNotification($user, $ref)
    {
        $check = $this->notificationRepository->checkQueueNotification($ref, $user);
        if ($check != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getNotificationByReceiver($receiver, $pageSize)
    {
        return $this->notificationRepository->getNotificationByReceiver($receiver, $pageSize);
    }

}
