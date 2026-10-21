<?php

namespace App\Livewire\Siswa;

use App\Models\Enrollment;
use Livewire\Component;

class KelasSaya extends Component
{
    public function mount(): void
    {
        abort_unless(auth()->user()->role === 'student', 403);
    }

    // DELETE: keluar dari kelas / batalkan permintaan bergabung
    // Status diubah ke 'keluar' (bukan hapus baris) agar riwayat presensi dan nilai tetap utuh
    public function keluar(int $enrollmentId): void
    {
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->whereIn('status', [Enrollment::AKTIF, Enrollment::MENUNGGU])
            ->findOrFail($enrollmentId);

        $enrollment->update(['status' => Enrollment::KELUAR]);

        session()->flash('success', 'Kamu sudah keluar dari kelas tersebut.');
    }

    // READ: semua kelas yang diikuti dalam satu daftar
    public function render()
    {
        $daftarEnrollment = Enrollment::with('kelas.guru')
            ->where('user_id', auth()->id())
            ->whereIn('status', [Enrollment::AKTIF, Enrollment::MENUNGGU])
            ->latest()
            ->get();

        return view('livewire.siswa.kelas-saya', compact('daftarEnrollment'));
    }
}
