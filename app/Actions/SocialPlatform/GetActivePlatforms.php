<?php

namespace App\Actions\SocialPlatform;

use App\Models\SocialPlatform;

class GetActivePlatforms
{
    /**
     * Execute the get active platforms action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute()
    {
        $platforms = SocialPlatform::active()
            ->ordered()
            ->get()
            ->map(function ($platform) {
                return [
                    'id' => $platform->id,
                    'name' => $platform->name,
                    'display_name' => $platform->display_name,
                    'icon' => $platform->icon,
                    'color' => $platform->color,
                ];
            });

        return success('Active platforms retrieved successfully', [
            'platforms' => $platforms,
        ]);
    }
}
