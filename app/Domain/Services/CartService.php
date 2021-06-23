<?php


namespace App\Domain\Services;


use App\Domain\Entities\Cart;
use App\Domain\Repositories\CartRepository;

class CartService
{
    private $cartRepository;

    /**
     * CartService constructor.
     * @param $cartRepository
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addNewCart($tableID){
        $data = [
            'cart_key' => md5(uniqid(rand(), true)),
            'table_id' => isset($tableID) ? $tableID : null,

        ];

        $cart = new Cart($data);
        $this->cartRepository->insert($cart);
        return $cart;
    }

    public function getCartByKey($cartKey){
        return $this->cartRepository->getCartByKey($cartKey);
    }

    public function delete($cart){
        return $this->cartRepository->delete($cart);
    }


}
