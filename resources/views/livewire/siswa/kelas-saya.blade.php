<div class="mx-auto max-w-4xl space-y-6 p-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Kelas Saya</h1>
        <a href="{{ route('siswa.jelajahi') }}" class="rounded bg-blue-600 px-4 py-2 text-white">Jelajahi Kelas</a>
    </div>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="space-y-3">
        @forelse ($daftarEnrollment as $enrollment)
            <div wire:key="enrollment-{{ $enrollment->id }}" class="flex items-center justify-between rounded border p-4">
                <div>
                    <h2 class="font-medium">{{ $enrollment->kelas->nama }}</h2>
                    <p class="text-sm text-gray-600">
                        {{ $enrollment->kelas->mata_pelajaran }} &middot; {{ $enrollment->kelas->guru->name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Status: {{ $enrollment->status === 'menunggu' ? 'Menunggu persetujuan' : 'Aktif' }}
                    </p>
                </div>

                <button wire:click="keluar({{ $enrollment->id }})"
                        wire:confirm="Yakin ingin keluar dari kelas ini?"
                        class="rounded border border-red-600 px-3 py-1 text-red-600">
                    {{ $enrollment->status === 'menunggu' ? 'Batalkan' : 'Keluar' }}
                </button>
            </div>
        @empty
            <p class="text-gray-500">Kamu belum mengikuti kelas apa pun.</p>
        @endforelse
    </div>
</div>
