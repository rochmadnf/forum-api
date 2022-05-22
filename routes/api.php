<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, ForumController, ForumCommentController, RegisterController, UserController};


Route::group(['middleware' => 'api'], function ($router) {
  Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'signUp']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
  });

  Route::prefix('user')->group(function () {
    Route::get('@{username}', [UserController::class, 'show']);
    Route::get('@{username}/activity', [UserController::class, 'getActivity']);
  });

  Route::get('/forums/tag/{tag}', [ForumController::class, 'filterTag']);
  Route::apiResource('forums', ForumController::class);
  Route::apiResource('forums.comments', ForumCommentController::class)->except(['index', 'show']);
});
