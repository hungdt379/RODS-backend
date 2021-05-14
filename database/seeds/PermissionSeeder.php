<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->truncate();
        // User
        DB::table('permissions')->insert([
            'name' => 'create_user',
            'display_name' => 'Create User',
            'module_name'  => \App\Constants\ModuleName::USER,
        ]);
        DB::table('permissions')->insert([
            'name' => 'edit_user',
            'display_name' => 'Edit User',
            'module_name'  => \App\Constants\ModuleName::USER
        ]);
        DB::table('permissions')->insert([
            'name' => 'delete_user',
            'display_name' => 'Delete User',
            'module_name'  => \App\Constants\ModuleName::USER
        ]);
        DB::table('permissions')->insert([
            'name' => 'show_user',
            'display_name' => 'Show User',
            'module_name'  => \App\Constants\ModuleName::USER
        ]);
        DB::table('permissions')->insert([
            'name' => 'active_user',
            'display_name' => 'Active/Deacative User',
            'module_name'  => \App\Constants\ModuleName::USER
        ]);

        // Group
        DB::table('permissions')->insert([
            'name' => 'create_group',
            'display_name' => 'Create Group',
            'module_name'  => \App\Constants\ModuleName::GROUP,
        ]);
        DB::table('permissions')->insert([
            'name' => 'edit_group',
            'display_name' => 'Edit Group',
            'module_name'  => \App\Constants\ModuleName::GROUP
        ]);
        DB::table('permissions')->insert([
            'name' => 'delete_group',
            'display_name' => 'Delete Group',
            'module_name'  => \App\Constants\ModuleName::GROUP
        ]);
        DB::table('permissions')->insert([
            'name' => 'show_user',
            'display_name' => 'Show Group',
            'module_name'  => \App\Constants\ModuleName::GROUP
        ]);
        DB::table('permissions')->insert([
            'name' => 'active_group',
            'display_name' => 'Active/Deacative Group',
            'module_name'  => \App\Constants\ModuleName::GROUP
        ]);

        // Group
        DB::table('permissions')->insert([
            'name' => 'create_server',
            'display_name' => 'Create Server',
            'module_name'  => \App\Constants\ModuleName::SERVER,
        ]);
        DB::table('permissions')->insert([
            'name' => 'edit_server',
            'display_name' => 'Edit Server',
            'module_name'  => \App\Constants\ModuleName::SERVER
        ]);
        DB::table('permissions')->insert([
            'name' => 'delete_server',
            'display_name' => 'Delete Server',
            'module_name'  => \App\Constants\ModuleName::SERVER
        ]);
        DB::table('permissions')->insert([
            'name' => 'show_server',
            'display_name' => 'Show Server',
            'module_name'  => \App\Constants\ModuleName::SERVER
        ]);
        DB::table('permissions')->insert([
            'name' => 'active_server',
            'display_name' => 'Active/Deacative Server',
            'module_name'  => \App\Constants\ModuleName::SERVER
        ]);

        // Group
        DB::table('permissions')->insert([
            'name' => 'create_role',
            'display_name' => 'Create Role',
            'module_name'  => \App\Constants\ModuleName::ROLE,
        ]);
        DB::table('permissions')->insert([
            'name' => 'edit_role',
            'display_name' => 'Edit Role',
            'module_name'  => \App\Constants\ModuleName::ROLE
        ]);
        DB::table('permissions')->insert([
            'name' => 'delete_role',
            'display_name' => 'Delete Role',
            'module_name'  => \App\Constants\ModuleName::ROLE
        ]);
        DB::table('permissions')->insert([
            'name' => 'show_role',
            'display_name' => 'Show Role',
            'module_name'  => \App\Constants\ModuleName::ROLE
        ]);
        DB::table('permissions')->insert([
            'name' => 'active_role',
            'display_name' => 'Active/Deacative Role',
            'module_name'  => \App\Constants\ModuleName::ROLE
        ]);

        // Dashboard
        DB::table('permissions')->insert([
            'name' => 'dashboard_class',
            'display_name' => 'View Class Dashboard',
            'module_name'  => \App\Constants\ModuleName::DASHBOARD
        ]);
        DB::table('permissions')->insert([
            'name' => 'dashboard_server',
            'display_name' => 'View Server Dashboard',
            'module_name'  => \App\Constants\ModuleName::DASHBOARD
        ]);
    }
}
