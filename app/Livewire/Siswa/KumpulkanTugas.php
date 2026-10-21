<?php

namespace App\Livewire\Siswa;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\SubmissionVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class KumpulkanTugas extends Component
{
    use WithFileUploads;

    public Assignment $assignment;
    public ?Submission $pengumpulan = null;

    public string $jawabanTeks = '';
    public $berkas = null;

    public function mount(Assignment $assignment): void
    {
        abort_unless(auth()->user()->role === 'student', 403);

        // Prasyarat UC-09: siswa harus enrolled aktif di kelas tugas ini (include UC-08)
        $terdaftar = Enrollment::where('class_id', $assignment->class_id)
            ->where('user_id', auth()->id())
            ->where('status', Enrollment::AKTIF)
            ->exists();

        abort_unless($terdaftar, 403, 'Kamu belum terdaftar di kelas ini.');

        $this->assignment = $assignment;
        $this->muatPengumpulan();
    }

    private function muatPengumpulan(): void
    {
        $this->pengumpulan = Submission::with('versions')
            ->where('assignment_id', $this->assignment->id)
            ->where('user_id', auth()->id())
            ->first();

        $this->jawabanTeks = $this->pengumpulan?->jawaban_teks ?? '';
    }

    // CREATE (pengumpulan pertama) dan UPDATE (kumpul ulang sebelum deadline)
    public function kirim(): void
    {
        $this->validate(
            [
                'jawabanTeks' => 'nullable|string|max:10000',
                'berkas' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,zip',
            ],
            [
                'jawabanTeks.max' => 'Jawaban teks maksimal 10.000 karakter.',
                'berkas.max' => 'Ukuran berkas maksimal 10 MB.',
                'berkas.mimes' => 'Format berkas harus pdf, doc, docx, ppt, pptx, xls, xlsx, jpg, png, atau zip.',
            ]
        );

        if (blank($this->jawabanTeks) && ! $this->berkas) {
            $this->addError('berkas', 'Unggah berkas atau tulis jawaban terlebih dahulu.');
            return;
        }

        // Alur 3a: lewat deadline -> ditolak kalau guru tidak mengizinkan keterlambatan
        if (! $this->assignment->masihTerbuka()) {
            $this->addError('berkas', 'Batas waktu pengumpulan sudah lewat.');
            return;
        }

        // Pengumpulan yang sudah dinilai tidak boleh diubah lagi
        if ($this->pengumpulan?->status === Submission::DINILAI) {
            $this->addError('berkas', 'Tugas ini sudah dinilai guru dan tidak bisa diubah.');
            return;
        }

        $status = $this->assignment->sudahLewatDeadline()
            ? Submission::TERLAMBAT
            : Submission::TERKUMPUL;

        DB::transaction(function () use ($status) {
            $path = null;
            $nama = null;

            if ($this->berkas) {
                $nama = $this->berkas->getClientOriginalName();
                $path = $this->berkas->store(
                    'tugas/' . auth()->user()->school_id . '/' . $this->assignment->id,
                    'local'
                );
            }

            if ($this->pengumpulan) {
                // Alur 3b: simpan versi lama ke riwayat, lalu ganti isinya
                SubmissionVersion::create([
                    'submission_id' => $this->pengumpulan->id,
                    'versi' => $this->pengumpulan->versi,
                    'jawaban_teks' => $this->pengumpulan->jawaban_teks,
                    'file_path' => $this->pengumpulan->file_path,
                    'file_nama' => $this->pengumpulan->file_nama,
                    'submitted_at' => $this->pengumpulan->submitted_at,
                ]);

                $this->pengumpulan->update([
                    'jawaban_teks' => $this->jawabanTeks ?: null,
                    // Kalau siswa hanya mengubah teks, berkas lama dipertahankan
                    'file_path' => $path ?? $this->pengumpulan->file_path,
                    'file_nama' => $nama ?? $this->pengumpulan->file_nama,
                    'status' => $status,
                    'versi' => $this->pengumpulan->versi + 1,
                    'submitted_at' => now(),
                ]);
            } else {
                Submission::create([
                    'assignment_id' => $this->assignment->id,
                    'user_id' => auth()->id(),
                    'jawaban_teks' => $this->jawabanTeks ?: null,
                    'file_path' => $path,
                    'file_nama' => $nama,
                    'status' => $status,
                    'versi' => 1,
                    'submitted_at' => now(),
                ]);
            }
        });

        $this->reset('berkas');
        $this->muatPengumpulan();

        session()->flash('success', $status === Submission::TERLAMBAT
            ? 'Tugas terkumpul dengan status terlambat.'
            : 'Tugas berhasil dikumpulkan.');
    }

    // Unduh berkas milik siswa sendiri (disimpan di disk privat, bukan folder publik)
    public function unduh()
    {
        abort_unless($this->pengumpulan?->file_path, 404);

        return Storage::disk('local')->download(
            $this->pengumpulan->file_path,
            $this->pengumpulan->file_nama
        );
    }

    public function render()
    {
        return view('livewire.siswa.kumpulkan-tugas', [
            'bisaKumpul' => $this->assignment->masihTerbuka()
                && $this->pengumpulan?->status !== Submission::DINILAI,
        ]);
    }
}
