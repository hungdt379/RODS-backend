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
        $combo = Category::where('name', 'combo')->first();
        $drink = Category::where('name', 'drink')->first();
        $normal = Category::where('name', 'normal')->first();
        $fastFood = Category::where('name', 'fast')->first();
        $alcohol = Category::where('name', 'alcohol')->first();
        $beer = Category::where('name', 'beer')->first();
        $menu = Menu::where('name', 'Combo nướng 249k')
            ->orWhere('category_id', $drink->_id)
            ->orWhere('category_id', $normal->_id)
            ->orWhere('category_id', $alcohol->_id)
            ->orWhere('category_id', $beer->_id)
            ->orWhere('category_id', $fastFood->_id)->get();

        foreach ($menu as $x){
            $searchCombo209 = new SearchCombo209k();
            $searchCombo209->_id = $x->_id;
            $searchCombo209->name = $x->name;
            if ($searchCombo209->name == 'Combo nướng 249k'){
                $searchCombo209->cost = 0;
            }else{
                $searchCombo209->cost = $x->cost;
            }
            $searchCombo209->image = $x->image;
            $searchCombo209->hotpot = $x->hotpot;
            $searchCombo209->category_id = $x->category_id;
            $searchCombo209->save();
        }
    }
}
