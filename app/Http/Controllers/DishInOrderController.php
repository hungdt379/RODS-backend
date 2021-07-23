<?php

namespace App\Http\Controllers;

use App\Domain\Services\CategoryService;
use App\Domain\Services\DishInOrderService;
use App\Traits\ApiResponse;
use Validator;
use \Illuminate\Http\Response as Res;

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
            'pageSize' => 'required|numeric|',
            'page' => 'required|numeric|',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_NO_CONTENT);
        }

        $pageSize = $param['pageSize'];
        $page = $param['page'];
        $status = $param['status'];
        $categoryCombo = $this->categoryService->getComboCategory();
        $categoryFast = $this->categoryService->getFastCategory();
        $categoryNormal = $this->categoryService->getNormalCategory();
        $categoryID = [$categoryCombo['_id'], $categoryFast['_id'], $categoryNormal['_id']];

        $data = $this->dishInOrderService->getDishInOrder($categoryID, $page, $pageSize, $status);
        $total = $this->dishInOrderService->getTotalDishInOrder($categoryID, $status);
        return $this->successResponseWithPaging($data, 'Success', $page, $pageSize, $total);
    }

    public function getDrinkInOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'pageSize' => 'required|numeric|',
            'page' => 'required|numeric|',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $pageSize = $param['pageSize'];
        $page = $param['page'];
        $status = $param['status'];
        $categoryDrink = $this->categoryService->getDrinkCategory();
        $categoryAlcohol = $this->categoryService->getAlcoholCategory();
        $categoryBeer = $this->categoryService->getBeerCategory();
        $categoryID = [$categoryDrink['_id'], $categoryAlcohol['_id'], $categoryBeer['_id']];

        $data = $this->dishInOrderService->getDishInOrder($categoryID, $page, $pageSize, $status);
        $total = $this->dishInOrderService->getTotalDishInOrder($categoryID, $status);
        return $this->successResponseWithPaging($data, 'Success', $page, $pageSize, $total);
    }

    public function updateStatus()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $dishInOrderID = $param['_id'];
        $dishInOrder = $this->dishInOrderService->getDishInOrderByID($dishInOrderID);
        $categoryCombo = $this->categoryService->getComboCategory();
        $categoryFast = $this->categoryService->getFastCategory();
        $categoryNormal = $this->categoryService->getNormalCategory();
        $categoryID = [$categoryCombo['_id'], $categoryFast['_id'], $categoryNormal['_id']];
        if ($dishInOrder) {
            $this->dishInOrderService->updateStatus($dishInOrder);
            if (in_array($dishInOrder['category_id'], $categoryID)) {
                if ($dishInOrder['category_id'] == $categoryCombo['_id']) {
                    $data = $this->dishInOrderService->exportPdf($dishInOrder, $categoryCombo['name']);
                } else {
                    $data = $this->dishInOrderService->exportPdf($dishInOrder, 'Thường');
                }

                return $this->successResponse($data, 'Update Success');
            }
            return $this->successResponse(null, 'Update Success');
        } else {
            return $this->errorResponse('Not found dish in order', null, false, Res::HTTP_NO_CONTENT);
        }

    }

}
