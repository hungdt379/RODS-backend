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
        return Menu::whereRaw(array('$text'=>array('$search'=> $name)))
                    ->Where('name', 'not like', 'Lẩu')->get();
    }

    public function searchCombo129($name)
    {
        return SearchCombo129K::whereRaw(array('$text'=>array('$search'=>$name)))->get();
    }

    public function searchCombo169($name)
    {
        return SearchCombo169K::whereRaw(array('$text'=>array('$search'=>$name)))->get();
    }

    public function searchCombo209($name)
    {
        return SearchCombo209K::whereRaw(array('$text'=>array('$search'=>$name)))->get();
    }

    public function getDetailItemByID($id)
    {
        return Menu::raw(function ($collection) use ($id) {
            return $collection->aggregate(
                [
                    [
                        '$addFields' => [
                            'category_id' => ['$toObjectId' => '$category_id'],
                            '_id' => ['$toString' => '$_id'],
                        ]
                    ],

                    ['$match' => ['_id' => $id]],

                    [
                        '$lookup' => [
                            'as' => 'category',
                            'from' => 'category',
                            'foreignField' => '_id',
                            'localField' => 'category_id'
                        ]
                    ],

                    ['$unwind' => '$category'],

                ]);

        });
    }


}
