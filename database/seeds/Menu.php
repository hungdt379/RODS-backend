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
        DB::table('menu')->truncate();
        $combo = Category::where('name', 'combo')->first();
        $drink = Category::where('name', 'drink')->first();
        $normal = Category::where('name', 'normal')->first();
        $fastFood = Category::where('name', 'fast')->first();
        $alcohol = Category::where('name', 'alcohol')->first();
        $beer = Category::where('name', 'beer')->first();

        DB::table('menu')->insert([

            'name' => 'Combo nướng 139k',
            'cost' => 139000,
            'image' => 'http://165.227.99.160/image/nuong-129.png',
            'hotpot' => true,
            'category_id' => $combo->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([

            'name' => 'Combo nướng 169k',
            'cost' => 169000,
            'image' => 'http://165.227.99.160/image/nuong-169.png',
            'hotpot' => true,
            'category_id' => $combo->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([

            'name' => 'Combo lẩu nướng 249k',
            'cost' => 249000,
            'image' => 'http://165.227.99.160/image/nuong-169.png',
            'hotpot' => false,
            'category_id' => $combo->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([
            'name' => 'Lẩu',
            'cost' => 40000,
            'image' => 'http://165.227.99.160/image/lau.png',
            'hotpot' => false,
            'category_id' => $combo->_id,
            'is_sold_out' => false
        ]);

        // gọi riêng
        DB::table('menu')->insert([
            'name' => 'Nầm heo',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Sụn heo',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Ba chỉ bò',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Ba chỉ heo',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Dẻ sườn',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Cổ bò',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bò cuốn nấm',
            'cost' => 60000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Tôm sú',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Mực',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Chân gà',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        //đồ ăn nhanh
        DB::table('menu')->insert([
            'name' => 'Kimchi',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Khoai tây chiên',
            'cost' => 30000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bánh bao chiên',
            'cost' => 20000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Nem chua rán',
            'cost' => 30000,
            'image' => 'http://165.227.99.160/image/nem-chua-ran.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Khoai lang kén',
            'cost' => 30000,
            'image' => 'http://165.227.99.160/image/khoai-lang-ken.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([
            'name' => 'Ngô chiên',
            'cost' => 30000,
            'image' => 'http://165.227.99.160/image/ngo-chien.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([
            'name' => 'Gà lắc',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/ga-lac.png',
            'hotpot' => false,
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        ////đồ uống
        DB::table('menu')->insert([
            'name' => '7Up',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/7-up.png',
            'hotpot' => false,
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Coca lon',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/coca.png',
            'hotpot' => false,
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Pepsi',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/pepsi.png',
            'hotpot' => false,
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        /////bia
        DB::table('menu')->insert([
            'name' => 'Bia Hà Nội',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/bia.png',
            'hotpot' => false,
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bia Sài Gòn',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/bia-sai-gon.png',
            'hotpot' => false,
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);


        //////rượu
        DB::table('menu')->insert([
            'name' => 'Rượu Soju Hàn Quốc',
            'cost' => 70000,
            'image' => 'http://165.227.99.160/image/soju.png',
            'hotpot' => false,
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu chuối hột 500ml',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu ngô 500ml',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/no-image.png',
            'hotpot' => false,
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu táo mèo 500ml',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/tao-meo.png',
            'hotpot' => false,
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu nếp trắng 500ml',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/nep-trang.png',
            'hotpot' => false,
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);
    }
}
