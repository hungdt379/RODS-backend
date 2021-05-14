<?php

namespace App\Domain\Repositories;

use App\Common\Utility;
use App\Domain\Entities\ServerHardHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ServerHardHistoryRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = ServerHardHistory::class;

    /**
     * @var meeting
     */
    static $defaultLimit = 100;
    public $errorMessage = '';

    /**
     * @var ServerAccount
     */
    private $serverHardHistory;

    /**
     * Server constructor.
     *
     * @param ServerAccount $serverHardHistory
     */
    public function __construct(ServerHardHistory $serverHardHistory)
    {
        $this->serverHardHistory = $serverHardHistory;
    }

    public function createData(array $data)
    {
        return $this->serverHardHistory->create($data);
    }

    /**
     * update data
     *
     * @param ServerAccount $serverHardHistory
     * @param array $data
     * @return type
     */
    public function updateData(ServerHardHistory $serverHardHistory, array $data)
    {
        return $serverHardHistory->update($data);
    }

    /**
     * check record is exist
     *
     * @param type $data
     * @return boolean
     */
    function checkExist($data = [])
    {
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
    function getFirst($data = [])
    {
        $serverHardHistory = $this->serverHardHistory;
        if (isset($data['_id'])) {
            $serverHardHistory = $serverHardHistory->where('_id', $data['_id']);
        }
        if (isset($data['id'])) {
            $serverHardHistory = $serverHardHistory->where('_id', $data['id']);
        }
        return $serverHardHistory->first();
    }

    public function filterData($options = [])
    {
        $serverHardHistory = $this->serverHardHistory;
        $request = isset($options['request']) ? $options['request'] : (new Request());
        unset($options['request']);
        $params = $request->all();
        $options = array_merge($options, $params);
        if (isset($options['select'])) {
            $serverHardHistory = $serverHardHistory->select($options['select']);
        }
        if (isset($options['_id'])) {
            $serverHardHistory = $serverHardHistory->where('_id', '=', $options['_id']);
        }
        if (isset($options['serverID'])) {
            $serverHardHistory = $serverHardHistory->where('serverID', '=', $options['serverID']);
        }
        if (isset($options['time'])) {
            $svOperation = isset($options['timeOP']) ? $options['timeOP'] : '=';
            $serverHardHistory = $serverHardHistory->where('createdTime', $svOperation, $options['time']);
        }
        if (isset($options['fromTime'])) {
            $svOperation = '>';
            $serverHardHistory = $serverHardHistory->where('createdTime', $svOperation, $options['fromTime']);
        }
        if (isset($options['toTime'])) {
            $svOperation = '<';
            $serverHardHistory = $serverHardHistory->where('createdTime', $svOperation, $options['toTime']);
        }
        if (isset($options['sort']) && isset($options['sortDirection'])) {
            $serverHardHistory = $serverHardHistory->orderBy($options['sort'], $options['sortDirection']);
            $serverHardHistory = $serverHardHistory->options(['allowDiskUse' => true]);
        } else {
            $serverHardHistory = $serverHardHistory->orderBy('created_at', 'desc');
        }
        $limit = isset($options['limit']) ? (int)$options['limit'] : self::$defaultLimit;
        $serverHardHistory = $serverHardHistory->take($limit);
        //
        $page = isset($options['page']) ? (int)$options['page'] : 1;
        $offset = ($page - 1) * $limit;
        $serverHardHistory = $serverHardHistory->skip($offset);
        //
        $is_paginate = isset($options['is_paginate']) ? $options['is_paginate'] : false;
        if ($is_paginate) {
            return $serverHardHistory;
        }
        $data = $serverHardHistory->get();
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
    function deleteData($data = [])
    {
        $serverHardHistory = $this->getFirst($data);
        if ($serverHardHistory) {
            return $serverHardHistory->delete();
        }
        return false;
    }

}
