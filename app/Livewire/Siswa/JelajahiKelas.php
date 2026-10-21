<?php

namespace App\Livewire\Siswa;

use App\Models\Enrollment;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;

class JelajahiKelas extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kodeUndangan = '';

    public function mount(): void
    {
        abort_unless(auth()->user()->role === 'student', 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // CREATE: gabung lewat tombol "Ikuti Kelas"
    public function gabung(int $kelasId): void
    {
        $kelas = Kelas::aktif()->findOrFail($kelasId);
        $this->daftar($kelas);
    }

    // CREATE: gabung lewat kode undangan
    public function gabungDenganKode(): void
    {
        $this->validate(
            ['kodeUndangan' => 'required|string|size:8'],
            [
                'kodeUndangan.required' => 'Kode undangan wajib diisi.',
                'kodeUndangan.size' => 'Kode undangan terdiri dari 8 karakter.',
            ]
        );

        $kelas = Kelas::aktif()
            ->where('kode_undangan', strtoupper($this->kodeUndangan))
            ->first();

        if (! $kelas) {
            $this->addError('kodeUndangan', 'Kode undangan tidak ditemukan atau kelas sudah nonaktif.');
            return;
        }

        $this->daftar($kelas);
        $this->reset('kodeUndangan');
    }

    private function daftar(Kelas $kelas): void
    {
        $enrollment = Enrollment::where('class_id', $kelas->id)
            ->where('user_id', auth()->id())
            ->first();

        // Alur 4b: sudah terdaftar / menunggu -> jangan buat enrollment ganda
        if ($enrollment && in_array($enrollment->status, [Enrollment::AKTIF, Enrollment::MENUNGGU])) {
            session()->flash('info', 'Kamu sudah terdaftar atau sedang menunggu persetujuan di kelas ini.');
            return;
        }

        // Alur 4a: kelas butuh persetujuan guru -> status menunggu
        $status = $kelas->perlu_persetujuan ? Enrollment::MENUNGGU : Enrollment::AKTIF;

        if ($enrollment) {
            // UPDATE: siswa yang sebelumnya keluar bergabung lagi
            $enrollment->update(['status' => $status]);
        } else {
            Enrollment::create([
                'class_id' => $kelas->id,
                'user_id' => auth()->id(),
                'status' => $status,
            ]);
        }

        session()->flash('success', $status === Enrollment::MENUNGGU
            ? 'Permintaan bergabung terkirim, menunggu persetujuan guru.'
            : 'Berhasil bergabung ke kelas ' . $kelas->nama . '.');
    }

    // READ: katalog kelas aktif lintas guru dalam satu tenant
    public function render()
    {
        $daftarKelas = Kelas::aktif()
            ->with('guru')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', "%{$this->search}%")
                        ->orWhere('mata_pelajaran', 'like', "%{$this->search}%")
                        ->orWhereHas('guru', fn ($g) => $g->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->paginate(10);

        $statusSaya = Enrollment::where('user_id', auth()->id())
            ->pluck('status', 'class_id');

        return view('livewire.siswa.jelajahi-kelas', compact('daftarKelas', 'statusSaya'));
    }
}
