<?php

use App\Models\User;
use App\Notifications\SystemMessageNotification;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('notification center lists notifications and shares unread count', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('Member');
    $user->notify(new SystemMessageNotification(
        title: 'New access update',
        message: 'Your role permissions were updated.',
        actionUrl: '/dashboard',
        actionLabel: 'Review dashboard',
    ));

    $this->actingAs($user)
        ->get(route('notifications.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('notifications/Index')
            ->where('stats.unreadCount', 1)
            ->where('auth.notificationCount', 1)
            ->has('notifications.data', 1),
        );
});

test('notifications can be marked read individually and all at once', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('Member');
    $user->notify(new SystemMessageNotification(
        title: 'First',
        message: 'First message',
    ));
    $user->notify(new SystemMessageNotification(
        title: 'Second',
        message: 'Second message',
    ));

    $notification = $user->notifications()->latest()->firstOrFail();

    $this->actingAs($user)
        ->post(route('notifications.read', $notification->id))
        ->assertRedirect(route('notifications.index'));

    expect($notification->fresh()->read_at)->not->toBeNull();

    $this->actingAs($user)
        ->post(route('notifications.read-all'))
        ->assertRedirect(route('notifications.index'));

    expect($user->fresh()->unreadNotifications()->count())->toBe(0);
});

test('users without notification permission cannot access the notification center', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('ReadOnly');

    $this->actingAs($user)
        ->get(route('notifications.index'))
        ->assertForbidden();
});
