<div class="mx-auto max-w-4xl space-y-6 p-4">
    <h1 class="text-xl font-semibold">Jelajahi Kelas</h1>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="rounded bg-yellow-100 p-3 text-yellow-800">{{ session('info') }}</div>
    @endif

    {{-- Gabung dengan kode undangan --}}
    <form wire:submit="gabungDenganKode" class="space-y-1">
        <div class="flex gap-2">
            <input type="text" wire:model="kodeUndangan" maxlength="8"
                   placeholder="Masukkan kode undangan (8 karakter)"
                   class="w-full rounded border p-2 uppercase">
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Gabung</button>
        </div>
        @error('kodeUndangan') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </form>

    {{-- Cari kelas --}}
    <input type="search" wire:model.live.debounce.300ms="search"
           placeholder="Cari nama kelas, mata pelajaran, atau nama guru..."
           class="w-full rounded border p-2">

    <div class="space-y-3">
        @forelse ($daftarKelas as $kelas)
            @php($status = $statusSaya[$kelas->id] ?? null)
            <div wire:key="kelas-{{ $kelas->id }}" class="flex items-center justify-between rounded border p-4">
                <div>
                    <h2 class="font-medium">{{ $kelas->nama }}</h2>
                    <p class="text-sm text-gray-600">{{ $kelas->mata_pelajaran }} &middot; {{ $kelas->guru->name }}</p>
                    @if ($kelas->perlu_persetujuan)
                        <p class="text-xs text-gray-500">Perlu persetujuan guru</p>
                    @endif
                </div>

                @if ($status === 'aktif')
                    <span class="rounded bg-green-100 px-3 py-1 text-sm text-green-800">Sudah bergabung</span>
                @elseif ($status === 'menunggu')
                    <span class="rounded bg-yellow-100 px-3 py-1 text-sm text-yellow-800">Menunggu persetujuan</span>
                @else
                    <button wire:click="gabung({{ $kelas->id }})"
                            class="rounded bg-blue-600 px-4 py-2 text-white">Ikuti Kelas</button>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Tidak ada kelas yang cocok.</p>
        @endforelse
    </div>

    {{ $daftarKelas->links() }}
</div>
