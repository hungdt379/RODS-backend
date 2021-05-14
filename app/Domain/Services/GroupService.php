<?php

namespace App\Domain\Services;

use App\Domain\Entities\Group;
use App\Domain\Repositories\GroupRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GroupService {
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    public function __construct(
        GroupRepository $groupRepository
    ){
        $this->groupRepository = $groupRepository;
    }

    /**
     * Add new group
     *
     * @param array $groupInfo
     *
     * @return Group
     */
    public function createGroup(array $groupInfo){
        $groupInfo['status'] = true;
        return $this->groupRepository->createGroup($groupInfo);
    }

    /**
     * Update exist group
     *
     * @param Request $request
     * @param Group $group
     * @return Group
     */
    public function updateGroup(Request $request, Group $group){
        $input = $request->all();
        $group->update($input);
        $attachServer = $request['servers'];
        if($attachServer && count($attachServer)){
            $this->assignServersToGroup($attachServer, $group);
        }
        return $group;
    }

    /**
     * Active/Deactive group
     *
     * @param Request $request
     * @param Group $group
     * @return Group
     */
    public function changeStatusGroup(Request $request, Group $group){
        $isActive = $request['status'];
        $group->update(['status' => $isActive]);
        return $group;
    }

    /**
     * Delete exist group
     *
     * @param Group $group
     * @return bool
     * @throws \Exception
     */
    public function deleteGroup(Group $group){
        return $group->delete();
    }

    /**
     * Add Servers to Group
     *
     * @param array $attachServers
     * @param Group $groupData
     * @return integer
     */
    public function assignServersToGroup(array $attachServers, Group $groupData){
        return $this->groupRepository->assignServersToGroup($attachServers, $groupData);
    }

    /**
     * group list with search sort filter
     *
     * @param Request $request
     * @return Group
     */
    public function filterGroupList(Request $request){
        return $this->groupRepository->filterGroupList($request);
    }

    /**
     * group list without search sort filter
     *
     * @return Builder
     */
    public function groupList(){
        return $this->groupRepository->groupList();
    }

    /**
     * change status Active/Deactive of multiple groups
     *
     * @param array $groupIds
     * @param string $status
     * @return bool
     */
    public function bulkChangeStatus(array $groupIds, string $status){
        return $this->groupRepository->bulkChangeStatus($groupIds, $status);
    }

    /**
     * delete multiple groups
     *
     * @param array $groupIds
     * @return bool
     */
    public function bulkDelete(array $groupIds){
        return $this->groupRepository->bulkDelete($groupIds);
    }
}
