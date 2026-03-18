<?php

use App\Models\Page;
use App\Models\User;
use App\Support\WorkflowTransitionRegistry;
use Database\Seeders\RolePermissionSeeder;

test('workflow registry exposes allowed page transitions', function () {
    expect(WorkflowTransitionRegistry::canTransitionPage('draft', 'review'))->toBeTrue()
        ->and(WorkflowTransitionRegistry::canTransitionPage('review', 'published'))->toBeTrue()
        ->and(WorkflowTransitionRegistry::canTransitionPage('archived', 'published'))->toBeFalse();
});

test('manager can move a page from draft to review', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $page = Page::factory()->draft()->create();

    $this->actingAs($manager)
        ->put(route('pages.update', $page), [
            'title' => $page->title,
            'slug' => $page->slug,
            'excerpt' => $page->excerpt,
            'content' => $page->content,
            'seo_title' => $page->seo_title,
            'seo_description' => $page->seo_description,
            'status' => 'review',
        ])
        ->assertRedirect(route('pages.edit', $page));

    expect($page->fresh()->status->value)->toBe('review')
        ->and($page->fresh()->is_published)->toBeFalse()
        ->and($page->fresh()->published_at)->toBeNull();
});

test('invalid page workflow transitions are rejected', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $page = Page::factory()->archived()->create();

    $this->actingAs($manager)
        ->put(route('pages.update', $page), [
            'title' => $page->title,
            'slug' => $page->slug,
            'excerpt' => $page->excerpt,
            'content' => $page->content,
            'seo_title' => $page->seo_title,
            'seo_description' => $page->seo_description,
            'status' => 'published',
        ])
        ->assertSessionHasErrors('status');

    expect($page->fresh()->status->value)->toBe('archived');
});
