<?php

use Illuminate\Database\Seeder;

class DishInOrder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dish_in_order')->truncate();
        DB::table('dish_in_order')->insert([
            'table_id' => '60bf956e37610000b8004ec6',
            'table_name' => 'Bàn 2',
            'item_id' => '60c244acc861000091001532',
            'quantity' => '7',
            'category_id' => '60c244a6e06b000084000b44',
            'status' => 'prepare',
            'ts' => time()
        ]);

    }
}
