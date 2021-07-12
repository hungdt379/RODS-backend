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
            'image' => 'http://165.227.99.160/image/139.png',
            'category_id' => $combo->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([

            'name' => 'Combo nướng 169k',
            'cost' => 169000,
            'image' => 'http://165.227.99.160/image/169.png',
            'category_id' => $combo->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([

            'name' => 'Combo nướng 249k',
            'cost' => 249000,
            'image' => 'http://165.227.99.160/image/249.png',
            'category_id' => $combo->_id,
            'is_sold_out' => false
        ]);

        // gọi riêng
        DB::table('menu')->insert([
            'name' => 'Nầm heo',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/namheo.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Sụn heo',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/sunheo.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Ba chỉ bò',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/bachibo.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Ba chỉ heo',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/bachiheo.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Dẻ sườn',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/desuon.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Cổ bò',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/cobo.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bò cuốn nấm',
            'cost' => 60000,
            'image' => 'http://165.227.99.160/image/bocuonnam.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Tôm sú',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/tomsu.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Mực',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/muc.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Chân gà',
            'cost' => 100000,
            'image' => 'http://165.227.99.160/image/changa.png',
            'category_id' => $normal->_id,
            'is_sold_out' => false
        ]);

        //đồ ăn nhanh
        DB::table('menu')->insert([
            'name' => 'Kimchi',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/kimchi.png',
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Khoai tây chiên',
            'cost' => 30000,
            'image' => 'http://165.227.99.160/image/khoaitaychien.png',
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bánh bao chiên',
            'cost' => 20000,
            'image' => 'http://165.227.99.160/image/banhbaochien.png',
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Nem chua rán',
            'cost' => 30000,
            'image' => 'http://165.227.99.160/image/nemchuaran.png',
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Ngô chiên',
            'cost' => 30000,
            'image' => 'http://165.227.99.160/image/ngochien.png',
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);
        DB::table('menu')->insert([
            'name' => 'Gà chiên mắm',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/gachienmam.png',
            'category_id' => $fastFood->_id,
            'is_sold_out' => false
        ]);

        ////đồ uống
        DB::table('menu')->insert([
            'name' => '7Up',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/7up.png',
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Coca lon',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/coca.png',
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Pepsi',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/pepsi.png',
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Lavie',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/lavie.png',
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bò húc',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/bohuc.png',
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Trà Ô Long',
            'cost' => 10000,
            'image' => 'http://165.227.99.160/image/traolong.png',
            'category_id' => $drink->_id,
            'is_sold_out' => false
        ]);

        /////bia
        DB::table('menu')->insert([
            'name' => 'Bia Hà Nội',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/hanoi.png',
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bia Sài Gòn',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/saigon.png',
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bia Heniken',
            'cost' => 20000,
            'image' => 'http://165.227.99.160/image/heniken.png',
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bia Sài Gòn',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/bia-sai-gon.png',
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bia 333',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/333.png',
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Bia Tiger',
            'cost' => 15000,
            'image' => 'http://165.227.99.160/image/tiger.png',
            'category_id' => $beer->_id,
            'is_sold_out' => false
        ]);

        //////rượu
        DB::table('menu')->insert([
            'name' => 'Rượu Soju Hàn Quốc',
            'cost' => 70000,
            'image' => 'http://165.227.99.160/image/sochu.png',
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu chuối hột',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/chuoihot.png',
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu ngô',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/ngo.png',
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu táo mèo',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/tao.png',
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);

        DB::table('menu')->insert([
            'name' => 'Rượu nếp',
            'cost' => 50000,
            'image' => 'http://165.227.99.160/image/nep.png',
            'category_id' => $alcohol->_id,
            'is_sold_out' => false
        ]);
    }
}
