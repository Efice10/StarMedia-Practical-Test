<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding database...');

        // Order matters: permissions and roles must be created before users
        $this->call([
            RoleAndPermissionSeeder::class,
            SocialPlatformSeeder::class,
            AdminUserSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
