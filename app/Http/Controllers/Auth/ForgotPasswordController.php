<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form permintaan link reset password.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email pengguna.
     * Menggunakan built-in Laravel Password Broker (throttled & token auto-expire).
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => $this->translateStatus($status)]);
    }

    /**
     * Terjemahkan status Password Broker ke pesan Bahasa Indonesia.
     */
    private function translateStatus(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER   => 'Email tidak ditemukan dalam sistem.',
            Password::RESET_THROTTLED => 'Anda sudah meminta reset password. Silakan tunggu beberapa saat sebelum mencoba lagi.',
            default                   => 'Terjadi kesalahan. Silakan coba lagi.',
        };
    }
}
