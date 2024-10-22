<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionsController;


Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/roles', [RoleController::class, 'index']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('user-profile', [UserController::class, 'userProfile']);
});

Route::post('/questions/import', [QuestionsController::class, 'import']);
Route::post('/questions/getExcelHeadings', [QuestionsController::class, 'getExcelHeadings']);
