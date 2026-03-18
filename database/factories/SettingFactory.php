<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Support\SettingRegistry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $key = fake()->unique()->slug(2, '_');

        return [
            'group' => 'custom',
            'key' => $key,
            'value' => fake()->sentence(),
        ];
    }

    public function known(string $key): static
    {
        $field = SettingRegistry::field($key);

        return $this->state(fn (): array => [
            'group' => $field['group'],
            'key' => $key,
            'value' => (string) $field['default'],
        ]);
    }
}
