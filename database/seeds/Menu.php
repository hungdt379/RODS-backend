<?php

use Illuminate\Database\Seeder;
use App\Domain\Entities\Category;
use Illuminate\Support\Facades\DB;


class Menu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('menu')->update(['is_sold_out' => false]);

        $combo = Category::where('name', 'combo')->first();
        $drink = Category::where('name', 'drink')->first();
        $fastFood = Category::where('name', 'fast')->first();

        DB::table('menu')->insert([
            'name' => '7Up',
            'cost' => 10000,
            'description' => null,
            'image' => 'http://165.227.99.160/image/7-up.png',
            'hotpot' => false,
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bia Sài Gòn',
            'cost' => 15000,
            'description' => null,
            'image' => 'http://165.227.99.160/image/bia-sai-gon.png',
            'hotpot' => false,
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Nem chua rán',
            'cost' => 30000,
            'description' => null,
            'image' => 'http://165.227.99.160/image/nem-chua-ran.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Pepsi',
            'cost' => 10000,
            'description' => null,
            'image' => 'http://165.227.99.160/image/pepsi.png',
            'hotpot' => false,
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu Soju Hàn Quốc',
            'cost' => 10000,
            'description' => null,
            'image' => 'http://165.227.99.160/image/pepsi.png',
            'hotpot' => false,
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

//        DB::table('menu')->truncate();
//        DB::table('menu')->insert([
//
//            'name' => 'Combo nướng 129k',
//            'cost' => 129000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/nuong-129.png',
//            'hotpot' => true,
//            'category_id' => $combo->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//
//            'name' => 'Combo nướng 169k',
//            'cost' => 169000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/nuong-169.png',
//            'hotpot' => true,
//            'category_id' => $combo->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//
//            'name' => 'Combo lẩu nướng 209k',
//            'cost' => 209000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/nuong-169.png',
//            'hotpot' => false,
//            'category_id' => $combo->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Lẩu',
//            'cost' => 40000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/lau.png',
//            'hotpot' => false,
//            'category_id' => $combo->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Khoai lang kén',
//            'cost' => 30000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/khoai-lang-ken.png',
//            'hotpot' => false,
//            'category_id' => $fastFood->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Ngô chiên',
//            'cost' => 30000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/ngo-chien.png',
//            'hotpot' => false,
//            'category_id' => $fastFood->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Gà lắc',
//            'cost' => 50000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/ga-lac.png',
//            'hotpot' => false,
//            'category_id' => $fastFood->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Coca lon',
//            'cost' => 10000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/coca.png',
//            'hotpot' => false,
//            'category_id' => $drink->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Bia lon',
//            'cost' => 15000,
//            'description' => null,
//            'image' => 'http://165.227.99.160/image/bia.png',
//            'hotpot' => false,
//            'category_id' => $drink->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Rượu táo mèo 500ml',
//            'cost' => 50000,
//            'description' => 'abc',
//            'image' => 'http://165.227.99.160/image/tao-meo.png',
//            'hotpot' => false,
//            'category_id' => $drink->_id,
//            'is_sold_out' => false
//        ]);
//        DB::table('menu')->insert([
//            'name' => 'Rượu nếp trắng 500ml',
//            'cost' => 50000,
//            'description' => 'abc',
//            'image' => 'http://165.227.99.160/image/nep-trang.png',
//            'hotpot' => false,
//            'category_id' => $drink->_id,
//            'is_sold_out' => false
//        ]);
    }
}
