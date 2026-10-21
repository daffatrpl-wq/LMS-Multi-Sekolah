<div class="mx-auto max-w-4xl space-y-6 p-4">
    <h1 class="text-xl font-semibold">Tugas Saya</h1>

    <div class="space-y-3">
        @forelse ($daftarTugas as $tugas)
            @php
                $kumpul = $pengumpulan[$tugas->id] ?? null;
                $lewat = $tugas->sudahLewatDeadline();
            @endphp
            <a wire:key="tugas-{{ $tugas->id }}"
               href="{{ route('siswa.tugas.kumpul', $tugas) }}"
               class="flex items-center justify-between rounded border p-4 hover:bg-gray-50">
                <div>
                    <h2 class="font-medium">{{ $tugas->judul }}</h2>
                    <p class="text-sm text-gray-600">
                        {{ $tugas->kelas->nama }} &middot; {{ $tugas->kelas->guru->name }}
                    </p>
                    <p class="text-xs {{ $lewat ? 'text-red-600' : 'text-gray-500' }}">
                        Deadline {{ $tugas->deadline->format('d M Y H:i') }}
                        ({{ $lewat ? 'lewat ' : '' }}{{ $tugas->deadline->diffForHumans() }})
                    </p>
                </div>

                @if (! $kumpul)
                    <span class="rounded bg-gray-100 px-3 py-1 text-sm text-gray-700">
                        {{ $lewat && ! $tugas->izinkan_terlambat ? 'Tidak dikumpulkan' : 'Belum dikumpulkan' }}
                    </span>
                @elseif ($kumpul->status === 'dinilai')
                    <span class="rounded bg-green-100 px-3 py-1 text-sm text-green-800">
                        Dinilai: {{ $kumpul->nilai }}/{{ $tugas->nilai_maks }}
                    </span>
                @elseif ($kumpul->status === 'terlambat')
                    <span class="rounded bg-yellow-100 px-3 py-1 text-sm text-yellow-800">Terlambat</span>
                @else
                    <span class="rounded bg-blue-100 px-3 py-1 text-sm text-blue-800">Terkumpul</span>
                @endif
            </a>
        @empty
            <p class="text-gray-500">Belum ada tugas dari kelas yang kamu ikuti.</p>
        @endforelse
    </div>
</div>
