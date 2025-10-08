<?php

use App\Http\Controllers\Analytics\AnalyticsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SocialShare\SocialShareController;
use Illuminate\Support\Facades\Route;

/**
 * Public Routes - No authentication required
 */
Route::prefix('social-shares')->group(function () {
    Route::post('/', [SocialShareController::class, 'track'])
        ->middleware(['throttle:60,1']);
    Route::get('/platforms', [SocialShareController::class, 'platforms']);
});

/**
 * Authentication Routes
 */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware(['throttle:5,1']);
});

/**
 * Protected Routes - Require authentication
 */
Route::middleware(['auth:sanctum'])->group(function () {
    /**
     * Auth Routes
     */
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    /**
     * Analytics Routes - Require view_analytics permission
     */
    Route::prefix('analytics')->middleware(['permission:view_analytics'])->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard']);
        Route::get('/', [AnalyticsController::class, 'index']);
    });
});
