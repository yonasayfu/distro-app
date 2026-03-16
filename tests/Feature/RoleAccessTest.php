<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

test('admin can access users and roles pages', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('Admin');

    $this->actingAs($user);

    $this->get(route('users.index'))->assertOk();
    $this->get(route('roles.index'))->assertOk();
});

test('manager cannot access users or roles pages', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('Manager');

    $this->actingAs($user);

    $this->get(route('users.index'))->assertForbidden();
    $this->get(route('roles.index'))->assertForbidden();
});

test('read only role can still access dashboard but not administration pages', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('ReadOnly');

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertOk();
    $this->get(route('users.index'))->assertForbidden();
    $this->get(route('roles.index'))->assertForbidden();
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
