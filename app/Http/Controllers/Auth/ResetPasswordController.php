<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan form reset password.
     * Diakses dari link yang dikirim via email.
     */
    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Proses reset password: validasi token, update password, auto-login.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'password.min'   => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => $this->translateStatus($status)]);
    }

    /**
     * Terjemahkan status Password Broker ke pesan Bahasa Indonesia.
     */
    private function translateStatus(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER  => 'Email tidak ditemukan dalam sistem.',
            Password::INVALID_TOKEN => 'Link reset password tidak valid atau sudah kadaluarsa.',
            default                 => 'Terjadi kesalahan. Silakan coba lagi.',
        };
    }
}
