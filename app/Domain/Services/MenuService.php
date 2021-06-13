<?php


namespace App\Domain\Services;


use App\Domain\Repositories\CategoryRepository;
use App\Domain\Repositories\MenuRepository;
use App\Domain\Repositories\OrderRepository;

class MenuService
{
    private $menuRepository;
    private $categoryRepository;
    private $orderRepository;

    /**
     * MenuService constructor.
     * @param MenuRepository $menuRepository
     * @param CategoryRepository $categoryRepository
     * @param OrderRepository $orderRepository
     */
    public function __construct(MenuRepository $menuRepository, CategoryRepository $categoryRepository, OrderRepository $orderRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
    }

    public function getMenu($tableID)
    {
        $checkExistingOrder = $this->orderRepository->checkExistingOrderInTable($tableID);
        if (!$checkExistingOrder) {
            $menu['combo'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getCombo()->_id);
            $menu['drink'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getDink()->_id);
            $menu['fast'] = $this->menuRepository->getMenuByCategory($this->categoryRepository->getFast()->_id);
        } else {
            if ($checkExistingOrder[0]['combo']['hotpot']) {
                $menu['combo'] = $this->menuRepository->getMenuComboNoHotpotAfterOrder($checkExistingOrder[0]['combo']['_id']);
            } else {
                $menu['combo'] = $this->menuRepository->getMenuComboHasHotpotAfterOrder($checkExistingOrder[0]['combo']['_id']);
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
