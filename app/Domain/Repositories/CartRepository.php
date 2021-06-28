<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Cart;

class CartRepository
{
    public function insert($cart){
        return $cart->save();
    }

    public function getCartByTableID($tableID){
        return Cart::where('table_id', $tableID)->first();
    }

    public function delete($cart){
        return $cart->delete();
    }

    public function update($cart){
        return $cart->update();
    }
}
