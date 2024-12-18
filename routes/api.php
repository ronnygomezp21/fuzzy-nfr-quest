<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameRoomController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\PasswordResetOTPController;
use App\Http\Controllers\PDFReportController;
use App\Http\Controllers\QuizController;
use App\Models\GameRoom;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/roles', [RoleController::class, 'index']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::post('/questions/import', [QuestionsController::class, 'import']);
    //Route::post('/questions/getExcelHeadings', [QuestionsController::class, 'getExcelHeadings']);
    Route::post('/questions/questionsByCode', [QuestionsController::class, 'questionsByCode']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/quiz', [QuizController::class, 'store']);
    //PASAR AL CONTROLADOR DE GAME ROOM 
    Route::post('/create-room-game-questions', [QuestionsController::class, 'createRoomGameQuestions']);
    Route::get('/game-history', [QuizController::class, 'getGameHistory']);
    Route::get('/game-rooms', [GameRoomController::class, 'getGameRooms']);
    Route::post('/delete-game-room', [GameRoomController::class, 'deleteGameRoom']);
    Route::post('generate-report-teacher-game-room', [PDFReportController::class, 'generateReportTeacherGameRoom']);
});

Route::post('password/send-otp', [PasswordResetOTPController::class, 'sendOTP']);
Route::post('password/reset-otp', [PasswordResetOTPController::class, 'resetPassword']);