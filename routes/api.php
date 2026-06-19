<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkerController;
use Illuminate\Support\Facades\Route;

// ── Public ──────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

Route::get('categories',      [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

Route::get('workers',              [WorkerController::class, 'index']);
Route::middleware('auth:api')->get('workers/me', [WorkerController::class, 'showMe']);
Route::get('workers/{id}',         [WorkerController::class, 'show']);
Route::get('workers/{id}/reviews', [WorkerController::class, 'reviews']);

Route::get('search', [SearchController::class, 'index']);

// ── Protected ────────────────────────────────────────────
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::prefix('users/me')->group(function () {
        Route::get('/',        [UserController::class, 'me']);
        Route::put('/',        [UserController::class, 'update']);
        Route::put('password', [UserController::class, 'updatePassword']);
        Route::post('avatar',  [UserController::class, 'uploadAvatar']);
        Route::delete('/',     [UserController::class, 'destroy']);
    });

    Route::post('workers',          [WorkerController::class, 'store']);
    Route::put('workers/me',        [WorkerController::class, 'update']);
    Route::put('workers/me/status', [WorkerController::class, 'updateStatus']);

    Route::post('orders',              [OrderController::class, 'store']);
    Route::get('orders',               [OrderController::class, 'index']);
    Route::get('orders/{id}',          [OrderController::class, 'show']);
    Route::put('orders/{id}/cancel',   [OrderController::class, 'cancel']);
    Route::put('orders/{id}/confirm',  [OrderController::class, 'confirm']);
    Route::put('orders/{id}/complete', [OrderController::class, 'complete']);

    Route::post('reviews',   [ReviewController::class, 'store']);
    Route::get('reviews/me', [ReviewController::class, 'me']);

    Route::get('favorites',                [FavoriteController::class, 'index']);
    Route::post('favorites/{worker_id}',   [FavoriteController::class, 'store']);
    Route::delete('favorites/{worker_id}', [FavoriteController::class, 'destroy']);

    Route::get('notifications',              [NotificationController::class, 'index']);
    Route::put('notifications/read-all',     [NotificationController::class, 'markAllRead']);
    Route::put('notifications/{id}/read',    [NotificationController::class, 'markRead']);
});
