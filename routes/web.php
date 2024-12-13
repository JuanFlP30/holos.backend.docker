<?php

use Illuminate\Support\Facades\Route;
use Notsoweb\ApiResponse\Enums\ApiResponse;

Route::get('/', function () {
    return ApiResponse::OK->response([
        "message" => "It's fine :D"
    ]);
});

Route::get('/version', function () {
    return ApiResponse::OK->response([
        "version" => config('app.version')
    ]);
});

Route::get('/login', function () {
    return ApiResponse::OK->response([
        "message" => __('login.required')
    ]);
})->name('login');