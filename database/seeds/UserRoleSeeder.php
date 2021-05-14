<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::first();
        $role = \App\Domain\Entities\Role::with('permissions')->first();
        $user->role()->save($role);
        $user->save();
        $role->users()->save($user);
        $role->save();
    }
}
