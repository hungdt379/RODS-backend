<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Menu;
use App\Domain\Entities\SearchCombo129K;
use App\Domain\Entities\SearchCombo169K;
use App\Domain\Entities\SearchCombo209K;

class MenuRepository
{

    public function getAllMenu()
    {
        return Menu::all();
    }

    public function getMenuByCategory($categoryID)
    {
        return Menu::where('category_id', $categoryID)->get();
    }


    public function getItemByID($id)
    {
        return Menu::where('_id', $id)->first();
    }

    public function getItemByIdOfMenu($id)
    {
        return Menu::where('_id', $id)->get();
    }

    public function update($menuItem)
    {
        $menuItem->save();
    }

    public function getItemByName($name)
    {
        return Menu::whereRaw(array('$text' => array('$search' => $name)))->where('name', 'LIKE', "%$name%")
            ->get();
    }

    public function searchCombo129($name)
    {
        return SearchCombo129K::whereRaw(array('$text' => array('$search' => $name)))->where('name', 'LIKE', "%$name%")->get();
    }

    public function searchCombo169($name)
    {
        return SearchCombo169K::whereRaw(array('$text' => array('$search' => $name)))->where('name', 'LIKE', "%$name%")->get();
    }

    public function searchCombo209($name)
    {
        return SearchCombo209K::whereRaw(array('$text' => array('$search' => $name)))->where('name', 'LIKE', "%$name%")->get();
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

    public function getMenu($textSearch)
    {
        return Menu::whereRaw(array('$text' => array('$search' => $textSearch)))->get();
    }
}
