<?php

use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
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

    Route::put('admin/users/{user}/roles', [UserManagementController::class, 'updateRoles'])
        ->middleware('permission:users.update')
        ->name('users.roles.update');

    Route::get('admin/roles', [RoleManagementController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('roles.index');

    Route::put('admin/roles/{role}', [RoleManagementController::class, 'update'])
        ->middleware('permission:roles.update')
        ->name('roles.update');
});

require __DIR__.'/settings.php';
