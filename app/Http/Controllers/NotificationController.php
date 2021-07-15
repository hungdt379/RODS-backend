<?php


namespace App\Http\Controllers;


use App\Domain\Entities\Notification;
use App\Domain\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JWTAuth;
use \Illuminate\Http\Response as Res;

class NotificationController extends Controller
{
    use ApiResponse;

    private $notificationService;

    /**
     * NotificationController constructor.
     * @param $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function callWaiter()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'content' => 'max:255'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $user = JWTAuth::user();

        $checkQueue = $this->notificationService->checkQueueNotification(Notification::RECEIVER_WAITER . '/' . $user->_id . '/' . Notification::TITLE_CALL_WAITER_EN);
        if ($checkQueue) {
            return $this->errorResponse('Your requirement is processing', null, false, 202);
        }

        $re = [Notification::RECEIVER_WAITER];
        $this->notificationService->notification($param['content'], Notification::TITLE_CALL_WAITER_VN, Notification::TITLE_CALL_WAITER_EN, $user, $re);
        return $this->successResponse('', 'Success');
    }

    public function callPayment()
    {
        $user = JWTAuth::user();

        $checkQueueWaiter = $this->notificationService->checkQueueNotification(Notification::RECEIVER_WAITER . '/' . $user->_id . '/' . Notification::TITLE_CALL_PAYMENT_EN);
        $checkQueueReceptionist = $this->notificationService->checkQueueNotification(Notification::RECEIVER_RECEPTIONIST . '/' . $user->_id);
        if ($checkQueueWaiter && $checkQueueReceptionist) {
            return $this->errorResponse('Your requirement is processing', null, false, 202);
        }

        $re = [Notification::RECEIVER_WAITER, Notification::RECEIVER_RECEPTIONIST];
        $this->notificationService->notification(null, Notification::TITLE_CALL_PAYMENT_VN, Notification::TITLE_CALL_PAYMENT_EN, $user, $re);
        return $this->successResponse('', 'Success');
    }

    //for waiter
    public function getNotificationOfEachTable()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'table_id' => 'required|alpha_num',
            'page' => 'required|integer',
            'pageSize' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid params', null, false, 400);
        }

        $data = $this->notificationService->getNotificationOfEachTable($param['table_id'], $param['pageSize']);
        if (sizeof($data) == 0) {
            return $this->errorResponse('Have no notification', null, false, Res::HTTP_NO_CONTENT);
        }

        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $param['pageSize'], $data->total());

    }

    // for kitchen manager and receptionist
    public function getAllNotification()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'page' => 'required|integer',
            'pageSize' => 'required|integer',
            'receiver' => Rule::in(Notification::RECEIVER_ALL_NOTIFICATION)
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid params', null, false, 400);
        }

        $data = $this->notificationService->getNotificationByReceiver($param['receiver'], $param['pageSize']);
        if (sizeof($data) == 0) {
            return $this->errorResponse('Have no notification', null, false, Res::HTTP_NO_CONTENT);
        }

        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $param['pageSize'], $data->total());
    }

    public function markAsReadOfWaiter()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'table_id' => 'required|alpha_num'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $this->notificationService->markAsRead(Notification::RECEIVER_WAITER, $param['table_id']);
        return $this->successResponse('', 'Success');

    }

    // for receptionist and kitchen manager
    public function markAsRead()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'receiver' => Rule::in(Notification::RECEIVER_ALL_NOTIFICATION)
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $this->notificationService->markAsRead($param['receiver'], '');
        return $this->successResponse('', 'Success');

    }

}
