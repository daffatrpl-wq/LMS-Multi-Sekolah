<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AdminSekolah\Http\Livewire\Dashboard;

// Dashboard Admin Sekolah (komponen Livewire, data dari database)
Route::middleware(['auth', 'verified', 'role:school_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    });

// Alamat lama diarahkan ke dashboard baru
Route::redirect('/admin-sekolah/dashboard', '/admin/dashboard')
    ->name('admin-sekolah.dashboard');