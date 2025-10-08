<?php

namespace Database\Seeders;

use App\Enums\SocialPlatformEnum;
use App\Models\SocialPlatform;
use Illuminate\Database\Seeder;

class SocialPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = SocialPlatformEnum::getPlatformData();

        foreach ($platforms as $platform) {
            SocialPlatform::updateOrCreate(
                ['name' => $platform['name']],
                $platform
            );
        }

        $this->command->info('Social platforms seeded successfully!');
    }
}
