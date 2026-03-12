<?php

namespace App\Http\Controllers;

use App\Models\KhsSubmission;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KhsController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $formActive = Setting::get('form_pendataan_active', '0') === '1';
        if (!$formActive) {
            return back()->with('error', 'Form pendataan sedang tidak aktif.');
        }

        $currentPeriod = Setting::get('form_pendataan_period', '');
        $user = auth()->user();

        // Check existing submission for this period (that's not rejected)
        $existing = KhsSubmission::where('user_id', $user->id)
            ->where('form_period', $currentPeriod)
            ->whereIn('status', ['pending', 'verified'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengisi form pendataan untuk periode ini.');
        }

        if ($user->khs_next_resubmit_at && now()->lessThan($user->khs_next_resubmit_at)) {
            $diffForHumans = $user->khs_next_resubmit_at->diffForHumans(['parts' => 2, 'short' => true]);
            return back()->with('error', "Akses form ditangguhkan. Silakan tunggu $diffForHumans lagi karena percobaan ditolak berulang kali.");
        }

        $validated = $request->validate([
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'ips' => ['required', 'numeric', 'min:0', 'max:4.00'],
            'khs_file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'semester.required' => 'Semester wajib dipilih.',
            'ips.required' => 'IPS wajib diisi.',
            'ips.min' => 'IPS minimal 0.00.',
            'ips.max' => 'IPS maksimal 4.00.',
            'khs_file.required' => 'File KHS wajib diupload.',
            'khs_file.mimes' => 'File harus berformat PDF.',
            'khs_file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $path = $request->file('khs_file')->store('khs', 'public');

        $status = 'pending';
        $message = 'Form pendataan berhasil disubmit. Menunggu verifikasi admin.';
        $isSuccess = true;

        if (false) { // Condition removed so all submissions go to pending state first
            $status = 'rejected';
            $user->update([
                'khs_next_resubmit_at' => now()->addHours(6) // 6 hours penalty for IPS rejection
            ]);
            $message = 'Maaf, form pendataan Anda otomatis ditolak karena IPS di bawah 3.0. Anda dapat mengisi kembali setelah waktu tunggu berakhir.';
            $isSuccess = false;
        }

        KhsSubmission::create([
            'user_id' => $user->id,
            'semester' => $validated['semester'],
            'ips' => $validated['ips'],
            'khs_file' => $path,
            'form_period' => $currentPeriod,
            'status' => $status,
            'submitted_at' => now(),
        ]);

        return back()->with($isSuccess ? 'success' : 'error', $message);
    }
}
