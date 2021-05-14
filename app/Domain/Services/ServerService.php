<?php

namespace App\Domain\Services;

use App\Domain\Entities\Server;
use App\Domain\Repositories\ServerRepository;
use Illuminate\Http\Request;
use App\Domain\Services\ServerAPI\ServerAPI;
use Illuminate\Routing\UrlGenerator as url;
use Illuminate\Support\Facades\Auth;
use App\Domain\Services\VM\PlatformService;

class ServerService
{

    /**
     * @var ServerRepository
     */
    private $serverRepository;
    static $defaultPlatform = PlatformService::bbb;

    public function __construct(ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    public function createServer(array $serverInfo)
    {
        $serverInfo['is_active'] = true;
        return $this->serverRepository->createGroup($serverInfo);
    }

    public function serverList()
    {
        return $this->serverRepository->serverList();
    }

    public function filterServerList(Request $request)
    {
        return $this->serverRepository->filterServerList($request);
    }

    public function assignGroupsToServer(array $attachGroups, Server $serverData)
    {
        return $this->serverRepository->assignGroupsToServer($attachGroups, $serverData);
    }

    public function deleteServer(Server $server)
    {
        try {
            return $this->serverRepository->deleteServer($server);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * change status Active/Deactive of multiple servers
     *
     * @param array $serverIds
     * @param string $status
     * @return bool
     */
    public function bulkChangeStatus(array $serverIds, string $status)
    {
        return $this->serverRepository->bulkChangeStatus($serverIds, $status);
    }

    /**
     * delete multiple servers
     *
     * @param array $serverIds
     * @return bool
     */
    public function bulkDelete(array $serverIds)
    {
        return $this->serverRepository->bulkDelete($serverIds);
    }

    public function getServerProviders()
    {
        return $this->serverRepository->getServerProviders();
    }

    /**
     * Update exist server
     *
     * @param Request $request
     * @param Server $server
     * @return Server
     */
    public function updateServer(Request $request, Server $server)
    {
        $input = $request->all();
        if (isset($input['is_active'])) {
            $input['is_active'] = filter_var($input['is_active'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($input['is_active']) && $input['is_active'] !== $server->is_active) { // $server->is_active la kieu boolean, $input['is_active'] la kieu string 'true', 'false'
            if ($input['is_active']) {
                if (!$this->turnOnServer($server)) {
                    unset($input['is_active']);
                }
            } else {
                if (!$this->turnOffServer($server)) {
                    unset($input['is_active']);
                }
            }
        }
        $attachGroups = $request['groups'];
        if ($attachGroups && count($attachGroups)) {
            $this->assignGroupsToServer($attachGroups, $server);
        }
        return $this->serverRepository->updateServer($server, $input);
    }

    public function getServerDetail($id, $options = [])
    {
        $server = $this->serverRepository->getSingle($id, $options);
        if (!$server) {
            return false;
        }
        $activeClass = $server->activeClass() ? 1 : 0;
        $activeParticipant = $server->activeParticipant() ? 1 : 0;
        $totalClass = $server->totalClass() ? 1 : 0;
        $percentActiveClass = $this->getPercentActiveClass($totalClass, $activeClass);
        $classInformation = [
            'total_class' => $totalClass,
            'active_class' => $activeClass,
            'active_participant' => $activeParticipant,
            'percent_active_class' => $percentActiveClass
        ];
        $serverData = $server->toArray();
        $serverData = array_merge($serverData, $classInformation);
        return $serverData;
    }

    public function getPercentActiveClass($totalClass, $activeClass)
    {
        if ($totalClass == 0 || $activeClass == 0) {
            return 0;
        }
        $percent = ($activeClass / $totalClass) * 100;
        return round($percent, 2);
    }

    /**
     * Bật server
     *
     * @param type $server
     */
    function turnOnServer($server = null)
    {
        if (!$server) {
            return false;
        }
        //
        if ($server->is_active) {
            return true;
        }
        //
        $accountID = isset($server->account) ? $server->account : null;
        // Nếu server chưa dc gán với tài khoản nào thì bỏ qua
        if (!$accountID) {
            return true;
        }
        //
        $serverAccountRe = new \App\Domain\Repositories\ServerAccountRepository(new \App\Domain\Entities\ServerAccount());
        $serverAccountDetail = $serverAccountRe->getFirst([
            'id' => $accountID,
        ]);
        if (!$serverAccountDetail) {
            return false;
        }
        $externalID = isset($server->externalID) ? $server->externalID : '';
        if (!$externalID) {
            return true;
        }
        //
        $serverAPI = new ServerAPI([
            'account' => $serverAccountDetail,
        ]);
        $isTurn = $serverAPI->turnOn([
            'id' => $externalID,
        ]);
        $response = $serverAPI->getResponse();
        if ($isTurn) {
            $serverLogsRe = new \App\Domain\Repositories\ServerLogsRepository(new \App\Domain\Entities\ServerLogs());
            $serverLogsRe->create([
                'serverID' => $server->_id,
                'action' => 1, // Bật
                'serverInfo' => $server->toArray(),
                'response' => $response,
                'request' => url()->current(),
                'createdTime' => time(),
                'adminID' => Auth::id(),
            ]);
            $server->is_active = true;
            $server->update();
        }
        return $isTurn;
    }

    /**
     * Tắt server
     *
     * @param type $server
     */
    function turnOffServer($server = null)
    {
        if (!$server) {
            return false;
        }
        //
        if (!$server->is_active) {
            return true;
        }
        //
        $accountID = isset($server->account) ? $server->account : null;
        // Nếu server chưa dc gán với tài khoản nào thì bỏ qua
        if (!$accountID) {
            return true;
        }
        //
        $serverAccountRe = new \App\Domain\Repositories\ServerAccountRepository(new \App\Domain\Entities\ServerAccount());
        $serverAccountDetail = $serverAccountRe->getFirst([
            'id' => $accountID,
        ]);
        if (!$serverAccountDetail) {
            return false;
        }
        $externalID = isset($server->externalID) ? $server->externalID : '';
        if (!$externalID) {
            return true;
        }
        //
        $serverAPI = new ServerAPI([
            'account' => $serverAccountDetail,
        ]);
        $count = 1;
        $isTurn = false;
        while ($count < 4) {
            $isTurn = $serverAPI->turnOff([
                'id' => $externalID,
            ]);
            $response = $serverAPI->getResponse();
            if ($isTurn) {
                $serverLogsRe = new \App\Domain\Repositories\ServerLogsRepository(new \App\Domain\Entities\ServerLogs());
                $serverLogsRe->create([
                    'serverID' => $server->_id,
                    'action' => 0, // tat
                    'serverInfo' => $server->toArray(),
                    'response' => $response,
                    'request' => url()->current(),
                    'createdTime' => time(),
                    'adminID' => Auth::id(),
                ]);
                $server->is_active = false;
                $server->update();
                break;
            } else {
                sleep(5);
                $count++;
            }
        }
        return $isTurn;
    }

    /**
     *
     * @param type $server
     */
    function focusTurnOnServer($server = null)
    {
        if (!$server) {
            return false;
        }
        //
        $accountID = isset($server->account) ? $server->account : null;
        // Nếu server chưa dc gán với tài khoản nào thì bỏ qua
        if (!$accountID) {
            return true;
        }
        //
        $serverAccountRe = new \App\Domain\Repositories\ServerAccountRepository(new \App\Domain\Entities\ServerAccount());
        $serverAccountDetail = $serverAccountRe->getFirst([
            'id' => $accountID,
        ]);
        if (!$serverAccountDetail) {
            return false;
        }
        $externalID = isset($server->externalID) ? $server->externalID : '';
        if (!$externalID) {
            return true;
        }
        //
        $serverAPI = new ServerAPI([
            'account' => $serverAccountDetail,
        ]);
        $isTurn = $serverAPI->turnOn([
            'id' => $externalID,
        ]);
        $response = $serverAPI->getResponse();
        //
        if ($isTurn) {
            if (!$server->is_active) {
                $serverLogsRe = new \App\Domain\Repositories\ServerLogsRepository(new \App\Domain\Entities\ServerLogs());
                $serverLogsRe->create([
                    'serverID' => $server->_id,
                    'action' => 1, // Bật
                    'serverInfo' => $server->toArray(),
                    'response' => $response,
                    'request' => url()->current(),
                    'createdTime' => time(),
                    'adminID' => Auth::id(),
                    'focus' => 1,
                ]);
                $server->is_active = true;
                $server->update();
            }
        }
        return $isTurn;
    }

    /**
     * check server is running
     *
     * @param type $server
     */
    function checkServerRunning($server = null)
    {
        if (!$server) {
            return false;
        }
        //
        $accountID = isset($server->account) ? $server->account : null;
        // Nếu server chưa dc gán với tài khoản nào thì bỏ qua
        if (!$accountID) {
            return true;
        }
        //
        $serverAccountRe = new \App\Domain\Repositories\ServerAccountRepository(new \App\Domain\Entities\ServerAccount());
        $serverAccountDetail = $serverAccountRe->getFirst([
            'id' => $accountID,
        ]);
        if (!$serverAccountDetail) {
            return false;
        }
        $externalID = isset($server->externalID) ? $server->externalID : '';
        if (!$externalID) {
            return true;
        }
        //
        $serverAPI = new ServerAPI([
            'account' => $serverAccountDetail,
        ]);
        $isTurn = $serverAPI->isRunning($externalID);
        return $isTurn;
    }

}
