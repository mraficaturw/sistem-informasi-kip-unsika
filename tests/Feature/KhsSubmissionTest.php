<?php

namespace Tests\Feature;

use App\Models\KhsSubmission;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Black-box Testing: KHS Submission (POST /khs)
 *
 * Menguji fitur pendataan KHS dari perspektif pengguna — tanpa melihat
 * implementasi internal. Fokus pada input/output, boundary values,
 * dan skenario error.
 */
class KhsSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $this->student = User::factory()->create([
            'role'   => 'student',
            'status' => 'approved',
        ]);
        $this->student->assignRole('student');

        // Aktifkan form pendataan
        Setting::set('form_pendataan_active', '1');
        Setting::set('form_pendataan_period', 'Genap 2025/2026');

        Storage::fake('public');
    }

    // ─── BB-01: Submit sukses dengan IPS dan IPK valid ─────────────────

    /**
     * BB-01: Submit form dengan semua field valid (termasuk IPK) harus berhasil.
     */
    public function test_successful_submission_with_valid_ips_and_ipk(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 3.45,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('khs_submissions', [
            'user_id'     => $this->student->id,
            'semester'    => 5,
            'ips'         => 3.50,
            'ipk'         => 3.45,
            'status'      => 'pending',
            'form_period' => 'Genap 2025/2026',
        ]);
    }

    // ─── BB-02: Submit TANPA IPK harus gagal validasi ──────────────────

    /**
     * BB-02: Field IPK wajib diisi — submit tanpa IPK harus ditolak.
     */
    public function test_submission_fails_without_ipk(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            // 'ipk' tidak diisi
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('ipk');
        $this->assertDatabaseCount('khs_submissions', 0);
    }

    // ─── BB-03: Submit TANPA IPS harus gagal (regresi) ─────────────────

    /**
     * BB-03: Field IPS tetap wajib — memastikan penambahan IPK tidak mengganggu.
     */
    public function test_submission_fails_without_ips(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            // 'ips' tidak diisi
            'ipk'      => 3.45,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('ips');
        $this->assertDatabaseCount('khs_submissions', 0);
    }

    // ─── BB-04 s/d BB-08: Boundary Value Analysis untuk IPK ────────────

    /**
     * BB-04: IPK = 0.00 (batas bawah valid) → harus diterima.
     */
    public function test_ipk_boundary_minimum_zero_accepted(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 0.00,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionDoesntHaveErrors('ipk');
        $this->assertDatabaseHas('khs_submissions', ['ipk' => 0.00]);
    }

    /**
     * BB-05: IPK = 4.00 (batas atas valid) → harus diterima.
     */
    public function test_ipk_boundary_maximum_four_accepted(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 4.00,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionDoesntHaveErrors('ipk');
        $this->assertDatabaseHas('khs_submissions', ['ipk' => 4.00]);
    }

    /**
     * BB-06: IPK = 4.01 (melebihi batas atas) → harus ditolak.
     */
    public function test_ipk_boundary_above_maximum_rejected(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 4.01,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('ipk');
        $this->assertDatabaseCount('khs_submissions', 0);
    }

    /**
     * BB-07: IPK = -1 (negatif) → harus ditolak.
     */
    public function test_ipk_boundary_negative_rejected(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => -1,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('ipk');
        $this->assertDatabaseCount('khs_submissions', 0);
    }

    /**
     * BB-08: IPK = 'abc' (non-numeric) → harus ditolak.
     */
    public function test_ipk_non_numeric_rejected(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 'abc',
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('ipk');
    }

    // ─── BB-09: Form tidak aktif → submit ditolak ──────────────────────

    /**
     * BB-09: Submit saat form pendataan tidak aktif harus ditolak.
     */
    public function test_submission_rejected_when_form_inactive(): void
    {
        Setting::set('form_pendataan_active', '0');

        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 3.45,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('khs_submissions', 0);
    }

    // ─── BB-10: Pengisian ganda pada periode yang sama → ditolak ───────

    /**
     * BB-10: Mahasiswa tidak boleh submit dua kali pada periode yang sama.
     */
    public function test_duplicate_submission_same_period_rejected(): void
    {
        // Submit pertama
        KhsSubmission::create([
            'user_id'      => $this->student->id,
            'semester'     => 5,
            'ips'          => 3.50,
            'ipk'         => 3.45,
            'khs_file'     => 'khs/existing.pdf',
            'status'       => 'pending',
            'form_period'  => 'Genap 2025/2026',
            'submitted_at' => now(),
        ]);

        // Submit kedua pada periode yang sama → harus ditolak
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.60,
            'ipk'      => 3.55,
            'khs_file' => UploadedFile::fake()->create('khs2.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('khs_submissions', 1);
    }

    // ─── BB-11: Dashboard menampilkan IPK terakhir ─────────────────────

    /**
     * BB-11: Dashboard mahasiswa harus menampilkan IPK terakhir.
     */
    public function test_dashboard_displays_latest_ipk(): void
    {
        KhsSubmission::create([
            'user_id'      => $this->student->id,
            'semester'     => 5,
            'ips'          => 3.65,
            'ipk'         => 3.60,
            'khs_file'     => 'khs/test.pdf',
            'status'       => 'verified',
            'form_period'  => 'Ganjil 2025/2026',
            'submitted_at' => now()->subMonth(),
        ]);

        KhsSubmission::create([
            'user_id'      => $this->student->id,
            'semester'     => 6,
            'ips'          => 3.70,
            'ipk'         => 3.62,
            'khs_file'     => 'khs/test2.pdf',
            'status'       => 'pending',
            'form_period'  => 'Genap 2025/2026',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->student)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('IPK Terakhir');
        $response->assertSee('3.62');
    }

    // ─── BB-12: Guest tidak bisa submit ────────────────────────────────

    /**
     * BB-12: User yang belum login tidak boleh submit form.
     */
    public function test_guest_cannot_submit_khs(): void
    {
        $response = $this->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 3.45,
            'khs_file' => UploadedFile::fake()->create('khs.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertRedirect(route('login'));
    }

    // ─── BB-13: File bukan PDF → ditolak (regresi) ─────────────────────

    /**
     * BB-13: Upload file non-PDF tetap ditolak setelah penambahan IPK.
     */
    public function test_non_pdf_file_rejected(): void
    {
        $response = $this->actingAs($this->student)->post(route('khs.store'), [
            'semester' => 5,
            'ips'      => 3.50,
            'ipk'      => 3.45,
            'khs_file' => UploadedFile::fake()->create('khs.jpg', 1024, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('khs_file');
    }
}
