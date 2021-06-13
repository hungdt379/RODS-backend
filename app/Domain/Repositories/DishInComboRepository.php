<?php


namespace App\Domain\Repositories;

use App\Domain\Entities\DishInCombo;


class DishInComboRepository
{
    public function getDishesByCombo($comboID)
    {
        return DishInCombo::where('pid', $comboID)
            ->get();
    }
}
