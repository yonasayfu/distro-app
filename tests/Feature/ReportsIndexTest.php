<?php

use App\Models\ActivityLog;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('manager can view the filterable pages report', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    Page::factory()->create([
        'title' => 'Operations Overview',
        'slug' => 'operations-overview',
        'status' => 'published',
    ]);

    Page::factory()->create([
        'title' => 'Draft Planning Note',
        'slug' => 'draft-planning-note',
        'status' => 'draft',
    ]);

    $this->actingAs($manager)
        ->get(route('reports.index', [
            'search' => 'operations',
            'status' => 'published',
        ]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('reports/Index')
            ->where('filters.search', 'operations')
            ->where('filters.status', 'published')
            ->has('pageReport.data', 1)
            ->where('pageReport.data.0.slug', 'operations-overview'),
        );
});

test('manager can export the filtered pages report as csv', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    Page::factory()->create([
        'title' => 'Business Report',
        'slug' => 'business-report',
        'status' => 'published',
    ]);

    Page::factory()->create([
        'title' => 'Archived Report',
        'slug' => 'archived-report',
        'status' => 'archived',
    ]);

    $response = $this->actingAs($manager)
        ->get(route('reports.pages.csv', [
            'search' => 'business',
            'status' => 'published',
        ]));

    $response
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $content = $response->streamedContent();

    expect($content)->toContain('Business Report')
        ->not->toContain('Archived Report');

    expect(ActivityLog::query()->where('event', 'reports.pages-csv')->exists())->toBeTrue();
});

test('member cannot access reports', function () {
    $this->seed(RolePermissionSeeder::class);

    $member = User::factory()->create();
    $member->assignRole('Member');

    $this->actingAs($member)
        ->get(route('reports.index'))
        ->assertForbidden();
});
