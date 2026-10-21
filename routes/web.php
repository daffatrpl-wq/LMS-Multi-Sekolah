<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Livewire\Siswa\DaftarTugas;
use App\Livewire\Siswa\JelajahiKelas;
use App\Livewire\Siswa\KelasSaya;
use App\Livewire\Siswa\KumpulkanTugas;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::resource('tenants', TenantController::class);
});

require __DIR__.'/auth.php';

// JANGAN disalin sebagai file. Tempel isinya ke routes/web.php

Route::middleware(['auth'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/kelas-saya', KelasSaya::class)->name('kelas-saya');
    Route::get('/jelajahi', JelajahiKelas::class)->name('jelajahi');
    Route::get('/tugas', DaftarTugas::class)->name('tugas');
    Route::get('/tugas/{assignment}', KumpulkanTugas::class)->name('tugas.kumpul');
});
