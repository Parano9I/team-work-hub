<?php

use Illuminate\Support\Facades\Route;
use TeamWorkHub\Modules\Auth\Http\Controllers\Api\AuthController;
use TeamWorkHub\Modules\Auth\Http\Controllers\Api\RoleController;

Route::prefix('/api/v1/auth')->group(function () {
    Route::get('health-check', function () {
        return 'Ok';
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')->name('api.v1.auth.login');
        Route::get('/logout', 'logout')->middleware(['auth:sanctum'])->name('api.v1.auth.logout');
    });
});
