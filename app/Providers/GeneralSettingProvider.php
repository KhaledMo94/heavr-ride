<?php

namespace App\Providers;

use App\Interfaces\SettingInterface;
use App\Services\SettingService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class GeneralSettingProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        App::bind(SettingInterface::class , SettingService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
