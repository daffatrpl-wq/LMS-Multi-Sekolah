<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Kelas aktif yang diikuti siswa
        $kelas = DB::table('enrollments as e')
            ->join('classes as c', 'c.id', '=', 'e.class_id')
            ->join('users as g', 'g.id', '=', 'c.guru_id')
            ->where('e.user_id', $user->id)
            ->where('e.status', 'aktif')
            ->where('c.status', 'aktif')
            ->select('c.id', 'c.nama', 'c.mata_pelajaran', 'g.name as guru')
            ->orderBy('c.nama')
            ->get();

        $classIds = $kelas->pluck('id')->all();

        // Tugas yang belum dikumpulkan dan masih bisa dikerjakan
        $belum = collect();
        if ($classIds) {
            $belum = DB::table('assignments as a')
                ->join('classes as c', 'c.id', '=', 'a.class_id')
                ->whereIn('a.class_id', $classIds)
                ->whereNotExists(function ($q) use ($user) {
                    $q->select(DB::raw(1))->from('submissions as s')
                      ->whereColumn('s.assignment_id', 'a.id')
                      ->where('s.user_id', $user->id);
                })
                ->where(function ($q) {
                    $q->where('a.deadline', '>=', now())->orWhere('a.izinkan_terlambat', true);
                })
                ->select('a.id', 'a.judul', 'a.deadline', 'c.mata_pelajaran')
                ->orderBy('a.deadline')
                ->get();
        }

        // Rata-rata nilai (dalam persen) dari tugas yang sudah dinilai
        $rata = DB::table('submissions as s')
            ->join('assignments as a', 'a.id', '=', 's.assignment_id')
            ->where('s.user_id', $user->id)
            ->whereNotNull('s.nilai')
            ->where('a.nilai_maks', '>', 0)
            ->selectRaw('AVG(s.nilai / a.nilai_maks * 100) as rata')
            ->value('rata');

        $stats = [
            ['label' => 'Mata Pelajaran',  'value' => $kelas->count(),
             'delta' => $kelas->count() ? 'Kelas yang kamu ikuti' : 'Belum mengikuti kelas',
             'icon' => 'i-book', 'tone' => 'blue'],
            ['label' => 'Tugas Aktif',     'value' => $belum->count(),
             'delta' => 'Belum dikumpulkan',
             'icon' => 'i-task', 'tone' => 'green'],
            // Belum ada tabel ujian di database
            ['label' => 'Ujian Mendatang', 'value' => 0,
             'delta' => 'Fitur ujian belum tersedia',
             'icon' => 'i-exam', 'tone' => 'orange'],
            ['label' => 'Rata-rata Nilai', 'value' => $rata !== null ? number_format($rata, 1) : '–',
             'delta' => $rata !== null ? 'Dari tugas yang sudah dinilai' : 'Belum ada nilai',
             'icon' => 'i-chart', 'tone' => 'purple'],
        ];

        $tenggat = $belum->take(5)->map(function ($t) {
            $d = Carbon::parse($t->deadline)->locale('id');

            return [
                'judul' => $t->judul,
                'mapel' => $t->mata_pelajaran,
                'waktu' => $this->labelTenggat($d),
                'warna' => $d->diffInHours(now(), false) > -24 ? '#e5484d' : '#e5a416', // <24 jam atau lewat = merah
                'url'   => route('siswa.tugas.kumpul', $t->id),
            ];
        })->values();

        return view('livewire.siswa.dashboard', compact('kelas', 'stats', 'tenggat'));
    }

    private function labelTenggat(Carbon $d): string
    {
        if ($d->isPast())     return 'Lewat · '.$d->translatedFormat('j M, H:i');
        if ($d->isToday())    return 'Hari ini, '.$d->format('H:i');
        if ($d->isTomorrow()) return 'Besok, '.$d->format('H:i');

        return $d->translatedFormat('j M Y, H:i');
    }
}