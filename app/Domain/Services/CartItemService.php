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

    public function getCartItemByTableID($tableID)
    {
        return $this->cartItemRepository->getCartItemByTableID($tableID);
    }

    public function deleteAllItemByTableID($tableID)
    {
        return $this->cartItemRepository->deleteAllItemByTableID($tableID);
    }

    public function getCartItemByItemID($tableID, $itemID)
    {
        return $this->cartItemRepository->getCartItemByItemID($tableID, $itemID);
    }

    public function update($tableID, $itemID, $quantity, $note, $dishInCombo, $cost)
    {
        $item = $this->cartItemRepository->getCartItemByItemID($tableID, $itemID);
        $item['quantity'] = $quantity;
        if (isset($note)) {
            $item['note'] = $note;
        }
        if (isset($dishInCombo)) {
            $item['dish_in_combo'] = json_decode($dishInCombo);
        }
        $item['total_cost'] = (int)$cost * (int)$quantity;

        return $this->cartItemRepository->update($item);;
    }

    public function addNewItem($tableID, $itemID, $quantity, $note, $dishInCombo, $cost)
    {
        $data = [
            'table_id' => $tableID,
            'item_id' => $itemID,
            'quantity' => $quantity,
            'note' => $note,
            'dish_in_combo' => json_decode($dishInCombo),
            'total_cost' => (int)$cost * (int)$quantity
        ];
        $cartItem = new CartItem($data);

        return $this->cartItemRepository->insert($cartItem);
    }

    public function deleteItemInCart($tableID, $itemID)
    {
        return $this->cartItemRepository->deleteItemInCart($tableID, $itemID);
    }
}
