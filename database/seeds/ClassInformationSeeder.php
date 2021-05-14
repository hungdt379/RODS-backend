<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClassInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $server = \App\Domain\Entities\Server::all()->first();
        $firstClass = \App\Domain\Entities\ClassInformation::all()->first();
        $lassClass = \App\Domain\Entities\ClassInformation::all()->last();

        $firstClass->server()->associate($server);
        $firstClass->save();

        $lassClass->server()->associate($server);
        $lassClass->save();

        $server->classInformation()->save($firstClass);
        $server->classInformation()->save($lassClass);
        $server->save();
    }
}
