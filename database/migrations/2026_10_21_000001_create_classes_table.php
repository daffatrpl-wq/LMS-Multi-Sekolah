<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Lewati file ini kalau tabel classes sudah ada di project.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->string('mata_pelajaran');
            $table->text('deskripsi')->nullable();
            $table->string('kode_undangan', 8)->unique();
            $table->boolean('perlu_persetujuan')->default(false);
            $table->string('status', 20)->default('aktif'); // aktif | nonaktif
            $table->timestamps();

            $table->index(['school_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
