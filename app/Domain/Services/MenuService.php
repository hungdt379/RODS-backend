<?php


namespace App\Domain\Services;


use App\Domain\Repositories\CategoryRepository;
use App\Domain\Repositories\DishInComboRepository;
use App\Domain\Repositories\MenuRepository;
use App\Domain\Repositories\OrderRepository;

class MenuService
{
    private $menuRepository;
    private $categoryRepository;
    private $orderRepository;
    private $dishInComboRepository;
    private $cartItemService;

    /**
     * MenuService constructor.
     * @param $menuRepository
     * @param $categoryRepository
     * @param $orderRepository
     * @param $dishInComboRepository
     */
    public function __construct(MenuRepository $menuRepository, CategoryRepository $categoryRepository, OrderRepository $orderRepository, DishInComboRepository $dishInComboRepository, CartItemService $cartItemService)
    {
        $this->menuRepository = $menuRepository;
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
        $this->dishInComboRepository = $dishInComboRepository;
        $this->cartItemService = $cartItemService;
    }


    public function getMenu($tableID)
    {
        $checkExistingOrder = $this->orderRepository->checkExistingOrderInTable($tableID)->toArray();
        if (($checkExistingOrder) == []) {
            $menu['combo'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getCombo()->_id);
            $menu['drink'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id);
            $menu['fast'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id);
        } else {
            if ($checkExistingOrder[0]['combo']['hotpot'] == false) {
                $menu['combo']['detail'] = $this->menuRepository->getItemByID($checkExistingOrder[0]['combo']['_id']);
                $menu['combo']['dish_in_combo'] = $this->dishInComboRepository->getDishesByCombo($checkExistingOrder[0]['combo']['_id']);
                $menu['combo']['detail'][0]['cost'] = 0;

            } else {
                $menu['combo']['detail'] = $this->menuRepository->getItemByID($checkExistingOrder[0]['combo']['_id']);
                $menu['combo']['dish_in_combo'] = $this->dishInComboRepository->getDishesByCombo($checkExistingOrder[0]['combo']['_id']);
                $menu['hotpot']['detail'] = $this->menuRepository->getHotpot();
                $menu['hotpot']['dish_in_hotpot'] = $this->dishInComboRepository->getDishesByCombo($menu['hotpot']['detail'][0]['_id']);
                $menu['combo']['detail'][0]['cost'] = 0;
            }
            if (isset($checkExistingOrder[0]['hotpot'])) {
                $menu['hotpot']['detail'][0]['cost'] = 0;
            }
            $menu['drink'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id);
            $menu['fast'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id);
        }
        return $menu;
    }


    public function getItemByName($name)
    {
        return $this->menuRepository->getItemByName($name);
    }

    public function getItemInCart($cartKey, $productID)
    {
        $item = $this->menuRepository->getItemByID($productID);
        $cartItem = $this->cartItemService->getCartItemByProductID($cartKey, $productID);

        $item[0]['quantity'] = $cartItem['quantity'];
        $item[0]['note'] = $cartItem['note'];
        $item[0]['total_cost'] = $cartItem['total_cost'];
        if (isset($cartItem['dish_in_combo'])) {
            $item[0]['dish_in_combo'] = $cartItem['dish_in_combo'];
        }

        return $item;
    }

    public function getDetailItemByID($id)
    {
        return $this->menuRepository->getDetailItemByID($id);
    }

    public function isCombo($id)
    {
        $item = $this->getDetailItemByID($id);

        if ($item[0]['category']['name'] == 'combo') {
            return true;
        }
        return false;
    }

}
