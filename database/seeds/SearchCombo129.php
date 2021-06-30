<?php

use Illuminate\Database\Seeder;
use App\Domain\Entities\Menu;
use App\Domain\Entities\Category;
use App\Domain\Entities\SearchCombo129K;

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
        $fastFood = Category::where('name', 'fast')->first();
        $menu = Menu::where('name', 'Combo nướng 129k')
                    ->orWhere('name', 'Lẩu')
                    ->orWhere('category_id', $drink->_id)
                    ->orWhere('category_id', $fastFood->_id)->get();

        foreach ($menu as $x){
            $searchCombo129 = new SearchCombo129k();
            $searchCombo129->_id = $x->_id;
            $searchCombo129->name = $x->name;
            $searchCombo129->cost = $x->cost;
            $searchCombo129->description = $x->description;
            $searchCombo129->image = $x->image;
            $searchCombo129->hotpot = $x->hotpot;
            $searchCombo129->category_id = $x->category_id;
            $searchCombo129->save();
        }
    }
}
