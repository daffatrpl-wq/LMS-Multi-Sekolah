<?php

namespace App\Modules\AdminSekolah\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class ManageUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';

    // Reset halaman saat kotak pencarian diubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Contoh query mengambil data user yang terikat dengan sekolah/tenant admin
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->latest()
            ->paginate(10);

        return view('modules.admin_sekolah.ManageUsers', [
            'users' => $users
        ]);
    }
}