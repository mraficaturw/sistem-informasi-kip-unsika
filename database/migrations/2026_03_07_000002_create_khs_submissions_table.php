<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khs_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->decimal('ips', 3, 2);
            $table->decimal('ipk', 3, 2);
            $table->string('khs_file');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('form_period')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('status');
            $table->index('form_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khs_submissions');
    }
};
