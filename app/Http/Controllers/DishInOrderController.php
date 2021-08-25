<?php

namespace App\Http\Controllers;

use App\Domain\Entities\Order;
use App\Domain\Services\CategoryService;
use App\Domain\Services\DishInOrderService;
use App\Domain\Services\OrderService;
use App\Traits\ApiResponse;
use Validator;
use \Illuminate\Http\Response as Res;

class DishInOrderController extends Controller
{
    use ApiResponse;

    private $dishInOrderService;
    private $categoryService;
    private $orderService;

    /**
     * DishInOrderController constructor.
     * @param DishInOrderService $dishInOrderService
     * @param CategoryService $categoryService
     * @param OrderService $orderService
     */
    public function __construct(DishInOrderService $dishInOrderService, CategoryService $categoryService, OrderService $orderService)
    {
        $this->dishInOrderService = $dishInOrderService;
        $this->categoryService = $categoryService;
        $this->orderService = $orderService;
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

    public function getDishInOrderByTableID()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'status' => 'required',
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $status = $param['status'];
        $tableID = $param['table_id'];
        $categoryCombo = $this->categoryService->getComboCategory();
        $categoryFast = $this->categoryService->getFastCategory();
        $categoryNormal = $this->categoryService->getNormalCategory();
        $categoryID = [$categoryCombo['_id'], $categoryFast['_id'], $categoryNormal['_id']];

        $data = $this->dishInOrderService->getDishInOrderByTableID($categoryID, $status, $tableID);

        return $this->successResponse($data, 'Success');
    }

    public function getDrinkInOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'status' => 'required',
            'table_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $status = $param['status'];
        $tableID = $param['table_id'];
        $categoryDrink = $this->categoryService->getDrinkCategory();
        $categoryAlcohol = $this->categoryService->getAlcoholCategory();
        $categoryBeer = $this->categoryService->getBeerCategory();
        $categoryID = [$categoryDrink['_id'], $categoryAlcohol['_id'], $categoryBeer['_id']];

        $data = $this->dishInOrderService->getDrinkInOrder($categoryID, $status, $tableID);

        return $this->successResponse($data, 'Success');
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

    public function matchingDishInOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }
        $dishInOrderID = $param['_id'];
        $dishInOrder = $this->dishInOrderService->getListDishInOrderByID($dishInOrderID)->toArray();
        for ($i = 0; $i < sizeof($dishInOrder) - 1; $i++) {
            if ($dishInOrder[$i]['order_id'] != $dishInOrder[$i + 1]['order_id']) {
                return $this->errorResponse('Can not merge dish in order', null, false, Res::HTTP_BAD_REQUEST);
            }
        }

        if ($dishInOrder) {
            foreach ($dishInOrder as $value) {
                $this->dishInOrderService->updateStatus($value);
            }
            $url = $this->dishInOrderService->exportListDishInOrder($dishInOrder);
            return $this->successResponse($url, 'Success');
        } else {
            return $this->errorResponse('Not found dish in order', null, false, Res::HTTP_NO_CONTENT);
        }
    }

    public function deleteDishInOrder()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            '_id' => 'required',
            'order_id' => 'required',
            'category_id' => 'required',
            'item_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $categoryCombo = $this->categoryService->getComboCategory();
        if ($categoryCombo->_id == $param['category_id']) {
            $this->dishInOrderService->deleteDishInOrder($param['_id']);
        } else {
            $order = $this->orderService->getOrderByID($param['order_id']);
            $this->orderService->deleteItemInConfirmOrder($order, $param['item_id']);
        }

        return $this->successResponse(null, 'Delete successful');
    }

}
