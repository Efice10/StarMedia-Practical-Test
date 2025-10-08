<?php

namespace App\Actions\SocialShare;

use App\Models\SocialShare;

class TrackSocialShare
{
    /**
     * Execute the track social share action
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(array $data)
    {
        $share = SocialShare::create([
            'url' => $data['url'],
            'page_title' => $data['page_title'] ?? null,
            'social_platform_id' => $data['social_platform_id'],
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('referer'),
            'metadata' => $data['metadata'] ?? null,
        ]);

        return created('Share tracked successfully', [
            'share_id' => $share->id,
            'platform' => $share->platform->display_name,
            'tracked_at' => $share->created_at,
        ]);
    }
}
