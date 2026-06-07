<?php

namespace Database\Seeders;

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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed all seeders
        $this->call([
            \Database\Seeders\DisabilityTypeSeeder::class,
            \Database\Seeders\AdminUserSeeder::class,
            \Database\Seeders\LocationSeeder::class,
            \Database\Seeders\WorkArrangementOptionSeeder::class,
            \Database\Seeders\AssistiveDeviceOptionSeeder::class,
            \Database\Seeders\JobPostingSeeder::class,
        ]);
    }
}
