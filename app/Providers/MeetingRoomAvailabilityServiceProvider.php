<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MeetingRoomAvailabilityService;

class MeetingRoomAvailabilityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MeetingRoomAvailabilityService::class, function ($app) {
            return new MeetingRoomAvailabilityService();
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
