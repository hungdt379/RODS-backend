<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Category;
use App\Domain\Entities\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class MenuRepository
{
    public function getMenuByCategory($categoryID)
    {
        return Menu::where('category_id', $categoryID)->where('name', 'not like', 'Lẩu')->get();
    }

    // có lẩu
    public function getMenuComboHasHotpotAfterOrder($comboID)
    {
        return Menu::where('_id', $comboID)->get();
    }

    // không lẩu
    public function getMenuComboNoHotpotAfterOrder($comboID)
    {
        return Menu::where('_id', $comboID)->orWhere('name', 'like', 'Lẩu')->get();
    }

    public function getDetailMenuByID($id)
    {
        return Menu::where('_id',$id)
            ->get();
    }


}
