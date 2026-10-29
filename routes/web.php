<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\TenantController;

// Perbaikan namespace Livewire (Liveware -> Livewire)
use App\Livewire\Siswa\Dashboard;
use App\Livewire\Siswa\DaftarTugas;
use App\Livewire\Siswa\JelajahiKelas;
use App\Livewire\Siswa\KelasSaya;
use App\Livewire\Siswa\KumpulkanTugas;


Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

// Dashboard: diarahkan sesuai role
Route::get('/dashboard', function () {
    return match (request()->user()->role) {
        'super_admin' => view('dashboard'),
        'student'     => redirect()->route('siswa.dashboard'),
        // TODO: school_admin & teacher -> ganti dengan redirect()->route('nama-route-modul')
        default       => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Super Admin
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::resource('tenants', TenantController::class);
});

require __DIR__.'/auth.php';

// Rute khusus Siswa (Livewire Components)
Route::middleware(['auth', 'role:student'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::view('/dashboard', 'livewire.siswa.dashboard')->name('dashboard');
    Route::get('/kelas-saya', KelasSaya::class)->name('kelas-saya');
    Route::get('/jelajahi', JelajahiKelas::class)->name('jelajahi');
    Route::get('/tugas', DaftarTugas::class)->name('tugas');
    Route::get('/tugas/{assignment}', KumpulkanTugas::class)->name('tugas.kumpul');
});

// Memuat rute modular untuk Admin Sekolah
require base_path('app/Modules/AdminSekolah/routes/web.php');