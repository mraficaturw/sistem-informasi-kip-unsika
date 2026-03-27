<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        // Gunakan custom Bootstrap paginator agar kompatibel dengan tema Bootstrap
        Paginator::defaultView('vendor.pagination.bootstrap-custom');

        // ── Di lingkungan non-production: cegah lazy loading (N+1 query) ──────
        // Jika Eloquent relation diakses tanpa eager load, akan throw exception
        // sehingga developer sadar lebih awal sebelum masuk production.
        if (! app()->environment('production')) {
            Model::preventLazyLoading();
        }

        // ── Di production: paksa semua URL yang digenerate Laravel memakai HTTPS ─
        // Ini bekerja bersama ForceHttps middleware untuk konsistensi URL.
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}

