<?php
/**
 * Vercel Serverless Entry Point
 *
 * File ini menjadi entry point untuk Vercel serverless function.
 * Semua request HTTP di-route ke sini oleh vercel.json,
 * lalu diteruskan ke Laravel public/index.php.
 *
 * Pada environment serverless (Vercel), filesystem bersifat read-only
 * kecuali /tmp. Oleh karena itu, semua path yang memerlukan write access
 * (cache, views, logs, sessions) di-redirect ke /tmp.
 */

// ── Redirect writable paths ke /tmp ─────────────────────────────────────────
// Vercel serverless hanya mengizinkan write di /tmp
$_ENV['APP_CONFIG_CACHE']    = '/tmp/config.php';
$_ENV['APP_PACKAGES_CACHE']  = '/tmp/packages.php';
$_ENV['APP_SERVICES_CACHE']  = '/tmp/services.php';
$_ENV['APP_ROUTES_CACHE']    = '/tmp/routes.php';
$_ENV['APP_EVENTS_CACHE']    = '/tmp/events.php';
$_ENV['VIEW_COMPILED_PATH']  = '/tmp/views';

// Pastikan direktori /tmp/views ada
if (! is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

// ── Forward ke Laravel entry point ──────────────────────────────────────────
require __DIR__ . '/../public/index.php';
