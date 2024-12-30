<?php

use App\Http\Controllers\Developer\AuthController;
use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ServerController::class, 'status']);
Route::get('/version', [ServerController::class, 'version']);
Route::get('/login', [ServerController::class, 'loginRequired'])->name('login');

Route::prefix('developer')->middleware('web')->group(function () {
    Route::get('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
