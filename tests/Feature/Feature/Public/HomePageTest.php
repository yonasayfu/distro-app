<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guest can view the public landing page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->where('canRegister', true),
        );
});

test('signed in user can still view the public landing page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome'),
        );
});
