<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;


Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/roles', [RoleController::class, 'index']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('user-profile', [UserController::class, 'userProfile']);
});
