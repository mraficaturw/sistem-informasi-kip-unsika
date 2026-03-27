<?php

namespace App\Services;

use App\Models\KhsSubmission;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * KhsService
 *
 * Memisahkan logika bisnis pengajuan KHS dari Controller agar lebih testable,
 * reusable, dan mudah dipahami developer berikutnya.
 *
 * Tanggung jawab:
 *  - Membaca status & periode form dari Setting (via cache)
 *  - Mengecek kondisi sebelum submit (duplikasi berdasarkan periode, cooldown)
 *  - Menyimpan file PDF dan membuat record KHS baru
 */
class KhsService
{
    /**
     * Periksa apakah form pendataan saat ini sedang aktif.
     */
    public function isFormActive(): bool
    {
        return Setting::get('form_pendataan_active', '0') === '1';
    }

    /**
     * Ambil string periode form pendataan yang sedang berjalan.
     * Contoh nilai: "2024/2025 Ganjil"
     */
    public function getCurrentPeriod(): string
    {
        return Setting::get('form_pendataan_period', '');
    }

    /**
     * Periksa apakah mahasiswa sudah memiliki submission aktif (pending/verified)
     * pada periode form tertentu — untuk mencegah pengisian ganda.
     */
    public function hasActiveSubmission(int $userId, string $period): bool
    {
        return KhsSubmission::where('user_id', $userId)
            ->forPeriod($period)
            ->active()
            ->exists();
    }

    /**
     * Simpan file KHS dan buat record submission baru dengan status 'pending'.
     * Mengembalikan instance KhsSubmission yang baru dibuat.
     *
     * @param  int          $userId    ID mahasiswa yang mengajukan
     * @param  int          $semester  Nomor semester
     * @param  float        $ips       Indeks Prestasi Semester
     * @param  UploadedFile $file      File PDF KHS yang diupload
     * @param  string       $period    Periode form pendataan aktif
     */
    public function store(int $userId, int $semester, float $ips, UploadedFile $file, string $period): KhsSubmission
    {
        // Simpan file PDF ke storage/app/public/khs
        $path = $file->store('khs', 'public');

        // Buat record baru; semua pengajuan masuk ke 'pending' untuk diverifikasi admin
        $submission = KhsSubmission::create([
            'user_id'      => $userId,
            'semester'     => $semester,
            'ips'          => $ips,
            'khs_file'     => $path,
            'form_period'  => $period,
            'status'       => 'pending',
            'submitted_at' => now(),
        ]);

        // Catat aktivitas submit ke log utama untuk audit trail
        Log::info('KHS submitted', [
            'user_id'  => $userId,
            'period'   => $period,
            'semester' => $semester,
        ]);

        return $submission;
    }
}
