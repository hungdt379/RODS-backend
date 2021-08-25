<?php

namespace App\Console\Commands;

use App\Domain\Entities\DishInOrder;
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
        $dishInOrder = DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)->get()->toArray();
        foreach ($dishInOrder as $item){
            if (time() - $item['ts'] >= 600){
                $item['is_late'] = true;
            }
            $newItem = new DishInOrder();
            $newItem = $item;
            $newItem->save();
        }
        DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)->delete();
    }
}
