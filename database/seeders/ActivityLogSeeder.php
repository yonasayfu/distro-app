<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actor = User::query()->where('email', 'admin@example.com')->first();

        if (! $actor) {
            return;
        }

        ActivityLog::query()->create([
            'actor_id' => $actor->id,
            'event' => 'boilerplate.seeded',
            'description' => 'Seeded the starter boilerplate data set.',
            'properties' => [
                'context' => 'database seeder',
            ],
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ]);
    }
}
