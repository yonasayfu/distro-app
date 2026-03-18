<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Support\SettingRegistry;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SettingRegistry::fields() as $key => $field) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'group' => $field['group'],
                    'value' => $field['default'],
                ],
            );
        }
    }
}
