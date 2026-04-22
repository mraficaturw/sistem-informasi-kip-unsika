<?php

namespace App\Services;

use App\Mail\NewAnnouncementMail;
use App\Mail\NewPeriodOpenedMail;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * EmailNotificationService
 *
 * Memusatkan logika pengiriman email broadcast ke mahasiswa yang opt-in.
 * Semua email dikirim melalui queue agar tidak memperlambat response admin.
 */
class EmailNotificationService
{
    /**
     * Ambil semua mahasiswa approved yang opt-in notifikasi email.
     */
    public function getOptedInStudents(): Collection
    {
        return User::where('role', 'student')
            ->where('status', 'approved')
            ->where('email_opt_in', true)
            ->get(['id', 'email', 'name']);
    }

    /**
     * Kirim email notifikasi berita baru ke semua mahasiswa yang opt-in.
     * Mengembalikan jumlah email yang di-queue.
     */
    public function notifyNewAnnouncement(Announcement $announcement): int
    {
        $students = $this->getOptedInStudents();

        foreach ($students as $student) {
            Mail::to($student->email)->queue(new NewAnnouncementMail($announcement));
        }

        Log::info('Email berita baru di-queue', [
            'announcement_id' => $announcement->id,
            'recipient_count' => $students->count(),
        ]);

        return $students->count();
    }

    /**
     * Kirim email notifikasi periode pendataan baru ke semua mahasiswa yang opt-in.
     * Mengembalikan jumlah email yang di-queue.
     */
    public function notifyNewPeriod(string $period): int
    {
        $students = $this->getOptedInStudents();

        foreach ($students as $student) {
            Mail::to($student->email)->queue(new NewPeriodOpenedMail($period));
        }

        Log::info('Email periode baru di-queue', [
            'period'          => $period,
            'recipient_count' => $students->count(),
        ]);

        return $students->count();
    }
}
