<?php

namespace App\Http\Controllers;

use App\Services\KhsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KhsController extends Controller
{
    /**
     * KhsController hanya bertanggung jawab atas alur HTTP (validasi input, redirect).
     * Semua logika bisnis didelegasikan ke KhsService.
     */
    public function __construct(private readonly KhsService $khsService)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        // ── 1. Periksa apakah form pendataan sedang aktif ──────────────────
        if (! $this->khsService->isFormActive()) {
            return back()->with('error', 'Form pendataan sedang tidak aktif.');
        }

        $currentPeriod = $this->khsService->getCurrentPeriod();
        $user          = auth()->user();

        // ── 2. Cegah pengisian ganda pada periode yang sama ────────────────
        // Hanya cek submission dengan status pending atau verified (bukan rejected)
        if ($this->khsService->hasActiveSubmission($user->id, $currentPeriod)) {
            return back()->with('error', 'Anda sudah mengisi form pendataan untuk periode ini.');
        }

        // ── 3. Cek apakah mahasiswa masih dalam masa cooldown (tunggu) ─────
        // Cooldown diterapkan setelah pengajuan ditolak secara berulang
        if ($user->isInKhsCooldown()) {
            $diffForHumans = $user->khs_next_resubmit_at->diffForHumans(['parts' => 2, 'short' => true]);
            return back()->with(
                'error',
                "Akses form ditangguhkan. Silakan tunggu $diffForHumans lagi karena percobaan ditolak berulang kali."
            );
        }

        // ── 4. Validasi input dari form ─────────────────────────────────────
        $validated = $request->validate([
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'ips'      => ['required', 'numeric', 'min:0', 'max:4.00'],
            'ipk'      => ['required', 'numeric', 'min:0', 'max:4.00'],
            'khs_file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'semester.required' => 'Semester wajib dipilih.',
            'ips.required'      => 'IPS wajib diisi.',
            'ips.min'           => 'IPS minimal 0.00.',
            'ips.max'           => 'IPS maksimal 4.00.',
            'ipk.required'      => 'IPK terakhir wajib diisi.',
            'ipk.min'           => 'IPK minimal 0.00.',
            'ipk.max'           => 'IPK maksimal 4.00.',
            'khs_file.required' => 'File KHS wajib diupload.',
            'khs_file.mimes'    => 'File harus berformat PDF.',
            'khs_file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        // ── 5. Delegasikan penyimpanan ke KhsService ────────────────────────
        // Service menangani: store file PDF + buat record KHS + audit log
        $this->khsService->store(
            userId:   $user->id,
            semester: (int) $validated['semester'],
            ips:      (float) $validated['ips'],
            ipk:      (float) $validated['ipk'],
            file:     $validated['khs_file'],
            period:   $currentPeriod,
        );

        return back()->with('success', 'Form pendataan berhasil disubmit. Menunggu verifikasi admin.');
    }
}
