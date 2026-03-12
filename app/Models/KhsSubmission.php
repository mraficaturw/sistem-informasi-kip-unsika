<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            'ips' => 'decimal:2',
            'submitted_at' => 'datetime',
            'semester' => 'integer',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
