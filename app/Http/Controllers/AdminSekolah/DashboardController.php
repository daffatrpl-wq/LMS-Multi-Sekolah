<?php

namespace App\Http\Controllers\AdminSekolah;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user     = $request->user();
        $schoolId = $user->school_id;

        abort_if(! $schoolId, 403, 'Akun admin belum terhubung ke sebuah sekolah.');

        $sekolah   = DB::table('schools')->where('id', $schoolId)->value('name');
        $awalBulan = now()->startOfMonth();

        // ---- Kartu statistik ----
        $hitungUser = function (string $role) use ($schoolId, $awalBulan) {
            $q = DB::table('users')->where('school_id', $schoolId)->where('role', $role);

            return [(clone $q)->count(), (clone $q)->where('created_at', '>=', $awalBulan)->count()];
        };
        [$guru, $guruBaru]   = $hitungUser('teacher');
        [$siswa, $siswaBaru] = $hitungUser('student');

        $kelasQ     = DB::table('classes')->where('school_id', $schoolId)->where('status', 'aktif');
        $kelasTotal = (clone $kelasQ)->count();
        $kelasBaru  = (clone $kelasQ)->where('created_at', '>=', $awalBulan)->count();

        $stats = [
            ['label' => 'Total Guru',  'value' => $guru,       'delta' => '+'.$guruBaru.' bulan ini',  'icon' => 'i-person', 'tone' => 'blue'],
            ['label' => 'Total Siswa', 'value' => $siswa,      'delta' => '+'.$siswaBaru.' bulan ini', 'icon' => 'i-users',  'tone' => 'green'],
            ['label' => 'Total Kelas', 'value' => $kelasTotal, 'delta' => '+'.$kelasBaru.' bulan ini', 'icon' => 'i-folder', 'tone' => 'orange'],
            // Belum ada tabel presensi di database
            ['label' => 'Kehadiran Hari Ini', 'value' => '–', 'delta' => 'Belum ada data presensi', 'icon' => 'i-circlecheck', 'tone' => 'purple'],
        ];

        // ---- Progres kelas (maks. 6 kelas) ----
        $kelasList = DB::table('classes as c')
            ->join('users as g', 'g.id', '=', 'c.guru_id')
            ->where('c.school_id', $schoolId)
            ->where('c.status', 'aktif')
            ->orderBy('c.nama')
            ->limit(6)
            ->select('c.id', 'c.nama', 'c.mata_pelajaran', 'g.name as guru')
            ->get();

        $ids = $kelasList->pluck('id')->all();

        $siswaPer = DB::table('enrollments')
            ->where('school_id', $schoolId)->where('status', 'aktif')->whereIn('class_id', $ids)
            ->groupBy('class_id')->selectRaw('class_id, COUNT(*) as n')->pluck('n', 'class_id');

        $tugasPer = DB::table('assignments')
            ->where('school_id', $schoolId)->whereIn('class_id', $ids)
            ->groupBy('class_id')->selectRaw('class_id, COUNT(*) as n')->pluck('n', 'class_id');

        $kumpulPer = DB::table('submissions as s')
            ->join('assignments as a', 'a.id', '=', 's.assignment_id')
            ->where('s.school_id', $schoolId)->whereIn('a.class_id', $ids)
            ->groupBy('a.class_id')
            ->selectRaw('a.class_id, COUNT(*) as n, AVG(CASE WHEN s.nilai IS NOT NULL AND a.nilai_maks > 0 THEN s.nilai / a.nilai_maks * 100 END) as rata')
            ->get()->keyBy('class_id');

        $progres = $kelasList->map(function ($c) use ($siswaPer, $tugasPer, $kumpulPer) {
            $s = (int) ($siswaPer[$c->id] ?? 0);
            $t = (int) ($tugasPer[$c->id] ?? 0);
            $k = $kumpulPer[$c->id] ?? null;

            // Progres tugas = pengumpulan / (jumlah siswa x jumlah tugas)
            $persen = ($s * $t) > 0 ? min(100, (int) round(($k->n ?? 0) / ($s * $t) * 100)) : null;

            return [
                'nama'   => $c->nama,
                'mapel'  => $c->mata_pelajaran,
                'persen' => $persen,
                'rata'   => ($k && $k->rata !== null) ? number_format($k->rata, 0) : null,
            ];
        });

        // ---- Aktivitas terbaru (gabungan beberapa tabel) ----
        $aktivitas = collect()
            ->merge(DB::table('assignments')->where('school_id', $schoolId)->latest('created_at')->limit(5)->get(['judul', 'created_at'])
                ->map(fn ($r) => ['teks' => 'Tugas "'.$r->judul.'" ditambahkan', 'at' => $r->created_at, 'tone' => 'blue', 'icon' => 'i-task']))
            ->merge(DB::table('users')->where('school_id', $schoolId)->where('role', 'student')->latest('created_at')->limit(5)->get(['name', 'created_at'])
                ->map(fn ($r) => ['teks' => 'Siswa baru terdaftar: '.$r->name, 'at' => $r->created_at, 'tone' => 'green', 'icon' => 'i-users']))
            ->merge(DB::table('users')->where('school_id', $schoolId)->where('role', 'teacher')->latest('created_at')->limit(5)->get(['name', 'created_at'])
                ->map(fn ($r) => ['teks' => 'Guru baru ditambahkan: '.$r->name, 'at' => $r->created_at, 'tone' => 'blue', 'icon' => 'i-person']))
            ->merge(DB::table('classes')->where('school_id', $schoolId)->latest('created_at')->limit(5)->get(['nama', 'created_at'])
                ->map(fn ($r) => ['teks' => 'Kelas "'.$r->nama.'" dibuat', 'at' => $r->created_at, 'tone' => 'purple', 'icon' => 'i-folder']))
            ->sortByDesc('at')
            ->take(5)
            ->values()
            ->map(fn ($a) => $a + ['tgl' => Carbon::parse($a['at'])->locale('id')->translatedFormat('j M Y')]);

        return view('AdminSekolah.dashboard', compact('sekolah', 'stats', 'progres', 'aktivitas'));
    }
}