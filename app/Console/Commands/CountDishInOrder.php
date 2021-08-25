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
        DishInOrder::where('status', DishInOrder::ORDER_ITEM_STATUS_PREPARE)->delete();
        foreach ($dishInOrder as $item){
            if (time() - $item['ts'] >= 600){
                $item['is_late'] = true;
            }
            $newItem = new DishInOrder();
            //$newItem->_id = $item['_id'];
            $newItem->order_id = $item['order_id'];
            $newItem->order_code = $item['order_code'];
            $newItem->table_id = $item['table_id'];
            $newItem->table_name = $item['table_name'];
            $newItem->item_id = $item['item_id'];
            $newItem->item_name = $item['item_name'];
            $newItem->quantity = $item['quantity'];
            $newItem->status = $item['status'];
            $newItem->category_id = $item['category_id'];
            $newItem->ts = $item['ts'];
            $newItem->is_late = $item['is_late'];
            $newItem->save();
        }

    }
}
