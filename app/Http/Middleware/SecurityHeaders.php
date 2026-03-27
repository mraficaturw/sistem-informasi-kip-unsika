<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: SecurityHeaders
 *
 * Menambahkan security headers ke setiap HTTP response secara global.
 * Headers ini melindungi aplikasi dari serangan umum seperti:
 * - Clickjacking (X-Frame-Options)
 * - MIME sniffing (X-Content-Type-Options)
 * - Cross-Site Scripting / injeksi resource asing (Content-Security-Policy)
 * - Kebocoran informasi referrer (Referrer-Policy)
 * - Akses sensor/kamera tidak sah (Permissions-Policy)
 * - Man-in-the-Middle / downgrade HTTPS (HSTS – hanya di production)
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ── 1. Cegah halaman dimuat dalam iframe (anti-clickjacking) ──────────
        $response->headers->set('X-Frame-Options', 'DENY');

        // ── 2. Cegah browser menebak tipe konten secara otomatis ─────────────
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── 3. Aktifkan filter XSS bawaan browser lama ───────────────────────
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ── 4. Batasi informasi referrer hanya ke origin saat lintas domain ───
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── 5. Larang akses kamera, mikrofon, dan geolokasi dari skrip ────────
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // ── 6. Content Security Policy (CSP) ──────────────────────────────────
        // Mengizinkan: aset Vite lokal, Google Fonts, Bootstrap Icons CDN,
        // serta inline style/script (dibutuhkan Filament & Alpine.js)
        $csp  = "default-src 'self';";
        $csp .= " script-src 'self' 'unsafe-inline' 'unsafe-eval';"; // Filament & Alpine.js memerlukan ini
        $csp .= " style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;";
        $csp .= " font-src 'self' https://fonts.gstatic.com data:;";
        $csp .= " img-src 'self' data: blob:;";
        $csp .= " connect-src 'self';";
        $csp .= " frame-ancestors 'none';"; // setara X-Frame-Options: DENY
        $csp .= " base-uri 'self';";
        $csp .= " form-action 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        // ── 7. HSTS: paksa HTTPS selama 1 tahun (hanya di production) ─────────
        // Di lokal/staging tidak dipasang agar tidak merusak HTTP dev server
        if (app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
