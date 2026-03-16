<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'actor_id' => User::factory(),
            'event' => fake()->randomElement([
                'users.created',
                'users.updated',
                'roles.updated',
                'notifications.read',
            ]),
            'description' => fake()->sentence(),
            'subject_type' => null,
            'subject_id' => null,
            'properties' => [
                'source' => 'factory',
            ],
            'ip_address' => fake()->ipv4(),
            'created_at' => now(),
        ];
    }
}
