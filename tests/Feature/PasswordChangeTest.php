<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // Buat mahasiswa approved dengan password 'Password123'
        $this->student = User::factory()->create([
            'password' => Hash::make('Password123'),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // HAPPY PATH — password berhasil diubah
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function student_can_change_password_with_valid_input(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'NewPassword456',
            'password_confirmation' => 'NewPassword456',
        ]);

        $response->assertRedirect(route('settings'));
        $response->assertSessionHas('success', 'Password berhasil diubah!');

        // Verifikasi password benar-benar berubah di database
        $this->student->refresh();
        $this->assertTrue(Hash::check('NewPassword456', $this->student->password));
        $this->assertFalse(Hash::check('Password123', $this->student->password));
    }

    /** @test */
    public function student_can_login_with_new_password_after_change(): void
    {
        // Ganti password
        $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'BrandNewPass99',
            'password_confirmation' => 'BrandNewPass99',
        ]);

        // Verifikasi password baru tersimpan di database
        $this->student->refresh();
        $this->assertTrue(Hash::check('BrandNewPass99', $this->student->password));

        // Verifikasi password lama sudah tidak bisa digunakan
        $this->assertFalse(Hash::check('Password123', $this->student->password));

        // Verifikasi credential baru valid melalui Auth facade
        $this->assertTrue(
            auth()->validate([
                'email' => $this->student->email,
                'password' => 'BrandNewPass99',
            ])
        );
    }

    /** @test */
    public function old_password_no_longer_works_after_change(): void
    {
        // Ganti password
        $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'NewPassword456',
            'password_confirmation' => 'NewPassword456',
        ]);

        // Logout
        $this->post(route('logout'));

        // Login dengan password lama — harus gagal
        $response = $this->post(route('login'), [
            'email'    => $this->student->email,
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ═══════════════════════════════════════════════════════════════
    // EDGE CASE: PASSWORD LAMA SALAH
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function fails_when_current_password_is_wrong(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'WrongPassword',
            'password'              => 'NewPassword456',
            'password_confirmation' => 'NewPassword456',
        ]);

        $response->assertSessionHasErrors('current_password');

        // Password TIDAK boleh berubah
        $this->student->refresh();
        $this->assertTrue(Hash::check('Password123', $this->student->password));
    }

    // ═══════════════════════════════════════════════════════════════
    // EDGE CASE: PASSWORD BARU TERLALU PENDEK
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function fails_when_new_password_is_less_than_8_characters(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'Short1',
            'password_confirmation' => 'Short1',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function fails_when_new_password_is_exactly_7_characters(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'Abcde12',
            'password_confirmation' => 'Abcde12',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function succeeds_when_new_password_is_exactly_8_characters(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'Abcdef12',
            'password_confirmation' => 'Abcdef12',
        ]);

        $response->assertSessionDoesntHaveErrors('password');
        $response->assertRedirect(route('settings'));
    }

    // ═══════════════════════════════════════════════════════════════
    // EDGE CASE: KONFIRMASI TIDAK COCOK
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function fails_when_confirmation_does_not_match(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'NewPassword456',
            'password_confirmation' => 'DifferentPass789',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function fails_when_confirmation_is_empty(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'NewPassword456',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ═══════════════════════════════════════════════════════════════
    // EDGE CASE: PASSWORD BARU = PASSWORD LAMA
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function fails_when_new_password_equals_current_password(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ═══════════════════════════════════════════════════════════════
    // EDGE CASE: PASSWORD HANYA SPASI / WHITESPACE
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function fails_when_password_is_only_spaces(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => '        ',  // 8 spasi
            'password_confirmation' => '        ',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function fails_when_password_is_mixed_whitespace(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => "  \t  \t  \t",
            'password_confirmation' => "  \t  \t  \t",
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ═══════════════════════════════════════════════════════════════
    // EDGE CASE: FIELD KOSONG / MISSING
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function fails_when_all_fields_are_empty(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password'      => '',
            'password'              => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['current_password', 'password']);
    }

    /** @test */
    public function fails_when_current_password_is_missing(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'password'              => 'NewPassword456',
            'password_confirmation' => 'NewPassword456',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    /** @test */
    public function fails_when_new_password_is_missing(): void
    {
        $response = $this->actingAs($this->student)->post(route('settings.password'), [
            'current_password' => 'Password123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ═══════════════════════════════════════════════════════════════
    // AUTH GUARD — unauthenticated user
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function unauthenticated_user_cannot_change_password(): void
    {
        $response = $this->post(route('settings.password'), [
            'current_password'      => 'Password123',
            'password'              => 'NewPassword456',
            'password_confirmation' => 'NewPassword456',
        ]);

        $response->assertRedirect(route('login'));
    }

    // ═══════════════════════════════════════════════════════════════
    // PAGE ACCESSIBILITY
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function settings_page_is_accessible_for_approved_student(): void
    {
        $response = $this->actingAs($this->student)->get(route('settings'));

        $response->assertOk();
        $response->assertSee('Ubah Password');
    }

    /** @test */
    public function pending_student_cannot_access_settings(): void
    {
        $pending = User::factory()->pending()->create();

        $response = $this->actingAs($pending)->get(route('settings'));

        // Middleware EnsureApprovedStudent redirects to verification status
        $response->assertRedirect(route('verification.status'));
    }
}
