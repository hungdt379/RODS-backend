<?php


namespace App\Domain\Repositories;

use App\Domain\Entities\DishInCombo;


class DishInComboRepository
{
    public function getDishesByCombo($comboID, $pageSize)
    {
        return DishInCombo::where('pid', $comboID)
            ->paginate((int)$pageSize);
    }
}
