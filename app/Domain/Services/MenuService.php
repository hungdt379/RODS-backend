<?php


namespace App\Domain\Services;


use App\Domain\Entities\Menu;
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
        $checkExistingOrder = $this->orderRepository->checkExistingOrderInTable($tableID);
        $combo = null;
        if (!$checkExistingOrder) {
            $menu['combo'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getCombo()->_id);
            $menu['drink'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id);
            $menu['fast'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id);
        } else {
            foreach ($checkExistingOrder['item'] as $value){
                if(strpos($value['detail_item']['name'], 'Combo') !== false){
                    $combo = $value['detail_item'];
                }
            }
            if ($combo['hotpot'] == false) {
                $menu['combo']['detail'] = $this->menuRepository->getItemByID($combo['_id']);
                $menu['combo']['dish_in_combo'] = $this->dishInComboRepository->getDishesByCombo($combo['_id']);
                $menu['combo']['detail'][0]['cost'] = 0;

            } else {
                $menu['combo']['detail'] = $this->menuRepository->getItemByID($combo['_id']);
                $menu['combo']['dish_in_combo'] = $this->dishInComboRepository->getDishesByCombo($combo['_id']);
                $menu['hotpot']['detail'] = $this->menuRepository->getHotpot();
                $menu['hotpot']['dish_in_hotpot'] = $this->dishInComboRepository->getDishesByCombo($menu['hotpot']['detail'][0]['_id']);
                $menu['combo']['detail']['cost'] = 0;
            }
            foreach ($checkExistingOrder['item'] as $value){
                if(strpos($value['detail_item']['name'], 'Lẩu') !== false){
                    $menu['hotpot']['detail'][0]['cost'] = 0;
                }
            }

            $menu['drink'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id);
            $menu['fast'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id);
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
            }else if ($checkExistingOrder[0]['combo']['name'] == Menu::COMBO_169){
                $resultSearch = $this->menuRepository->searchCombo169($name);
            }else if ($checkExistingOrder[0]['combo']['name'] == Menu::COMBO_209){
                $resultSearch = $this->menuRepository->searchCombo169($name);
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
        return $this->menuRepository->getDetailItemByID($itemID);
    }

    public function getItemByID($itemID){
        return $this->menuRepository->getItemByID($itemID);
    }

}
