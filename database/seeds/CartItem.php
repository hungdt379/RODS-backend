<?php

use Illuminate\Database\Seeder;

class CartItem extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(' cart_item')->truncate();
        DB::table('cart_item')->insert([
            'cart_key' => 'c86c8991b5baf8103c8c071f00b56e4e',
            'product_id' => '60c244acc861000091001538',
            'quantity' => 3,
            'note' => 'abc'
        ]);
    }
}
