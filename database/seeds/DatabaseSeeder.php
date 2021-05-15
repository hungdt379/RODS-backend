<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //TODO: can change Role permission to using laravel packages
        $this->call(UsersSeeder::class);
    }
}
