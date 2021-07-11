<?php

use App\Domain\Entities\SearchCombo169K;
use Illuminate\Database\Seeder;
use App\Domain\Entities\Category;
use App\Domain\Entities\Menu;

class SearchCombo169 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('search_combo_169')->truncate();
        $combo = Category::where('name', 'combo')->first();
        $drink = Category::where('name', 'drink')->first();
        $normal = Category::where('name', 'normal')->first();
        $fastFood = Category::where('name', 'fast')->first();
        $alcohol = Category::where('name', 'alcohol')->first();
        $beer = Category::where('name', 'beer')->first();
        $menu = Menu::where('name', 'Combo nướng 169k')
            ->orWhere('category_id', $drink->_id)
            ->orWhere('category_id', $normal->_id)
            ->orWhere('category_id', $alcohol->_id)
            ->orWhere('category_id', $beer->_id)
            ->orWhere('category_id', $fastFood->_id)->get();

        foreach ($menu as $x){
            $searchCombo169 = new SearchCombo169k();
            $searchCombo169->_id = $x->_id;
            $searchCombo169->name = $x->name;
            $searchCombo169->cost = $x->cost;
            $searchCombo169->image = $x->image;
            $searchCombo169->hotpot = $x->hotpot;
            $searchCombo169->category_id = $x->category_id;
            $searchCombo169->save();
        }
    }
}
