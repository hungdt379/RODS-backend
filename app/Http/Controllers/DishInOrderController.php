<?php

namespace App\Http\Controllers;

use App\Domain\Services\CategoryService;
use App\Domain\Services\DishInOrderService;
use App\Traits\ApiResponse;
use Validator;

class DishInOrderController extends Controller
{
    use ApiResponse;
    private $dishInOrderService;
    private $categoryService;

    /**
     * DishInOrderController constructor.
     * @param DishInOrderService $dishInOrderService
     * @param CategoryService $categoryService
     */
    public function __construct(DishInOrderService $dishInOrderService, CategoryService $categoryService)
    {
        $this->dishInOrderService = $dishInOrderService;
        $this->categoryService = $categoryService;
    }

    public function getDishInOrder(){
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required',
            'pageSize' => 'required|numeric|'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }

        $tableID = $param['table_id'];
        $pageSize = $param['pageSize'];
        $categoryCombo = $this->categoryService->getComboCategory();
        $categoryFast = $this->categoryService->getFastCategory();
        $categoryID = [$categoryCombo['_id'], $categoryFast['_id']];

        $data = $this->dishInOrderService->getDishInOrder($tableID, $categoryID, $pageSize);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $pageSize, $data->total());
    }

    public function getDrinkInOrder(){
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required',
            'pageSize' => 'required|numeric|'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 404);
        }

        $tableID = $param['table_id'];
        $pageSize = $param['pageSize'];
        $categoryDrink = $this->categoryService->getDrinkCategory();
        $categoryID = [$categoryDrink['_id']];

        $data = $this->dishInOrderService->getDishInOrder($tableID, $categoryID, $pageSize);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $pageSize, $data->total());
    }

}
