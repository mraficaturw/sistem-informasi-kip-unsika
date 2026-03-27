<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class KhsSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'semester',
        'ips',
        'khs_file',
        'status',
        'admin_notes',
        'form_period',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'ips'          => 'decimal:2',
            'submitted_at' => 'datetime',
            'semester'     => 'integer',
        ];
    }

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Setiap pengajuan KHS dimiliki oleh satu mahasiswa (user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filter pengajuan yang masih menunggu verifikasi admin.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Filter pengajuan yang sudah diverifikasi / disetujui admin.
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('status', 'verified');
    }

    /**
     * Filter pengajuan yang ditolak oleh admin.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Filter pengajuan berdasarkan periode form pendataan.
     */
    public function scopeForPeriod(Builder $query, string $period): Builder
    {
        return $query->where('form_period', $period);
    }

    /**
     * Filter pengajuan yang masih aktif (pending atau verified),
     * digunakan untuk mengecek apakah mahasiswa sudah mengisi di periode ini.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'verified']);
    }
}
