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

    public function getDishesByCombo($param)
    {
        return $this->dishInComboRepository->getDishesByCombo($param['id'], $param['pageSize']);
    }
}
