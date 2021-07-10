<?php


namespace App\Domain\Services;


use App\Domain\Entities\Menu;
use App\Domain\Repositories\CategoryRepository;
use App\Domain\Repositories\DishInComboRepository;
use App\Domain\Repositories\MenuRepository;
use App\Domain\Repositories\OrderRepository;
use JWTAuth;

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
        $confirmOrder = $this->orderRepository->getConfirmOrder($tableID);
        $combo = null;
        if (!$confirmOrder) {
            $menu['combo'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getCombo()->_id);
            $menu['fast'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id);
            $menu['normal'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getNormal()->_id);
            $menu['drink'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id);
            $menu['alcohol'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getAlcohol()->_id);
            $menu['beer'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getBeer()->_id);
        } else {
            foreach ($confirmOrder['item'] as $value) {
                if (strpos($value['detail_item']['name'], 'Combo') !== false) {
                    $combo = $value['detail_item'];
                }
            }
            if ($combo['hotpot'] == false) {
                $menu['combo'] = $this->menuRepository->getItemByID($combo['_id']);
                $menu['combo']['cost'] = 0;

            } else {
                $menu['combo'] = $this->menuRepository->getItemByID($combo['_id']);
                $menu['hotpot'] = $this->menuRepository->getHotpot();
                $menu['combo']['cost'] = 0;
            }
            foreach ($confirmOrder['item'] as $value) {
                if (strpos($value['detail_item']['name'], 'Lẩu') !== false) {
                    $menu['hotpot'][0]['cost'] = 0;
                }
            }

            $menu['fast'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id);
            $menu['normal'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getNormal()->_id);
            $menu['drink'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id);
            $menu['alcohol'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getAlcohol()->_id);
            $menu['beer'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getBeer()->_id);
        }
        return $menu;
    }


    public function getItemByName($name, $tableID)
    {
        $checkExistingOrder = $this->orderRepository->checkExistingOrderInTable($tableID)->toArray();

        if (($checkExistingOrder) == [] || !isset($checkExistingOrder[0]['combo'])) {
            $resultSearch = $this->menuRepository->getItemByName($name);
        } else {
            if ($checkExistingOrder[0]['combo']['name'] == Menu::COMBO_129) {
                $resultSearch = $this->menuRepository->searchCombo129($name);
            } else if ($checkExistingOrder[0]['combo']['name'] == Menu::COMBO_169) {
                $resultSearch = $this->menuRepository->searchCombo169($name);
            } else if ($checkExistingOrder[0]['combo']['name'] == Menu::COMBO_209) {
                $resultSearch = $this->menuRepository->searchCombo209($name);
            }
        }

        return $resultSearch;
    }

    public function getDetailItemInCart($tableID, $itemID)
    {
        $item = $this->menuRepository->getItemByID($itemID);
        $cartItem = $this->cartItemService->getCartItemByItemID($tableID, $itemID);

        $item['quantity'] = $cartItem['quantity'];
        $item['note'] = $cartItem['note'];
        $item['total_cost'] = $cartItem['total_cost'];
        if (isset($cartItem['dish_in_combo'])) {
            $item['dish_in_combo'] = $cartItem['dish_in_combo'];
        }

        return $item;
    }

    public function getDetailItemByID($itemID)
    {
        $tableID = JWTAuth::user()->_id;
        $cartItem = $this->cartItemService->getCartItemByTableID($tableID)->toArray();
        $item = $this->menuRepository->getDetailItemByID($itemID);

        foreach ($cartItem as $value) {
            if ($item[0]['_id'] == $value['item_id']) {
                $item[0]['quantity'] = $value['quantity'];
                if ($value['dish_in_combo'] == null) {
                    $value['dish_in_combo'] = [];
                } else {
                    $dishInCombo = $item[0]['dish_in_combo'];
                    $length = sizeof($value['dish_in_combo']);
                    for ($i = 0; $i < $length; $i++) {
                        for ($j = 0; $j < sizeof($dishInCombo); $j++) {
                            if ($value['dish_in_combo'][$i] == $dishInCombo[$j]['name']) {
                                $dishInCombo[$j]['is_selected'] = true;
                            }
                        }
                    }
                }
            }
        }

        return $item;
    }

    public function getItemByID($itemID)
    {
        return $this->menuRepository->getItemByID($itemID);
    }

    public function getItem($textSearch)
    {
        if ($textSearch == null || $textSearch == ''){
            $menu = $this->menuRepository->getAllMenu()->toArray();
            $dishInCombo = $this->dishInComboRepository->getAllDishInCombo();
        }else{
            $menu = $this->menuRepository->getMenu($textSearch)->toArray();
            $dishInCombo = $this->dishInComboRepository->getDishInCombo($textSearch);
        }

        foreach ($dishInCombo as $item) {
            array_push($menu, $item);
        }

        return $menu;
    }

    public function updateItemSoldOutStatus($menuItem, $dishItem)
    {
        if ($menuItem == null && $dishItem != null) {
            $dishItem->is_sold_out = true;
            $this->dishInComboRepository->update($dishItem);
        } else if ($menuItem != null && $dishItem == null) {
            $menuItem->is_sold_out = true;
            $this->menuRepository->update($menuItem);
        }
    }

}
