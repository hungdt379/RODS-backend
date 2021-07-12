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

    public function getDishInOrder()
    {
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
        $categoryNormal = $this->categoryService->getNormalCategory();
        $categoryID = [$categoryCombo['_id'], $categoryFast['_id'], $categoryNormal['_id']];

        $data = $this->dishInOrderService->getDishInOrder($tableID, $categoryID, $pageSize);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $pageSize, $data->total());
    }

    public function getDrinkInOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'table_id' => 'required',
            'pageSize' => 'required|numeric|'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 204);
        }

        $tableID = $param['table_id'];
        $pageSize = $param['pageSize'];
        $categoryDrink = $this->categoryService->getDrinkCategory();
        $categoryAlcohol = $this->categoryService->getAlcoholCategory();
        $categoryBeer = $this->categoryService->getBeerCategory();
        $categoryID = [$categoryDrink['_id'], $categoryAlcohol['_id'], $categoryBeer['_id']];

        $data = $this->dishInOrderService->getDishInOrder($tableID, $categoryID, $pageSize);
        return $this->successResponseWithPaging($data->items(), 'Success', $data->currentPage(), $pageSize, $data->total());
    }

    public function updateStatus()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 204);
        }
        $dishInOrderID = $param['_id'];
        $dishInOrder = $this->dishInOrderService->getDishInOrderByID($dishInOrderID);
        if ($dishInOrder) {
            $this->dishInOrderService->updateStatus($dishInOrder);

            return $this->successResponse(null, 'Update Success');
        } else {
            return $this->errorResponse('Not found dish in order', null, false, 204);
        }

    }
}
