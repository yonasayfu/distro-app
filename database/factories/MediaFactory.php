<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $originalName = fake()->word().'.pdf';

        return [
            'uploaded_by' => User::factory(),
            'collection' => 'library',
            'disk' => 'local',
            'directory' => 'media/library',
            'path' => 'media/library/'.fake()->uuid().'.pdf',
            'original_name' => $originalName,
            'stored_name' => fake()->uuid().'.pdf',
            'extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(1024, 50000),
            'metadata' => [
                'source' => 'factory',
            ],
        ];
    }
}
