<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Semua tugas dari kelas yang diikuti siswa; "selesai" jika sudah ada pengumpulan
        $tugas = DB::table('assignments as a')
            ->join('classes as c', 'c.id', '=', 'a.class_id')
            ->join('enrollments as e', function ($j) use ($user) {
                $j->on('e.class_id', '=', 'c.id')
                  ->where('e.user_id', $user->id)
                  ->where('e.status', 'aktif');
            })
            ->leftJoin('submissions as s', function ($j) use ($user) {
                $j->on('s.assignment_id', '=', 'a.id')
                  ->where('s.user_id', $user->id);
            })
            ->select('a.id', 'a.judul', 'a.deadline', 'c.mata_pelajaran', DB::raw('s.id as submission_id'))
            ->orderBy('a.deadline')
            ->get()
            ->map(fn ($r) => [
                'id'      => $r->id,
                'judul'   => $r->judul,
                'mapel'   => $r->mata_pelajaran,
                'tenggat' => Carbon::parse($r->deadline)->locale('id')->translatedFormat('j M Y'),
                'status'  => $r->submission_id ? 'selesai' : 'belum',
                'url'     => route('siswa.tugas.kumpul', $r->id),
            ])
            ->values();

        return view('livewire.siswa.daftar-tugas', compact('tugas'));
    }
}