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

        $latestKhs = $user->khsSubmissions()->orderByDesc('created_at')->first();
        $currentSemester = $latestKhs ? 'Semester ' . $latestKhs->semester : '-';
        $latestIps = $latestKhs ? number_format($latestKhs->ips, 2) : '-';

        $ipsAlertMessage = '';
        $ipsAlertColor = 'success';
        if ($latestKhs) {
            if ($latestKhs->ips < 3.00) {
                $ipsAlertColor = 'danger';
                $ipsAlertMessage = 'Perlu ditingkatkan';
            } elseif ($latestKhs->ips >= 3.00 && $latestKhs->ips < 3.50) {
                $ipsAlertColor = 'warning text-dark';
                $ipsAlertMessage = 'Lebih baik ditingkatkan';
            } else {
                $ipsAlertColor = 'success';
                $ipsAlertMessage = 'Good Job, Pertahankan!';
            }
        }

        $currentFormPeriod = Setting::get('form_pendataan_period', '');
        $currentPeriodKhs = $user->khsSubmissions()->where('form_period', $currentFormPeriod)->latest()->first();

        $currentPeriodStatus = 'Belum Mengisi';
        $currentPeriodStatusColor = 'secondary';
        $currentPeriodStatusIcon = 'bi-hourglass-top';

        if ($currentPeriodKhs) {
            if ($currentPeriodKhs->status === 'verified') {
                $currentPeriodStatus = 'Disetujui';
                $currentPeriodStatusColor = 'success';
                $currentPeriodStatusIcon = 'bi-check-circle';
            } elseif ($currentPeriodKhs->status === 'rejected') {
                $currentPeriodStatus = 'Ditolak';
                $currentPeriodStatusColor = 'danger';
                $currentPeriodStatusIcon = 'bi-x-circle';
            } else {
                $currentPeriodStatus = 'Menunggu Validasi';
                $currentPeriodStatusColor = 'warning text-dark';
                $currentPeriodStatusIcon = 'bi-hourglass-split';
            }
        }

        $recentAnnouncements = Announcement::published()->latest('publish_date')->take(5)->get();

        $resubmitAt = $user->khs_next_resubmit_at;
        $rejectedNotes = $currentPeriodKhs && $currentPeriodKhs->status === 'rejected' ? $currentPeriodKhs->admin_notes : null;

        // KHS submission history for "Riwayat Form Pendataan" table
        $khsHistory = $user->khsSubmissions()
            ->orderByDesc('created_at')
            ->get();

        // Form pendataan status
        $formPendataanActive = Setting::get('form_pendataan_active', '0') === '1';

        return view('student.dashboard', compact(
            'currentSemester', 'latestIps', 'ipsAlertMessage', 'ipsAlertColor', 
            'currentPeriodStatus', 'currentPeriodStatusColor', 'currentPeriodStatusIcon',
            'recentAnnouncements', 'khsHistory',
            'formPendataanActive', 'currentFormPeriod',
            'resubmitAt', 'rejectedNotes'
        ));
    }
}
