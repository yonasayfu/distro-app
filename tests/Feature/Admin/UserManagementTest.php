<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

test('admin can update another users roles', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $targetUser = User::factory()->create();
    $targetUser->assignRole('Member');

    $this->actingAs($admin)
        ->put(route('users.roles.update', $targetUser), [
            'roles' => ['Manager'],
        ])
        ->assertRedirect(route('users.index'));

    expect($targetUser->fresh()->hasRole('Manager'))->toBeTrue()
        ->and($targetUser->fresh()->hasRole('Member'))->toBeFalse();
});

test('admin cannot remove their own admin role from this screen', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->from(route('users.index'))
        ->put(route('users.roles.update', $admin), [
            'roles' => ['Member'],
        ])
        ->assertRedirect(route('users.index'))
        ->assertSessionHasErrors('roles');
});

test('manager cannot update user roles without the update permission', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $targetUser = User::factory()->create();
    $targetUser->assignRole('Member');

    $this->actingAs($manager)
        ->put(route('users.roles.update', $targetUser), [
            'roles' => ['ReadOnly'],
        ])
        ->assertForbidden();
});
