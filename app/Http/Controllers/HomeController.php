<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Document;
use App\Models\Faq;
use App\Models\KhsSubmission;
use App\Models\Setting;
use App\Models\TrackingStage;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // ── 1. Ambil setting dari DB satu kali (tiap key di-cache otomatis) ──
        $formPendataanActive = Setting::get('form_pendataan_active', '0') === '1';
        $currentFormPeriod   = Setting::get('form_pendataan_period', '');

        // ── 2. Berita terbaru untuk carousel (maksimal 10) ─────────────────
        $announcements = Announcement::published()
            ->latest('publish_date')
            ->take(10)
            ->get();

        // ── 3. Jumlah mahasiswa yang sudah disetujui ───────────────────────
        $stats = [
            'totalStudents' => User::where('role', 'student')
                ->where('status', 'approved')
                ->count(),
        ];

        // ── 4. Tahap tracking pencairan dana ───────────────────────────────
        $trackingStages = TrackingStage::ordered()->get();

        // ── 5. Data khusus mahasiswa yang sedang login ─────────────────────
        $alreadySubmitted = false;
        $resubmitAt       = null;
        $rejectedNotes    = null;

        if (auth()->check() && auth()->user()->isStudent()) {
            $user = auth()->user();

            // Ambil data cooldown resubmit (jika pernah ditolak)
            $resubmitAt = $user->khs_next_resubmit_at;

            if ($formPendataanActive) {
                // Satu query untuk mendapatkan pengajuan KHS pada periode ini
                // Diurutkan terbaru, sehingga bisa dipakai untuk cek status & catatan
                $currentPeriodKhs = KhsSubmission::where('user_id', $user->id)
                    ->forPeriod($currentFormPeriod)
                    ->latest()
                    ->first();

                // Mahasiswa dianggap sudah mengisi jika ada submission aktif (pending/verified)
                $alreadySubmitted = $currentPeriodKhs
                    && in_array($currentPeriodKhs->status, ['pending', 'verified']);

                // Tampilkan catatan penolakan hanya jika status terakhir adalah rejected
                $rejectedNotes = ($currentPeriodKhs && $currentPeriodKhs->status === 'rejected')
                    ? $currentPeriodKhs->admin_notes
                    : null;
            }
        }

        // ── 6. Dokumen SK per angkatan ─────────────────────────────────────
        $skDocuments = Document::whereNotNull('angkatan')->orderBy('angkatan')->get();

        // ── 7. FAQ aktif (maksimal 6) ──────────────────────────────────────
        $faqs = Faq::active()->take(6)->get();

        return view('home', compact(
            'announcements',
            'stats',
            'trackingStages',
            'formPendataanActive',
            'alreadySubmitted',
            'currentFormPeriod',
            'skDocuments',
            'faqs',
            'resubmitAt',
            'rejectedNotes'
        ));
    }
}
