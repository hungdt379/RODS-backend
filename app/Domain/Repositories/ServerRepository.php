<?php

namespace App\Domain\Repositories;

use App\Common\Utility;
use App\Domain\Entities\ClassInformation;
use App\Domain\Entities\Group;
use App\Domain\Entities\Server;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ServerRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Server::class;

    /**
     * @var Server
     */
    private $server;

    /**
     * Server constructor.
     *
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Update server info
     *
     * @param array $dataServer
     *
     * @return Server
     */
    public function createGroup(array $dataServer)
    {
        unset($dataServer['groups']);
        $dataServer['usedUsers'] = 0;
        $dataServer['meetings'] = [];
        $dataServer['platform'] = isset($dataServer['platform']) ? $dataServer['platform'] : \App\Domain\Services\ServerService::$defaultPlatform;
        $dataServer['lock'] = 0;
        if (!isset($dataServer['is_active'])) {
            $dataServer['is_active'] = true;
        }
        if (!isset($dataServer['groups']) || !$dataServer['groups']) {
            $dataServer['hasGroup'] = 0;
        } else {
            $dataServer['hasGroup'] = 1;
        }
        return $this->server->create($dataServer);
    }

    /**
     * Update server info
     *
     * @param Server $server
     * @param array $dataUpdate
     *
     * @return bool
     */
    public function updateServer(Server $server, array $dataUpdate)
    {
        unset($dataUpdate['meetings_info']);
        unset($dataUpdate['server_online_info']);
        if (isset($dataUpdate['lock'])) {
            $dataUpdate['lock'] = (int)$dataUpdate['lock'];
        }
        if (!isset($dataUpdate['groups']) || !$dataUpdate['groups']) {
            $dataUpdate['hasGroup'] = 0;
        } else {
            $dataUpdate['hasGroup'] = 1;
        }
        return $server->update($dataUpdate);
    }

    /**
     * Get an server by server Id
     *
     * @param $serverId
     * @return mixed
     */
    public function getSingle($serverId, $options = [])
    {
        $lastCrawlTurn = ClassInformation::latest('crawl_turn')->pluck('crawl_turn')->first();
        $data = $this->server->with('groups')->where('_id', $serverId)
            ->with(['details' => function ($query) {
                $query->orderBy('created_at', 'desc')->first();
            }])
            ->with(['classInformation' => function ($query) use ($lastCrawlTurn) {
                $query->where('crawl_turn', '=', $lastCrawlTurn)->get();
            }])
            ->lockForUpdate()->first();
        if ($data) {
            $meetings = isset($data->meetings) ? $data->meetings : [];
            $meetArr = [];
            foreach ($meetings as $meeting_id) {
                $meetRepo = new \App\Domain\Repositories\MeetingRepository(new \App\Domain\Entities\Meeting());
                $meetArr[] = $meetRepo->getFirst([
                    '_id' => $meeting_id
                ]);
            }
            $data['meetings_info'] = $meetArr;
            if (isset($options['getOnline']) && $options['getOnline'] == true) {
                if ($data->usedUsers > 0) {
                    $dt = $this->getServerOnlineInfo($data);
                    if ($dt) {
                        $data['server_online_info'] = $dt;
                    }
                }
            }
            if (isset($options['getHard']) && $options['getHard'] == true) {
                $hardInfo = $this->getServerHardInfo($data);
                $data['server_hard_info'] = $hardInfo;
            }
        }
        return $data;
    }

    public function serverList()
    {
        return $this->server->with('groups')->where('is_active', '=', true)->orderBy('created_at', 'desc')->get();
    }

    public function filterServerList(Request $request)
    {
        $isPaginate = !$request->get('is_paginate') || Utility::boolean($request->get('is_paginate')) == false ? false : true;
        $serverList = $this->server->with('groups');
        if ($request->filled('keyword')) {
            $searchString = strtolower($request->get('keyword'));
            $serverList = $serverList->where('ip_address', 'like', '%' . $searchString . '%')
                ->orwhere('provider', 'like', '%' . $searchString . '%')
                ->orWhereHas('groups', function ($q) use ($searchString) {
                    $q->where('name', 'like', '%' . $searchString . '%');
                });
        }
        if ($request->filled('status')) {
            $isActive = Utility::boolean($request->get('status'));
            $serverList = $serverList->status($isActive);
        }
        if ($request->filled('provider')) {
            $serverList = $serverList->provider($request->get('provider'));
        }
        if ($request->filled('date_from') && $request->filled('date_to') || $request->filled('from') && $request->filled('to')) {
            $startDate = $request->get('date_from') ? Carbon::createFromFormat('d/m/Y H:i:s', $request->get('date_from'))->toDateTimeString() : Carbon::createFromFormat('d/m/Y H:i:s', $request->get('from'))->toDateTimeString();
            $endDate = $request->get('date_to') ? Carbon::createFromFormat('d/m/Y H:i:s', $request->get('date_to'))->toDateTimeString() : Carbon::createFromFormat('d/m/Y H:i:s', $request->get('to'))->toDateTimeString();
            $serverList = $serverList->createDate($startDate, $endDate);
        }
        if ($request->filled('sort')) {
            $serverList = $serverList->sort($request->get('sort'));
        }
        if ($request->filled('used')) {
            $serverList = $serverList->where('usedUsers', '>', 0);
            $serverList = $serverList->orderBy('usedUsers', 'desc');
        } else {
            $serverList = $serverList->orderBy('created_at', 'desc');
        }
        //
        if ($request->filled('groupID')) {
            $serverList = $serverList->where('group_ids', $request->get('groupID'));
        }
        //
        if ($isPaginate) {
            return $serverList;
        }
        $data = $serverList->get();
        if (($request->filled('getOnline') || $request->get('getOnline') == true) && $request->filled('used')) {
            if ($data) {
                foreach ($data as $key => $sv) {
                    $dt = $this->getServerOnlineInfo($sv);
                    if ($dt) {
                        $data[$key]->server_online_info = $dt;
                    }
                }
            }
        }
        if ($request->filled('getHard')) {
            if ($data) {
                foreach ($data as $key => $sv) {
                    $hardInfo = $this->getServerHardInfo($sv);
                    $data[$key]->server_hard_info = $hardInfo;
                }
            }
        }
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
     * get server online info
     * @param type $sv
     * @return int
     */
    function getServerOnlineInfo($sv)
    {
        if (!$sv) {
            return false;
        }
        if (isset($sv['is_active']) && $sv['is_active'] === false) {
            return false;
        }
        $meeting = new \App\Domain\Entities\Meeting();
        $meetingRepository = new \App\Domain\Repositories\MeetingRepository($meeting);
        $meetingService = new \App\Domain\Services\MeetingService($meetingRepository);
        $dt = $meetingService->getMeetingsInSV([
            'serverDetail' => $sv,
        ]);
        if ($dt) {
            $dt = json_decode(json_encode($dt), true);
            $dt['totalUsers'] = 0;
            $dt['totalMeetings'] = 0;
            $meets = isset($dt['meetings']['meeting']['meetingName']) ? $dt['meetings'] : (isset($dt['meetings']['meeting']) ? $dt['meetings']['meeting'] : []);
            if ($meets) {
                $totalUsers = 0;
                $totalMeetings = 0;
                foreach ($meets as $meet) {
                    if (!isset($meet['participantCount'])) {
                        continue;
                    }
                    $meet['participantCount'] = (int)$meet['participantCount'];
                    $totalMeetings += 1;
                    $totalUsers += $meet['participantCount'];
                }
                $dt['totalUsers'] = $totalUsers;
                $dt['totalMeetings'] = $totalMeetings;
            }
        } else {
            $dt = [];
            $dt['totalUsers'] = 0;
            $dt['totalMeetings'] = 0;
        }
        return $dt;
    }

    /**
     * Lấy thông tin phần cứng của server
     *
     * @param type $sv
     */
    function getServerHardInfo($sv)
    {
        if (!$sv) {
            return false;
        }
        if (isset($sv['is_active']) && $sv['is_active'] === false) {
            return false;
        }
        $bbbUrl = $sv->bbbUrl;
        if (!$bbbUrl) {
            return false;
        }
        //
        $parseurl = parse_url($bbbUrl);
        $host = isset($parseurl['host']) ? trim($parseurl['host']) : '';
        $baseUrl = ($host) ? $parseurl['scheme'] . '://' . $parseurl['host'] : '';
        //
        $linkGet = $baseUrl . '/stat/statics.json';
        $hardInfo = $this->curlGetMaster($linkGet, true);
        if (isset($hardInfo['disks'])) {
            $disks = $hardInfo['disks'];
            foreach ($disks as $key => $disk) {
                $total = $disk['total'];
                $used = $disk['used'];
                $usedPercent = round($used / $total * 100, 2);
                $hardInfo['disks'][$key]['usedPercent'] = $usedPercent;
            }
        }
        if (isset($hardInfo['memory'])) {
            $memory = $hardInfo['memory'];
            $total = $memory['total'];
            $used = $memory['used'];
            $usedPercent = round($used / $total * 100, 2);
            $hardInfo['memory']['usedPercent'] = $usedPercent;
        }
        return $hardInfo;
    }

    /**
     *
     * @param type $url
     * @param type $decode
     * @return type
     */
    function curlGetMaster($url, $decode = false)
    {
        $parseurl = parse_url($url);
        $host = isset($parseurl['host']) ? trim($parseurl['host']) : '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Host:$host";
        $head[] = "Accept-Encoding:gzip, deflate, sdch";
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Content-Type:text/html; charset=utf-8";
        $head[] = "Accept-Language:en-US,en;q=0.8";
        $head[] = "Accept:*/*";
        $head[] = "X-Requested-With: XMLHttpRequest";
        $user_agent_default = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent_default);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $_re = curl_exec($ch);
        $_re = trim(preg_replace('/[\t|\s]/', '', $_re));
        $info = [];
        //
        if ($_re) {
            $info = ($decode) ? json_decode($_re, true) : $_re;
        }
        return $info;
    }

    /**
     * Assign servers to group
     *
     * @param array $groupIds
     * @param $serverData
     * @return mixed
     */
    public function assignGroupsToServer(array $groupIds, Server $serverData)
    {
        $groupsData = Group::whereIn('_id', $groupIds)->get();
        if ($groupsData && count($groupsData)) {
            $serverData->groups()->sync($groupsData);
            return $serverData->save();
        }
        return false;
    }

    /**
     * Delete exist server
     *
     * @param Server $server
     * @return bool
     * @throws Exception
     */
    public function deleteServer(Server $server)
    {
        $server->lock = 1;
        $server->save();
        return true;
        //return $server->delete();
    }

    /**
     *
     * @param array $serverIds
     * @param string $status
     * @return bool
     */
    public function bulkChangeStatus(array $serverIds, string $status)
    {
        $isActive = $status == config('constants.BULK_ACTION_TYPE.ACTIVE') ? true : false;
        return Server::whereIn('_id', $serverIds)->update(['is_active' => $isActive]);
    }

    /**
     *
     * @param array $serverIds
     * @return bool
     */
    public function bulkDelete(array $serverIds)
    {
        return Server::whereIn('_id', $serverIds)->delete();
    }

    public function getServerProviders()
    {
        return Server::all()->unique('provider')->pluck('provider');
    }

}
