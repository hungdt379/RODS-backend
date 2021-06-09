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
            'content'=>'Do an ngon, phuc vu tot'
        ]);
        DB::table('feedback')->insert([
            'rate_service' => 'hai long',
            'rate_dish'=>'binh thuong',
            'content'=>'Phuc vu tot'
        ]);
    }
}
