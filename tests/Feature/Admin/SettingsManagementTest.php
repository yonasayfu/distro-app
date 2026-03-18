<?php

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('admin can view the settings editor', function () {
    $this->seed([RolePermissionSeeder::class, SettingsSeeder::class]);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->get(route('admin-settings.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/Settings/Edit')
            ->has('settingGroups', 3),
        );
});

test('admin can update shared business settings', function () {
    $this->seed([RolePermissionSeeder::class, SettingsSeeder::class]);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->put(route('admin-settings.update'), [
            'app_display_name' => 'Acme Operations',
            'app_tagline' => 'A stronger operations workspace.',
            'support_email' => 'support@acme.test',
            'organization_name' => 'Acme Group',
            'organization_legal_name' => 'Acme Group PLC',
            'organization_email' => 'hello@acme.test',
            'organization_phone' => '+251911000000',
            'public_site_title' => 'Acme Platform',
            'public_tagline' => 'Operations, visibility, and control.',
            'public_cta_label' => 'Explore the handbook',
            'public_cta_url' => '/handbook?document=roadmap',
            'public_footer_text' => 'Acme combines public presence with operator control.',
        ])
        ->assertRedirect(route('admin-settings.edit'));

    expect(Setting::query()->where('key', 'app_display_name')->value('value'))->toBe('Acme Operations')
        ->and(Setting::query()->where('key', 'public_site_title')->value('value'))->toBe('Acme Platform');

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('name', 'Acme Operations')
            ->where('settings.publicSiteTitle', 'Acme Platform')
            ->where('settings.organizationName', 'Acme Group'),
        );
});

test('manager can view but not update settings', function () {
    $this->seed([RolePermissionSeeder::class, SettingsSeeder::class]);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    $this->actingAs($manager)
        ->get(route('admin-settings.edit'))
        ->assertOk();

    $this->actingAs($manager)
        ->put(route('admin-settings.update'), [
            'app_display_name' => 'Should fail',
            'app_tagline' => '',
            'support_email' => '',
            'organization_name' => '',
            'organization_legal_name' => '',
            'organization_email' => '',
            'organization_phone' => '',
            'public_site_title' => '',
            'public_tagline' => '',
            'public_cta_label' => '',
            'public_cta_url' => '',
            'public_footer_text' => '',
        ])
        ->assertForbidden();
});

test('member cannot access business settings', function () {
    $this->seed([RolePermissionSeeder::class, SettingsSeeder::class]);

    $member = User::factory()->create();
    $member->assignRole('Member');

    $this->actingAs($member)
        ->get(route('admin-settings.edit'))
        ->assertForbidden();
});
