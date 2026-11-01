<?php

namespace App\Modules\AdminSekolah\Http\Livewire;

use Livewire\Component;
use App\Models\User;
// Jika Anda punya model Kelas, import modelnya di sini:
// use App\Modules\AdminSekolah\Models\Kelas; // atau App\Models\Kelas

class Dashboard extends Component
{
    public function render()
    {
        // Menghitung statistik data secara dinamis dari database
        $totalSiswa = User::where('role', 'student')->count();
        $totalGuru = User::where('role', 'teacher')->count();
        $totalAdmin = User::where('role', 'admin_sekolah')->orWhere('role', 'admin')->orWhere('role', 'school_admin')->count();
        
        // Tambahkan perhitungan total kelas jika modelnya sudah ada
        // $totalKelas = \App\Models\Kelas::count(); 
        $totalKelas = 0; // Ubah nilai ini atau gunakan Model Kelas asli Anda jika sudah ada

        // Mengambil daftar pengguna terbaru untuk tabel ringkasan di dashboard
        $recentUsers = User::latest()->take(5)->get();

        return view('modules.admin_sekolah.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalGuru' => $totalGuru,
            'totalAdmin' => $totalAdmin,
            'totalKelas' => $totalKelas, // Variabel yang tadinya dicari oleh view
            'recentUsers' => $recentUsers,
            'kehadiranHariIni' => $kehadiranHariIni ?? 0, // Pastikan variabel ini ada atau set default
        ]);
    }
}