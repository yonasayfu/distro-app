<?php

use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('admin can view the pages index', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    Page::factory()->count(2)->create();

    $this->actingAs($admin)
        ->get(route('pages.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/Pages/Index')
            ->has('pages.data', 2),
        );
});

test('manager can create and update public pages', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $this->actingAs($manager)
        ->post(route('pages.store'), [
            'title' => 'About the starter',
            'slug' => 'about-starter',
            'excerpt' => 'High-level summary.',
            'content' => 'Detailed public page content.',
            'seo_title' => 'About the starter',
            'seo_description' => 'SEO summary.',
            'is_published' => true,
        ])
        ->assertRedirect();

    $page = Page::query()->where('slug', 'about-starter')->firstOrFail();

    expect($page->is_published)->toBeTrue()
        ->and($page->published_at)->not->toBeNull();

    $this->actingAs($manager)
        ->put(route('pages.update', $page), [
            'title' => 'About the boilerplate',
            'slug' => 'about-boilerplate',
            'excerpt' => 'Updated summary.',
            'content' => 'Updated public page content.',
            'seo_title' => 'About the boilerplate',
            'seo_description' => 'Updated SEO summary.',
            'is_published' => false,
        ])
        ->assertRedirect(route('pages.edit', $page));

    expect($page->fresh()->title)->toBe('About the boilerplate')
        ->and($page->fresh()->slug)->toBe('about-boilerplate')
        ->and($page->fresh()->is_published)->toBeFalse()
        ->and($page->fresh()->published_at)->toBeNull();
});

test('member cannot access page management routes', function () {
    $this->seed(RolePermissionSeeder::class);

    $member = User::factory()->create();
    $member->assignRole('Member');

    $page = Page::factory()->create();

    $this->actingAs($member)
        ->get(route('pages.index'))
        ->assertForbidden();

    $this->actingAs($member)
        ->get(route('pages.edit', $page))
        ->assertForbidden();
});

test('manager cannot delete pages without delete permission', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $page = Page::factory()->create();

    $this->actingAs($manager)
        ->delete(route('pages.destroy', $page))
        ->assertForbidden();
});

test('reserved page slugs are rejected', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->post(route('pages.store'), [
            'title' => 'Dashboard copy',
            'slug' => 'dashboard',
            'excerpt' => 'Reserved slug test.',
            'content' => 'Reserved slug content.',
            'seo_title' => '',
            'seo_description' => '',
            'is_published' => false,
        ])
        ->assertSessionHasErrors('slug');
});

test('admin can delete a page', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $page = Page::factory()->create();

    $this->actingAs($admin)
        ->delete(route('pages.destroy', $page))
        ->assertRedirect(route('pages.index'));

    expect(Page::query()->whereKey($page->id)->exists())->toBeFalse();
});
