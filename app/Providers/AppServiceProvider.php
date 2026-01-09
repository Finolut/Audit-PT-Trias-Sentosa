<?php

namespace App\Providers;

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
    if (app()->environment('production')) {
        // Mengarahkan folder sementara ke /tmp milik Vercel
        config(['session.files' => '/tmp/sessions']);
        config(['view.compiled' => '/tmp/views']);
        config(['cache.stores.file.path' => '/tmp/cache']);
    }
}
}
