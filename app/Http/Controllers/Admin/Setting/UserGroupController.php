<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Http\Requests\Settings\GroupStoreRequest;
use App\Service\Settings\GroupService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(private GroupService $groupService)
    {
    }

    public function index(Request $request)
    {
        CoreController::hasPermission('GROUP_LIST');
        Log::info($request->all());
        $groups = $this->groupService->all($request->all());
        if($groups instanceof LengthAwarePaginator){
            return $groups;
        }
        return $this->sendResponse($groups,'fethed successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupStoreRequest $request)
    {
        CoreController::hasPermission(array('GROUP_ADD'));
        $validate = $request->validated();
        $validate['createdBy']=Auth::user()->id;
        try {
            $user = $this->groupService->save($validate);
            return $this->sendResponse($user, 'Create Group Successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        CoreController::hasPermission(array('GROUP_EDIT'));

        $data = $this->groupService->find($id);
        if ($data) {
            return $this->sendResponse($data, 'Fetch Group Successfully');
        }
        return $this->sendError("group Not Found");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(GroupStoreRequest $request, $id)
    {
        CoreController::hasPermission(array('GROUP_EDIT'));
        // $validate = $request->validated();
        $group = $this->groupService->update($request->all(), $id);
        if ($group) {
            return $this->sendResponse($group, 'Update Group Successfully');
        }
        return $this->sendError('Group Not Found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CoreController::hasPermission(array('GROUP_DELETE'));
        $isDeleted = $this->groupService->delete($id);
        if ($isDeleted) {
            return $this->sendResponse($isDeleted, 'Update Group Successfully');
        }
        return $this->sendError('Group Not Found');
    }
    public function groupsCount(){
        return $this->sendResponse(
            $this->groupService->getGroupsCount(),'Count');
    }
}
