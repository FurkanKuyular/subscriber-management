<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('zotlo', function () {
            return Http::withHeaders([
                'AccessKey' => config('zotlo.access_key'),
                'AccessSecret' => config('zotlo.access_secret'),
                'ApplicationId' => config('zotlo.application_id'),
                'Language' => config('zotlo.language'),
            ])->baseUrl(config('zotlo.endpoint'));
        });
    }
}
