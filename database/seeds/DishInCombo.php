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
        DB::table('dish_in_combo')->update(['is_sold_out' => false]);

//        $combo1 = Menu::where('name', 'Combo nướng 129k')->first();
//        $combo2 = Menu::where('name', 'Combo nướng 169k')->first();
//        $combo3 = Menu::where('name', 'Combo lẩu nướng 209k')->first();
//        $hotpot = Menu::where('name', 'Lẩu')->first();
//        DB::table('dish_in_combo')->truncate();
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
//            'name' => 'Ba chỉ bò',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
//            'name' => 'Ba chỉ lợn',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
//            'name' => 'Xúc xích',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
//            'name' => 'Sụn',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
//            'name' => 'Bắp bò',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
//            'name' => 'Kim chi',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
//            'name' => 'Rau củ quả',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo2->_id, $combo3->_id],
//            'name' => 'Gà',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo2->_id, $combo3->_id],
//            'name' => 'Mực',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo2->_id, $combo3->_id],
//            'name' => 'Tôm',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$combo2->_id, $combo3->_id],
//            'name' => 'Bạch tuộc',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$hotpot->_id, $combo3->_id],
//            'name' => 'Mì tôm',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$hotpot->_id, $combo3->_id],
//            'name' => 'Rau',
//            'is_sold_out' => false
//        ]);
//        DB::table('dish_in_combo')->insert([
//            'pid' => [$hotpot->_id, $combo3->_id],
//            'name' => 'Đậu',
//            'is_sold_out' => false
//        ]);
    }
}
