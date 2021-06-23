<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\CartItem;

class CartItemRepository
{
    public function getByCartKey($cartKey){
        return CartItem::where('cart_key', $cartKey)->get();
    }

    public function deleteByCartKey($cartKey){
        return CartItem::where('cart_key', $cartKey)->delete();
    }

    public function getItemByProductID($cartKey, $productID){
        return CartItem::where(['cart_key' => $cartKey, 'product_id' => $productID])->first();
    }

    public function update($item){
        return $item->update();
    }

    public function insert($cartItem){
        return $cartItem->save();
    }

    public function deleteItemInCart($cartKey, $productID){
       return CartItem::where(['cart_key' => $cartKey, 'product_id' => $productID])->delete();
    }

}
