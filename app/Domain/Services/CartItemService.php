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

    public function getItemByProductID($cartKey, $productID)
    {
        return $this->cartItemRepository->getItemByProductID($cartKey, $productID);
    }

    public function updateQuantity($cartKey, $productID, $quantity, $dishInCombo)
    {
        $item = $this->cartItemRepository->getItemByProductID($cartKey, $productID);
        $item['quantity'] = $quantity;
        if (isset($item['dish_in_combo'])) {
            $item['dish_in_combo'] = json_decode($dishInCombo);
        }
        $this->cartItemRepository->update($item);

        return $item;
    }

    public function addNewItem($cartKey, $productID, $quantity, $note, $dishInCombo)
    {
        $data = [
            'cart_key' => $cartKey,
            'product_id' => $productID,
            'quantity' => $quantity,
            'note' => $note,
            'dish_in_combo' => json_decode($dishInCombo)
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
