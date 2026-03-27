<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration ini menambahkan composite index pada tabel khs_submissions
 * untuk mempercepat query yang paling sering dijalankan sistem:
 *
 *  1. Cek apakah mahasiswa sudah submit pada periode tertentu
 *     → WHERE user_id = ? AND form_period = ?
 *
 *  2. Filter pengajuan berdasarkan user dan status
 *     → WHERE user_id = ? AND status IN (...)
 *
 * Composite index jauh lebih efisien daripada single-column index
 * untuk pola query multi-kolom seperti di atas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khs_submissions', function (Blueprint $table) {
            // Index untuk query cek duplikasi per user per periode
            // Digunakan di KhsController::store() dan HomeController::index()
            $table->index(['user_id', 'form_period'], 'khs_user_period_idx');

            // Index untuk query filter riwayat KHS per user berurut tanggal
            // Digunakan di DashboardController::index()
            $table->index(['user_id', 'created_at'], 'khs_user_createdat_idx');
        });
    }

    public function down(): void
    {
        Schema::table('khs_submissions', function (Blueprint $table) {
            // Hapus index saat migration di-rollback
            $table->dropIndex('khs_user_period_idx');
            $table->dropIndex('khs_user_createdat_idx');
        });
    }
};
