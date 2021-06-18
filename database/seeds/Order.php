<?php

use Illuminate\Database\Seeder;

class Order extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order')->truncate();
        DB::table('order')->insert([
            'number_of_customer' => 5,
            'table_id' => '60bf956e37610000b8004ec5',
            'table_name' => 'Bàn 1',
            'status' => 'queue',
            'combo' => [
                '_id' => '60c244acc861000091001532',
                'name' => 'Combo nướng 129k',
                'cost' => 129000,
                'hotpot' => false,
                'dish_in_combo' => [
                    'dish1' => 'bo',
                    'dish2' => 'lon'
                ]
            ],
            'side_dish_drink' => [
                'item1' => [
                    '_id' => '60c244acc861000091001536',
                    'name' => 'Khoai Lang Kén',
                    'cost' => 30000,
                    'qty' => 1,
                    'note' => 'abc',
                    'total_cost' => 30000 * 1
                ],
                'item2' => [
                    '_id' => '60c244acc86100009100153a',
                    'name' => 'bia',
                    'cost' => 15000,
                    'qty' => 1,
                    'note' => 'bac',
                    'total_cost' => 15000 * 1
                ]
            ],
            'total_cost' => 129000 * 5 + 30000 * 1 + 15000 * 1,
            'note' => 'abc',
            'ts' => time()
        ]);
    }
}
