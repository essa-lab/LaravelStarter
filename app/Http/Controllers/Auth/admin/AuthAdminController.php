<?php

namespace App\Http\Controllers\Auth\admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Permission\GroupPermission;
use App\Models\Permission\Permission;
use App\Service\Settings\UserService;
use Exception;

class AuthAdminController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        try{
            $user = $this->userService->register($validated);

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
                'status' => '201'
            ], 201);
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * login user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = $this->userService->login($validated);
            if($user['data'] == null) {
                return response()->json([
                    'status' => '403',
                    'data' => array(),
                    'message' => 'invalid email',
                ]);
            }
            if (!$user['token']) {
                return response()->json([
                    'status' => '401',
                    'data' => [],
                    'message' => 'Unauthorized',
                ]);
            }
            return response()->json([
                'status' => 200,
                'data' => $user,
                'message'=> 'success'
            ]);

        }catch (\Exception $ex){
            return response()->json([
                'status' => 500,
                'data' => $ex->getMessage(),
                'message'=> 'Error Server !'
            ]);
        }

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser()
    {
        return response()->json(auth()->user());
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => '200',
            'data' => [],
            'message' => 'User successfully logged out.',
        ]);

    }

    /**
     * Refresh token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => '200',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
            'message' => 'successfully',
        ]);

    }


    public static function getPermission($user){
        $result = array();
        if($user->id == 1){
            $permissions = Permission::get();
        }else{
            $permissions = GroupPermission::where('group_id', $user->group_id)->get();
        }
//        $permissions = Permission::get();
        foreach ($permissions as $permission){
            array_push($result, $permission->role);
        }
        return $result;
    }
}
