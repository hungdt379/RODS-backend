<?php

namespace App\Domain\Services;

use App\Domain\Repositories\ServerBWHistoryRepository;
use Illuminate\Http\Request;

class ServerBWHistoryService {

    /**
     * @var $serverBWHistoryRepository
     */
    private $serverBWHistoryRepository;

    public function __construct(ServerBWHistoryRepository $serverBWHistoryRepository) {
        $this->serverBWHistoryRepository = $serverBWHistoryRepository;
    }

    /**
     * create data
     * 
     * @param array $info
     * @return type
     */
    public function createData(array $info) {
        $checkCreate = $this->serverBWHistoryRepository->createData($info);
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
        $checkUpdate = $this->serverBWHistoryRepository->updateData($meeting, $info);
        return $checkUpdate;
    }

    /**
     * filter list
     * 
     * @param Request $request
     * @return type
     */
    public function filterData($options = []) {
        return $this->serverBWHistoryRepository->filterData($options);
    }

    /**
     * Dừng, xóa meeting
     *
     * @param type $data
     */
    function deleteData($data = []) {
        return $this->serverBWHistoryRepository->deleteData($data);
    }

}
