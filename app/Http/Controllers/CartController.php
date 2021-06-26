<?php


namespace App\Http\Controllers;


use App\Domain\Services\CartItemService;
use App\Domain\Services\CartService;
use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

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
        $param = request()->all();
        $validator = Validator::make($param, [
            'cart_key' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 400);
        }
        $cartKey = $param['cart_key'];
        $cart = $this->cartService->getCartByKey($cartKey);

        if ($cart['cart_key'] == $cartKey) {
            $cartItem = $this->cartItemService->getItemByCartKey($cartKey);
            $listItem = [];
            $totalCost = 0;
            foreach ($cartItem as $item) {
                $detailItem = $this->menuService->getItemInCart($cartKey, $item['item_id']);
                array_push($listItem, $detailItem);
                $totalCost += $item['total_cost'];
            }
            $cart['total_cost'] = $totalCost;
            $this->cartService->update($cart);
            $data = ['cart' => $cart['cart_key'], 'item_in_cart' => $listItem, 'total_cost' => $totalCost];
            return $this->successResponse($data, 'Success');

        } else {
            return $this->errorResponse('The CartKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }

    }

    public function deleteCart()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'cart_key' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 400);
        }

        $cartKey = $param['cart_key'];
        $cart = $this->cartService->getCartByKey($cartKey);
        if ($cart['cart_key'] == $cartKey) {
            $this->cartService->delete($cart);
            $this->cartItemService->deleteByCartKey($cartKey);
            return $this->successResponse(null, 'Delete Success');
        } else {
            return $this->errorResponse('The CartKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }

    }

    public function addProducts()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'cart_key' => 'required',
            'item_id' => 'required',
            'quantity' => 'required|numeric|min:1|max:10',
            'cost' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 400);
        }

        $cartKey = $param['cart_key'];
        $itemID = $param['item_id'];
        $quantity = $param['quantity'];
        $note = $param['note'];
        $cost = $param['cost'];
        $dishInCombo = isset($param['dish_in_combo']) ? $param['dish_in_combo'] : null;
        //Check if the CarKey is Valid
        $cart = $this->cartService->getCartByKey($cartKey);
        if ($cart['cart_key'] == $cartKey) {
            //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
            $cartItem = $this->cartItemService->getCartItemByItemID($cartKey, $itemID);
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $data = $this->cartItemService->update($cartKey, $itemID, $quantity, $note, $dishInCombo, $cost);
                return $this->successResponse($data, 'Update success');
            } else {
                $data = $this->cartItemService->addNewItem($cartKey, $itemID, $quantity, $note, $dishInCombo, $cost);
                return $this->successResponse($data, 'Add item Success');
            }
        } else {
            return $this->errorResponse('The CartKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }

    }

    public function deleteItemInCart()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'cart_key' => 'required',
            'item_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 400);
        }

        $cartKey = $param['cart_key'];
        $itemID = $param['item_id'];
        $cart = $this->cartService->getCartByKey($cartKey);
        if ($cart['cart_key'] == $cartKey) {
            $cartItem = $this->cartItemService->getCartItemByItemID($cartKey, $itemID);
            if ($cartItem) {
                $this->cartItemService->deleteItemInCart($cartKey, $itemID);
                return $this->successResponse(null, 'Delete Success');
            } else {
                return $this->errorResponse('Not found item', null, false, 400);
            }

        } else {
            return $this->errorResponse('The CartKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }
    }

}
