<?php

namespace App\Domain\Repositories;

use App\Common\Utility;
use App\Domain\Entities\ServerBWHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ServerBWHistoryRepository extends BaseRepository {

    /**
     * Associated Repository Model.
     */
    const MODEL = ServerBWHistory::class;

    /**
     * @var meeting
     */
    static $defaultLimit = 100;
    public $errorMessage = '';

    /**
     * @var ServerAccount
     */
    private $serverBWHistory;

    /**
     * Server constructor.
     *
     * @param ServerAccount $serverBWHistory
     */
    public function __construct(ServerBWHistory $serverBWHistory) {
        $this->serverBWHistory = $serverBWHistory;
    }

    public function createData(array $data) {
        return $this->serverBWHistory->create($data);
    }

    /**
     * update data
     *
     * @param ServerAccount $serverBWHistory
     * @param array $data
     * @return type
     */
    public function updateData(ServerBWHistory $serverBWHistory, array $data) {
        return $serverBWHistory->update($data);
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
        $serverBWHistory = $this->serverBWHistory;
        if (isset($data['_id'])) {
            $serverBWHistory = $serverBWHistory->where('_id', $data['_id']);
        }
        if (isset($data['id'])) {
            $serverBWHistory = $serverBWHistory->where('_id', $data['id']);
        }
        return $serverBWHistory->first();
    }

    public function filterData($options = []) {
        $serverBWHistory = $this->serverBWHistory;
        $request = isset($options['request']) ? $options['request'] : (new Request());
        unset($options['request']);
        $params = $request->all();
        $options = array_merge($options, $params);
        if (isset($options['select'])) {
            $serverBWHistory = $serverBWHistory->select($options['select']);
        }
        if (isset($options['_id'])) {
            $serverBWHistory = $serverBWHistory->where('_id', '=', $options['_id']);
        }
        if (isset($options['serverID'])) {
            $serverBWHistory = $serverBWHistory->where('serverID', '=', $options['serverID']);
        }
        if (isset($options['time'])) {
            $svOperation = isset($options['timeOP']) ? $options['timeOP'] : '=';
            $serverBWHistory = $serverBWHistory->where('createdTime', $svOperation, $options['time']);
        }
        if (isset($options['fromTime'])) {
            $svOperation = '>';
            $serverBWHistory = $serverBWHistory->where('createdTime', $svOperation, $options['fromTime']);
        }
        if (isset($options['toTime'])) {
            $svOperation = '<';
            $serverBWHistory = $serverBWHistory->where('createdTime', $svOperation, $options['toTime']);
        }
        if (isset($options['sort']) && isset($options['sortDirection'])) {
            $serverBWHistory = $serverBWHistory->orderBy($options['sort'], $options['sortDirection']);
        } else {
            $serverBWHistory = $serverBWHistory->orderBy('created_at', 'desc');
        }
        $limit = isset($options['limit']) ? (int) $options['limit'] : self::$defaultLimit;
        $serverBWHistory = $serverBWHistory->take($limit);
        //
        $page = isset($options['page']) ? (int) $options['page'] : 1;
        $offset = ($page - 1) * $limit;
        $serverBWHistory = $serverBWHistory->skip($offset);
        //
        $is_paginate = isset($options['is_paginate']) ? $options['is_paginate'] : false;
        if ($is_paginate) {
            return $serverBWHistory;
        }
        $data = $serverBWHistory->get();
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
        $serverBWHistory = $this->getFirst($data);
        if ($serverBWHistory) {
            return $serverBWHistory->delete();
        }
        return false;
    }

}
