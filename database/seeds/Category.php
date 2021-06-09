<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Category extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->truncate();
        DB::table('category')->insert([
            'name' => 'combo',
        ]);
        DB::table('category')->insert([
            'name' => 'drink',
        ]);
        DB::table('category')->insert([
            'name' => 'fast',
        ]);
    }
}
