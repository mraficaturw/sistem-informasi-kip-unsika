<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
    }

    // ═══════════════════════════════════════════════════════════════
    // FORM ACCESSIBILITY
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function forgot_password_page_is_accessible(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
        $response->assertSee('Lupa Password');
    }

    /** @test */
    public function reset_password_form_is_accessible_with_token(): void
    {
        $response = $this->get(route('password.reset', [
            'token' => 'dummy-token',
            'email' => 'test@example.com',
        ]));

        $response->assertOk();
        $response->assertSee('Reset Password');
    }

    // ═══════════════════════════════════════════════════════════════
    // SEND RESET LINK
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function reset_link_sent_for_registered_email(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $response = $this->post(route('password.email'), ['email' => $user->email]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function reset_link_not_sent_for_unregistered_email(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        Notification::assertNothingSent();
    }

    /** @test */
    public function reset_link_requires_valid_email_format(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'bukan-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function reset_link_requires_email_field(): void
    {
        $response = $this->post(route('password.email'), ['email' => '']);

        $response->assertSessionHasErrors('email');
    }

    // ═══════════════════════════════════════════════════════════════
    // RESET PASSWORD ACTION
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        // Request reset link
        $this->post(route('password.email'), ['email' => $user->email]);

        // Extract token & reset
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post(route('password.update'), [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'NewResetPass123',
                'password_confirmation' => 'NewResetPass123',
            ]);

            $response->assertRedirect(route('login'));
            $response->assertSessionHas('success');

            // Verify password changed
            $user->refresh();
            $this->assertTrue(Hash::check('NewResetPass123', $user->password));

            return true;
        });
    }

    /** @test */
    public function reset_fails_with_invalid_token(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('password.update'), [
            'token'                 => 'invalid-token-12345',
            'email'                 => $user->email,
            'password'              => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function reset_fails_when_password_too_short(): void
    {
        $response = $this->post(route('password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'test@example.com',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function reset_fails_when_confirmation_mismatch(): void
    {
        $response = $this->post(route('password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'test@example.com',
            'password'              => 'NewPassword123',
            'password_confirmation' => 'DifferentPassword456',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function reset_fails_when_password_is_only_spaces(): void
    {
        $response = $this->post(route('password.update'), [
            'token'                 => 'some-token',
            'email'                 => 'test@example.com',
            'password'              => '        ',
            'password_confirmation' => '        ',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ═══════════════════════════════════════════════════════════════
    // AUTH GUARD — logged-in users
    // ═══════════════════════════════════════════════════════════════

    /** @test */
    public function authenticated_user_is_redirected_from_forgot_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('password.request'));

        // Guest middleware redirects authenticated users
        $response->assertRedirect();
    }
}
