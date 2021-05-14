<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServerDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $server = \App\Domain\Entities\Server::all()->first();
        $serverDetail = \App\Domain\Entities\ServerDetail::all()->last();

        $serverDetail->server()->associate($server);
        $serverDetail->save();

        $server->details()->save($serverDetail);
        $server->save();
    }
}
