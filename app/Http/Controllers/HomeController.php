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
        // 1. Berita terbaru (10 for carousel)
        $announcements = Announcement::published()
            ->latest('publish_date')
            ->take(10)
            ->get();

        // 2. Stats counter — approved students count
        $stats = [
            'totalStudents' => User::where('role', 'student')
                ->where('status', 'approved')
                ->count(),
        ];

        // 3. Tracking stages (global admin-managed)
        $trackingStages = TrackingStage::ordered()->get();

        // 4. Form pendataan status
        $formPendataanActive = Setting::get('form_pendataan_active', '0') === '1';

        // Check if current user already submitted for this form period
        $alreadySubmitted = false;
        $resubmitAt = null;
        $rejectedNotes = null;
        $currentFormPeriod = Setting::get('form_pendataan_period', '');
        
        if (auth()->check() && auth()->user()->isStudent()) {
            $user = auth()->user();
            $resubmitAt = $user->khs_next_resubmit_at;
            
            if ($formPendataanActive) {
                $alreadySubmitted = KhsSubmission::where('user_id', $user->id)
                    ->where('form_period', $currentFormPeriod)
                    ->whereIn('status', ['pending', 'verified'])
                    ->exists();

                $currentPeriodKhs = KhsSubmission::where('user_id', $user->id)
                    ->where('form_period', $currentFormPeriod)
                    ->latest()
                    ->first();
                
                $rejectedNotes = $currentPeriodKhs && $currentPeriodKhs->status === 'rejected' ? $currentPeriodKhs->admin_notes : null;
            }
        }

        // 5. Latest SK document
        $latestDocument = Document::latest()->first();

        // 6. FAQ
        $faqs = Faq::active()->take(6)->get();

        return view('home', compact(
            'announcements',
            'stats',
            'trackingStages',
            'formPendataanActive',
            'alreadySubmitted',
            'currentFormPeriod',
            'latestDocument',
            'faqs',
            'resubmitAt',
            'rejectedNotes'
        ));
    }
}
