<?php

namespace Database\Seeders;

use App\Models\HubSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
            'password' => 'password',
        ])->forceFill([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ])->save();

        HubSetting::query()->updateOrCreate(
            ['id' => 1],
            HubSetting::defaults(),
        );

        $this->call(ShortcutSeeder::class);
    }
}
