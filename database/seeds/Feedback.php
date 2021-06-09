<?php

use Illuminate\Database\Seeder;

class Feedback extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feedback')->truncate();
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'ngon',
            'content'=>'Do an ngon, phuc vu tot',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot 2',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot 3',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot 4',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot 5',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot 6',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot 7',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot 8',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

    }
}
