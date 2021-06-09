<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Menu;

class MenuRepository
{
    public function getMenuByCategory($categoryID){
        return Menu::where('category_id', $categoryID)->where('name', 'not like', 'Lẩu')->get();
    }

    // có lẩu
    public function getMenuComboHasHotpotAfterOrder($comboID){
        return Menu::where('_id', $comboID)->get();
    }

    // không lẩu
    public function getMenuComboNoHotpotAfterOrder($comboID){
        return Menu::where('_id', $comboID)->orWhere('name', 'like', 'Lẩu')->get();
    }
}
