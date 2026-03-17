<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;

test('authenticated users without dashboard permission are forbidden', function () {
    Permission::findOrCreate('dashboard.view', 'web');

    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('dashboard'))->assertForbidden();
});

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users with dashboard permission can visit the dashboard', function () {
    $permission = Permission::findOrCreate('dashboard.view', 'web');
    $user = User::factory()->create();
    $user->givePermissionTo($permission);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Dashboard'));
});
