<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AdminSekolah\Http\Livewire\Dashboard;
use App\Modules\AdminSekolah\Http\Livewire\ManageUsers;

Route::middleware(['auth', 'role:admin,school_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', ManageUsers::class)->name('users.index');
});