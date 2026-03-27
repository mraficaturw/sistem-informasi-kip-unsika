<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: ForceHttps
 *
 * Memaksa semua request menggunakan HTTPS di environment production.
 * Di lokal (APP_ENV=local) tidak aktif agar tidak mengganggu proses development.
 *
 * Cara kerja:
 *  - Jika env bukan 'local' dan request masuk via HTTP (bukan HTTPS),
 *    redirect permanen (301) ke versi HTTPS dari URL yang sama.
 *  - Juga memberi tahu Laravel agar menganggap semua request sebagai HTTPS
 *    (berguna saat di balik reverse proxy/load balancer).
 */
class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya aktif di environment selain lokal
        if (! app()->environment('local')) {
            // Paksa URL yang dihasilkan oleh Laravel (url(), route(), dll) memakai HTTPS
            \Illuminate\Support\Facades\URL::forceScheme('https');

            // Jika request masuk via HTTP, redirect ke HTTPS
            if (! $request->isSecure()) {
                return redirect()->secure($request->getRequestUri(), 301);
            }
        }

        return $next($request);
    }
}
