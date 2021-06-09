<?php


namespace App\Http\Controllers;


use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use JWTAuth;

class MenuController
{
    use ApiResponse;
    private $menuService;

    /**
     * MenuController constructor.
     * @param $menuService
     */
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function getMenu(){
        $user = JWTAuth::user();
        $data = $this->menuService->getMenu($user->_id);
        return $this->successResponse($data, 'Success');
    }
}
