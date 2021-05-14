<?php


namespace App\Domain\Services;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportServerTime implements FromArray, WithHeadings
{
    public $headers = [];
    public $data = [];

    function __construct($headers = [], $data = [])
    {
        $this->headers = $headers;
        $this->data = $data;
    }

    /**
     * mang du lieu
     *
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    //Thêm hàng tiêu đề cho bảng
    public function headings(): array
    {
        return $this->headers;
    }
}
