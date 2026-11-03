{{-- Tampilan komponen Livewire: harus punya SATU elemen root. Layout dipasang dari class Dashboard. --}}
<div>
    <section class="cards c4">
        @foreach ($stats as $s)
            <article class="card stat t-{{ $s['tone'] }}">
                <div class="row">
                    <span class="ico {{ $s['tone'] }}"><svg class="i"><use href="#{{ $s['icon'] }}"/></svg></span>
                    <div><div class="lb">{{ $s['label'] }}</div><div class="val">{{ $s['value'] }}</div></div>
                </div>
                <div class="delta">{{ $s['delta'] }}</div>
            </article>
        @endforeach
    </section>

    <section class="mid m32">
        <article class="card panel">
            <div class="hd"><h3>Progres Kelas</h3></div>
            @if ($progres->isEmpty())
                <div class="kosong">Belum ada kelas di sekolah ini.</div>
            @else
                <div class="pw">
                    <table class="pt">
                        <thead><tr><th>Kelas</th><th>Kehadiran</th><th>Progres Tugas</th><th>Rata-rata Nilai</th></tr></thead>
                        <tbody>
                            @foreach ($progres as $p)
                                <tr>
                                    <td class="nm">{{ $p['nama'] }}<small>{{ $p['mapel'] }}</small></td>
                                    <td class="cell"><span>–</span><div class="bar g"><i style="width:0"></i></div></td>
                                    <td class="cell">
                                        <span>{{ $p['persen'] !== null ? $p['persen'].'%' : '–' }}</span>
                                        <div class="bar o"><i style="width:{{ $p['persen'] ?? 0 }}%"></i></div>
                                    </td>
                                    <td>{{ $p['rata'] ?? '–' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

        <article class="card panel">
            <div class="hd"><h3>Aktivitas Terbaru</h3></div>
            @forelse ($aktivitas as $a)
                <div class="ak">
                    <span class="dot {{ $a['tone'] }}"><svg class="i"><use href="#{{ $a['icon'] }}"/></svg></span>
                    <span class="t">{{ $a['teks'] }}</span>
                    <span class="w">{{ $a['tgl'] }}</span>
                </div>
            @empty
                <div class="kosong">Belum ada aktivitas.</div>
            @endforelse
        </article>
    </section>
</div>