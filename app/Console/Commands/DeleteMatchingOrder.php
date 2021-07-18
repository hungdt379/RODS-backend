<?php

namespace App\Console\Commands;

use App\Domain\Entities\Order;
use Illuminate\Console\Command;

class DeleteMatchingOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:matching';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete matching order per day';

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
     * @return int
     */
    public function handle()
    {
        Order::where('status', Order::ORDER_STATUS_MATCHING)->delete();
    }
}
