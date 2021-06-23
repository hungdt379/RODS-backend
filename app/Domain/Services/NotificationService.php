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

    /**
     * notification.
     * @param $content (truyền bừ vào)
     * @param $title (title tiếng việt trong class Notification)
     * @param $titleEN (title EN trong class Notification)
     * @param $user (người gửi)
     * @param $re (người nhận)
     */
    public function notification($content, $title, $titleEN, $user, $re)
    {
        for ($i = 0; $i < sizeof($re); $i++) {
            $notifcation = new Notification();
            $notifcation->user_id = $user->_id;
            $notifcation->user_fulname = $user->full_name;
            $notifcation->read = false;
            $notifcation->title = $title;
            $notifcation->content = $content;
            $notifcation->receiver = $re[$i];
            $notifcation->ts = time();
            $this->notificationRepository->insertMongo($notifcation);

            $data = [
                'user_id' => $user->_id,
                'user_fullname' => $user->full_name,
                'read' => false,
                'title' => $title,
                'content' => $content,
                'receiver' => $re[$i],
                'ts' => time()
            ];
            if ($re[$i] == Notification::RECEIVER_WAITER) {
                $this->notificationRepository->insertFirebase($re[$i] . '/' . $user->_id . '/' . $titleEN, $data);
            } else {
                $this->notificationRepository->insertFirebase($re[$i] . '/' . $user->_id, $data);
            }

        }
    }

    public function checkQueueNotification($ref)
    {
        $check = $this->notificationRepository->checkQueueNotification($ref);
        if ($check != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getNotificationOfEachTable($tableID, $pageSize)
    {
        $this->notificationRepository->removeReferenceAfterRead(Notification::RECEIVER_WAITER . '/' . $tableID);
        return $this->notificationRepository->getNotificationByTableId($tableID, $pageSize);
    }

    public function getNotificationByReceiver($receiver, $pageSize)
    {
        $this->notificationRepository->removeReferenceAfterRead($receiver . '/');
        return $this->notificationRepository->getNotificationByReceiver($receiver, $pageSize);
    }

}
