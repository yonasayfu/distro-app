<?php

use App\Models\Media;
use App\Models\Note;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('admin can create a note on a user and see it on the edit page', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $managedUser = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('notes.store'), [
            'noteable_type' => 'user',
            'noteable_id' => $managedUser->id,
            'content' => 'Follow up with this user about role changes.',
        ])
        ->assertRedirect();

    expect(Note::query()->whereMorphedTo('noteable', $managedUser)->count())->toBe(1);

    $this->actingAs($admin)
        ->get(route('users.edit', $managedUser))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/Users/Edit')
            ->where('noteTarget.type', 'user')
            ->has('user.notes', 1)
            ->where('user.notes.0.content', 'Follow up with this user about role changes.'),
        );
});

test('manager can create and delete a page note through the shared notes route', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $page = Page::factory()->create();

    $this->actingAs($manager)
        ->post(route('notes.store'), [
            'noteable_type' => 'page',
            'noteable_id' => $page->id,
            'content' => 'Keep this page in draft until legal review is complete.',
        ])
        ->assertRedirect();

    $note = Note::query()->whereMorphedTo('noteable', $page)->firstOrFail();

    expect($note->author_id)->toBe($manager->id);

    $this->actingAs($manager)
        ->delete(route('notes.destroy', $note))
        ->assertRedirect();

    expect(Note::query()->whereKey($note->id)->exists())->toBeFalse();
});

test('manager can attach a note to media through the shared polymorphic note flow', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $media = Media::factory()->create([
        'uploaded_by' => $manager->id,
    ]);

    $this->actingAs($manager)
        ->post(route('notes.store'), [
            'noteable_type' => 'media',
            'noteable_id' => $media->id,
            'content' => 'Approved source file for the contract packet.',
        ])
        ->assertRedirect();

    expect(Note::query()->whereMorphedTo('noteable', $media)->count())->toBe(1);
});

test('member cannot create notes', function () {
    $this->seed(RolePermissionSeeder::class);

    $member = User::factory()->create();
    $member->assignRole('Member');

    $page = Page::factory()->create();

    $this->actingAs($member)
        ->post(route('notes.store'), [
            'noteable_type' => 'page',
            'noteable_id' => $page->id,
            'content' => 'This should be blocked.',
        ])
        ->assertForbidden();
});
