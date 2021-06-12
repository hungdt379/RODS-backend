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

    public function getItemByName($name)
    {
        return Menu::raw(function ($collection) use ($name) {
            return $collection->aggregate(
                [
                    [
                        '$addFields' => [
                            'category_id' => ['$toObjectId' => '$category_id'],
                            '_id' => ['$toString' => '$_id'],
                        ]
                    ],

                    [
                        '$lookup' => [
                            'as' => 'category',
                            'from' => 'category',
                            'foreignField' => '_id',
                            'localField' => 'category_id'
                        ]
                    ],

                    ['$unwind' => '$output'],
                    ['$match' => [
                        '$or' =>
                            ['name' => ['$regex' => $name, '$options' => 'i']
                            ]
                    ]
                    ]
                ]);

        });


    }

    public function getDetailItemByID($id)
    {
        return Menu::where('_id', $id)
            ->get();
    }


}
