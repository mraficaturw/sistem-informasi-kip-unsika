<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KhsController;
use App\Http\Controllers\EmailConsentController;
use App\Http\Controllers\StudentSettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Preview halaman maintenance (untuk development — dapat dihapus di production)
Route::get('/maintenance-preview', function () {
    return view('maintenance');
})->name('maintenance');

Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');

/*
|--------------------------------------------------------------------------
| Auth Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Lupa Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Verification Status (Auth, any status)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/verification-status', function () {
        $user = auth()->user();
        // If already approved, redirect to dashboard
        if ($user->isApproved()) {
            return redirect()->route('dashboard');
        }
        // Admin should not see this page
        if ($user->isAdmin()) {
            return redirect('/admin');
        }
        return view('auth.verification-status');
    })->name('verification.status');
});

/*
|--------------------------------------------------------------------------
| Student Routes (Auth + Approved)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\EnsureApprovedStudent::class])->group(function () {
    // Email consent — harus bisa diakses SEBELUM EnsureEmailConsent
    Route::get('/email-consent', [EmailConsentController::class, 'show'])->name('email.consent');
    Route::post('/email-consent', [EmailConsentController::class, 'store'])->name('email.consent.store');

    // Routes yang memerlukan consent sudah dijawab
    Route::middleware(\App\Http\Middleware\EnsureEmailConsent::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/khs', [KhsController::class, 'store'])->name('khs.store');

        // Pengaturan akun mahasiswa
        Route::get('/settings', [StudentSettingsController::class, 'show'])->name('settings');
        Route::post('/settings/password', [StudentSettingsController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/email', [StudentSettingsController::class, 'updateEmailPreference'])->name('settings.email');
    });
});
