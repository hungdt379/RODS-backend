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

}
