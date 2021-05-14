<?php

namespace App\Domain\Repositories;

use App\Common\Utility;
use App\Domain\Entities\ClassInformation;
use App\Domain\Entities\Group;
use App\Domain\Entities\ServerAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ServerAccountRepository extends BaseRepository {

    /**
     * Associated Repository Model.
     */
    const MODEL = ServerAccount::class;

    /**
     * @var meeting
     */
    static $defaultLimit = 50;
    public $errorMessage = '';

    /**
     * @var ServerAccount
     */
    private $serverAccount;

    /**
     * Server constructor.
     *
     * @param ServerAccount $serverAccount
     */
    public function __construct(ServerAccount $serverAccount) {
        $this->serverAccount = $serverAccount;
    }

    public function createData(array $data) {
        return $this->serverAccount->create($data);
    }

    /**
     * update data
     * 
     * @param ServerAccount $serverAccount
     * @param array $data
     * @return type
     */
    public function updateData(ServerAccount $serverAccount, array $data) {
        return $serverAccount->update($data);
    }

    /**
     * check record is exist
     * 
     * @param type $data
     * @return boolean
     */
    function checkExist($data = []) {
        $first = $this->getFirst($data);
        if ($first) {
            $this->addError();
            return true;
        }
        return false;
    }

    /**
     * Get first record
     */
    function getFirst($data = []) {
        $serverAccount = $this->serverAccount;
        if (isset($data['_id'])) {
            $serverAccount = $serverAccount->where('_id', $data['_id']);
        }
        if (isset($data['id'])) {
            $serverAccount = $serverAccount->where('_id', $data['id']);
        }
        return $serverAccount->first();
    }

    public function filterData($options = []) {
        $serverAccount = $this->serverAccount;
        $request = isset($options['request']) ? $options['request'] : (new Request());
        unset($options['request']);
        $params = $request->all();
        $options = array_merge($options, $params);
        if (isset($options['_id'])) {
            $serverAccount = $serverAccount->where('_id', '=', $options['_id']);
        }
        if (isset($options['name'])) {
            $searchString = $options['name'];
            $serverAccount = $serverAccount->where('name', 'like', '%' . $searchString . '%');
        }
        if (isset($options['sort']) && isset($options['sortDirection'])) {
            $serverAccount = $serverAccount->orderBy($options['sort'], $options['sortDirection']);
        } else {
            $serverAccount = $serverAccount->orderBy('created_at', 'desc');
        }
        $limit = isset($options['limit']) ? (int) $options['limit'] : self::$defaultLimit;
        $serverAccount = $serverAccount->take($limit);
        //
        $page = isset($options['page']) ? (int) $options['page'] : 1;
        $offset = ($page - 1) * $limit;
        $serverAccount = $serverAccount->skip($offset);
        //
        $is_paginate = isset($options['is_paginate']) ? $options['is_paginate'] : false;
        if ($is_paginate) {
            return $serverAccount;
        }
        $data = $serverAccount->get();
        $isObject = isset($options['isObject']) ? $options['isObject'] : false;
        $useID = isset($options['use_ID']) ? $options['use_ID'] : false;
        if (!$isObject) {
            $results = [];
            foreach ($data as $object) {
                $object = $object->toArray();
                if ($useID) {
                    $results[$object['_id']] = $object;
                } else {
                    $results[] = $object;
                }
            }
        } else {
            $results = $data;
        }
        return $results;
    }

    /**
     * Dừng, xóa meeting
     *
     * @param type $data
     */
    function deleteData($data = []) {
        $serverAccount = $this->getFirst($data);
        if ($serverAccount) {
            return $serverAccount->delete();
        }
        return false;
    }

}
