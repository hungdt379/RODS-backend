<?php

namespace App\Domain\Repositories;

use App\Common\Utility;
use App\Domain\Entities\ServerLogs;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ServerLogsRepository extends BaseRepository {

    /**
     * Associated Repository Model.
     */
    const MODEL = ServerLogs::class;

    /**
     * @var meeting
     */
    static $defaultLimit = 100;
    public $errorMessage = '';

    /**
     * @var ServerAccount
     */
    private $serverLogs;

    /**
     * Server constructor.
     *
     * @param ServerAccount $serverLogs
     */
    public function __construct(ServerLogs $serverLogs) {
        $this->serverLogs = $serverLogs;
    }

    public function createData(array $data) {
        return $this->serverLogs->create($data);
    }

    /**
     * update data
     * 
     * @param ServerAccount $serverLogs
     * @param array $data
     * @return type
     */
    public function updateData(ServerLogs $serverLogs, array $data) {
        return $serverLogs->update($data);
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
        $serverLogs = $this->serverLogs;
        if (isset($data['_id'])) {
            $serverLogs = $serverLogs->where('_id', $data['_id']);
        }
        if (isset($data['id'])) {
            $serverLogs = $serverLogs->where('_id', $data['id']);
        }
        return $serverLogs->first();
    }

    public function filterData($options = []) {
        $serverLogs = $this->serverLogs;
        $request = isset($options['request']) ? $options['request'] : (new Request());
        unset($options['request']);
        $params = $request->all();
        $options = array_merge($options, $params);
        if (isset($options['_id'])) {
            $serverLogs = $serverLogs->where('_id', '=', $options['_id']);
        }
        if (isset($options['action'])) {
            $serverLogs = $serverLogs->where('action', '=', $options['action']);
        }
        if (isset($options['serverID'])) {
            $serverLogs = $serverLogs->where('serverID', '=', $options['serverID']);
        }
        if (isset($options['time'])) {
            $svOperation = isset($options['timeOP']) ? $options['timeOP'] : '=';
            $meetingUser = $meetingUser->where('createdTime', $svOperation, $options['time']);
        }
        if (isset($options['sort']) && isset($options['sortDirection'])) {
            $serverLogs = $serverLogs->orderBy($options['sort'], $options['sortDirection']);
        } else {
            $serverLogs = $serverLogs->orderBy('created_at', 'desc');
        }
        $limit = isset($options['limit']) ? (int) $options['limit'] : self::$defaultLimit;
        $serverLogs = $serverLogs->take($limit);
        //
        $page = isset($options['page']) ? (int) $options['page'] : 1;
        $offset = ($page - 1) * $limit;
        $serverLogs = $serverLogs->skip($offset);
        //
        $is_paginate = isset($options['is_paginate']) ? $options['is_paginate'] : false;
        if ($is_paginate) {
            return $serverLogs;
        }
        $data = $serverLogs->get();
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
        $serverLogs = $this->getFirst($data);
        if ($serverLogs) {
            return $serverLogs->delete();
        }
        return false;
    }

}
