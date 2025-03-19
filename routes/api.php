<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('user', [AuthController::class, 'user']);
Route::put('roles/{roleId}/permissions', [RoleController::class, 'assignPermissions']);

Route::prefix('users')->group(function () {
    // Menampilkan semua user
    Route::get('/', [UserController::class, 'index']);

    // Menampilkan user berdasarkan id
    Route::get('{id}', [UserController::class, 'show']);

    // Menambahkan user baru
    Route::post('/', [UserController::class, 'store']);

    // Memperbarui user
    Route::put('{id}', [UserController::class, 'update']);

    // Menghapus user
    Route::delete('{id}', [UserController::class, 'destroy']);
});

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::put('{id}', [RoleController::class, 'update']);
    Route::delete('{id}', [RoleController::class, 'destroy']);
});

Route::prefix('permissions')->group(function () {
    Route::get('/', [PermissionController::class, 'index']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::put('{id}', [PermissionController::class, 'update']);
    Route::delete('{id}', [PermissionController::class, 'destroy']);
});


