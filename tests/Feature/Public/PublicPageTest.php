<?php

use App\Models\Page;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guest can view a published public page', function () {
    $page = Page::factory()->published()->create([
        'title' => 'About us',
        'slug' => 'about-us',
    ]);

    $this->get(route('public-pages.show', ['page' => $page->slug]))
        ->assertOk()
        ->assertInertia(fn (Assert $response) => $response
            ->component('public/Pages/Show')
            ->where('page.title', 'About us')
            ->where('page.slug', 'about-us'),
        );
});

test('draft pages are not visible publicly', function () {
    $page = Page::factory()->draft()->create([
        'slug' => 'draft-page',
    ]);

    $this->get(route('public-pages.show', ['page' => $page->slug]))
        ->assertNotFound();
});

test('signed in users still use the public layout for published pages', function () {
    $user = User::factory()->create();
    $page = Page::factory()->published()->create([
        'title' => 'Architecture',
        'slug' => 'architecture',
    ]);

    $this->actingAs($user)
        ->get(route('public-pages.show', ['page' => $page->slug]))
        ->assertOk()
        ->assertInertia(fn (Assert $response) => $response
            ->component('public/Pages/Show')
            ->where('page.title', 'Architecture'),
        );
});
