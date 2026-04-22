<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailConsentController extends Controller
{
    /**
     * Tampilkan halaman consent email.
     * Jika mahasiswa sudah menjawab, langsung redirect ke dashboard.
     */
    public function show(): View|RedirectResponse
    {
        if (auth()->user()->hasRespondedEmailConsent()) {
            return redirect()->route('dashboard');
        }

        return view('student.email-consent');
    }

    /**
     * Simpan pilihan consent email mahasiswa.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email_opt_in' => ['required', 'boolean'],
        ]);

        $request->user()->update([
            'email_opt_in' => $request->boolean('email_opt_in'),
        ]);

        $message = $request->boolean('email_opt_in')
            ? 'Terima kasih! Anda akan menerima notifikasi email.'
            : 'Preferensi disimpan. Anda tidak akan menerima notifikasi email.';

        return redirect()->route('dashboard')->with('success', $message);
    }
}
