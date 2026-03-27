<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: SuspiciousActivityLogger
 *
 * Mencatat aktivitas mencurigakan ke log channel 'security' secara otomatis.
 * Log mencakup: IP address, URL yang diakses, user agent, dan user ID (jika login).
 *
 * Aktivitas yang dicatat:
 *  1. Upaya login yang gagal (POST /login dengan session error)
 *  2. Akses ke route yang membutuhkan auth tanpa sesi valid (401/403)
 *  3. Response 419 (CSRF mismatch) — ditangkap via response status code
 */
class SuspiciousActivityLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $statusCode = $response->getStatusCode();

        // ── Catat upaya login gagal (POST /login → redirect dengan error session) ─
        if ($request->isMethod('POST') && $request->is('login') && session()->has('errors')) {
            Log::channel('security')->warning('Login gagal', [
                'ip'         => $request->ip(),
                'email'      => $request->input('npm_or_email', $request->input('email', '-')),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // ── Catat akses tidak sah ke halaman protected (401 Unauthorized / 403 Forbidden) ─
        if (in_array($statusCode, [401, 403])) {
            Log::channel('security')->warning("Akses ditolak ({$statusCode})", [
                'ip'         => $request->ip(),
                'url'        => $request->fullUrl(),
                'user_id'    => auth()->id() ?? 'guest',
                'user_agent' => $request->userAgent(),
            ]);
        }

        // ── Catat CSRF mismatch (419 Page Expired) ────────────────────────────
        if ($statusCode === 419) {
            Log::channel('security')->warning('CSRF token mismatch (419)', [
                'ip'         => $request->ip(),
                'url'        => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
