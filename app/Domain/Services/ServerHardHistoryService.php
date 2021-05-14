<?php

namespace App\Domain\Services;

use App\Domain\Repositories\ServerHardHistoryRepository;
use Illuminate\Http\Request;

class ServerHardHistoryService {

    /**
     * @var $serverHardHistoryRepository
     */
    private $serverHardHistoryRepository;

    public function __construct(ServerHardHistoryRepository $serverHardHistoryRepository) {
        $this->serverHardHistoryRepository = $serverHardHistoryRepository;
    }

    /**
     * create data
     * 
     * @param array $info
     * @return type
     */
    public function createData(array $info) {
        $checkCreate = $this->serverHardHistoryRepository->createData($info);
        return $checkCreate;
    }

    /**
     * update data
     * 
     * @param Meeting $meeting
     * @param array $info
     * @return type
     */
    public function updateData(Meeting $meeting, array $info) {
        $checkUpdate = $this->serverHardHistoryRepository->updateData($meeting, $info);
        return $checkUpdate;
    }

    /**
     * filter list
     * 
     * @param Request $request
     * @return type
     */
    public function filterData($options = []) {
        return $this->serverHardHistoryRepository->filterData($options);
    }

    /**
     * Dừng, xóa meeting
     *
     * @param type $data
     */
    function deleteData($data = []) {
        return $this->serverHardHistoryRepository->deleteData($data);
    }

}
