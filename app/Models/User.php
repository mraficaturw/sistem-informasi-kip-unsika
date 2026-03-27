<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'npm',
        'name',
        'email',
        'faculty',
        'study_program',
        'cohort',
        'role',
        'status',
        'password',
        'khs_rejection_count',
        'khs_next_resubmit_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'khs_next_resubmit_at' => 'datetime',
        ];
    }

    // ─── Akses Panel Filament ───────────────────────────────────

    /**
     * Hanya user dengan role admin yang boleh mengakses panel Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    // ─── Helper Role & Status ───────────────────────────────────

    /**
     * Periksa apakah akun mahasiswa sudah disetujui admin.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Periksa apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Periksa apakah user adalah mahasiswa.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Periksa apakah mahasiswa sedang dalam masa tunggu (cooldown) pengisian ulang KHS.
     * Cooldown diterapkan setelah pengajuan KHS ditolak beberapa kali.
     */
    public function isInKhsCooldown(): bool
    {
        return $this->khs_next_resubmit_at !== null && now()->lessThan($this->khs_next_resubmit_at);
    }

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Mahasiswa dapat memiliki banyak pengajuan KHS.
     */
    public function khsSubmissions(): HasMany
    {
        return $this->hasMany(KhsSubmission::class);
    }

    /**
     * Admin dapat membuat banyak pengumuman/berita.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }
}
