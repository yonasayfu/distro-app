<?php

use App\Models\ImportRun;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;

test('manager can preview a page csv import and see import history', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $file = UploadedFile::fake()->createWithContent('pages.csv', <<<'CSV'
title,slug,excerpt,content,seo_title,seo_description,status
Imported Page,imported-page,Short summary,Long body,Imported SEO,Imported description,published
Review Page,review-page,,Body for review,,,"review"
CSV);

    $this->actingAs($manager)
        ->post(route('pages.import.preview'), [
            'file' => $file,
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/Pages/Import')
            ->where('preview.summary.rows', 2)
            ->where('preview.summary.validRows', 2)
            ->has('importRuns', 1),
        );

    expect(ImportRun::query()->where('resource', 'pages')->count())->toBe(1);
});

test('manager can run a previewed page import', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $importRun = ImportRun::factory()->create([
        'user_id' => $manager->id,
        'resource' => 'pages',
        'status' => 'previewed',
        'file_name' => 'pages.csv',
        'rows_count' => 2,
        'valid_rows_count' => 2,
        'preview_rows' => [
            [
                'line' => 2,
                'title' => 'Imported Page',
                'slug' => 'imported-page',
                'excerpt' => 'Short summary',
                'content' => 'Long body',
                'seo_title' => 'Imported SEO',
                'seo_description' => 'Imported description',
                'status' => 'published',
                'valid' => true,
                'errors' => [],
            ],
            [
                'line' => 3,
                'title' => 'Review Page',
                'slug' => 'review-page',
                'excerpt' => null,
                'content' => 'Body for review',
                'seo_title' => null,
                'seo_description' => null,
                'status' => 'review',
                'valid' => true,
                'errors' => [],
            ],
        ],
    ]);

    $this->actingAs($manager)
        ->post(route('pages.import.store'), [
            'import_run_id' => $importRun->id,
        ])
        ->assertRedirect(route('pages.index'));

    expect(Page::query()->where('slug', 'imported-page')->exists())->toBeTrue()
        ->and(Page::query()->where('slug', 'review-page')->exists())->toBeTrue()
        ->and($importRun->fresh()->status)->toBe('completed')
        ->and($importRun->fresh()->imported_rows_count)->toBe(2);
});

test('admin can restore a soft deleted page', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $page = Page::factory()->create();
    $page->delete();

    $this->actingAs($admin)
        ->post(route('pages.restore', $page->id))
        ->assertRedirect(route('pages.index', ['trashed' => 'with']));

    expect($page->fresh()->deleted_at)->toBeNull();
});

test('member cannot import or restore pages', function () {
    $this->seed(RolePermissionSeeder::class);

    $member = User::factory()->create();
    $member->assignRole('Member');

    $page = Page::factory()->create();
    $page->delete();

    $this->actingAs($member)
        ->get(route('pages.import'))
        ->assertForbidden();

    $this->actingAs($member)
        ->post(route('pages.restore', $page->id))
        ->assertForbidden();
});
