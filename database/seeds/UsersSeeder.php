<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = DB::table('roles')->select('id')->first();
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'full_name' => 'Administrator',
            'email' => 'admin@vieted.com',
            'password'  => Hash::make('123456'),
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'deleted_at' => null
        ]);
        DB::table('users')->insert([
            'full_name' => 'LMS',
            'email' => 'lms@vieted.com',
            'password'  => Hash::make('123456'),
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'deleted_at' => null
        ]);
    }
}
