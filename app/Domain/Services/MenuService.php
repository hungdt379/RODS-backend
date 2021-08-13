<?php


namespace App\Domain\Services;


use App\Domain\Entities\Menu;
use App\Domain\Repositories\CategoryRepository;
use App\Domain\Repositories\DishInComboRepository;
use App\Domain\Repositories\MenuRepository;
use App\Domain\Repositories\OrderRepository;
use App\Domain\Repositories\QueueOrderRepository;
use JWTAuth;
use App\Traits\ApiResponse;

class MenuService
{
    use ApiResponse;

    private $menuRepository;
    private $categoryRepository;
    private $orderRepository;
    private $queueOrderRepository;
    private $dishInComboRepository;
    private $cartItemService;

    /**
     * MenuService constructor.
     * @param MenuRepository $menuRepository
     * @param CategoryRepository $categoryRepository
     * @param OrderRepository $orderRepository
     * @param QueueOrderRepository $queueOrderRepository
     * @param DishInComboRepository $dishInComboRepository
     * @param CartItemService $cartItemService
     */
    public function __construct(MenuRepository $menuRepository,
                                CategoryRepository $categoryRepository,
                                OrderRepository $orderRepository,
                                QueueOrderRepository $queueOrderRepository,
                                DishInComboRepository $dishInComboRepository,
                                CartItemService $cartItemService)
    {
        $this->menuRepository = $menuRepository;
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
        $this->queueOrderRepository = $queueOrderRepository;
        $this->dishInComboRepository = $dishInComboRepository;
        $this->cartItemService = $cartItemService;
    }

    function in_array_field($needle, $needle_field, $haystack)
    {
        foreach ($haystack as $item) {
            if (isset($item['detail_item'][$needle_field]) && $item['detail_item'][$needle_field] == $needle)
                return true;
        }

        return false;
    }

    private function in_menu_array($needle, $needle_field, $haystack)
    {
        foreach ($haystack as $item) {
            if (isset($item[$needle_field]) && $item[$needle_field] == $needle)
                return true;
        }

        return false;
    }

    private function in_menu_array_quantiy($needle, $needle_field, $haystack)
    {
        foreach ($haystack as $item) {
            if (isset($item[$needle_field]) && $item[$needle_field] == $needle)
                return $item['quantity'];
        }

        return 0;
    }

    private function setChosenItemInMenu($cartItem, $menuWithCategory)
    {
        foreach ($menuWithCategory as $item) {
            $quantity = $this->in_menu_array_quantiy($item['_id'], 'item_id', $cartItem);
            if ($quantity > 0) {
                $item['in_cart'] = true;
                $item['quantity'] = $quantity;
            } else {
                $item['in_cart'] = false;
                $item['quantity'] = 0;
            }
        }

        return $menuWithCategory;
    }

    public function getMenu($tableID)
    {
        $confirmOrder = $this->orderRepository->getConfirmOrder($tableID);
        $cartItem = $this->cartItemService->getCartItemByTableID($tableID)->toArray();
        if (!$confirmOrder || !$this->in_array_field($this->categoryRepository->getCombo()->_id, 'category_id', $confirmOrder['item'])) {
            $menu['combo'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getCombo()->_id));
            $menu['fast'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id));
            $menu['normal'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getNormal()->_id));
            $menu['drink'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id));
            $menu['alcohol'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getAlcohol()->_id));
            $menu['beer'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getBeer()->_id));
        } else {
            foreach ($confirmOrder['item'] as $value) {
                if (strpos($value['detail_item']['name'], 'Combo') !== false) {
                    $combo = $value['detail_item'];
                    $menu['combo'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getItemByIdOfMenu($combo['_id']));
                    $menu['combo'][0]['cost'] = 0;
                }
            }

            $menu['fast'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id));
            $menu['normal'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getNormal()->_id));
            $menu['drink'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id));
            $menu['alcohol'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getAlcohol()->_id));
            $menu['beer'] = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getMenuByCategory($this->categoryRepository->getBeer()->_id));
        }
        return $menu;
    }


    public function getItemByName($name, $tableID)
    {
        $confirmOrder = $this->orderRepository->getConfirmOrder($tableID);
        $cartItem = $this->cartItemService->getCartItemByTableID($tableID)->toArray();
        if (!$confirmOrder || !$this->in_array_field($this->categoryRepository->getCombo()->_id, 'category_id', $confirmOrder['item'])) {
            $resultSearch = $this->setChosenItemInMenu($cartItem, $this->menuRepository->getItemByName($name));
        } else {
            if ($this->in_array_field(Menu::COMBO_129, 'name', $confirmOrder['item'])) {
                $resultSearch = $this->setChosenItemInMenu($cartItem, $this->menuRepository->searchCombo129($name));
            } else if ($this->in_array_field(Menu::COMBO_169, 'name', $confirmOrder['item'])) {
                $resultSearch = $this->setChosenItemInMenu($cartItem, $this->menuRepository->searchCombo169($name));
            } else if ($this->in_array_field(Menu::COMBO_209, 'name', $confirmOrder['item'])) {
                $resultSearch = $this->setChosenItemInMenu($cartItem, $this->menuRepository->searchCombo209($name));
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

        $confirmOrder = $this->orderRepository->getConfirmOrder($tableID);
        if ($confirmOrder) {
            foreach ($confirmOrder['item'] as $value) {
                if (strpos($value['detail_item']['name'], 'Combo') !== false) {
                    $category = $this->categoryRepository->getCombo();
                    if ($item[0]['category_id'] == $category->_id) {
                        $item[0]['cost'] = 0;
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
        if ($textSearch == null || $textSearch == '') {
            $menu = $this->menuRepository->getAllMenu()->toArray();
            $dishInCombo = $this->dishInComboRepository->getAllDishInCombo();
        } else {
            $menu = $this->menuRepository->getItemByName($textSearch);
            $dishInCombo = $this->dishInComboRepository->getDishInCombo($textSearch);
        }

        foreach ($dishInCombo as $item) {
            if (!$this->in_menu_array($item['name'], 'name', $menu))
                array_push($menu, $item);
        }

        return $menu;
    }

    public function updateItemSoldOutStatus($menuItem, $dishItem, $dishItemInMenu, $isSoldOut = false)
    {
        if ($menuItem == null && $dishItem != null && $dishItemInMenu == null) {
            $dishItem->is_sold_out = $isSoldOut;
            $this->dishInComboRepository->update($dishItem);
        } else if ($menuItem != null && $dishItem == null && $dishItemInMenu == null) {
            $menuItem->is_sold_out = $isSoldOut;
            $this->menuRepository->update($menuItem);
        } else if ($menuItem != null && $dishItem == null && $dishItemInMenu != null) {
            $menuItem->is_sold_out = $isSoldOut;
            $this->menuRepository->update($menuItem);
            $dishItemInMenu->is_sold_out = $isSoldOut;
            $this->dishInComboRepository->update($dishItemInMenu);
        }
    }

}
