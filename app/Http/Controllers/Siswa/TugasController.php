<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Nama relasi dan kolom di bawah ini TEBAKAN — sesuaikan dengan skema-mu
        $tugas = Assignment::query()
            ->with('course')
            ->whereIn('course_id', $user->courses()->pluck('courses.id'))
            ->withExists(['submissions as sudah' => fn ($q) => $q->where('user_id', $user->id)])
            ->orderBy('due_date')
            ->get()
            ->map(fn ($a) => [
                'id'      => $a->id,
                'judul'   => $a->title,
                'mapel'   => $a->course->name,
                'tenggat' => $a->due_date->translatedFormat('j M Y'),
                'status'  => $a->sudah ? 'selesai' : 'belum',
                'url'     => route('siswa.tugas.kumpul', $a),
            ]);

        return view('livewire.siswa.daftar-tugas', compact('tugas'));
    }
}