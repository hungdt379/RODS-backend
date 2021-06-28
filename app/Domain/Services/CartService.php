<?php


namespace App\Domain\Services;


use App\Domain\Entities\Cart;
use App\Domain\Repositories\CartRepository;

class CartService
{
    private $cartRepository;

    /**
     * CartService constructor.
     * @param $cartRepository
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
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

    public function delete($cart)
    {
        return $this->cartRepository->delete($cart);
    }


}
