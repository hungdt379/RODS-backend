<?php


namespace App\Domain\Services;


use App\Domain\Repositories\QueueOrderRepository;
use App\Domain\Entities\QueueOrder;

class QueueOrderService
{
    private $queueOrderRepository;

    /**
     * QueueOrderService constructor.
     * @param $queueOrderRepository
     */
    public function __construct(QueueOrderRepository $queueOrderRepository)
    {
        $this->queueOrderRepository = $queueOrderRepository;
    }

    public function checkExistQueueOrderInTable($tableID)
    {
        $queueOrder = $this->getQueueOrderByTableID($tableID)->toArray();
        if ($queueOrder == []) {
            return false;
        }
        return true;
    }

    public function insertToQueueOrder($param)
    {
        $queueOrder = new QueueOrder();

        $queueOrder->number_of_customer = $param['number_of_customer'];
        $queueOrder->table_id = $param['table_id'];
        $queueOrder->table_name = $param['table_name'];
        $queueOrder->status = $param['status'];
        if(isset($param['combo'])){
            $queueOrder->combo = json_decode($param['combo']);
        }
        if(isset($param['side_dish_drink'])){
            $queueOrder->side_dish_drink = json_decode($param['side_dish_drink']);
        }
        if(isset($param['hotpot'])){
            $queueOrder->hotpot = json_decode($param['hotpot']);
        }
        $queueOrder->total_cost = $param['total_cost'];
        $queueOrder->ts = time();

        return $this->queueOrderRepository->insert($queueOrder);
    }

    public function getQueueOrderByTableID($tableID)
    {
        return $this->queueOrderRepository->getQueueOrderByTableID($tableID);
    }

}
