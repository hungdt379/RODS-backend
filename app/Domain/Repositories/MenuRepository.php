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
        $menuItem->update();
    }

    public function getItemByName($name)
    {
        $fullTextResult = Menu::whereRaw(array('$text' => array('$search' => $name)))->get()->toArray();
        $likeResult = Menu::where('name', 'LIKE', "%$name%")->get()->toArray();

        if (sizeof($fullTextResult) == 0) {
            return $likeResult;
        } else {
            foreach ($likeResult as $likeItem) {
                if (!in_array($likeItem, $fullTextResult)) array_push($fullTextResult, $likeItem);
            }
            return $fullTextResult;
        }
    }

    public function searchCombo129($name)
    {
        $fullTextResult = SearchCombo129K::whereRaw(array('$text' => array('$search' => $name)))->get()->toArray();
        $likeResult = SearchCombo129K::where('name', 'LIKE', "%$name%")->get()->toArray();

        if (sizeof($fullTextResult) == 0) {
            return $likeResult;
        } else {
            foreach ($likeResult as $likeItem) {
                if (!in_array($likeItem, $fullTextResult)) array_push($fullTextResult, $likeItem);
            }
            return $fullTextResult;
        }
    }

    public function searchCombo169($name)
    {
        $fullTextResult = SearchCombo169K::whereRaw(array('$text' => array('$search' => $name)))->toArray();
        $likeResult = SearchCombo169K::where('name', 'LIKE', "%$name%")->get()->toArray();

        if (sizeof($fullTextResult) == 0) {
            return $likeResult;
        } else {
            foreach ($likeResult as $likeItem) {
                if (!in_array($likeItem, $fullTextResult)) array_push($fullTextResult, $likeItem);
            }
            return $fullTextResult;
        }
    }

    public function searchCombo209($name)
    {
        $fullTextResult =  SearchCombo209K::whereRaw(array('$text' => array('$search' => $name)))->get();
        $likeResult = SearchCombo209K::where('name', 'LIKE', "%$name%")->get()->toArray();

        if (sizeof($fullTextResult) == 0) {
            return $likeResult;
        } else {
            foreach ($likeResult as $likeItem) {
                if (!in_array($likeItem, $fullTextResult)) array_push($fullTextResult, $likeItem);
            }
            return $fullTextResult;
        }
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
