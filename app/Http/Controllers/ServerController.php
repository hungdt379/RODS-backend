<?php

namespace App\Http\Controllers;

use App\Common\Utility;
use App\Domain\Entities\Server;
use App\Domain\Services\ServerService;
use App\Http\Request\Server\StoreServerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

class ServerController extends BaseController {

    /**
     * @var ServerService
     */
    private $serverService;

    /**
     * ServerService constructor.
     *
     * @param ServerService $serverService
     */
    public function __construct(ServerService $serverService) {
        $this->serverService = $serverService;
    }

    /**
     * Display a listing of the group.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) {
        $isPaginate = !$request->get('is_paginate') || Utility::boolean($request->get('is_paginate')) == false ? false : true;
        if ($isPaginate) {
            $serverList = $this->serverService->filterServerList($request);
            $perPage = (int) $request->get('take') ? (int) $request->get('take') : config('constants.RECORDS_PER_PAGE');
            return response()->json($serverList->paginate($perPage), 200);
        } else {
            $serverList = $this->serverService->filterServerList($request);
            $response = [
                'status' => 200,
                'success' => true,
                'data' => $serverList,
            ];
            return $this->jsonResponse($response);
        }
    }

    /**
     * Store a newly created group.
     *
     * @param StoreServerRequest $request
     * @return JsonResponse
     */
    public function store(StoreServerRequest $request) {
        try {
            $attachGroup = $request['groups'];
            $serverData = $this->serverService->createServer($request->except('_token'));
            if (isset($attachGroup) && count($attachGroup)) {
                $this->serverService->assignGroupsToServer($attachGroup, $serverData);
            }
            return $this->sendResponse($serverData, 'Tạo server thành công');
        } catch (\Exception $exception) {
            return $this->sendError('Tạo server không thành công', 401);
        }
    }

    /**
     * Show group detail
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id) {
        $server = $this->serverService->getServerDetail($id, ['getOnline' => true, 'getHard' => true]);
        if (!$server) {
            return $this->sendError('Server không tồn tại', 404);
        }
        return $this->sendResponse($server, '');
    }

    /**
     * Soft delete a group
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request) {
        try {
            $serverId = $request->route('id');
            $server = Server::find($serverId);
            if (!$server) {
                return $this->sendError('Server không tồn tại', 404);
            }
            $this->serverService->deleteServer($server);
            return response()->json(['code' => 200, 'message' => 'Xóa server thành công']);
        } catch (\Exception $exception) {
            return response()->json([
                        'message' => 'Xóa server không thành công',
                        'code' => 401
                            ], 200);
        }
    }

    /**
     * Bulk active/deactive or delete servers
     *
     * @param array $serverIds
     * @param string $actionType
     * @return JsonResponse
     */
    public function bulkAction(Request $request) {
        $ids = $request->get('serverIds');
        $actionType = $request->get('actionType');
        try {
            switch ($actionType) {
                case config('constants.BULK_ACTION_TYPE.ACTIVE'):
                case config('constants.BULK_ACTION_TYPE.DEACTIVE'):
                    $this->serverService->bulkChangeStatus($ids, $actionType);
                    break;
                case config('constants.BULK_ACTION_TYPE.DELETE'):
                    $this->serverService->bulkDelete($ids);
                    break;
            }
            $message = $actionType == config('constants.BULK_ACTION_TYPE.DELETE') ? 'Xóa các server thành công' : 'Cập nhật các server thành công';
            return response()->json(['code' => 200, 'message' => $message]);
        } catch (\Exception $exception) {
            $message = $actionType == config('constants.BULK_ACTION_TYPE.DELETE') ? 'Xóa các server không thành công' : 'Cập nhật các server không thành công';
            return response()->json([
                        'message' => $message,
                        'code' => 401
                            ], 200);
        }
    }

    public function getProvider() {
        return $this->serverService->getServerProviders();
    }

    /**
     * Store a newly created group.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) {
        $groupId = $request->route('id');
        $server = Server::find($groupId);
        if (!$server) {
            return $this->sendError('Server không tồn tại', 404);
        }
        try {
            $this->serverService->updateServer($request, $server);
            return $this->sendResponse($server, 'Cập nhật server thành công');
        } catch (\Exception $exception) {
            return $this->sendError('Cập nhật server không thành công. Mess: '.$exception->getMessage(), 401);
        }
    }

}
