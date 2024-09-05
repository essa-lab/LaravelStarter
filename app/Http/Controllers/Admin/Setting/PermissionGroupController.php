<?php

namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PermissionStoreRequest;
use App\Service\Settings\PermissionService;


class PermissionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(private PermissionService $permissionService)
    {
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        CoreController::hasPermission(array('GROUP_EDIT'));
        $data = $this->permissionService->find($id);
        if ($data) {
            return $this->sendResponse($data, 'Fetch Permission Successfully');
        }
        return $this->sendError("Permission Not Found");
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserGroup
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionStoreRequest $request, $id)
    {
        CoreController::hasPermission(array('GROUP_EDIT'));
        $validator = $request->validated();
        DB::beginTransaction();
        $permission = $this->permissionService->update($validator, $id);
        if(!$permission){
            DB::rollBack();
            return response()->json(['data' => [], 'message' =>'permission error','status' => 404], 200);
        }
        DB::commit();

        return $this->sendResponse($permission,'permission updated Successfuly');
    }




}
