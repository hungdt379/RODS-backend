<?php


namespace App\Http\Controllers;



use App\Domain\Services\CartItemService;
use App\Domain\Services\CartService;
use App\Domain\Services\MenuService;
use App\Traits\ApiResponse;
use JWTAuth;
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
    public function __construct(CartService $cartService,CartItemService $cartItemService,MenuService $menuService)
    {
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
        $this->menuService = $menuService;
    }


    public function store($tableID)
    {
        return $this->cartService->addNewCart($tableID);
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

            $cartItem = $this->cartItemService->getByCartKey($cartKey);
            $listItem = [];
            $totalCost = 0;
            foreach ($cartItem as $item){
                $detailItem = $this->menuService->getItemById($cartKey, $item['product_id']);
                array_push($listItem, $detailItem);
                $totalCost+= $detailItem[0]['cost'] * $detailItem[0]['quantity'];
            }

            $data = ['cart' => $cart['cart_key'], 'item_in_cart' => $listItem, 'total_cost' => $totalCost];
            return $this->successResponse($data, 'Success');

        } else {
            return $this->errorResponse('The CarKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }

    }

    public function destroy()
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
            return $this->errorResponse('The CarKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }

    }

    public function addProducts()
    {
        $param = request()->all();
        $validator = Validator::make($param, [
            'cart_key' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 400);
        }

        $cartKey = $param['cart_key'];
        $productID = $param['product_id'];
        $quantity = $param['quantity'];
        $note = $param['note'];
        //Check if the CarKey is Valid
        $cart = $this->cartService->getCartByKey($cartKey);
        if ($cart['cart_key']== $cartKey) {
            //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
            $cartItem = $this->cartItemService->getItemByProductID($cartKey, $productID);
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $data = $this->cartItemService->updateQuantity($cartKey, $productID, $quantity);
                return $this->successResponse($data, 'Update quantity success');
            } else {
                $data = $this->cartItemService->addNewItem($cartKey, $productID, $quantity, $note);
                return $this->successResponse($data, 'Add item Success');
            }
        } else {
            return $this->errorResponse('The CarKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }

    }

    public function deleteItemInCart(){
        $param = request()->all();
        $validator = Validator::make($param, [
            'cart_key' => 'required',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null, false, 400);
        }

        $cartKey = $param['cart_key'];
        $productID = $param['product_id'];
        $cart = $this->cartService->getCartByKey($cartKey);
        if ($cart['cart_key'] == $cartKey) {
            $cartItem = $this->cartItemService->getItemByProductID($cartKey, $productID);
            if ($cartItem) {
                $this->cartItemService->deleteItemInCart($cartKey, $productID);
                return $this->successResponse(null, 'Delete Success');
            } else {
                return $this->errorResponse('Not found item', null, false, 400);
            }

        } else {
            return $this->errorResponse('The CarKey you provided does not match the Cart Key for this Cart.', null, false, 400);
        }
    }

}
