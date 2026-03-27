<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tambah Index Performa Database
 *
 * Menambahkan index pada kolom yang sering dipakai di klausa WHERE/ORDER BY:
 *  - khs_submissions: composite index (user_id, form_period)
 *    → mempercepat query scope active() + forPeriod() di KhsController & HomeController
 *  - announcements: composite index (is_published, publish_date)
 *    → mempercepat query scope published() + latest() yang dipanggil di setiap halaman
 *
 * Index ini tidak mengubah data atau logika apapun — hanya meningkatkan kecepatan query.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khs_submissions', function (Blueprint $table): void {
            // Composite index: pengecekan duplikasi & aktif per pengguna & periode
            $table->index(['user_id', 'form_period'], 'idx_khs_user_period');
        });

        Schema::table('announcements', function (Blueprint $table): void {
            // Composite index: filter berita published + urutkan berdasarkan tanggal
            $table->index(['is_published', 'publish_date'], 'idx_announcements_published_date');
        });
    }

    public function down(): void
    {
        Schema::table('khs_submissions', function (Blueprint $table): void {
            $table->dropIndex('idx_khs_user_period');
        });

        Schema::table('announcements', function (Blueprint $table): void {
            $table->dropIndex('idx_announcements_published_date');
        });
    }
};
