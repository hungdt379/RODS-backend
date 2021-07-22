<?php


namespace App\Domain\Services;


use App\Domain\Entities\Cart;
use App\Domain\Repositories\CartRepository;

class CartService
{
    private $cartRepository;
    private $cartItemService;

    /**
     * CartService constructor.
     * @param $cartRepository
     * @param $cartItemService
     */
    public function __construct(CartRepository $cartRepository, CartItemService $cartItemService)
    {
        $this->cartRepository = $cartRepository;
        $this->cartItemService = $cartItemService;
    }


    public function addNewCart($tableID)
    {
        $data = [
            'table_id' => isset($tableID) ? $tableID : null,
            'total_cost' => 0
        ];
        $cart = new Cart($data);

        return $this->cartRepository->insert($cart);
    }

    public function getCartByTableID($tableID)
    {
        return $this->cartRepository->getCartByTableID($tableID);
    }

    public function update($cart)
    {
        return $this->cartRepository->update($cart);
    }

    public function delete($tableID)
    {
        $cart = $this->getCartByTableID($tableID);
        if ($cart) {
            $this->cartItemService->deleteAllItemByTableID($tableID);
            $this->cartRepository->delete($cart);
        }
    }


}
