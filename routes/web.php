<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\MediaManagementController;
use App\Http\Controllers\Admin\NoteManagementController;
use App\Http\Controllers\Admin\PageManagementController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\SettingsManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ExportCenterController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\HandbookController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('handbook', HandbookController::class)->name('handbook.index');

Route::middleware(['auth', 'verified', 'permission:dashboard.view'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('search', GlobalSearchController::class)
        ->middleware('permission:search.view')
        ->name('search.index');

    Route::get('exports', [ExportCenterController::class, 'index'])
        ->middleware('permission:exports.view')
        ->name('exports.index');

    Route::get('exports/users.csv', [ExportCenterController::class, 'usersCsv'])
        ->middleware(['permission:exports.view', 'permission:users.view'])
        ->name('exports.users.csv');

    Route::get('exports/summary/print', [ExportCenterController::class, 'printSummary'])
        ->middleware('permission:exports.view')
        ->name('exports.summary.print');

    Route::get('admin/users', [UserManagementController::class, 'index'])
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::get('admin/pages', [PageManagementController::class, 'index'])
        ->middleware('permission:pages.view')
        ->name('pages.index');

    Route::get('admin/settings', [SettingsManagementController::class, 'edit'])
        ->middleware('permission:settings.view')
        ->name('admin-settings.edit');

    Route::put('admin/settings', [SettingsManagementController::class, 'update'])
        ->middleware('permission:settings.update')
        ->name('admin-settings.update');

    Route::get('admin/media', [MediaManagementController::class, 'index'])
        ->middleware('permission:media.view')
        ->name('media.index');

    Route::post('admin/media', [MediaManagementController::class, 'store'])
        ->middleware('permission:media.create')
        ->name('media.store');

    Route::get('admin/media/{media}/download', [MediaManagementController::class, 'download'])
        ->middleware('permission:media.view')
        ->name('media.download');

    Route::delete('admin/media/{media}', [MediaManagementController::class, 'destroy'])
        ->middleware('permission:media.delete')
        ->name('media.destroy');

    Route::post('admin/notes', [NoteManagementController::class, 'store'])
        ->middleware('permission:notes.create')
        ->name('notes.store');

    Route::delete('admin/notes/{note}', [NoteManagementController::class, 'destroy'])
        ->middleware('permission:notes.delete')
        ->name('notes.destroy');

    Route::get('admin/pages/create', [PageManagementController::class, 'create'])
        ->middleware('permission:pages.create')
        ->name('pages.create');

    Route::post('admin/pages', [PageManagementController::class, 'store'])
        ->middleware('permission:pages.create')
        ->name('pages.store');

    Route::get('admin/pages/{page}/edit', [PageManagementController::class, 'edit'])
        ->middleware('permission:pages.update')
        ->name('pages.edit');

    Route::put('admin/pages/{page}', [PageManagementController::class, 'update'])
        ->middleware('permission:pages.update')
        ->name('pages.update');

    Route::delete('admin/pages/{page}', [PageManagementController::class, 'destroy'])
        ->middleware('permission:pages.delete')
        ->name('pages.destroy');

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

Route::get('{page:slug}', PublicPageController::class)->name('public-pages.show');
