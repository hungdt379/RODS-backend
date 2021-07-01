<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Category;
use App\Domain\Entities\Menu;
use App\Domain\Entities\SearchCombo129K;
use App\Domain\Entities\SearchCombo169K;
use App\Domain\Entities\SearchCombo209K;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class MenuRepository
{
    public function getMenuByCategory($categoryID)
    {
        return Menu::where('category_id', $categoryID)->where('name', 'not like', 'Lẩu')->get();
    }

    public function getHotpot()
    {
        return Menu::Where('name', 'like', 'Lẩu')->get();
    }


    public function getItemByID($id)
    {
        return Menu::where('_id', $id)->first();

    }

    public function getItemByName($name)
    {
        return Menu::whereRaw(array('$text' => array('$search' => $name)))
            ->Where('name', 'not like', 'Lẩu')->get();
    }

    public function searchCombo129($name)
    {
        return SearchCombo129K::whereRaw(array('$text' => array('$search' => $name)))->get();
    }

    public function searchCombo169($name)
    {
        return SearchCombo169K::whereRaw(array('$text' => array('$search' => $name)))->get();
    }

    public function searchCombo209($name)
    {
        return SearchCombo209K::whereRaw(array('$text' => array('$search' => $name)))->get();
    }

    public function getDetailItemByID($itemID)
    {
        return Menu::raw(function ($collection) use ($itemID) {
            return $collection->aggregate([
                [
                    '$addFields' => [
                        '_id' => ['$toString' => '$_id']
                    ]
                ],
                [
                    '$match' => [
                        '_id' => $itemID
                    ]
                ],
                [
                    '$lookup' => [
                        'as' => 'dish_in_combo',
                        'from' => 'dish_in_combo',
                        'foreignField' => 'pid',
                        'localField' => '_id'
                    ]
                ]

            ]);
        });
    }
}
