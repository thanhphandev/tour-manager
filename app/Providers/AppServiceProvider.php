<?php

namespace App\Providers;

use App\Services\MarkdownService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MarkdownService::class, function ($app) {
            return new MarkdownService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ép buộc dùng https khi chạy trên production (Railway)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
