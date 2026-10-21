<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('jawaban_teks')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_nama')->nullable();
            $table->string('status', 20)->default('terkumpul'); // terkumpul | terlambat | dinilai
            $table->unsignedInteger('versi')->default(1);
            $table->dateTime('submitted_at');
            $table->unsignedSmallInteger('nilai')->nullable();   // diisi guru (FR-GR-03)
            $table->text('umpan_balik')->nullable();             // diisi guru
            $table->timestamps();

            // Satu siswa hanya punya satu pengumpulan per tugas
            $table->unique(['school_id', 'assignment_id', 'user_id']);
            $table->index(['school_id', 'user_id']);
        });

        // Riwayat versi pengumpulan (alur alternatif 3b di UC-09)
        Schema::create('submission_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('submission_id')->constrained('submissions')->cascadeOnDelete();
            $table->unsignedInteger('versi');
            $table->text('jawaban_teks')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_nama')->nullable();
            $table->dateTime('submitted_at');
            $table->timestamps();

            $table->index(['school_id', 'submission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_versions');
        Schema::dropIfExists('submissions');
    }
};
