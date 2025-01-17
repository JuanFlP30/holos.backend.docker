<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\PermissionTypeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\MyUserController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\System\LoginController;
use App\Http\Controllers\System\NotificationController;
use App\Http\Controllers\System\SystemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ServerController::class, 'status'])->name('status');

Route::middleware('auth:api')->group(function () {
    // Aplicación
    Route::prefix('user')->name('user.')->group(function() {
        Route::get('/', [MyUserController::class, 'show'])->name('show');
        Route::put('/', [MyUserController::class, 'update'])->name('update');
        Route::delete('/', [MyUserController::class, 'destroy'])->name('destroy');
        Route::delete('photo', [MyUserController::class, 'destroyPhoto'])->name('photo');
        Route::put('password', [MyUserController::class, 'updatePassword'])->name('password');
        Route::post('password-confirm', [MyUserController::class, 'confirmPassword'])->name('password-confirm');
        Route::get('permissions', [MyUserController::class, 'permissions'])->name('permissions');
        Route::get('roles', [MyUserController::class, 'roles'])->name('roles');
    });

    Route::prefix('admin')->name('admin.')->group(function() {
        Route::apiResource('activities', ActivityController::class)->only(['index']);

        Route::prefix('users')->name('users.')->group(function() {
            Route::prefix('{user}')->group(function() {
                Route::get('roles', [UserController::class, 'roles'])->name('roles');
                Route::put('roles', [UserController::class, 'updateRoles']);
                Route::put('password', [UserController::class, 'updatePassword'])->name('password');
                Route::get('permissions', [UserController::class, 'permissions'])->name('permissions');
            });
        });
        Route::apiResource('users', UserController::class);
    
        // Roles
        Route::apiResource('roles', RoleController::class);
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
    });


    Route::prefix('permission-types')->name('permission-types.')->group(function() {    
        Route::get('all', [PermissionTypeController::class, 'all'])->name('all');
        Route::get('all-with-permissions', [PermissionTypeController::class, 'allWithPermissions'])->name('all-with-permissions');
    });

    // Sistema
    Route::prefix('system')->name('system.')->group(function() {
        Route::get('permissions', [SystemController::class, 'permissions'])->name('permissions');
        Route::get('roles', [SystemController::class, 'roles'])->name('roles');
        Route::prefix('notifications')->name('notifications.')->group(function() {
            Route::get('all', [NotificationController::class, 'index'])->name('all');
            Route::get('all-unread', [NotificationController::class, 'allUnread'])->name('all-unread');
            Route::post('read', [NotificationController::class, 'read'])->name('read');
            Route::post('close', [NotificationController::class, 'close'])->name('close');
        });
    });

    Route::post('auth/logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::get('changelogs', ChangelogController::class)->name('changelogs');
});

Route::prefix('resources')->name('resources.')->group(function() {
    Route::get('app', [ResourceController::class, 'app'])->name('app');
    Route::get('routes', [ResourceController::class, 'routes'])->name('routes');
});

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('reset-password', [LoginController::class, 'resetPassword'])->name('reset-password');
});
