<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Group;
use App\Domain\Entities\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Common\Utility;
use Illuminate\Support\Facades\DB;

class GroupRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Group::class;
    /**
     * @var Group
     */
    private $group;

    /**
     * Group constructor.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Update group info
     *
     * @param Group $group
     * @param array $dataUpdate
     *
     * @return bool
     */
    public function updateGropup(Group $group, array $dataUpdate)
    {
        return $group->update($dataUpdate);
    }

    /**
     * Update group info
     *
     * @param array $dataGroup
     * @return Group
     */
    public function createGroup(array $dataGroup)
    {
        unset($dataGroup['servers']);
        return $this->group->create($dataGroup);
    }

    /**
     * Assign servers to group
     *
     * @param $serverIds
     * @param Group $groupData
     * @return mixed
     */
    public function assignServersToGroup(array $serverIds, $groupData)
    {
        $serverData = Server::whereIn('_id', $serverIds)->get();
        if ($serverData && count($serverData)){
            $groupData->servers()->sync($serverData);
            return $groupData->save();
        }
        return false;
    }

    public function groupList(){
        return $this->group->with('servers')->where('status','=', true)->orderBy('created_at', 'desc')->get();
    }

    public function filterGroupList(Request $request){
        $groupList = $this->group->with('servers');
        if ($request->filled('keyword')){
            $searchString = strtolower($request->get('keyword'));
            $groupList =  $groupList->where('name' , 'like', '%' . $searchString . '%')
                ->orWhere('description', 'like', '%' . $searchString . '%')
                ->orWhereHas('servers', function($groupList) use ($searchString){
                    $groupList->where('dns_name', 'LIKE', '%' . $searchString . '%');
                    $groupList->orWhere('description', 'LIKE', '%' . $searchString . '%');
                })->with('servers');
        }
        if ($request->filled('status')){
            $status =  Utility::boolean($request->get('status'));
            $groupList= $groupList->status($status);
        }
        if ($request->filled('date_from') && $request->filled('date_to')){
            $startDate = Carbon::createFromFormat('d/m/Y H:i:s', $request->get('date_from'))->toDateTimeString();
            $endDate =  Carbon::createFromFormat('d/m/Y H:i:s', $request->get('date_to'))->toDateTimeString();
            $groupList = $groupList->createDate($startDate, $endDate);
        }
        if($request->filled('sort_group')){
            $groupList = $groupList->sort($request->get('sort_group'));
        }
        $groupList = $groupList->orderBy('created_at', 'desc');
        return $groupList;
    }

    /**
     * Get a Group by group Id
     *
     * @param $groupId
     * @return mixed
     */
    public function getSingle($groupId)
    {
        return $this->query()->where('_id', $groupId)->with('servers')->lockForUpdate()->first();
    }

    public function bulkChangeStatus(array $groupIds, string $status){
        $isActive = $status == config('constants.BULK_ACTION_TYPE.ACTIVE') ? true : false;
        return Group::whereIn('_id', $groupIds)->update(['status' => $isActive]);

    }

    public function bulkDelete(array $groupIds){
        return Group::whereIn('_id', $groupIds)->delete();
    }
}
