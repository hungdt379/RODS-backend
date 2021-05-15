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
            'full_name' => 'Dang The Hung',
            'username' => 'hungdt',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('users')->insert([
            'full_name' => 'Nguyen Huy Hoang',
            'username' => 'hoangnh',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
