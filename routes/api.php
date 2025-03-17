<?php

use App\Http\Controllers\AuthController;

Route::middleware('auth:api')->get('user', [AuthController::class, 'user']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
