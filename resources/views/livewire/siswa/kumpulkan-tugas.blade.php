<div class="mx-auto max-w-3xl space-y-6 p-4">
    <a href="{{ route('siswa.tugas') }}" class="text-sm text-blue-600">&larr; Kembali ke daftar tugas</a>

    <div>
        <h1 class="text-xl font-semibold">{{ $assignment->judul }}</h1>
        <p class="text-sm text-gray-600">{{ $assignment->kelas->nama }}</p>
        <p class="text-sm {{ $assignment->sudahLewatDeadline() ? 'text-red-600' : 'text-gray-600' }}">
            Deadline {{ $assignment->deadline->format('d M Y H:i') }}
            ({{ $assignment->sudahLewatDeadline() ? 'lewat ' : '' }}{{ $assignment->deadline->diffForHumans() }})
        </p>
        @if ($assignment->deskripsi)
            <p class="mt-3 whitespace-pre-line">{{ $assignment->deskripsi }}</p>
        @endif
    </div>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    {{-- Status pengumpulan saat ini (READ) --}}
    @if ($pengumpulan)
        <div class="space-y-1 rounded border p-4">
            <p class="font-medium">
                Status: {{ ucfirst($pengumpulan->status) }} &middot; versi {{ $pengumpulan->versi }}
            </p>
            <p class="text-sm text-gray-600">Dikirim {{ $pengumpulan->submitted_at->format('d M Y H:i') }}</p>

            @if ($pengumpulan->file_path)
                <button wire:click="unduh" class="text-sm text-blue-600 underline">
                    {{ $pengumpulan->file_nama }}
                </button>
            @endif

            @if ($pengumpulan->status === 'dinilai')
                <p class="pt-2">Nilai: <strong>{{ $pengumpulan->nilai }}</strong> / {{ $assignment->nilai_maks }}</p>
                @if ($pengumpulan->umpan_balik)
                    <p class="whitespace-pre-line text-sm">Umpan balik: {{ $pengumpulan->umpan_balik }}</p>
                @endif
            @endif
        </div>
    @endif

    {{-- Form kumpul / kumpul ulang (CREATE / UPDATE) --}}
    @if ($bisaKumpul)
        <form wire:submit="kirim" class="space-y-4 rounded border p-4">
            <h2 class="font-medium">{{ $pengumpulan ? 'Kumpulkan ulang' : 'Kumpulkan tugas' }}</h2>

            @if ($assignment->sudahLewatDeadline())
                <p class="text-sm text-yellow-700">Deadline sudah lewat. Pengumpulan akan ditandai terlambat.</p>
            @endif

            <div>
                <label class="mb-1 block text-sm">Jawaban teks (opsional)</label>
                <textarea wire:model="jawabanTeks" rows="5" class="w-full rounded border p-2"></textarea>
                @error('jawabanTeks') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm">Berkas (maks. 10 MB)</label>
                <input type="file" wire:model="berkas">
                <div wire:loading wire:target="berkas" class="text-sm text-gray-500">Mengunggah...</div>
                @error('berkas') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="kirim,berkas"
                    class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50">
                {{ $pengumpulan ? 'Kirim ulang' : 'Kumpulkan' }}
            </button>
        </form>
    @else
        <p class="rounded bg-gray-100 p-3 text-gray-700">
            {{ $pengumpulan?->status === 'dinilai'
                ? 'Tugas ini sudah dinilai dan tidak bisa diubah lagi.'
                : 'Pengumpulan sudah ditutup.' }}
        </p>
    @endif

    {{-- Riwayat versi --}}
    @if ($pengumpulan && $pengumpulan->versions->isNotEmpty())
        <div class="space-y-2">
            <h2 class="font-medium">Riwayat versi sebelumnya</h2>
            @foreach ($pengumpulan->versions as $versi)
                <div wire:key="versi-{{ $versi->id }}" class="rounded border p-3 text-sm">
                    Versi {{ $versi->versi }} &middot; {{ $versi->submitted_at->format('d M Y H:i') }}
                    @if ($versi->file_nama) &middot; {{ $versi->file_nama }} @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
