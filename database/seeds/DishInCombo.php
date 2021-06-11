<?php

use Illuminate\Database\Seeder;
use App\Domain\Entities\Menu;

class DishInCombo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $combo1 = Menu::where('name', 'Combo nướng 129k')->first();
        $combo2 = Menu::where('name', 'Combo nướng 169k')->first();
        $combo3 = Menu::where('name', 'Combo lẩu nướng 209k')->first();
        $hotpot = Menu::where('name', 'Lẩu')->first();
        DB::table('dish_in_combo')->truncate();
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Ba chi bo'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Ba chi lon'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Xuc xich'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Sun'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Bap bo'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Kim chi'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Rau cu qua'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Ga'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Muc'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Tom'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Bach tuoc'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$hotpot->_id],
            'name' => 'Mi tom'
        ]);
    }
}
