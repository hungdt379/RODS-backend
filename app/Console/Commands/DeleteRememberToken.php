<?php

namespace App\Console\Commands;

use App\Domain\Entities\User;
use Illuminate\Console\Command;

class DeleteRememberToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete remember token per day';

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
        User::where('role', '=', 't')->update(['remember_token' => []]);
    }
}
