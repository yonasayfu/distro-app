<?php

use App\Http\Controllers\Api\V1\AdminUserController;
use App\Http\Controllers\Api\V1\AuthTokenController;
use App\Http\Controllers\Api\V1\CurrentUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('login', [AuthTokenController::class, 'store'])->middleware('guest');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('me', CurrentUserController::class);
            Route::post('logout', [AuthTokenController::class, 'destroy']);
        });
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('admin/users', AdminUserController::class)
            ->middleware('permission:users.view');
    });
});
