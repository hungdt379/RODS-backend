<?php


namespace App\Domain\Services;


use App\Domain\Repositories\QueueOrderRepository;
use App\Domain\Entities\QueueOrder;
use JWTAuth;

class QueueOrderService
{
    private $queueOrderRepository;
    private $cartService;
    private $cartItemService;
    private $menuService;

    /**
     * QueueOrderService constructor.
     * @param QueueOrderRepository $queueOrderRepository
     * @param CartService $cartService
     * @param CartItemService $cartItemService
     * @param MenuService $menuService
     */
    public function __construct(QueueOrderRepository $queueOrderRepository, CartService $cartService, CartItemService $cartItemService, MenuService $menuService)
    {
        $this->queueOrderRepository = $queueOrderRepository;
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
        $this->menuService = $menuService;
    }


    public function checkExistQueueOrderInTable($tableID)
    {
        $queueOrder = $this->getQueueOrderByTableID($tableID);
        if (!$queueOrder) {
            return false;
        }
        return true;
    }

    public function insertToQueueOrder()
    {
        $table = JWTAuth::user();
        $tableID = $table->_id;
        $tableName = $table->full_name;
        $numberOfCustomer = $table->number_of_customer;

        $cart = $this->cartService->getCartByTableID($tableID);
        $cartItem = $this->cartItemService->getCartItemByTableID($tableID)->toArray();

        $item = [];
        foreach ($cartItem as $value) {
            $detailItem = $this->menuService->getItemByID($value['item_id']);
            $value['detail_item'] = $detailItem->toArray();
            if ($value['total_cost'] == 0) {
                $value['quantity'] = 0;
            }
            array_push($item, $value);
        }
        $queueOrder = new QueueOrder();
        $queueOrder->table_id = $tableID;
        $queueOrder->table_name = $tableName;
        $queueOrder->number_of_customer = $numberOfCustomer;
        $queueOrder->status = QueueOrder::QUEUE_ORDER_STATUS_QUEUED;
        $queueOrder->item = $item;
        $queueOrder->total_cost = $cart['total_cost'];
        $queueOrder->ts = time();

        return $this->queueOrderRepository->insert($queueOrder);
    }

    public function getQueueOrderByTableID($tableID)
    {
        return $this->queueOrderRepository->getQueueOrderByTableID($tableID);
    }

    public function delete($id)
    {
        return $this->queueOrderRepository->delete($id);
    }

    public function update($queueOrder)
    {
        return $this->queueOrderRepository->update($queueOrder);
    }
}
