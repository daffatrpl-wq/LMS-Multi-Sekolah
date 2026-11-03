<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\School;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $fmt = fn ($n) => number_format($n, 0, ',', '.');

        // ---- Kartu statistik ----
        $totalSekolah = School::count();
        $aktif        = School::where('status', 'active')->count();
        $suspended    = School::where('status', 'suspended')->count();
        $nonaktif     = School::where('status', 'inactive')->count();
        $guru         = User::where('role', 'teacher')->count();
        $siswa        = User::where('role', 'student')->count();
        $totalKelas   = Kelas::withoutGlobalScopes()->count();

        $stats = [
            ['label' => 'Total Sekolah',  'value' => $fmt($totalSekolah), 'tone' => 'blue',   'icon' => 'school',
             'note' => "$aktif Aktif | $suspended Ditangguhkan | $nonaktif Nonaktif"],
            ['label' => 'Sekolah Aktif',  'value' => $fmt($aktif),        'tone' => 'green',  'icon' => 'check',
             'note' => ($totalSekolah ? round($aktif / $totalSekolah * 100) : 0) . '% dari total'],
            ['label' => 'Total Pengguna', 'value' => $fmt(User::count()), 'tone' => 'orange', 'icon' => 'users',
             'note' => 'Guru ' . $fmt($guru) . ' | Siswa ' . $fmt($siswa)],
            ['label' => 'Total Kelas',    'value' => $fmt($totalKelas),   'tone' => 'purple', 'icon' => 'class',
             'note' => "dari $totalSekolah sekolah"],
        ];

        // ---- Grafik: jumlah kumulatif 9 bulan terakhir ----
        $chart = ['labels' => [], 'pengguna' => [], 'kelas' => [], 'sekolah' => []];
        for ($i = 8; $i >= 0; $i--) {
            $end = now()->subMonths($i)->endOfMonth();
            $chart['labels'][]   = $end->copy()->locale('id')->isoFormat('MMM');
            $chart['pengguna'][] = User::where('created_at', '<=', $end)->count();
            $chart['kelas'][]    = Kelas::withoutGlobalScopes()->where('created_at', '<=', $end)->count();
            $chart['sekolah'][]  = School::where('created_at', '<=', $end)->count();
        }

        // ---- Sekolah terbaru ----
        $label = ['active' => 'Aktif', 'suspended' => 'Ditangguhkan', 'inactive' => 'Nonaktif'];
        $badge = ['active' => 'aktif', 'suspended' => 'tangguh',      'inactive' => 'nonaktif'];

        $schools = School::withCount('users')->latest()->take(5)->get()->map(fn ($s) => [
            'nama'     => $s->name,
            'status'   => $label[$s->status] ?? ucfirst($s->status),
            'badge'    => $badge[$s->status] ?? 'nonaktif',
            'pengguna' => $s->users_count,
            'tgl'      => $s->created_at->locale('id')->translatedFormat('d M Y'),
        ]);

        // ---- Aktivitas terbaru (diturunkan dari data sekolah) ----
        // Belum ada tabel audit log, jadi aktivitas dibaca dari perubahan data sekolah.
        $activities = School::latest('updated_at')->take(5)->get()->map(function ($s) {
            if ($s->status === 'suspended') {
                $text = "Status tenant {$s->name} diubah menjadi Ditangguhkan"; $tone = 'red';
            } elseif ($s->updated_at->gt($s->created_at->copy()->addMinute())) {
                $text = "Data/kuota sekolah {$s->name} diperbarui"; $tone = 'blue';
            } else {
                $text = "Sekolah {$s->name} baru didaftarkan"; $tone = 'orange';
            }
            return ['text' => $text, 'tone' => $tone,
                    'time' => $s->updated_at->locale('id')->translatedFormat('d M Y H:i')];
        });

        return view('super_admin.dashboard', compact('stats', 'chart', 'schools', 'activities'));
    }
}