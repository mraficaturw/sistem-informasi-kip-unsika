<?php

use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SuspiciousActivityLogger;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ── Global middleware: berjalan di SETIAP request ─────────────────────
        // ForceHttps: redirect HTTP → HTTPS di production (tidak aktif di lokal)
        // SecurityHeaders: tambahkan security headers ke semua response
        $middleware->prepend([
            ForceHttps::class,
            SecurityHeaders::class,
        ]);

        // ── Web group middleware: berjalan di semua route web ─────────────────
        // SuspiciousActivityLogger: log login gagal, akses tidak sah, CSRF mismatch
        $middleware->appendToGroup('web', SuspiciousActivityLogger::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // ── Tangani CSRF mismatch ─────────────────────────────────────────────
        // Kembalikan response 419 agar SuspiciousActivityLogger dapat mendeteksinya
        $exceptions->render(function (TokenMismatchException $e): Response {
            return response('Sesi kadaluarsa, silakan muat ulang halaman.', 419);
        });
    })->create();
