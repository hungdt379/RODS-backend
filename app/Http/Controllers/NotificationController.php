<?php


namespace App\Http\Controllers;


use App\Domain\Services\NotificationService;
use App\Traits\ApiResponse;
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

    public function callWaiterNotification()
    {
        $param = request()->all();
        $user = JWTAuth::user();
        $checkQueue = $this->notificationService->checkQueueNotification($user);
        if ($checkQueue) {
            return $this->errorResponse('Your requrement is processing', null,false,202);
        }

        $this->notificationService->callWaiterNotification($param, $user);
        return $this->successResponse('', 'Success');
    }

    public function testSaveDataToFireBase()
    {
        //request()->all();
        $data = [
            'name' => 'kakak',
            'email' => '1234'
        ];
//        $push = $this->database->getReference('/test')->set([
//            'name' => 'kakak',
//            'email' => 'dang the hung'
//        ]);
        $push = $this->database->getReference('/call_waiter')
            ->orderByChild('user_id_read')
            ->equalTo('60bcd6ef911e000042003ec5_false')
            ->getSnapshot()->getValue();

        //$push = $this->database->getReference('/call_waiter/60bcd6ef911e000042003ec5')->set(['read' => true]);
        return response()->json($push);
    }
}
