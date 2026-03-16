<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified', 'permission:dashboard.view'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('admin/users', 'admin/Users/Index')
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::inertia('admin/roles', 'admin/Roles/Index')
        ->middleware('permission:roles.view')
        ->name('roles.index');
});

require __DIR__.'/settings.php';
