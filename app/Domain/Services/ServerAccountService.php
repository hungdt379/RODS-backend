<?php

namespace App\Domain\Services;

use App\Domain\Entities\ServerAccount;
use App\Domain\Repositories\ServerAccountRepository;
use Illuminate\Http\Request;
use BigBlueButton\Parameters;
use Illuminate\Routing\UrlGenerator as url;

class ServerAccountService {

    private $serverAccountRepository;

    public function __construct(ServerAccountRepository $serverAccountRepository) {
        $this->serverAccountRepository = $serverAccountRepository;
    }

    /**
     * create data
     * 
     * @param array $info
     * @return type
     */
    public function createData(array $info) {
        $checkCreate = $this->serverAccountRepository->createData($info);
        return $checkCreate;
    }

    /**
     * $serverAccount
     * 
     * @param ServerAccount $serverAccount
     * @param array $info
     * @return type
     */
    public function updateData(ServerAccount $serverAccount, array $info) {
        $checkUpdate = $this->serverAccountRepository->updateData($serverAccount, $info);
        return $checkUpdate;
    }

    /**
     * filter list
     * 
     * @return type
     */
    public function filterData($options = []) {
        return $this->serverAccountRepository->filterData($options);
    }

    /**
     * Xoa
     *
     * @param type $data
     */
    function deleteData($data = []) {
        return $this->serverAccountRepository->deleteData($data);
    }
    
    /**
     * get detail
     */
    function getDetail($id){
        return $this->serverAccountRepository->getFirst(['id'=>$id]);
    }

}
