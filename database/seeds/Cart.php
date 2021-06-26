<?php

use Illuminate\Database\Seeder;

class Cart extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(' cart')->truncate();
        DB::table('cart')->insert([
            'cart_key' => 'c86c8991b5baf8103c8c071f00b56e4e',
            'table_id' => '60bf956e37610000b8004ec6',

        ]);

    }
}
