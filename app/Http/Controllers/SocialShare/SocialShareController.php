<?php

namespace App\Http\Controllers\SocialShare;

use App\Actions\SocialPlatform\GetActivePlatforms;
use App\Actions\SocialShare\TrackSocialShare;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialShare\TrackShareRequest;

class SocialShareController extends Controller
{
    /**
     * Track a social share button click
     */
    public function track(TrackShareRequest $request, TrackSocialShare $action)
    {
        return $action->execute($request->validated());
    }

    /**
     * Get active social platforms
     */
    public function platforms(GetActivePlatforms $action)
    {
        return $action->execute();
    }
}
