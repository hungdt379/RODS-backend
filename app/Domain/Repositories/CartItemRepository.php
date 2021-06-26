<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\CartItem;

class CartItemRepository
{
    public function getItemByCartKey($cartKey){
        return CartItem::where('cart_key', $cartKey)->get();
    }

    public function deleteByCartKey($cartKey){
        return CartItem::where('cart_key', $cartKey)->delete();
    }

    public function getCartItemByItemID($cartKey, $itemID){
        return CartItem::where(['cart_key' => $cartKey, 'item_id' => $itemID])->first();
    }

    public function update($item){
        return $item->update();
    }

    public function insert($cartItem){
        return $cartItem->save();
    }

    public function deleteItemInCart($cartKey, $itemID){
       return CartItem::where(['cart_key' => $cartKey, 'item_id' => $itemID])->delete();
    }

}
