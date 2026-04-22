<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentSettingsController extends Controller
{
    /**
     * Tampilkan halaman pengaturan akun mahasiswa.
     */
    public function show(): View
    {
        return view('student.settings');
    }

    /**
     * Proses ganti password: validasi password lama, update password baru.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'password.min'   => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        $user = $request->user();

        // Verifikasi password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withFragment('password');
        }

        // Cegah password baru sama dengan password lama
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama.'])->withFragment('password');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Update preferensi notifikasi email.
     */
    public function updateEmailPreference(Request $request): RedirectResponse
    {
        $request->validate([
            'email_opt_in' => ['required', 'boolean'],
        ]);

        $request->user()->update([
            'email_opt_in' => $request->boolean('email_opt_in'),
        ]);

        $message = $request->boolean('email_opt_in')
            ? 'Notifikasi email diaktifkan.'
            : 'Notifikasi email dinonaktifkan.';

        return redirect()->route('settings')->with('success', $message);
    }
}
