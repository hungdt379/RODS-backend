<?php

use App\Domain\Entities\SearchCombo209K;
use Illuminate\Database\Seeder;
use App\Domain\Entities\Category;
use App\Domain\Entities\Menu;

class SearchCombo209 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('search_combo_209')->truncate();
        $drink = Category::where('name', 'drink')->first();
        $fastFood = Category::where('name', 'fast')->first();
        $menu = Menu::where('name', 'Combo lẩu nướng 209k')
            ->orWhere('category_id', $drink->_id)
            ->orWhere('category_id', $fastFood->_id)->get();

        foreach ($menu as $x){
            $searchCombo209 = new SearchCombo209k();
            $searchCombo209->_id = $x->_id;
            $searchCombo209->name = $x->name;
            $searchCombo209->cost = $x->cost;
            $searchCombo209->description = $x->description;
            $searchCombo209->image = $x->image;
            $searchCombo209->hotpot = $x->hotpot;
            $searchCombo209->category_id = $x->category_id;
            $searchCombo209->save();
        }
    }
}
