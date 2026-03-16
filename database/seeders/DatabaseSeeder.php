<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\SystemMessageNotification;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $accounts = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'Admin',
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'role' => 'Manager',
            ],
            [
                'name' => 'Member User',
                'email' => 'member@example.com',
                'role' => 'Member',
            ],
            [
                'name' => 'Read Only User',
                'email' => 'readonly@example.com',
                'role' => 'ReadOnly',
            ],
            [
                'name' => 'External User',
                'email' => 'external@example.com',
                'role' => 'External',
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'Admin',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::query()->updateOrCreate([
                'email' => $account['email'],
            ], [
                'name' => $account['name'],
                'password' => Hash::make('password'),
            ]);

            $user->syncRoles([$account['role']]);

            if ($user->notifications()->doesntExist()) {
                $user->notify(new SystemMessageNotification(
                    title: 'Boilerplate access ready',
                    message: "Your {$account['role']} account is ready for starter-kit review.",
                    actionUrl: '/dashboard',
                    actionLabel: 'Open dashboard',
                ));
            }
        }

        $this->call(ActivityLogSeeder::class);
    }
}
