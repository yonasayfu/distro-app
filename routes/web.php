<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified', 'permission:dashboard.view'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin/users', [UserManagementController::class, 'index'])
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::get('admin/users/create', [UserManagementController::class, 'create'])
        ->middleware('permission:users.create')
        ->name('users.create');

    Route::post('admin/users', [UserManagementController::class, 'store'])
        ->middleware('permission:users.create')
        ->name('users.store');

    Route::get('admin/users/{user}/edit', [UserManagementController::class, 'edit'])
        ->middleware('permission:users.update')
        ->name('users.edit');

    Route::put('admin/users/{user}', [UserManagementController::class, 'update'])
        ->middleware('permission:users.update')
        ->name('users.update');

    Route::put('admin/users/{user}/roles', [UserManagementController::class, 'updateRoles'])
        ->middleware('permission:users.update')
        ->name('users.roles.update');

    Route::delete('admin/users/{user}', [UserManagementController::class, 'destroy'])
        ->middleware('permission:users.delete')
        ->name('users.destroy');

    Route::get('admin/roles', [RoleManagementController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('roles.index');

    Route::get('admin/roles/create', [RoleManagementController::class, 'create'])
        ->middleware('permission:roles.create')
        ->name('roles.create');

    Route::post('admin/roles', [RoleManagementController::class, 'store'])
        ->middleware('permission:roles.create')
        ->name('roles.store');

    Route::get('admin/roles/{role}/edit', [RoleManagementController::class, 'edit'])
        ->middleware('permission:roles.update')
        ->name('roles.edit');

    Route::put('admin/roles/{role}', [RoleManagementController::class, 'update'])
        ->middleware('permission:roles.update')
        ->name('roles.update');

    Route::put('admin/roles/{role}/permissions', [RoleManagementController::class, 'updatePermissions'])
        ->middleware('permission:roles.update')
        ->name('roles.permissions.update');

    Route::delete('admin/roles/{role}', [RoleManagementController::class, 'destroy'])
        ->middleware('permission:roles.delete')
        ->name('roles.destroy');

    Route::get('notifications', [NotificationController::class, 'index'])
        ->middleware('permission:notifications.view')
        ->name('notifications.index');

    Route::post('notifications/{notification}/read', [NotificationController::class, 'read'])
        ->middleware('permission:notifications.view')
        ->name('notifications.read');

    Route::post('notifications/read-all', [NotificationController::class, 'readAll'])
        ->middleware('permission:notifications.view')
        ->name('notifications.read-all');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('permission:activity-logs.view')
        ->name('activity-logs.index');

    Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])
        ->middleware('permission:activity-logs.view')
        ->name('activity-logs.show');
});

require __DIR__.'/settings.php';
