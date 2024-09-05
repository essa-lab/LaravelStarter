<?php

namespace App\Http\Controllers\Auth\mobile;


use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\ResetPasswordRequest;
use App\Http\Requests\Customer\Auth\ConfirmCodeRequest;
use App\Http\Requests\Customer\Auth\ConfirmTokenRequest;
use App\Http\Requests\Customer\Auth\LoginRequest;
use App\Http\Requests\Customer\Auth\RegisterRequest;
use App\Http\Requests\Customer\Auth\SubmitResetPasswordRequest;
use App\Service\Customer\Auth\CustomerService;
use App\Service\Customer\Auth\ResetPasswordService;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthMobileController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private CustomerService $customerService)
    {}

    /**
     * Register user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        try{
            $customer = $this->customerService->register($validated);
            if($customer){
                return $this->sendResponse($customer,'Customer successfully registered',201);
            }
            return $this->sendError("Faild Create Customer");
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
    }
    public function confirmByToken(ConfirmTokenRequest $request){
        $validated = $request->validated();
        try{
        $customer = $this->customerService->confirmToken($validated->token);
        if($customer){
            return response()->json([
                'message' => 'Email successfully verified',
                'status' => '200'
                ], 200);
        } 
    }catch(Exception $e){
        return $this->sendError($e->getMessage());
    }

    }
    public function confirmByCode(ConfirmCodeRequest $request){
        $validated = $request->validated();
        try{
            $customer = $this->customerService->confirmCode($validated);
            if($customer){
                return response()->json([
                    'message' => 'Email successfully verified',
                    'status' => '200'
                    ], 200);
            } 
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
            $customer = $this->customerService->login($validated);
            if($customer['data'] == null) {
                return response()->json([
                    'status' => '403',
                    'data' => array(),
                    'message' => 'invalid email',
                ]);
            }
            if (!$customer['token']) {
                return response()->json([
                    'status' => '401',
                    'data' => [],
                    'message' => 'Unauthorized',
                ]);
            }
            return response()->json([
                'status' => 200,
                'data' => $customer,
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

    public function requestResetPassword(ResetPasswordRequest $request,ResetPasswordService $service){
        $validated = $request->validated();
        try{
            $resetCode = $service->requestResetPassword($validated['registeration_option']);
            if($resetCode){
                return $this->sendResponse($resetCode,'Reset Code Sent Successfuly');
            }
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    public function resetPassword(SubmitResetPasswordRequest $request,ResetPasswordService $service){
        $validated = $request->validated();
        try{
            $reseted = $service->resetPassword($validated);
            if($reseted){
                return $this->sendResponse($reseted,'Password Reseted Successfuly');
            } 
            return $this->sendError('Unable to reset password');
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
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


}
