<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AdminSekolah\Http\Livewire\Dashboard;
use App\Modules\AdminSekolah\Http\Livewire\ManageUsers;

Route::middleware(['auth', 'role:admin,school_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:school_admin'])
    ->prefix('admin-sekolah')
    ->name('admin-sekolah.')
    ->group(function () {
        Route::view('/dashboard', 'admin_sekolah.dashboard')
            ->name('dashboard');
    });
