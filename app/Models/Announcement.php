<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'cover_image',
        'category',
        'publish_date',
        'created_by',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'publish_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    // ─── Relationships ──────────────────────────────────────────

    /**
     * Setiap berita/pengumuman dibuat oleh satu admin (user).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Filter berita yang sudah dipublikasikan dan tanggal publish-nya sudah lewat.
     * Digunakan untuk menampilkan berita yang layak ditampilkan ke publik.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                     ->where('publish_date', '<=', now());
    }

    /**
     * Filter berita berdasarkan kategori tertentu.
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
