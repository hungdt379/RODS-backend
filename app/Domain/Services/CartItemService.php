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

    public function getByCartKey($cartKey)
    {
        return $this->cartItemRepository->getByCartKey($cartKey);
    }

    public function deleteByCartKey($cartKey)
    {
        return $this->cartItemRepository->deleteByCartKey($cartKey);
    }

    public function getCartItemByProductID($cartKey, $productID)
    {
        return $this->cartItemRepository->getCartItemByProductID($cartKey, $productID);
    }

    public function update($cartKey, $productID, $quantity, $note, $dishInCombo, $cost)
    {
        $item = $this->cartItemRepository->getCartItemByProductID($cartKey, $productID);
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

    public function addNewItem($cartKey, $productID, $quantity, $note, $dishInCombo, $cost)
    {
        $data = [
            'cart_key' => $cartKey,
            'product_id' => $productID,
            'quantity' => $quantity,
            'note' => $note,
            'dish_in_combo' => json_decode($dishInCombo),
            'total_cost' => (int)$cost * (int)$quantity
        ];
        $cartItem = new CartItem($data);
        $this->cartItemRepository->insert($cartItem);

        return $cartItem;
    }

    public function deleteItemInCart($cartKey, $productID)
    {
        return $this->cartItemRepository->deleteItemInCart($cartKey, $productID);
    }
}
