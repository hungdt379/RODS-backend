<?php

namespace App\Http\Controllers;

use App\Common\Utility;
use App\Domain\Entities\Group;
use App\Domain\Services\GroupService;
use App\Http\Request\Groups\StoreGroupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

class GroupController extends BaseController {
    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * GroupController constructor.
     *
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * Display a listing of the group.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request){
        $isPaginate = !$request->get('is_paginate') || Utility::boolean($request->get('is_paginate')) == false ? false : true;
        if ($isPaginate === false) {
            $groupList = $this->groupService->groupList();
            return response()->json($groupList, 200);
        }
        $groupList = $this->groupService->filterGroupList($request);
        $perPage = (int)$request->get('take') ?? config('constants.RECORDS_PER_PAGE');
        return response()->json($groupList->paginate($perPage), 200);
    }

    /**
     * Store a newly created group.
     *
     * @param StoreGroupRequest $request
     * @return JsonResponse
     */
    public function store(StoreGroupRequest $request){
        try {
            $attachServer = $request['servers'];
            $groupData = $this->groupService->createGroup($request->except('_token'));
            if(isset($attachServer) && count($attachServer)){
                $this->groupService->assignServersToGroup($attachServer, $groupData);
            }
            return $this->sendResponse($groupData, 'Tạo nhóm thành công');
        } catch (\Exception $exception) {
            return $this->sendError('Tạo nhóm không thành công', 401);
        }
    }

    /**
     * Store a newly created group.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request){
        $groupId= $request->route('id');
        $group = Group::find($groupId);
        if (!$group){
            return $this->sendError('Nhóm không tồn tại', 404);
        }
        try {
            $this->groupService->updateGroup($request, $group);
            return $this->sendResponse($group, 'Cập nhật nhóm thành công');
        } catch (\Exception $exception) {
            return $this->sendError('Cập nhật nhóm không thành công', 401);
        }
    }

    /**
     * Soft delete a group
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request){
        try {
            $groupId= $request->route('id');
            $group = Group::find($groupId);
            if (!$group){
                return $this->sendError('Nhóm không tồn tại', 404);
            }
            $this->groupService->deleteGroup($group);
            return response()->json(['code' => 200, 'message' => 'Xóa nhóm thành công']);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Xóa nhóm không thành công',
                'code' => 401
            ], 200);
        }
    }

    /**
     * Show group detail
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id){
        $group = Group::with('servers')->find($id);
        if (is_null($group)) {
            return $this->sendError('Nhóm không tồn tại', 404);
        }
        return $this->sendResponse($group->toArray(), '');
    }

    /**
     * Bulk active/deactive or delete groups
     *
     * @param array $groupIds
     * @param string $actionType
     * @return JsonResponse
     */
    public function bulkAction(Request $request){
        $ids = $request->get('groupIds');
        $actionType = $request->get('actionType');
        try {
            switch ($actionType){
                case config('constants.BULK_ACTION_TYPE.ACTIVE'):
                case config('constants.BULK_ACTION_TYPE.DEACTIVE'):
                    $this->groupService->bulkChangeStatus($ids, $actionType);
                    break;
                case config('constants.BULK_ACTION_TYPE.DELETE'):
                    $this->groupService->bulkDelete($ids);
                    break;
            }
            $message = $actionType == config('constants.BULK_ACTION_TYPE.DELETE') ? 'Xóa các nhóm thành công' : 'Cập nhật các nhóm thành công';
            return response()->json(['code' => 200, 'message' => $message]);
        } catch (\Exception $exception) {
            $message = $actionType == config('constants.BULK_ACTION_TYPE.DELETE') ? 'Xóa các nhóm không thành công' : 'Cập nhật các nhóm không thành công';
            return response()->json([
                'message' => $message,
                'code' => 401
            ], 200);
        }
    }
}
