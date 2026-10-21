<?php

namespace App\Livewire\Siswa;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Submission;
use Livewire\Component;

class DaftarTugas extends Component
{
    public function mount(): void
    {
        abort_unless(auth()->user()->role === 'student', 403);
    }

    // READ: tugas dari semua kelas yang diikuti siswa (enrollment aktif)
    public function render()
    {
        $kelasIds = Enrollment::where('user_id', auth()->id())
            ->where('status', Enrollment::AKTIF)
            ->pluck('class_id');

        $daftarTugas = Assignment::with('kelas.guru')
            ->whereIn('class_id', $kelasIds)
            ->orderBy('deadline')
            ->get();

        $pengumpulan = Submission::where('user_id', auth()->id())
            ->whereIn('assignment_id', $daftarTugas->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        return view('livewire.siswa.daftar-tugas', compact('daftarTugas', 'pengumpulan'));
    }
}
