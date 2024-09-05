<?php

use App\Http\Controllers\Admin\Setting\PermissionController;
use App\Http\Controllers\Admin\Setting\PermissionGroupController;
use App\Http\Controllers\Admin\Setting\UserController;
use App\Http\Controllers\Admin\Setting\UserGroupController;
use App\Http\Controllers\Auth\admin\AuthAdminController;
use App\Http\Controllers\Auth\AuthJWTController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Admin')->prefix('v1')->middleware(['middleware' => 'api'])->group(function($router) {
    Route::post('/register', [AuthAdminController::class, 'register']);
    Route::post('/login', [AuthAdminController::class, 'login']);
    Route::post('/logout', [AuthAdminController::class, 'logout']);
    Route::post('/refresh', [AuthAdminController::class, 'refresh']);
    Route::post('/profile', [AuthAdminController::class, 'profile']);
});

Route::namespace('Admin\Setting')->prefix('v1/setting')->middleware(['jwt.verify'])->group( function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::get('users-count', [UserController::class, 'usersCount']);

    Route::get('groups', [UserGroupController::class, 'index']);
    Route::get('groups/{group_id}', [UserGroupController::class, 'show']);
    Route::put('groups/{id}', [UserGroupController::class, 'update']);
    Route::post('groups', [UserGroupController::class, 'store']);
    Route::delete('groups/{id}', [UserGroupController::class, 'destroy']);
    Route::get('groups-count', [UserGroupController::class, 'groupsCount']);

    Route::get('group/permissions', [PermissionGroupController::class, 'index']);
    Route::get('group/permissions/{id}', [PermissionGroupController::class, 'show']);
    Route::put('group/permissions/{id}', [PermissionGroupController::class, 'update']);
    Route::post('group/permissions', [PermissionGroupController::class, 'store']);
});

