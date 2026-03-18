<?php

use App\Models\ActivityLog;
use App\Models\ImportRun;
use App\Models\Media;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;

test('manager sees dashboard widgets and reporting props', function () {
    $this->seed(RolePermissionSeeder::class);

    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    Page::factory()->count(3)->create();
    Media::factory()->count(2)->create(['uploaded_by' => $manager->id]);
    ImportRun::factory()->create(['status' => 'completed']);
    ActivityLog::factory()->count(2)->create();

    $this->actingAs($manager)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('metrics', 4)
            ->has('recentActivity', 2)
            ->where('reportHighlights.completedImports', 1)
            ->where('auth.can.viewReports', true)
            ->where('auth.can.managePages', true),
        );
});
