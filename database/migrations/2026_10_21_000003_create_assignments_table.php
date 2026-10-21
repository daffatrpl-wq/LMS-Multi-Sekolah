<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel ini sebenarnya dikelola guru (FR-GR-03). Dibuat minimal di sini
// supaya sisi siswa bisa diuji. Lewati kalau sudah ada.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->dateTime('deadline');
            $table->boolean('izinkan_terlambat')->default(false);
            $table->unsignedSmallInteger('nilai_maks')->default(100);
            $table->timestamps();

            $table->index(['school_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
