<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\CartItem;

class CartItemRepository
{
    public function getCartItemByTableID($tableID){
        return CartItem::where('table_id', $tableID)->get();
    }

    public function deleteAllItemByTableID($tableID){
        return CartItem::where('table_id', $tableID)->delete();
    }

    public function getCartItemByItemID($tableID, $itemID){
        return CartItem::where(['table_id' => $tableID, 'item_id' => $itemID])->first();
    }

    public function update($item){
        return $item->update();
    }

    public function insert($cartItem){
        return $cartItem->save();
    }

    public function deleteItemInCart($tableID, $itemID){
       return CartItem::where(['table_id' => $tableID, 'item_id' => $itemID])->delete();
    }



}
