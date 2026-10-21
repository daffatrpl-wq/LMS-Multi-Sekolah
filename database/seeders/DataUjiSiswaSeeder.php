<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Data uji untuk halaman siswa (FR-SW-01 dan FR-SW-03).
// Aman dijalankan berulang kali: data yang sudah ada tidak digandakan.
class DataUjiSiswaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. Sekolah
        DB::table('schools')->updateOrInsert(
            ['name' => 'SMA Contoh 1'],
            [
                'address' => 'Jl. Contoh No. 1, Banyuwangi',
                'contact_email' => 'admin@sma-contoh1.test',
                'status' => 'active',
                'max_users' => 100,
                'max_classes' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        $schoolId = DB::table('schools')->where('name', 'SMA Contoh 1')->value('id');

        // 2. Pengguna: satu guru dan satu siswa (password: password)
        $password = Hash::make('password');

        DB::table('users')->updateOrInsert(
            ['email' => 'guru@contoh.test'],
            [
                'name' => 'Pak Guru Contoh',
                'password' => $password,
                'email_verified_at' => $now,
                'school_id' => $schoolId,
                'role' => 'teacher',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        DB::table('users')->updateOrInsert(
            ['email' => 'siswa@contoh.test'],
            [
                'name' => 'Siswa Contoh',
                'password' => $password,
                'email_verified_at' => $now,
                'school_id' => $schoolId,
                'role' => 'student',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        $guruId = DB::table('users')->where('email', 'guru@contoh.test')->value('id');

        // 3. Dua kelas: satu langsung aktif, satu butuh persetujuan guru
        DB::table('classes')->updateOrInsert(
            ['kode_undangan' => 'KELAS001'],
            [
                'school_id' => $schoolId,
                'guru_id' => $guruId,
                'nama' => 'Matematika X-A',
                'mata_pelajaran' => 'Matematika',
                'deskripsi' => 'Kelas uji tanpa persetujuan guru.',
                'perlu_persetujuan' => false,
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        DB::table('classes')->updateOrInsert(
            ['kode_undangan' => 'KELAS002'],
            [
                'school_id' => $schoolId,
                'guru_id' => $guruId,
                'nama' => 'Bahasa Indonesia X-A',
                'mata_pelajaran' => 'Bahasa Indonesia',
                'deskripsi' => 'Kelas uji yang butuh persetujuan guru.',
                'perlu_persetujuan' => true,
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
        $kelasId = DB::table('classes')->where('kode_undangan', 'KELAS001')->value('id');

        // 4. Tiga tugas di kelas pertama untuk menguji tiap kondisi deadline
        $tugas = [
            ['judul' => 'Tugas 1 - Deadline masih lama', 'deadline' => $now->copy()->addDays(3), 'izinkan_terlambat' => false],
            ['judul' => 'Tugas 2 - Lewat deadline, boleh terlambat', 'deadline' => $now->copy()->subDay(), 'izinkan_terlambat' => true],
            ['judul' => 'Tugas 3 - Lewat deadline, ditutup', 'deadline' => $now->copy()->subDays(2), 'izinkan_terlambat' => false],
        ];

        foreach ($tugas as $t) {
            DB::table('assignments')->updateOrInsert(
                ['class_id' => $kelasId, 'judul' => $t['judul']],
                [
                    'school_id' => $schoolId,
                    'deskripsi' => 'Tugas uji untuk mencoba pengumpulan.',
                    'deadline' => $t['deadline'],
                    'izinkan_terlambat' => $t['izinkan_terlambat'],
                    'nilai_maks' => 100,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
