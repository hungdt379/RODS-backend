<?php


namespace App\Http\Controllers;


use App\Domain\Services\CartItemService;
use App\Domain\Services\CartService;
use App\Domain\Services\CategoryService;
use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use \Illuminate\Http\Response as Res;

class CartController extends Controller
{
    use ApiResponse;

    private $cartService;
    private $cartItemService;
    private $menuService;
    private $categoryService;

    /**
     * CartController constructor.
     * @param CartService $cartService
     * @param CartItemService $cartItemService
     * @param MenuService $menuService
     * @param CategoryService $categoryService
     */
    public function __construct(CartService $cartService, CartItemService $cartItemService, MenuService $menuService, CategoryService $categoryService)
    {
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
        $this->menuService = $menuService;
        $this->categoryService = $categoryService;
    }

    public function show()
    {
        $tableID = JWTAuth::user()->_id;
        $cart = $this->cartService->getCartByTableID($tableID);
        if ($cart) {
            $cartItem = $this->cartItemService->getCartItemByTableID($tableID);
            $listItem = [];
            $totalCost = 0;
            foreach ($cartItem as $item) {
                $detailItem = $this->menuService->getDetailItemInCart($tableID, $item['item_id']);
                array_push($listItem, $detailItem);
                $totalCost += $item['total_cost'];
            }
            $cart['total_cost'] = $totalCost;
            $this->cartService->update($cart);
            $data = ['table_id' => $tableID, 'item_in_cart' => $listItem, 'total_cost' => $totalCost];
            return $this->successResponse($data, 'Success');
        } else {
            return $this->errorResponse('Not found cart to show', null, false, Res::HTTP_NO_CONTENT);
        }

    }

    public function addItems()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'item_id' => 'required',
            'quantity' => 'required|numeric|min:0|max:10',
            'cost' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, Res::HTTP_BAD_REQUEST);
        }

        $tableID = JWTAuth::user()->_id;
        $itemID = $param['item_id'];
        $quantity = (int)$param['quantity'];
        $item = $this->menuService->getItemByID($itemID);
        $category = $this->categoryService->getComboCategory();
        if ($item['category_id'] == $category['_id']) {
            $quantity = JWTAuth::user()->number_of_customer;
        }
        $note = isset($param['note']) ? $param['note'] : '';
        $cost = $param['cost'];
        $dishInCombo = isset($param['dish_in_combo']) ? $param['dish_in_combo'] : null;
        $cart = $this->cartService->getCartByTableID($tableID);
        if ($cart) {
            $cartItem = $this->cartItemService->getCartItemByItemID($tableID, $itemID);
            $listItemInCart = $this->cartItemService->getCartItemByTableID($tableID);
            foreach ($listItemInCart as $value) {
                $itemInCart = $this->menuService->getItemByID($value['item_id']);
                if ((strpos($itemInCart['name'], 'Combo') !== false) &&
                    (strpos($item['name'], 'Combo') !== false) &&
                    ($itemInCart['_id'] != $item['_id'])) {
                    return $this->errorResponse('The cart already exist combo', null, false, Res::HTTP_CONFLICT);
                }
            }
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $this->cartItemService->update($tableID, $itemID, $quantity, $note, $dishInCombo, $cost);
                return $this->successResponse(null, 'Update success');
            } else {
                $this->cartItemService->addNewItem($tableID, $itemID, $quantity, $note, $dishInCombo, $cost);
                return $this->successResponse(null, 'Add item Success');
            }
        } else {
            return $this->errorResponse('Not found cart to add', null, false, Res::HTTP_NO_CONTENT);
        }
    }

    public function deleteItemInCart()
    {
        $param = request()->all();
        $itemID = isset($param['item_id']) ? $param['item_id'] : "";
        $tableID = JWTAuth::user()->_id;
        if ($itemID == "") {
            $this->cartItemService->deleteAllItemByTableID($tableID);
            return $this->successResponse(null, 'Delete Success');
        } else {
            $cartItem = $this->cartItemService->getListCartItemByItemID($tableID, $itemID)->toArray();
            if (count($itemID) == count($cartItem)) {
                $this->cartItemService->deleteItemInCart($tableID, $itemID);
                return $this->successResponse(null, 'Delete Success');
            } else
                return $this->errorResponse('One or more item not found', null, false, Res::HTTP_NO_CONTENT);
        }
    }

}
