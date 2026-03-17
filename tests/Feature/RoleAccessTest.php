<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

test('admin can access users and roles pages', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('Admin');

    $this->actingAs($user);

    $this->get(route('users.index'))->assertOk();
    $this->get(route('pages.index'))->assertOk();
    $this->get(route('roles.index'))->assertOk();
    $this->get(route('notifications.index'))->assertOk();
    $this->get(route('activity-logs.index'))->assertOk();
});

test('manager can access permission-backed shared modules but not admin pages', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('Manager');

    $this->actingAs($user);

    $this->get(route('pages.index'))->assertOk();
    $this->get(route('users.index'))->assertForbidden();
    $this->get(route('roles.index'))->assertForbidden();
    $this->get(route('notifications.index'))->assertOk();
    $this->get(route('activity-logs.index'))->assertOk();
});

test('member sees notifications but not admin pages or activity logs', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('Member');

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertOk();
    $this->get(route('pages.index'))->assertForbidden();
    $this->get(route('notifications.index'))->assertOk();
    $this->get(route('activity-logs.index'))->assertForbidden();
    $this->get(route('users.index'))->assertForbidden();
    $this->get(route('roles.index'))->assertForbidden();
});

test('read only role only gets the base workspace', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('ReadOnly');

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertOk();
    $this->get(route('pages.index'))->assertForbidden();
    $this->get(route('notifications.index'))->assertForbidden();
    $this->get(route('activity-logs.index'))->assertForbidden();
    $this->get(route('users.index'))->assertForbidden();
    $this->get(route('roles.index'))->assertForbidden();
});
