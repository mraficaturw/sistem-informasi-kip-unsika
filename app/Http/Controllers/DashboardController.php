<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\KhsSubmission;
use App\Models\Setting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // ── 1. Ambil setting sekali (di-cache oleh Setting model) ──────────
        $currentFormPeriod   = Setting::get('form_pendataan_period', '');
        $formPendataanActive = Setting::get('form_pendataan_active', '0') === '1';

        // ── 2. Ambil seluruh riwayat KHS mahasiswa, diurutkan terbaru ──────
        // Menggunakan satu query yang hasilnya di-reuse untuk berbagai keperluan
        $khsHistory = $user->khsSubmissions()
            ->orderByDesc('created_at')
            ->get();

        // KHS terbaru adalah elemen pertama dari koleksi (sudah diurutkan)
        $latestKhs = $khsHistory->first();

        // ── 3. Informasi semester, IPS, dan IPK terakhir ────────────────
        $currentSemester = $latestKhs ? 'Semester ' . $latestKhs->semester : '-';
        $latestIps       = $latestKhs ? number_format($latestKhs->ips, 2) : '-';
        $latestIpk       = $latestKhs ? number_format($latestKhs->ipk, 2) : '-';

        // ── 4. Tentukan pesan dan warna alert IPS & IPK berdasarkan nilainya ─
        [$ipsAlertMessage, $ipsAlertColor] = $this->resolveIpsAlert($latestKhs);
        [$ipkAlertMessage, $ipkAlertColor] = $this->resolveIpkAlert($latestKhs);

        // ── 5. Cari KHS yang diajukan pada periode form pendataan saat ini ─
        // Gunakan koleksi yang sudah diambil — tidak perlu query tambahan ke DB
        $currentPeriodKhs = $khsHistory->firstWhere('form_period', $currentFormPeriod);

        // ── 6. Tentukan label, warna, dan ikon status pengajuan periode ini ─
        [$currentPeriodStatus, $currentPeriodStatusColor, $currentPeriodStatusIcon]
            = $this->resolveSubmissionStatus($currentPeriodKhs);

        // ── 7. Data terbaru untuk pengumuman dan cooldown resubmit ─────────
        $recentAnnouncements = Announcement::published()
            ->latest('publish_date')
            ->take(5)
            ->get();

        // Waktu mahasiswa boleh mengisi ulang (null jika tidak dalam cooldown)
        $resubmitAt = $user->khs_next_resubmit_at;

        // Catatan penolakan dari admin (hanya tampil jika status terakhir ditolak)
        $rejectedNotes = ($currentPeriodKhs && $currentPeriodKhs->status === 'rejected')
            ? $currentPeriodKhs->admin_notes
            : null;

        return view('student.dashboard', compact(
            'currentSemester',
            'latestIps',
            'latestIpk',
            'ipsAlertMessage',
            'ipsAlertColor',
            'ipkAlertMessage',
            'ipkAlertColor',
            'currentPeriodStatus',
            'currentPeriodStatusColor',
            'currentPeriodStatusIcon',
            'recentAnnouncements',
            'khsHistory',
            'formPendataanActive',
            'currentFormPeriod',
            'resubmitAt',
            'rejectedNotes'
        ));
    }

    /**
     * Tentukan pesan dan warna alert berdasarkan nilai IPS terakhir mahasiswa.
     * Mengembalikan array [pesan, warna_css].
     *
     * Aturan:
     *  - IPS < 3.00  → danger  (perlu perhatian serius)
     *  - IPS 3.00–3.49 → warning (masih bisa ditingkatkan)
     *  - IPS >= 3.50 → success (pertahankan prestasi)
     */
    private function resolveIpsAlert(?object $latestKhs): array
    {
        // Jika belum pernah submit KHS, tidak ada alert yang perlu ditampilkan
        if (! $latestKhs) {
            return ['', 'success'];
        }

        $ips = (float) $latestKhs->ips;

        if ($ips < 3.00) {
            return ['Perlu ditingkatkan', 'danger'];
        }

        if ($ips < 3.50) {
            return ['Lebih baik ditingkatkan', 'warning text-dark'];
        }

        return ['Good Job, Pertahankan!', 'success'];
    }

    /**
     * Tentukan label status, warna badge, dan ikon untuk pengajuan KHS periode ini.
     * Mengembalikan array [label_status, warna_css, nama_ikon_bootstrap].
     *
     * Aturan:
     *  - Tidak ada pengajuan → Belum Mengisi (secondary)
     *  - verified → Disetujui (success)
     *  - rejected → Ditolak (danger)
     *  - pending  → Menunggu Validasi (warning)
     */
    private function resolveSubmissionStatus(?object $currentPeriodKhs): array
    {
        // Mahasiswa belum pernah mengisi form pada periode ini
        if (! $currentPeriodKhs) {
            return ['Belum Mengisi', 'secondary', 'bi-hourglass-top'];
        }

        return match ($currentPeriodKhs->status) {
            'verified' => ['Disetujui',          'success',          'bi-check-circle'],
            'rejected' => ['Ditolak',             'danger',           'bi-x-circle'],
            default    => ['Menunggu Validasi',   'warning text-dark', 'bi-hourglass-split'],
        };
    }

    /**
     * Tentukan pesan dan warna alert berdasarkan nilai IPK terakhir mahasiswa.
     * Mengembalikan array [pesan, warna_css].
     *
     * Aturan sama dengan IPS:
     *  - IPK < 3.00  → danger  (perlu perhatian serius)
     *  - IPK 3.00–3.49 → warning (masih bisa ditingkatkan)
     *  - IPK >= 3.50 → success (pertahankan prestasi)
     */
    private function resolveIpkAlert(?object $latestKhs): array
    {
        if (! $latestKhs) {
            return ['', 'success'];
        }

        $ipk = (float) $latestKhs->ipk;

        if ($ipk < 3.00) {
            return ['Perlu ditingkatkan', 'danger'];
        }

        if ($ipk < 3.50) {
            return ['Lebih baik ditingkatkan', 'warning text-dark'];
        }

        return ['Good Job, Pertahankan!', 'success'];
    }
}
