<?php

namespace Tests\Feature;

use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Mail\KhsRejectedMail;
use App\Mail\KhsVerifiedMail;
use App\Mail\NewAnnouncementMail;
use App\Mail\NewPeriodOpenedMail;
use App\Models\Announcement;
use App\Models\KhsSubmission;
use App\Models\User;
use App\Services\EmailNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed hanya roles (bukan seluruh seeder) agar test lebih cepat
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
    }

    /**
     * Helper: buat admin user.
     */
    private function createAdmin(): User
    {
        $admin = User::factory()->admin()->create();
        $admin->assignRole('admin');
        return $admin;
    }

    /**
     * Helper: buat announcement.
     */
    private function createAnnouncement(User $admin, string $title = 'Test Berita'): Announcement
    {
        return Announcement::create([
            'title' => $title,
            'content' => '<p>Konten berita untuk testing.</p>',
            'category' => 'lainnya',
            'publish_date' => now(),
            'is_published' => true,
            'created_by' => $admin->id,
        ]);
    }

    /**
     * Helper: buat KHS submission.
     */
    private function createSubmission(User $user, string $status = 'pending', ?string $notes = null): KhsSubmission
    {
        return KhsSubmission::create([
            'user_id' => $user->id,
            'semester' => 3,
            'ips' => 3.75,
            'ipk' => 3.60,
            'khs_file' => 'khs/test.pdf',
            'form_period' => '2025/2026 Ganjil',
            'status' => $status,
            'admin_notes' => $notes,
            'submitted_at' => now(),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // RENDER TESTS — memastikan setiap email bisa di-render
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function account_approved_mail_renders_correctly(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso']);
        $rendered = (new AccountApprovedMail($user))->render();

        $this->assertStringContainsString('Akun Anda Telah Disetujui', $rendered);
        $this->assertStringContainsString('Budi Santoso', $rendered);
        $this->assertStringContainsString('/login', $rendered);
    }

    /** @test */
    public function account_rejected_mail_renders_correctly(): void
    {
        $user = User::factory()->create(['name' => 'Dewi Lestari']);
        $rendered = (new AccountRejectedMail($user))->render();

        $this->assertStringContainsString('Pendaftaran Akun Ditolak', $rendered);
        $this->assertStringContainsString('Dewi Lestari', $rendered);
        $this->assertStringContainsString('/login', $rendered);
    }

    /** @test */
    public function new_announcement_mail_renders_with_correct_link(): void
    {
        $admin = $this->createAdmin();
        $announcement = $this->createAnnouncement($admin, 'Pencairan Dana KIP');
        $rendered = (new NewAnnouncementMail($announcement))->render();

        $this->assertStringContainsString('Berita Baru', $rendered);
        $this->assertStringContainsString('Pencairan Dana KIP', $rendered);
        $this->assertStringContainsString('/announcements/' . $announcement->id, $rendered);
    }

    /** @test */
    public function new_period_mail_renders_with_dashboard_link(): void
    {
        $rendered = (new NewPeriodOpenedMail('2025/2026 Ganjil'))->render();

        $this->assertStringContainsString('Form Pendataan KHS Telah Dibuka', $rendered);
        $this->assertStringContainsString('2025/2026 Ganjil', $rendered);
        // Harus mengarah ke /dashboard, bukan /
        $this->assertStringContainsString('/dashboard', $rendered);
    }

    /** @test */
    public function khs_verified_mail_renders_with_dashboard_link(): void
    {
        $user = User::factory()->create(['name' => 'Rina Novita']);
        $submission = $this->createSubmission($user, 'verified');
        $rendered = (new KhsVerifiedMail($submission))->render();

        $this->assertStringContainsString('KHS Anda Telah Diverifikasi', $rendered);
        $this->assertStringContainsString('Rina Novita', $rendered);
        $this->assertStringContainsString('Semester 3', $rendered);
        // Harus mengarah ke /dashboard, bukan /khs
        $this->assertStringContainsString('/dashboard', $rendered);
    }

    /** @test */
    public function khs_rejected_mail_renders_with_admin_notes(): void
    {
        $user = User::factory()->create(['name' => 'Arif Rahman']);
        $submission = $this->createSubmission($user, 'rejected', 'File KHS buram, silakan upload ulang.');
        $rendered = (new KhsRejectedMail($submission))->render();

        $this->assertStringContainsString('Pendataan KHS Ditolak', $rendered);
        $this->assertStringContainsString('Arif Rahman', $rendered);
        $this->assertStringContainsString('File KHS buram', $rendered);
        $this->assertStringContainsString('/dashboard', $rendered);
    }

    // ═══════════════════════════════════════════════════════════════
    // SUBJECT TESTS — memastikan subject line email benar
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function all_mailables_have_correct_subjects(): void
    {
        $user = User::factory()->create();
        $admin = $this->createAdmin();
        $announcement = $this->createAnnouncement($admin, 'Pencairan KIP');
        $submission = $this->createSubmission($user);

        $this->assertTrue((new AccountApprovedMail($user))->hasSubject('Akun KIP UNSIKA Anda Telah Disetujui'));
        $this->assertTrue((new AccountRejectedMail($user))->hasSubject('Pendaftaran KIP UNSIKA — Akun Ditolak'));
        $this->assertTrue((new NewAnnouncementMail($announcement))->hasSubject('Berita Baru: Pencairan KIP'));
        $this->assertTrue((new NewPeriodOpenedMail('Genap 2025/2026'))->hasSubject('Form Pendataan Dibuka: Genap 2025/2026'));
        $this->assertTrue((new KhsVerifiedMail($submission))->hasSubject('KHS Anda Telah Diverifikasi'));
        $this->assertTrue((new KhsRejectedMail($submission))->hasSubject('Pendataan KHS Ditolak'));
    }

    // ═══════════════════════════════════════════════════════════════
    // SERVICE TESTS — EmailNotificationService opt-in filter
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function announcement_email_sent_only_to_opted_in_students(): void
    {
        Mail::fake();

        User::factory()->count(3)->create(['email_opt_in' => true]);
        User::factory()->count(2)->emailOptOut()->create();

        $admin = $this->createAdmin();
        $announcement = $this->createAnnouncement($admin);

        $count = (new EmailNotificationService())->notifyNewAnnouncement($announcement);

        $this->assertEquals(3, $count);
        Mail::assertQueued(NewAnnouncementMail::class, 3);
    }

    /** @test */
    public function period_email_sent_only_to_opted_in_students(): void
    {
        Mail::fake();

        User::factory()->count(2)->create(['email_opt_in' => true]);
        User::factory()->emailOptOut()->create();

        $count = (new EmailNotificationService())->notifyNewPeriod('2025/2026 Ganjil');

        $this->assertEquals(2, $count);
        Mail::assertQueued(NewPeriodOpenedMail::class, 2);
    }

    /** @test */
    public function no_emails_sent_when_all_students_opted_out(): void
    {
        Mail::fake();

        User::factory()->count(3)->emailOptOut()->create();
        $admin = $this->createAdmin();
        $announcement = $this->createAnnouncement($admin);

        $count = (new EmailNotificationService())->notifyNewAnnouncement($announcement);

        $this->assertEquals(0, $count);
        Mail::assertNothingQueued();
    }

    /** @test */
    public function no_emails_sent_to_non_approved_students(): void
    {
        Mail::fake();

        // Mahasiswa pending yang opt-in — seharusnya TIDAK menerima email
        User::factory()->pending()->create(['email_opt_in' => true]);
        User::factory()->rejected()->create(['email_opt_in' => true]);

        $admin = $this->createAdmin();
        $announcement = $this->createAnnouncement($admin);

        $count = (new EmailNotificationService())->notifyNewAnnouncement($announcement);

        $this->assertEquals(0, $count);
        Mail::assertNothingQueued();
    }

    /** @test */
    public function admin_never_receives_broadcast_emails(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        // Tidak ada student yang opt-in
        $announcement = $this->createAnnouncement($admin);

        $count = (new EmailNotificationService())->notifyNewAnnouncement($announcement);

        $this->assertEquals(0, $count);
        Mail::assertNothingQueued();
    }
}
