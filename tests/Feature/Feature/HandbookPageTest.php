<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected away from the handbook', function () {
    $this->get(route('handbook.index'))
        ->assertRedirect(route('login'));
});

test('signed in member can read the handbook index', function () {
    $this->seed(RolePermissionSeeder::class);

    $member = User::factory()->create();
    $member->assignRole('Member');

    $this->actingAs($member)
        ->get(route('handbook.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('handbook/Index')
            ->where('filters.document', 'roadmap')
            ->has('groups', 3)
            ->has('currentDocument')
            ->has('lessonItems', 0),
        );
});

test('signed in member can open a laravel basics lesson', function () {
    $this->seed(RolePermissionSeeder::class);

    $member = User::factory()->create();
    $member->assignRole('Member');

    $this->actingAs($member)
        ->get(route('handbook.index', [
            'document' => 'laravelbasics',
            'lesson' => 'entry-20-public-layout-and-landing-page-foundation',
        ]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('handbook/Index')
            ->where('filters.document', 'laravelbasics')
            ->where('filters.lesson', 'entry-20-public-layout-and-landing-page-foundation')
            ->where('currentLesson.entryNumber', 20)
            ->where('currentLesson.title', 'Public Layout and Landing Page Foundation')
            ->has('lessonItems'),
        );
});
