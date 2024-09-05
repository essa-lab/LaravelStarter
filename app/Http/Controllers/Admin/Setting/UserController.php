<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Http\Requests\Settings\UserStoreRequest;
use App\Http\Requests\Settings\UserUpdateRequest;
use App\Service\Settings\UserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(private UserService $userService)
    {
    }

    public function index(Request $request)
    {
        CoreController::hasPermission('USER_LIST');

        return $this->userService->all($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        CoreController::hasPermission(array('USER_ADD'));
        $validate = $request->all();
        try {
            $user = $this->userService->save($validate);
            return $this->sendResponse($user, 'Create User Successfully');
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
        CoreController::hasPermission(array('USER_EDIT'));

        $data = $this->userService->find($id);
        if ($data) {
            return $this->sendResponse($data, 'Fetch User Successfully');
        }
        return $this->sendError("user Not Found");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        CoreController::hasPermission(array('USER_EDIT'));
        // $validate = $request->validated();
        $user = $this->userService->update($request->all(), $id);
        if ($user) {
            return $this->sendResponse($user, 'Update User Successfully');
        }
        return $this->sendError('User Not Found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CoreController::hasPermission(array('USER_DELETE'));
        $isDeleted = $this->userService->delete($id);
        if ($isDeleted) {
            return $this->sendResponse($isDeleted, 'Update User Successfully');
        }
        return $this->sendError('Kiosk Not Found');
    }
    public function usersCount(){
        return $this->sendResponse(
            $this->userService->getUsersCount(),'Count');
    }
}
