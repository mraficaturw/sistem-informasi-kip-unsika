<?php

namespace Database\Seeders;

use App\Models\TrackingStage;
use Illuminate\Database\Seeder;

/**
 * Seeder ini berisi data dummy tracking pencairan yang realistis
 * untuk keperluan screenshot dan dokumentasi laporan.
 *
 * Jalankan dengan: php artisan db:seed --class=TrackingDummySeeder
 */
class TrackingDummySeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data tracking yang ada terlebih dahulu
        TrackingStage::truncate();

        $stages = [
            [
                'title'       => 'Pengumpulan Data Mahasiswa',
                'description' => 'Mahasiswa mengisi form pendataan dan mengunggah Kartu Hasil Studi (KHS) melalui sistem website KIP UNSIKA.',
                'date'        => '2026-01-15',
                'status'      => 'completed',
                'sort_order'  => 1,
                'notes'       => 'Selesai — 243 dari 250 mahasiswa telah mengumpulkan data tepat waktu.',
            ],
            [
                'title'       => 'Verifikasi Data oleh Admin Prodi',
                'description' => 'Tim admin program studi memverifikasi kelengkapan berkas, keabsahan KHS, dan kesesuaian data akademik yang diajukan mahasiswa.',
                'date'        => '2026-02-01',
                'status'      => 'completed',
                'sort_order'  => 2,
                'notes'       => 'Selesai — 240 mahasiswa lolos verifikasi. 3 mahasiswa diminta melengkapi berkas ulang.',
            ],
            [
                'title'       => 'Proses Rekapitulasi oleh Universitas',
                'description' => 'Data mahasiswa yang lulus verifikasi direkap dan diteruskan ke Biro Keuangan UNSIKA untuk proses selanjutnya.',
                'date'        => '2026-02-20',
                'status'      => 'completed',
                'sort_order'  => 3,
                'notes'       => null,
            ],
            [
                'title'       => 'Pengajuan ke SIMPKIP Nasional',
                'description' => 'Rekapitulasi data mahasiswa KIP UNSIKA dikirimkan secara resmi ke sistem SIMPKIP Pusat untuk proses pencairan dana beasiswa.',
                'date'        => '2026-03-10',
                'status'      => 'active',
                'sort_order'  => 4,
                'notes'       => 'Sedang dalam proses review oleh Kemendikbud Puslapdik. Estimasi selesai: 25 Maret 2026.',
            ],
            [
                'title'       => 'Pencairan Dana ke Rekening Mahasiswa',
                'description' => 'Dana KIP Kuliah semester Genap 2025/2026 akan dicairkan langsung ke rekening bank masing-masing mahasiswa penerima.',
                'date'        => null,
                'status'      => 'upcoming',
                'sort_order'  => 5,
                'notes'       => 'Estimasi pencairan: Akhir Maret — Awal April 2026.',
            ],
        ];

        foreach ($stages as $stage) {
            TrackingStage::create($stage);
        }

        $this->command->info('✅ Data dummy tracking pencairan berhasil dibuat (' . count($stages) . ' tahap).');
    }
}
