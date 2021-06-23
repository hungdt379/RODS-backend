<?php


namespace App\Http\Controllers;


use App\Domain\Entities\Notification;
use App\Domain\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JWTAuth;

class NotificationController extends Controller
{
    use ApiResponse;

    private $notificationService;
    protected $database;

    /**
     * NotificationController constructor.
     * @param $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->database = app('firebase.database');
    }

    //for customer
    public function notificationFromCustomer()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'title' => Rule::in(Notification::CUSTOMER_TITLE),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('', null, false, 400);
        }

        if ($param['title'] == Notification::TITLE_CALL_WAITER) {
            $receiver = Notification::RECEIVER_WAITER;
            $ref = Notification::REFERENCE_CALL_WAITER;
        } else if ($param['title'] == Notification::TITLE_CALL_PAYMENT) {
            $receiver = Notification::RECEIVER_RECEPTIONIST;
            $ref = Notification::REFERENCE_CALL_PAYMENT;
        }

        $user = JWTAuth::user();

        $checkQueue = $this->notificationService->checkQueueNotification($user, $ref);
        if ($checkQueue) {
            return $this->errorResponse('Your requirement is processing', null, false, 202);
        }

        $this->notificationService->notification($param['content'], $param['title'], $user, $receiver, $ref);
        return $this->successResponse('', 'Success');

    }

    //for waiter
    public function getNotificationOfEachTable()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'table_id' => 'required|alpha_num|30',
            'page' => 'required|integer|max:10',
            'pageSize' => 'required|integer|max:10'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('', null, false, 400);
        }


    }

    // for kitchen manager and receptionist
    public function getAllNotification()
    {
        $param = request()->all();

        $validator = Validator::make($param, [
            'page' => 'required|integer|max:10',
            'pageSize' => 'required|integer|max:10',
            'receiver' => Rule::in(Notification::RECEIVER_ALL_NOTIFICATION)
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('', null, false, 400);
        }

        $data = $this->notificationService->getNotificationByReceiver($param['receiver'], $param['pageSize']);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $param['pageSize'], $data->total());
    }

}
