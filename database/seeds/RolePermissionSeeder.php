<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = \App\Domain\Entities\Role::first();
        $permissions = \App\Domain\Entities\Permission::get();
        $role->permissions()->sync($permissions);
        $role->save();
    }
}
