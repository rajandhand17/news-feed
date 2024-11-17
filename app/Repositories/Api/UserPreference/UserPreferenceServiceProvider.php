<?php

namespace App\Repositories\Api\UserPreference;

use Illuminate\Support\ServiceProvider;

class UserPreferenceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Api\UserPreference\UserPreferenceInterface', 'App\Repositories\Api\UserPreference\UserPreferenceRepository');
    }
}
