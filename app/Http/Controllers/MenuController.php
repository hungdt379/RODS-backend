<?php


namespace App\Http\Controllers;


use App\Domain\Services\DishInComboService;
use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($param, [
            '_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $data = $this->menuService->getDetailItemByID($param['_id']);
        if (sizeof($data) == 0){
            return $this->errorResponse('Not found item', null,false, 404);
        }

        return $this->successResponse($data, 'Success');
    }

    public function searchItem()
    {
        $param = request()->all();
        if ($param['q'] == null){
            return $this->errorResponse('Not found items', null, false, 404);
        }

        $data = $this->menuService->getItemByName($param['q'], JWTAuth::user()->_id);

        if (sizeof($data) == 0 ){
            return $this->errorResponse('Not found items', null, false, 404);
        }

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
