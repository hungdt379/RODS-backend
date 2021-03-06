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
        //DB::table('dish_in_combo')->update(['is_sold_out' => false]);

        $combo1 = Menu::where('name', 'Combo nướng 139k')->first();
        $combo2 = Menu::where('name', 'Combo nướng 169k')->first();
        $combo3 = Menu::where('name', 'Combo nướng 249k')->first();
        DB::table('dish_in_combo')->truncate();
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Ba chỉ bò',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Ba chỉ heo',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Ba chỉ bò quấn nấm kim',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Vai bò',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Lườn ngỗng',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Chân gà ướp sa tế',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Dưa chuột chẻ',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Kim chi',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Salad trộn',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Khoai lang kén',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Bánh bao chiên',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo1->_id, $combo2->_id, $combo3->_id],
            'name' => 'Tráng miệng dưa hấu',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Râu mực',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Tôm',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Nầm nướng',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Sụn heo',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo2->_id, $combo3->_id],
            'name' => 'Cánh gà chiên mắm',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo3->_id],
            'name' => 'Mực trứng',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo3->_id],
            'name' => 'Dẻ sườn bò',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo3->_id],
            'name' => 'Cổ bò',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo3->_id],
            'name' => 'Ngô chiên',
            'is_sold_out' => false
        ]);
        DB::table('dish_in_combo')->insert([
            'pid' => [$combo3->_id],
            'name' => 'Phồng tôm',
            'is_sold_out' => false
        ]);

    }
}
