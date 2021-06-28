<?php


namespace App\Http\Controllers;


use App\Domain\Services\CartItemService;
use App\Domain\Services\CartService;
use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class CartController extends Controller
{
    use ApiResponse;

    private $cartService;
    private $cartItemService;
    private $menuService;

    /**
     * CartController constructor.
     * @param $cartService
     * @param $cartItemService
     * @param $menuService
     */
    public function __construct(CartService $cartService, CartItemService $cartItemService, MenuService $menuService)
    {
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
        $this->menuService = $menuService;
    }

    public function show()
    {
        $tableID = JWTAuth::user()->_id;
        $cart = $this->cartService->getCartByTableID($tableID);

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
    }

    public function addItems()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'item_id' => 'required',
            'quantity' => 'required|numeric|min:1|max:10',
            'cost' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 400);
        }

        $tableID = JWTAuth::user()->_id;
        $itemID = $param['item_id'];
        $quantity = $param['quantity'];
        $note = $param['note'];
        $cost = $param['cost'];
        $dishInCombo = isset($param['dish_in_combo']) ? $param['dish_in_combo'] : null;
        //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
        $cartItem = $this->cartItemService->getCartItemByItemID($tableID, $itemID);
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $this->cartItemService->update($tableID, $itemID, $quantity, $note, $dishInCombo, $cost);
            return $this->successResponse(null, 'Update success');
        } else {
            $this->cartItemService->addNewItem($tableID, $itemID, $quantity, $note, $dishInCombo, $cost);
            return $this->successResponse(null, 'Add item Success');
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
            foreach ($itemID as $item) {
                $cartItem = $this->cartItemService->getCartItemByItemID($tableID, $item);
                if ($cartItem) {
                    $this->cartItemService->deleteItemInCart($tableID, $item);
                } else {
                    $strError = 'Not found item by id : ' . $item;
                    return $this->errorResponse($strError, null, false, 400);
                }
            }
        }
        return $this->successResponse(null, 'Delete Success');;
    }

}
