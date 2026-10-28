<?php

namespace App\Modules\AdminSekolah\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
  public function render()
    {
        return view('modules.admin_sekolah.dashboard', [
            'totalSiswa' => 450,
            'totalGuru' => 35,
            'totalKelas' => 15,
            'kehadiranHariIni' => '95%',
        ]);
    }
}