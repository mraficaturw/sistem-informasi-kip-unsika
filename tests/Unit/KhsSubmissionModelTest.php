<?php

namespace Tests\Unit;

use App\Models\KhsSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * White-box Testing: KhsSubmission Model
 *
 * Memverifikasi bahwa kolom IPK terintegrasi dengan benar di level model,
 * termasuk fillable, cast, dan scopes yang sudah ada tetap berfungsi.
 */
class KhsSubmissionModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * WB-M-01: Kolom 'ipk' harus termasuk dalam $fillable.
     */
    public function test_ipk_is_fillable(): void
    {
        $model = new KhsSubmission();

        $this->assertContains('ipk', $model->getFillable());
    }

    /**
     * WB-M-02: Kolom 'ips' harus tetap ada di $fillable (regresi).
     */
    public function test_ips_is_still_fillable(): void
    {
        $model = new KhsSubmission();

        $this->assertContains('ips', $model->getFillable());
    }

    /**
     * WB-M-03: Cast 'ipk' harus bertipe 'decimal:2'.
     */
    public function test_ipk_cast_is_decimal_2(): void
    {
        $model = new KhsSubmission();
        $casts = $model->getCasts();

        $this->assertArrayHasKey('ipk', $casts);
        $this->assertEquals('decimal:2', $casts['ipk']);
    }

    /**
     * WB-M-04: IPK harus tersimpan dan terbaca dengan presisi 2 desimal.
     */
    public function test_ipk_stored_with_correct_precision(): void
    {
        $user = User::factory()->create(['role' => 'student', 'status' => 'approved']);

        $submission = KhsSubmission::create([
            'user_id'      => $user->id,
            'semester'     => 5,
            'ips'          => 3.67,
            'ipk'          => 3.45,
            'khs_file'     => 'khs/test.pdf',
            'status'       => 'pending',
            'form_period'  => 'Genap 2025/2026',
            'submitted_at' => now(),
        ]);

        $submission->refresh();

        $this->assertEquals('3.45', $submission->ipk);
        $this->assertEquals('3.67', $submission->ips);
    }

    /**
     * WB-M-05: Scope 'active' masih berfungsi setelah perubahan (regresi).
     */
    public function test_active_scope_still_works(): void
    {
        $user = User::factory()->create(['role' => 'student', 'status' => 'approved']);

        KhsSubmission::create([
            'user_id' => $user->id, 'semester' => 5,
            'ips' => 3.50, 'ipk' => 3.40, 'khs_file' => 'khs/a.pdf',
            'status' => 'pending', 'form_period' => 'Genap 2025/2026', 'submitted_at' => now(),
        ]);

        KhsSubmission::create([
            'user_id' => $user->id, 'semester' => 4,
            'ips' => 3.20, 'ipk' => 3.10, 'khs_file' => 'khs/b.pdf',
            'status' => 'rejected', 'form_period' => 'Ganjil 2025/2026', 'submitted_at' => now(),
        ]);

        // Scope active hanya mengembalikan 'pending' dan 'verified', bukan 'rejected'
        $activeCount = KhsSubmission::where('user_id', $user->id)->active()->count();
        $this->assertEquals(1, $activeCount);
    }

    /**
     * WB-M-06: Scope 'forPeriod' masih berfungsi setelah perubahan (regresi).
     */
    public function test_for_period_scope_still_works(): void
    {
        $user = User::factory()->create(['role' => 'student', 'status' => 'approved']);

        KhsSubmission::create([
            'user_id' => $user->id, 'semester' => 5,
            'ips' => 3.50, 'ipk' => 3.40, 'khs_file' => 'khs/a.pdf',
            'status' => 'verified', 'form_period' => 'Genap 2025/2026', 'submitted_at' => now(),
        ]);

        $result = KhsSubmission::forPeriod('Genap 2025/2026')->count();
        $this->assertEquals(1, $result);

        $result = KhsSubmission::forPeriod('Ganjil 2024/2025')->count();
        $this->assertEquals(0, $result);
    }
}
