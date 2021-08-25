<?php

namespace App\Console\Commands;

use App\Domain\Entities\DishInOrder;
use App\Domain\Entities\Notification;
use App\Domain\Entities\User;
use App\Domain\Repositories\NotificationRepository;
use App\Domain\Services\NotificationService;
use Illuminate\Console\Command;

class CountDishInOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count time to export dish in order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     *
     */
    public function handle()
    {
        $notificationRepo = new NotificationRepository();
        $notification = new NotificationService($notificationRepo);
        $dishInOrder = DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)->get()->toArray();
        $kt = User::where('username', 'QLB')->first();

        foreach ($dishInOrder as $item) {
            if (time() - $item['ts'] >= 600 && !isset($item['is_late'])) {
                $notification->notification(null, Notification::TITLE_DISH_LATE_VN, Notification::TITLE_DISH_LATE_EN, $kt, [Notification::RECEIVER_KITCHEN_MANAGER]);
                break;
            }
        }

        foreach ($dishInOrder as $item) {
            if (time() - $item['ts'] >= 600 && !isset($item['is_late'])) {
                $groupByUserID = DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)->groupBy('table_id')->get()->toArray();
                foreach ($groupByUserID as $value) {
                    $user = User::where('_id', $value['table_id'])->first();
                    $notification->notification(null, Notification::TITLE_DISH_LATE_VN, Notification::TITLE_DISH_LATE_EN, $user, [Notification::RECEIVER_WAITER]);
                }
                break;
            }
        }

        foreach ($dishInOrder as $item) {
            if (time() - $item['ts'] >= 600) {
                $updateItem = DishInOrder::where('_id', $item['_id'])->first();
                $updateItem->is_late = true;
                $updateItem->save();
            }
        }
    }
}
