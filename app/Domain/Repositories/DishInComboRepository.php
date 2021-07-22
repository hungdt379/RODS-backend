<?php


namespace App\Domain\Repositories;

use App\Domain\Entities\DishInCombo;


class DishInComboRepository
{
    public function getAllDishInCombo()
    {
        return DishInCombo::all();
    }

    public function getDishInComboById($id)
    {
        return DishInCombo::where('_id', $id)->first();
    }

    public function update($dishInCombo)
    {
        $dishInCombo->save();
    }

    public function getDishesByCombo($comboID)
    {
        return DishInCombo::where('pid', $comboID)
            ->get();
    }

    public function getDishInCombo($textSearch)
    {
        $fullTextResult = DishInCombo::whereRaw(array('$text' => array('$search' => $textSearch)))->get();
        $likeResult = DishInCombo::where('name', 'LIKE', "%$textSearch%")->get()->toArray();

        if (sizeof($fullTextResult) == 0) {
            return $likeResult;
        } else {
            foreach ($likeResult as $likeItem) {
                if (!in_array($likeItem, $fullTextResult)) array_push($fullTextResult, $likeItem);
            }
            return $fullTextResult;
        }
    }

    public function getDishInComboByName($name)
    {
        return DishInCombo::where('name', $name)->first();
    }

}
