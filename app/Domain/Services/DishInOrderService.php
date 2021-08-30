<?php


namespace App\Domain\Services;


use App\Domain\Entities\DishInOrder;
use App\Domain\Repositories\DishInOrderRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Illuminate\Support\Facades\Storage;

class DishInOrderService
{
    private $dishInOrderRepository;
    private $categoryService;

    /**
     * DishInOrderService constructor.
     * @param DishInOrderRepository $dishInOrderRepository
     * @param CategoryService $categoryService
     */
    public function __construct(DishInOrderRepository $dishInOrderRepository, CategoryService $categoryService)
    {
        $this->dishInOrderRepository = $dishInOrderRepository;
        $this->categoryService = $categoryService;
    }

    public function insert($dishInOrder)
    {
        return $this->dishInOrderRepository->insert($dishInOrder);
    }

    public function getDishInOrder($categoryID, $page, $pageSize, $status)
    {
        return $this->dishInOrderRepository->getDishInOrder($categoryID, $page, $pageSize, $status);
    }

    public function getTotalDishInOrder($categoryID, $status)
    {
        $data = $this->dishInOrderRepository->getTotalDishInOrder($categoryID, $status);
        return sizeof($data);
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

    public function getListDishInOrderByID($id){
        return $this->dishInOrderRepository->getListDishInOrderByID($id);
    }

    public function updateStatus($dishInOrder)
    {
        $dishInOrder->status = DishInOrder::ORDER_ITEM_STATUS_COMPLETED;
        return $this->dishInOrderRepository->update($dishInOrder);
    }

    public function exportPdf($dishInOrder, $type)
    {
        $html = '
                <div style=" font-size: 14px; font-family: DejaVu Sans;" align="center" ><b>NHẤT NƯỚNG QUÁN</b></div>
                <hr>
                <table style=" font-size: 10px; width: 200px; font-family: DejaVu Sans; border: 1px">
                    <tr>
                        <td style="width: 40%">
                            <b>Bàn:</b>
                        </td>
                        <td style="width: 60%">
                            ' . $dishInOrder->table_name . '
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%">
                            <b>Tên món:</b>
                        </td>
                        <td style="width: 60%">
                            ' . $dishInOrder->item_name . '
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%">
                            <b>Số lượng:</b>
                        </td>
                        <td style="width: 60%">
                            ' . $dishInOrder->quantity . '
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%">
                            <b>Chú thích:</b>
                        </td>
                        <td style="width: 60%">
                            ' . $dishInOrder->note . '
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%">
                            <b>Loại:</b>
                        </td>
                        <td style="width: 60%">
                            ' . $type . '
                        </td>
                    </tr>
                </table>
        ';

        $dompdf = PDF::loadHTML($html)->setPaper(array(0, 0, 210, 80 * 2.838), 'landscape');
        $nameFile = 'cd_' . time() . '.pdf';
        Storage::disk('completeDish')->put($nameFile, $dompdf->output());
        return $url = asset('completeDish/' . $nameFile);
    }

    public function exportListDishInOrder($dishInOrder){
        $tempPdf = new Dompdf();
        $html = '
                <div align="center" style="font-size: 12px; font-family: DejaVu Sans;"><b>NHẤT NƯỚNG QUÁN</b></div>
                <div align="center" style="font-size: 12px; font-family: DejaVu Sans;"><b>DANH SÁCH MÓN</b></div>
                <div align="center" style="font-size: 10px; font-family: DejaVu Sans;"><b>' . $dishInOrder[0]['table_name'] . '</b></div>
                <br>
                <div style="font-size: 10px; font-family: DejaVu Sans">Thời gian: ' . date('d-m-Y H:i:s', time()) . '</div>
                <br>
                <table style="font-size: 10px; width: 290px; font-family: DejaVu Sans; border: 1px solid black;">
                    <tr>
                        <th style="border-bottom: 1px solid black">Tên món</th>
                        <th style="border-bottom: 1px solid black">SL</th>
                    </tr>
                    ';
        for ($i = 0; $i < sizeof($dishInOrder); $i++) {
            $html = $html . '<tr>
                                <td style="border-bottom: 1px solid black">' . $dishInOrder[$i]['item_name'] . '</td>
                                <td align="center" style="border-bottom: 1px solid black">' . $dishInOrder[$i]['quantity'] . '</td>
                            </tr>';
        }

        $tempPdf->loadHtml($html);
        $tempPdf->setPaper(array(0, 0, 150, 100 * 2.838), 'landscape');
        $tempPdf->render();
        $pageCount = $tempPdf->getCanvas()->get_page_count();
        unset($tempPdf);

        $option = new Options();
        $option->setIsRemoteEnabled(true);
        $pdf = new Dompdf($option);
        $customPaper = array(0, 0, (150 * $pageCount)/1.7, 100 * 2.838);
        $pdf->loadHtml($html);
        $pdf->setPaper($customPaper, 'landscape');
        $pdf->render();
        $nameFile = 'cd_' . time() . '.pdf';
        Storage::disk('completeDish')->put($nameFile, $pdf->output());
        return asset('completeDish/' . $nameFile);
    }


    public function deleteDishInOrder($id)
    {
        $this->dishInOrderRepository->delete($id);
    }

    public function getDrinkInOrder($categoryID, $status, $tableID)
    {
        return $this->dishInOrderRepository->getDishInOrderByTableID($categoryID, $status, $tableID);
    }

    public function getDishInOrderByTableID($categoryID, $status, $tableID)
    {
        return $this->dishInOrderRepository->getDishInOrderByTableID($categoryID, $status, $tableID);
    }

}
