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
        DB::table('user')->where('role', 't')->update(['max_customer'=>6]);

//        DB::table('user')->truncate();
//        DB::table('user')->insert([
//            'full_name' => 'Thu Ngân',
//            'username' => 'TN',
//            'password'  => Hash::make('123'),
//            'is_active' => true,
//            'role' => 'r',
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bồi bàn',
//            'username' => 'BB',
//            'password'  => Hash::make('123'),
//            'is_active' => true,
//            'role' => 'w',
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//
//        DB::table('user')->insert([
//            'full_name' => 'Quản lý bếp',
//            'username' => 'QLB',
//            'password'  => Hash::make('123'),
//            'is_active' => true,
//            'role' => 'k',
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 1',
//            'username' => 'MB01',
//            'password'  => Hash::make('123'),
//            'is_active' => true,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 2',
//            'username' => 'MB02',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 3',
//            'username' => 'MB03',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 4',
//            'username' => 'MB04',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 5',
//            'username' => 'MB05',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 6',
//            'username' => 'MB06',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 7',
//            'username' => 'MB07',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 8',
//            'username' => 'MB08',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 9',
//            'username' => 'MB09',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 10',
//            'username' => 'MB10',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 11',
//            'username' => 'MB11',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 12',
//            'username' => 'MB12',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 13',
//            'username' => 'MB13',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 14',
//            'username' => 'MB14',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
//        DB::table('user')->insert([
//            'full_name' => 'Bàn 15',
//            'username' => 'MB15',
//            'password'  => Hash::make('123'),
//            'is_active' => false,
//            'role' => 't',
//            'number_of_customer' => 0,
//            'remember_token' => [],
//            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
//        ]);
    }
}
