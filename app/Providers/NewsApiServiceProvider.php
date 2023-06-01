<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NewsApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('news_api', 'App\Services\NewsApi');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
