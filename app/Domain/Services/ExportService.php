<?php

namespace App\Domain\Services;

use Illuminate\Http\Request;
use App\Domain\Entities\Server;
use App\Domain\Repositories\ServerRepository;
use App\Domain\Entities\ServerLogs;
use App\Domain\Repositories\ServerLogsRepository;
use App\Domain\Entities\ServerBWHistory;
use App\Domain\Repositories\ServerBWHistoryRepository;
use App\Domain\Entities\ServerHardHistory;
use App\Domain\Repositories\ServerHardHistoryRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Domain\Services\ExportServerTime;

//

class ExportService
{
    /**
     * Xuất báo cáo thống kê chi tiết số h sử dụng của từng server theo từng tháng
     *
     */
    function exportServerStatistics($data = [])
    {
        //
        $month = isset($data['month']) ? $data['month'] : date('m');
        $year = isset($data['year']) ? $data['year'] : date('Y');
        $startDate = "1-$month-$year 00:00:00";
        $startTime = strtotime($startDate);
        $lastday = date('t', $startTime);
        $endDate = "$lastday-$month-$year 23:59:59";
        $endTime = strtotime($endDate);
        $queryParams = [
            'select' => ['serverID', 'createdTime'],
            'fromTime' => $startTime,
            'toTime' => $endTime,
            'limit' => 1000000,
            'sort' => 'all.ip,createdTime',
            'sortDirection' => 'ASC',
        ];
        //$bwHistories = $this->getBandWidthHistories($queryParams);
        //$calCulates = $this->calculateUseTimeFromBWH($bwHistories);
        $hwHistories = $this->getHardwareHistories($queryParams);
        $calCulates = $this->calculateUseTimeFromHWH($hwHistories);
        $listServers = $this->getAllServers(['provider' => 'cmc']);
        $listDatesofMonth = [];
        for ($i = 1; $i <= $lastday; $i++) {
            $listDatesofMonth[] = "$i-$month-$year";
        }
        $headers = array_merge(['IP', 'TotalTime'], $listDatesofMonth);
        $data = [];
        foreach ($calCulates as $serverID => $dt) {
            if (isset($listServers[$serverID])) {
                $temp = [
                    $listServers[$serverID]['ip_address'],
                    round($dt['totalTime'] / 3600, 2),
                ];
                foreach ($listDatesofMonth as $day) {
                    $temp[] = isset($dt['dates'][$day]) ? round($dt['dates'][$day] / 3600, 2) : 0;
                }
                $data[] = $temp;
            }
        }
        $nameExport = "Báo cáo chi tiết thời gian sử dụng tháng $month";
        $file = Excel::download(new ExportServerTime($headers, $data), $nameExport . '.xlsx');
        return $file;
    }

    /**
     * Tính toán thời gian sử dụng của từng server theo từng ngày
     *
     * @param null $hwHistories
     * @return array
     */
    function calculateUseTimeFromHWH($hwHistories = null)
    {
        if (!$hwHistories) {
            return [];
        }
        $newData = [
            'totalTime' => 0, // Tổng s sử dụng
            'dates' => [],
        ];
        $rangeTime = 2 * 60;
        $results = [];
        $backSteps = []; //
        foreach ($hwHistories as $history) {
            if (!isset($backSteps[$history['serverID']])) {
                $backSteps[$history['serverID']] = 0;
            }
            $backStep = $backSteps[$history['serverID']];
            //
            if (!isset($results[$history['serverID']])) {
                $results[$history['serverID']] = $newData;
            }
            //
            $hDate = date('j-n-Y', $history['createdTime']);
            if (!isset($results[$history['serverID']]['dates'][$hDate])) {
                $backStep = 0;
                $results[$history['serverID']]['dates'][$hDate] = 0;
            }
            // Giữa ngày tắt server đi thì khoảng time của lần trước và lần sau có thể cách nhau rất nhiều nên phát cắt khoảng này (time lần sau > 2 lần 30' lần trước thì tính là nghỉ)
            if ($backStep && (($history['createdTime'] - $backStep) > (2 * $rangeTime + 30))) {
                $backStep = 0;
            }
            // với time bắt đầu mở trong ngày thì mất khoảng 30' bắt đầu từ lúc bật mới có log
            if (!$backStep) {
                $backStep = $history['createdTime'] - $rangeTime;
            }
            //
            $results[$history['serverID']]['dates'][$hDate] += $history['createdTime'] - $backStep;
            $results[$history['serverID']]['totalTime'] += $history['createdTime'] - $backStep;
            $backStep = $history['createdTime'];
            $backSteps[$history['serverID']] = $backStep;
        }
        return $results;
    }

    /**
     * get hardware Histories : Lấy log hardware của các server 2' log 1 lần
     *
     * @param array $options
     * @return mixed
     */
    function getHardwareHistories($options = [])
    {
        $bwHisRepo = new ServerHardHistoryRepository(new ServerHardHistory());
        return $bwHisRepo->filterData($options);
    }

    /**
     * Tính toán thời gian sử dụng của từng server theo từng ngày
     *
     * @param null $bwHistories
     * @return array
     */
    function calculateUseTimeFromBWH($bwHistories = null)
    {
        if (!$bwHistories) {
            return [];
        }
        $newData = [
            'totalTime' => 0, // Tổng s sử dụng
            'dates' => [],
        ];
        $rangeTime = 30 * 60;
        $results = [];
        $backSteps = []; //
        foreach ($bwHistories as $history) {
            if (!isset($backSteps[$history['serverID']])) {
                $backSteps[$history['serverID']] = 0;
            }
            $backStep = $backSteps[$history['serverID']];
            //
            if (!isset($results[$history['serverID']])) {
                $results[$history['serverID']] = $newData;
            }
            //
            $hDate = date('j-n-Y', $history['createdTime']);
            if (!isset($results[$history['serverID']]['dates'][$hDate])) {
                $backStep = 0;
                $results[$history['serverID']]['dates'][$hDate] = 0;
            }
            // Giữa ngày tắt server đi thì khoảng time của lần trước và lần sau có thể cách nhau rất nhiều nên phát cắt khoảng này (time lần sau > 2 lần 30' lần trước thì tính là nghỉ)
            if ($backStep && (($history['createdTime'] - $backStep) > (2 * $rangeTime + 30))) {
                $backStep = 0;
            }
            // với time bắt đầu mở trong ngày thì mất khoảng 30' bắt đầu từ lúc bật mới có log
            if (!$backStep) {
                $backStep = $history['createdTime'] - $rangeTime;
            }
            //
            $results[$history['serverID']]['dates'][$hDate] += $history['createdTime'] - $backStep;
            $results[$history['serverID']]['totalTime'] += $history['createdTime'] - $backStep;
            $backStep = $history['createdTime'];
            $backSteps[$history['serverID']] = $backStep;
        }
        return $results;
    }

    /**
     * get bandwidthHistories : Lấy log bandwidth của các server 30' log 1 lần
     *
     * @param array $options
     * @return mixed
     */
    function getBandWidthHistories($options = [])
    {
        $bwHisRepo = new ServerBWHistoryRepository(new ServerBWHistory());
        return $bwHisRepo->filterData($options);
    }

    /**
     * Get all server
     */
    function getAllServers($options = [])
    {
        $serverModel = new Server();
        //$serverModel = $serverModel->where('lock', 0);
        if (isset($options['provider']) && $options['provider']) {
            $serverModel = $serverModel->where('provider', $options['provider']);
        }
        $data = $serverModel->get();
        $isObject = isset($options['isObject']) ? $options['isObject'] : false;
        $results = [];
        if (!$isObject) {
            foreach ($data as $sv) {
                $sv = $sv->toArray();
                $results[$sv['_id']] = $sv;
            }
        } else {
            foreach ($data as $sv) {
                $results[$sv->_id] = $sv;
            }
        }
        return $results;
    }

}
