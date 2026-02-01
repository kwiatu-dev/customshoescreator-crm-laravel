<?php

use App\Http\Controllers\Api\ProjectStatusController;
use App\Http\Controllers\Api\ProjectTypeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatUserConversationController;

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

Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::get('check_token', function (Request $request) {
        return $request->user();
    });

    Route::get('conversation', [ChatUserConversationController::class, 'getActiveConversation']);
    Route::delete('conversation', [ChatUserConversationController::class, 'deactivateConversation']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/project-statuses/find', [ProjectStatusController::class, 'find']);
    Route::get('/project-types/find', [ProjectTypeController::class, 'find']);
    Route::get('/users/find', [UserController::class, 'find']);
});
