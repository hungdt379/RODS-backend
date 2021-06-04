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

        DB::table('user')->truncate();
        DB::table('user')->insert([
            'full_name' => 'Dang The Hung',
            'username' => 'receptionist',
            'password'  => Hash::make('123'),
            'is_active' => true,
            'role' => 'r',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'NV',
            'username' => 'waiter',
            'password'  => Hash::make('123'),
            'is_active' => true,
            'role' => 'w',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('user')->insert([
            'full_name' => 'Quan li bep',
            'username' => 'kitchenmanager',
            'password'  => Hash::make('123'),
            'is_active' => true,
            'role' => 'k',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB1',
            'username' => 'table1',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB2',
            'username' => 'table2',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB3',
            'username' => 'table3',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB4',
            'username' => 'table4',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB5',
            'username' => 'table5',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB6',
            'username' => 'table6',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB7',
            'username' => 'table7',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB8',
            'username' => 'table8',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB9',
            'username' => 'table9',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB10',
            'username' => 'table10',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB11',
            'username' => 'table11',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB12',
            'username' => 'table12',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB13',
            'username' => 'table13',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB14',
            'username' => 'table14',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('user')->insert([
            'full_name' => 'MB15',
            'username' => 'table15',
            'password'  => Hash::make('123'),
            'is_active' => false,
            'role' => 't',
            'numberOfCustomer' => 0,
            'remember_token' => [],
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
