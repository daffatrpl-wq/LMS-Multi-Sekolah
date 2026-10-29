@php
    $nama  = request()->user()?->name ?? 'Siswa';
    $kelas = 'XI RPL 1'; // contoh: ganti dengan data kelas siswa dari database
    $hari  = now()->locale('id')->translatedFormat('l, j F Y');

    $stats = [
        ['label' => 'Mata Pelajaran', 'value' => '12',   'delta' => '+2 dari semester lalu',   'icon' => 'i-book', 'tone' => 'blue'],
        ['label' => 'Tugas Aktif',    'value' => '5',    'delta' => '+2 belum dikumpulkan',    'icon' => 'i-task', 'tone' => 'green'],
        ['label' => 'Ujian Mendatang','value' => '2',    'delta' => '-1 dalam 7 hari',         'icon' => 'i-exam', 'tone' => 'orange'],
        ['label' => 'Rata-rata Nilai','value' => '87.5', 'delta' => '+3.3 dari semester lalu', 'icon' => 'i-chart','tone' => 'purple'],
    ];

    $jadwal = [
        ['jam' => '07:00 - 08:30', 'mapel' => 'Pemrograman Dasar', 'ruang' => 'Lab 1',   'icon' => 'i-monitor', 'tone' => 'blue'],
        ['jam' => '08:45 - 10:15', 'mapel' => 'Basis Data',        'ruang' => 'Lab 2',   'icon' => 'i-folder',  'tone' => 'green'],
        ['jam' => '10:30 - 12:00', 'mapel' => 'Matematika',        'ruang' => 'Ruang 12','icon' => 'i-chart',   'tone' => 'orange'],
        ['jam' => '13:00 - 14:30', 'mapel' => 'Bahasa Indonesia',  'ruang' => 'Ruang 8', 'icon' => 'i-book',    'tone' => 'purple'],
        ['jam' => '14:45 - 16:15', 'mapel' => 'Desain Web',        'ruang' => 'Lab 1',   'icon' => 'i-exam',    'tone' => 'blue'],
    ];

    $pengumuman = [
        ['judul' => 'Jadwal Ujian Tengah Semester (UTS)', 'waktu' => '29 Agu 2025 · 10:30', 'warna' => '#2f6fe4'],
        ['judul' => 'Pengumpulan Tugas Desain Web',       'waktu' => '26 Agu 2025 · 14:20', 'warna' => '#1ba7d9'],
        ['judul' => 'Perubahan Jadwal Matematika',        'waktu' => '26 Agu 2025 · 08:00', 'warna' => '#7c5cf0'],
        ['judul' => 'Kegiatan Literasi Digital',          'waktu' => '24 Agu 2025 · 16:45', 'warna' => '#2f6fe4'],
        ['judul' => 'Pengumuman Libur Nasional',          'waktu' => '20 Agu 2025 · 06:00', 'warna' => '#e5484d'],
    ];
@endphp

<x-siswa-layout title="Dashboard Siswa"
                :subtitle="'Selamat datang kembali, '.$nama.'!'"
                tagline="Semangat belajar dan raih prestasimu! 🚀"
                :kelas="$kelas">

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
            <div class="hd"><h3>Jadwal Hari Ini</h3><a href="#" class="link">Lihat Semua ›</a></div>
            <p class="sub-date">{{ $hari }}</p>
            <div class="jd">
                @foreach ($jadwal as $j)
                    <div class="r">
                        <span class="tm">{{ $j['jam'] }}</span>
                        <span class="mp"><span class="mpi {{ $j['tone'] }}"><svg class="i"><use href="#{{ $j['icon'] }}"/></svg></span>{{ $j['mapel'] }}</span>
                        <span class="kl">{{ $kelas }}</span>
                        <span class="rg">{{ $j['ruang'] }}</span>
                    </div>
                @endforeach
            </div>
            <p class="quote"><svg class="i" style="width:14px;height:14px"><use href="#i-circlecheck"/></svg>"Ilmu hari ini, kesuksesan esok hari."</p>
        </article>

        <article class="card panel">
            <div class="hd"><h3><svg class="i"><use href="#i-megaphone"/></svg>Pengumuman</h3><a href="#" class="link">Lihat Semua</a></div>
            <ul class="pg">
                @foreach ($pengumuman as $p)
                    <li>
                        <span class="dt" style="background:{{ $p['warna'] }}"></span>
                        <div><b>{{ $p['judul'] }}</b><small>{{ $p['waktu'] }}</small></div>
                    </li>
                @endforeach
            </ul>
        </article>
    </section>

</x-siswa-layout>