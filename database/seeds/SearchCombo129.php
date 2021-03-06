<?php

use App\Domain\Entities\SearchCombo129K;
use Illuminate\Database\Seeder;
use App\Domain\Entities\Menu;
use App\Domain\Entities\Category;

class SearchCombo129 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('search_combo_129')->truncate();
        $drink = Category::where('name', 'drink')->first();
        $normal = Category::where('name', 'normal')->first();
        $fastFood = Category::where('name', 'fast')->first();
        $alcohol = Category::where('name', 'alcohol')->first();
        $beer = Category::where('name', 'beer')->first();
        $menu = Menu::where('name', 'Combo nướng 139k')
            ->orWhere('category_id', $drink->_id)
            ->orWhere('category_id', $normal->_id)
            ->orWhere('category_id', $alcohol->_id)
            ->orWhere('category_id', $beer->_id)
            ->orWhere('category_id', $fastFood->_id)->get();

        foreach ($menu as $x) {
            $searchCombo129 = new SearchCombo129K();
            $searchCombo129->_id = $x->_id;
            $searchCombo129->name = $x->name;
            if ($searchCombo129->name == 'Combo nướng 139k') {
                $searchCombo129->cost = 0;
            } else {
                $searchCombo129->cost = $x->cost;
            }
            $searchCombo129->image = $x->image;
            $searchCombo129->hotpot = $x->hotpot;
            $searchCombo129->category_id = $x->category_id;
            $searchCombo129->save();
        }
    }
}
