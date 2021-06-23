<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Cart;

class CartRepository
{
    public function insert($cart){
        return $cart->save();
    }

    public function getCartByKey($cartKey){
        return Cart::where('cart_key', $cartKey)->first();
    }

    public function delete($cart){
        return $cart->delete();
    }

    public function update($cart){
        return $cart->update();
    }
}
