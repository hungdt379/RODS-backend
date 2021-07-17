<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Repositories\DishInOrderRepository;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Support\Facades\Storage;

class DishInOrderService
{
    private $dishInOrderRepository;
    private $menuService;

    /**
     * DishInOrderService constructor.
     * @param DishInOrderRepository $dishInOrderRepository
     * @param MenuService $menuService
     */
    public function __construct(DishInOrderRepository $dishInOrderRepository, MenuService $menuService)
    {
        $this->dishInOrderRepository = $dishInOrderRepository;
        $this->menuService = $menuService;
    }

    public function insert($dishInOrder)
    {
        return $this->dishInOrderRepository->insert($dishInOrder);
    }

    public function getDishInOrder($categoryID, $pageSize)
    {
        return $this->dishInOrderRepository->getDishInOrder($categoryID, $pageSize);
    }

    public function getAllDishInOrderByTableID($tableID)
    {
        return $this->dishInOrderRepository->getAllDishInOrderByTableID($tableID);
    }

    public function updateDishInOrderToNewTable($dishInOrder, $toTable)
    {
        foreach ($dishInOrder as $value) {
            $value->table_id = $toTable['_id'];
            $value->table_name = $toTable['full_name'];
            $this->dishInOrderRepository->update($value);
        }
    }

    public function getDishInOrderByID($dishInOrderID)
    {
        return $this->dishInOrderRepository->getDishInOrderByID($dishInOrderID);
    }

    public function updateStatus($dishInOrder)
    {
        $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_COMPLETED;
        return $this->dishInOrderRepository->update($dishInOrder);
    }

    public function exportPdf($dishInOrder)
    {

        $pdf = new Dompdf();
        $customPaper = array(0, 0, 50.10, 283.80);
        $pdf->setPaper($customPaper, 'landscape');
        $html = '
            <div align="center" style="width: 300px; border: 1px solid #000000; font-family: DejaVu Sans";>
            <div><b style="font-size: 20px">' . $dishInOrder->table_name . '</b></div>
            <div style="padding-top: 10px; padding-bottom: 20px">
                <table style="width: 250px">
                    <tr>
                        <td style="width: 150px; padding-left: 20px">
                            <b>Tên món:</b>
                        </td>
                        <td style="width: 100px">
                            ' . $dishInOrder->item_name . '
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150px; padding-left: 20px">
                            <b>Số lượng:</b>
                        </td>
                        <td style="width: 100px">
                            ' . $dishInOrder->quantity . '
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 130px; padding-left: 20px">
                            <b>Trạng thái:</b>
                        </td>
                        <td style="width: 120px">
                            Đã hoàn thành
                        </td>
                    </tr>
                </table>
            </div>
            </div>
        ';
        $pdf->loadHtml($html);
        $pdf->render();
        $pageCount = $pdf->getCanvas()->get_page_count();
        var_dump($pageCount);
        unset($pdf);
        $dompdf = PDF::loadHTML($html)->setPaper(array(0, 0, 50.10 * $pageCount, 290), 'landscape');
        $nameFile = '_' . time() . '.pdf';
        Storage::disk('completeDish')->put($nameFile, $dompdf->output());
        return $url = asset('completeDish/' . $nameFile);
    }
}
