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
            'name' => 'Ba chỉ bò'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Ba chỉ lợn'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Xúc xích'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Sụn'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Bắp bò'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Kim chi'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Rau củ quả'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Gà'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Mực'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Tôm'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Bạch tuộc'
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$hotpot->_id],
            'name' => 'Mì tôm'
        ]);
    }
}
