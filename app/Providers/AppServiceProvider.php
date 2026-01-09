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
    // Tambahkan ini agar folder bootstrap bisa ditulis di folder /tmp Vercel
    if (config('app.env') === 'production') {
        config(['session.files' => '/tmp/sessions']);
        config(['view.compiled' => '/tmp/views']);
        config(['cache.stores.file.path' => '/tmp/cache']);
    }
}
}
