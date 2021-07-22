<?php


namespace App\Domain\Services;


use App\Domain\Repositories\DishInComboRepository;

class DishInComboService
{
    private $dishInComboRepository;

    /**
     * DishInComboService constructor.
     * @param $dishInComboRepository
     */
    public function __construct(DishInComboRepository $dishInComboRepository)
    {
        $this->dishInComboRepository = $dishInComboRepository;
    }

    public function getDishesByCombo($comboID)
    {
        return $this->dishInComboRepository->getDishesByCombo($comboID);
    }

    public function getDishInComboById($itemId)
    {
        return $this->dishInComboRepository->getDishInComboById($itemId);
    }

    public function getDishInComboByName($name)
    {
        return $this->dishInComboRepository->getDishInComboByName($name);
    }
}
