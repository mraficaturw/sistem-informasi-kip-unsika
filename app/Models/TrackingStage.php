<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TrackingStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'status',
        'sort_order',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date'       => 'date',
            'sort_order' => 'integer',
        ];
    }

    // ─── Scopes ─────────────────────────────────────────────────

    /**
     * Urutkan tahap tracking berdasarkan sort_order secara ascending.
     * Scope ini memastikan urutan tampil sesuai urutan yang ditentukan admin.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
