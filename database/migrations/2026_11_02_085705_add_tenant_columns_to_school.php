<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel schools sudah punya: name, address, contact_email, status, max_users, max_classes.
     * Form "Tambah Sekolah" juga menyimpan nomor telepon, jadi hanya kolom phone yang ditambah.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('schools', 'phone')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->string('phone', 20)->nullable()->after('contact_email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('schools', 'phone')) {
            Schema::table('schools', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }
    }
};