<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::post('/register', [AuthController::class, 'store']);

    Route::apiResource('users', UserController::class);
    Route::get('users/count/total', [UserController::class, 'getUserCount']);
    
    Route::get('/roles', [RolesController::class, 'getRoles']);
    Route::post('/create-roles', [RolesController::class, 'createRole']);
});

