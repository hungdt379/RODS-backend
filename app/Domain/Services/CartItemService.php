<?php


namespace App\Domain\Services;


use App\Domain\Entities\CartItem;
use App\Domain\Repositories\CartItemRepository;

class CartItemService
{
    private $cartItemRepository;

    /**
     * CartItemService constructor.
     * @param $cartItemRepository
     */
    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    public function getItemByCartKey($cartKey)
    {
        return $this->cartItemRepository->getItemByCartKey($cartKey);
    }

    public function deleteByCartKey($cartKey)
    {
        return $this->cartItemRepository->deleteByCartKey($cartKey);
    }

    public function getCartItemByItemID($cartKey, $itemID)
    {
        return $this->cartItemRepository->getCartItemByItemID($cartKey, $itemID);
    }

    public function update($cartKey, $itemID, $quantity, $note, $dishInCombo, $cost)
    {
        $item = $this->cartItemRepository->getCartItemByItemID($cartKey, $itemID);
        $item['quantity'] = $quantity;
        if (isset($note)) {
            $item['note'] = $note;
        }
        if (isset($dishInCombo)) {
            $item['dish_in_combo'] = json_decode($dishInCombo);
        }
        $item['total_cost'] = (int)$cost * (int)$quantity;
        $this->cartItemRepository->update($item);

        return $item;
    }

    public function addNewItem($cartKey, $itemID, $quantity, $note, $dishInCombo, $cost)
    {
        $data = [
            'cart_key' => $cartKey,
            'item_id' => $itemID,
            'quantity' => $quantity,
            'note' => $note,
            'dish_in_combo' => json_decode($dishInCombo),
            'total_cost' => (int)$cost * (int)$quantity
        ];
        $cartItem = new CartItem($data);
        $this->cartItemRepository->insert($cartItem);

        return $cartItem;
    }

    public function deleteItemInCart($cartKey, $itemID)
    {
        return $this->cartItemRepository->deleteItemInCart($cartKey, $itemID);
    }
}
