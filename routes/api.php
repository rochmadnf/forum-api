<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, ForumController, ForumCommentController, RegisterController};


Route::group(['middleware' => 'api'], function ($router) {
  Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'signUp']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
  });

  Route::apiResource('forums', ForumController::class);
  Route::apiResource('forums.comments', ForumCommentController::class)->except(['index', 'show']);
});
