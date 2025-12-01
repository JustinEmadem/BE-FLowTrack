<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/roles', [RolesController::class, 'getRoles']);
Route::post('/create-roles', [RolesController::class, 'createRole']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'destroy']);