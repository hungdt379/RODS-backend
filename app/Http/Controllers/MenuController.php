<?php


namespace App\Http\Controllers;


use App\Domain\Services\DishInComboService;
use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use \Illuminate\Http\Response as Res;

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
        if (sizeof($data) == 0) {
            return $this->errorResponse('Not found item', null, false, Res::HTTP_NO_CONTENT);
        }

        return $this->successResponse($data, 'Success');
    }

    public function searchItem()
    {
        $param = request()->all();
        if ($param['q'] == null) {
            return $this->errorResponse('Not found items', null, false, Res::HTTP_NO_CONTENT);
        }

        $data = $this->menuService->getItemByName($param['q'], JWTAuth::user()->_id);

        if (sizeof($data) == 0) {
            return $this->errorResponse('Not found items', null, false, Res::HTTP_NO_CONTENT);
        }

        return $this->successResponse($data, 'Success');
    }

    public function getAllItem()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'page' => 'required|integer',
            'pageSize' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid params', null, false, 400);
        }

        $data = $this->menuService->getItem($param['q']);
        if (sizeof($data) == 0) return $this->errorResponse('Not found items', null, false, Res::HTTP_NO_CONTENT);

        $dataWithPaging = [];
        $start = ($param['page'] - 1) * $param['pageSize'];
        $end = $param['page'] * $param['pageSize'];
        $total = sizeof($data);
        $totalPage = ceil($total / $param['pageSize']);

        if ($param['page'] > (int)$totalPage) {
            return $this->errorResponse('Not found items', null, false, Res::HTTP_NO_CONTENT);
        }

        if ($end >= $total) $end = $total;

        for ($i = $start; $i < $end; $i++) {
            array_push($dataWithPaging, $data[$i]);
        }
        return $this->successResponseWithPaging($dataWithPaging, 'Success', $param['page'], $param['pageSize'], $total);
    }

    public function updateItemSoldOutStatus()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'item_id' => 'required|alpha_num',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Invalid param', null, false, 400);
        }

        $menuItem = $this->menuService->getItemByID($param['item_id']);
        $dishItem = $this->dishInComboService->getDishInComboById($param['item_id']);
        if ($menuItem == null && $dishItem == null) {
            return $this->errorResponse('Not found items', null, false, Res::HTTP_NO_CONTENT);
        }

        $this->menuService->updateItemSoldOutStatus($menuItem, $dishItem);
        return $this->successResponse(null, 'Update successful');
    }

    // day là code của thịnh viết lên trên function này
    public function searchItem123()
    {
        $param = request()->all();
        $data = $this->menuService->getItemByName($param['q']);

        return $this->successResponse($data, 'Success');
    }
}
