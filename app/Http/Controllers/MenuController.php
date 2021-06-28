<?php


namespace App\Http\Controllers;


use App\Domain\Services\DishInComboService;
use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use JWTAuth;

class MenuController
{
    use ApiResponse;

    private $menuService;
    private $dishInComboService;

    /**
     * MenuController constructor.
     * @param $menuService
     * @param $dishInComboService
     */
    public function __construct(MenuService $menuService, DishInComboService $dishInComboService)
    {
        $this->menuService = $menuService;
        $this->dishInComboService = $dishInComboService;
    }

    public function getMenu()
    {
        $user = JWTAuth::user();
        $data = $this->menuService->getMenu($user->_id);
        return $this->successResponse($data, 'Success');
    }

    public function getDetailItem()
    {
        $param = request()->all();
        $check = $this->menuService->isCombo($param['id']);

        $data = $this->menuService->getDetailItemByID($param['id']);
        if ($check) {
            $dishInCombo = $this->dishInComboService->getDishesByCombo($param['id']);
            $data['dish_in_combo'] = $dishInCombo;
            return $this->successResponse($data, 'Success');
        }

        return $this->successResponse($data, 'Success');
    }

    public function searchItem()
    {
        $param = request()->all();
        $data = $this->menuService->getItemByName($param['q']);

        return $this->successResponse($data, 'Success');
    }

    // day là code của thịnh viết lên trên function này
    public function searchItem123()
    {
        $param = request()->all();
        $data = $this->menuService->getItemByName($param['q']);

        return $this->successResponse($data, 'Success');
    }
}
