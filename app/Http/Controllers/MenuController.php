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

    public function getDetailMenu()
    {
        $param = request()->all();
        if ($param['category_name'] == 'combo'){
            $data = $this->dishInComboService->getDishesByCombo($param);
            return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $param['pageSize'], $data->total());
        }

        $data = $this->menuService->getDetailMenuByID($param['id']);

        return $this->successResponse($data, 'Success');
    }
}
