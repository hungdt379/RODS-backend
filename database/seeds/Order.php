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
            'table_id' => '60bcd6ef911e000042003ec5',
            'table_name' => 'Bàn 1',
            'status' => 'confirmed',
            'combo' => [
                '_id' => '60c083b9ee26000095002fd2',
                'name' => 'Combo nướng 129k',
                'cost' => 129000,
                'description' => null,
                'image' => 'http://165.227.99.160/image/nuong-129.png',
                'hotpot' => true,
                'category_id' => '60c083b495040000200069a2'
            ],
            'total_cost' => 129000*5,
            'note' => 'abc',
            'ts' => time()
        ]);
    }
}
