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
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(ServerSeeder::class);
//        $this->call(ServerDetailSeeder::class);
//        $this->call(ClassInformationSeeder::class);
    }
}
