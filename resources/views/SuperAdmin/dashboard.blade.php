@php
    $namaUser = auth()->user()->name ?? 'Super Admin';

    $stats = [
        ['label' => 'Total Sekolah',  'value' => '25',    'foot' => '22 Aktif | 2 Ditangguhkan | 1 Nonaktif', 'icon' => 'i-school', 'tone' => 'blue'],
        ['label' => 'Sekolah Aktif',  'value' => '22',    'foot' => '88% dari total',                         'icon' => 'i-check',  'tone' => 'green'],
        ['label' => 'Total Pengguna', 'value' => '8.540', 'foot' => 'Guru 700 | Siswa 7,820',                 'icon' => 'i-users',  'tone' => 'orange'],
        ['label' => 'Total Kelas',    'value' => '980',   'foot' => 'dari 25 sekolah',                        'icon' => 'i-class',  'tone' => 'purple'],
    ];

    $sekolah = [
        ['nama' => 'SMAN 1 Banyuwangi',  'status' => 'Aktif',        'pengguna' => 450, 'tgl' => '15 Sep 2026'],
        ['nama' => 'SMKN 1 Banyuwangi',  'status' => 'Aktif',        'pengguna' => 380, 'tgl' => '12 Sep 2026'],
        ['nama' => 'SMA 2 Genteng',      'status' => 'Aktif',        'pengguna' => 250, 'tgl' => '10 Sep 2026'],
        ['nama' => 'SMAN 3 Banyuwangi',  'status' => 'Ditangguhkan', 'pengguna' => 120, 'tgl' => '8 Sep 2026'],
        ['nama' => 'SMK 1 Cluring',      'status' => 'Aktif',        'pengguna' => 310, 'tgl' => '5 Sep 2026'],
    ];

    $aktivitas = [
        ['teks' => 'Sekolah SMAN 1 Banyuwangi baru didaftarkan',       'waktu' => '15 Sep 2026  10:24', 'tone' => 'warn', 'icon' => 'i-school'],
        ['teks' => 'Kuota sekolah SMKN 1 Banyuwangi diperbarui',       'waktu' => '14 Sep 2026  16:32', 'tone' => 'info', 'icon' => 'i-quota'],
        ['teks' => 'Status tenant SMA 2 Genteng diubah menjadi Ditangguhkan', 'waktu' => '12 Sep 2026  09:15', 'tone' => 'danger', 'icon' => 'i-alert'],
    ];

    $chart = [
        'bulan'    => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep'],
        'pengguna' => [3800, 4600, 5700, 5900, 6100, 7000, 7800, 8400, 9000],
        'kelas'    => [2000, 2700, 3100, 3000, 3600, 3800, 4300, 4700, 4900],
        'sekolah'  => [600, 1000, 1100, 1000, 1300, 1400, 1900, 2300, 2600],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Dashboard Super Admin – SIKESMA</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  color-scheme:light;
  --navy:#0b2a5c; --navy2:#0a2250; --active:#1f56a8;
  --bg:#f2f5fa; --card:#fff; --line:#e4eaf3; --ink:#14233f; --muted:#6b7a93; --link:#2563eb;
  --blue:#2f6fe4; --green:#1fae7a; --purple:#7c5cf0;
  box-sizing:border-box;
}
*{box-sizing:border-box}
html{scroll-padding-top:env(safe-area-inset-top,0px)}
body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--ink);font-size:13px;
  padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
.app{display:flex;min-height:100vh}
.i{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex:none}

/* Sidebar */
.side{width:232px;flex:none;background:linear-gradient(180deg,var(--navy),var(--navy2));color:#fff;display:flex;flex-direction:column;padding:18px 10px;position:sticky;top:0;height:100vh}
.brand{display:flex;align-items:center;gap:10px;padding:2px 8px 22px}
.brand .logo{width:34px;height:34px;border-radius:50%;background:radial-gradient(circle at 30% 25%,#5b9bff,#2457b8);display:grid;place-items:center}
.brand .logo svg{width:18px;height:18px}
.brand b{display:block;font-size:13px;font-weight:600}
.brand small{font-size:10px;opacity:.75}
.menu{display:flex;flex-direction:column;gap:6px;flex:1}
.menu a{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;color:#e6eefc;text-decoration:none;font-size:12.5px;font-weight:500}
.menu a:hover{background:rgba(255,255,255,.08)}
.menu a.on{background:var(--active);color:#fff;font-weight:600}
.logout{margin:0}
.logout button{all:unset;box-sizing:border-box;width:100%;display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;color:#e6eefc;font-size:12.5px;font-weight:500;cursor:pointer}
.logout button:hover{background:rgba(255,255,255,.08)}
.logout button:focus-visible,.menu a:focus-visible{outline:2px solid #9cc2ff}

/* Main */
.main{flex:1;min-width:0;padding:22px 26px 30px}
.top{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:16px}
.top h1{margin:0 0 6px;font-size:21px;font-weight:700}
.top p{margin:0;color:#41506b;font-size:12px}
.user{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--line);padding:5px 10px 5px 6px;border-radius:999px;font-size:12px;font-weight:500;white-space:nowrap}
.avatar{width:26px;height:26px;border-radius:50%;background:var(--navy);color:#fff;display:grid;place-items:center}
.avatar svg{width:15px;height:15px}
.user .chev{width:12px;height:12px;stroke-width:2.5}

.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:14px}
.card{background:var(--card);border:1px solid var(--line);border-radius:14px;box-shadow:0 2px 8px rgba(20,35,63,.05)}
.stat{padding:16px 16px 12px}
.stat .row{display:flex;align-items:center;gap:12px}
.ico{width:40px;height:40px;border-radius:10px;display:grid;place-items:center;flex:none}
.ico .i{width:20px;height:20px}
.blue{background:#e4edff;color:#2f6fe4}.green{background:#dcf3e8;color:#1f9d63}.orange{background:#ffeed6;color:#e58a1f}.purple{background:#e9e4ff;color:#6a4fe0}
.stat .lb{font-size:11.5px;color:#41506b;font-weight:500}
.stat .val{font-size:26px;font-weight:700;line-height:1.1;margin-top:2px}
.stat .foot{margin-top:12px;padding-top:9px;border-top:1px solid var(--line);text-align:center;font-size:10.5px;color:var(--muted)}

.mid{display:grid;grid-template-columns:1.12fr 1fr;gap:14px;margin-bottom:14px}
.panel{padding:16px}
.panel h3{margin:0;font-size:13px;font-weight:700}
.panel .hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:10px;flex-wrap:wrap}
.link{color:var(--link);font-size:11px;font-weight:600;text-decoration:none}
.legend{display:flex;gap:14px;font-size:11px;color:#41506b;flex-wrap:wrap}
.legend span{display:inline-flex;align-items:center;gap:5px}
.legend i{width:8px;height:8px;border-radius:50%;display:inline-block}
#chart{width:100%;height:auto;display:block}
#chart text{font-family:inherit;font-size:10px;fill:#6b7a93}

.tbl{width:100%;border-collapse:collapse;font-size:11.5px}
.tbl th{background:#f0f4fa;text-align:left;font-weight:600;color:#41506b;padding:9px 12px;font-size:11px}
.tbl td{padding:11px 12px;border-bottom:1px solid var(--line)}
.tbl tr:last-child td{border-bottom:0}
.tbl td:first-child{font-weight:500}
.tblwrap{overflow-x:auto;margin:0 -16px -16px}
.badge{display:inline-block;padding:2px 9px;border-radius:6px;font-size:10.5px;font-weight:600;border:1px solid}
.b-aktif{background:#e7f7ee;border-color:#9edbb8;color:#1b9a58}
.b-tangguh{background:#fff1dc;border-color:#f4c27d;color:#d8801f}

.act{display:flex;align-items:center;gap:12px;padding:8px 0}
.act .dot{width:28px;height:28px;border-radius:50%;display:grid;place-items:center;flex:none}
.act .dot .i{width:14px;height:14px}
.warn{background:#fff1dc;color:#e58a1f}.info{background:#dcebff;color:#2f6fe4}.danger{background:#ffe1e1;color:#dc3b3b}
.act .t{flex:1;color:#2a3956;font-size:12px}
.act .w{color:var(--muted);font-size:11px;white-space:pre}

@media (max-width:1100px){.cards{grid-template-columns:repeat(2,1fr)}.mid{grid-template-columns:1fr}}
@media (max-width:820px){
  .app{flex-direction:column}
  .side{width:100%;height:auto;position:static;flex-direction:row;align-items:center;overflow-x:auto;padding:10px;gap:8px}
  .brand{padding:0 8px 0 0}.brand small{display:none}
  .menu{flex-direction:row;flex:none}.menu a{white-space:nowrap}
  .logout{flex:none}.logout button{white-space:nowrap}
  .main{padding:18px 14px 26px}
  .top{flex-direction:column}
  .act{flex-wrap:wrap}.act .w{white-space:normal;width:100%;padding-left:40px}
}
@media (max-width:520px){.cards{grid-template-columns:1fr}}
</style>
</head>
<body>

<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="i-home" viewBox="0 0 24 24"><path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></symbol>
    <symbol id="i-school" viewBox="0 0 24 24"><path d="M4 21V6l8-3 8 3v15M2 21h20M9 21v-5h6v5M9 9h2M13 9h2M9 12.500h2M13 12.500h2"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.500"/><path d="M5 20c0-3.800 3-6 7-6s7 2.200 7 6"/></symbol>
    <symbol id="i-monitor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></symbol>
    <symbol id="i-quota" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 12l3.500-3.500M12 7v1.500M7 12h1.500"/></symbol>
    <symbol id="i-audit" viewBox="0 0 24 24"><path d="M6 3h9l4 4v14H6z"/><path d="M9 12h7M9 16h7"/></symbol>
    <symbol id="i-logout" viewBox="0 0 24 24"><path d="M9 21H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4M16 17l5-5-5-5M21 12H9"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="M4 21V6l8-3 8 3v15M2 21h20"/><path d="M8.500 12.500l2.500 2.500 4.500-5"/></symbol>
    <symbol id="i-class" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M7 9h4M7 13h10M8 21h8"/></symbol>
    <symbol id="i-alert" viewBox="0 0 24 24"><path d="M12 3l10 18H2z"/><path d="M12 10v5M12 18v.5"/></symbol>
    <symbol id="i-chev" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></symbol>
  </defs>
</svg>

<div class="app">
  <aside class="side">
    <div class="brand">
      <span class="logo"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4 3 8.500l9 4.500 9-4.500z" fill="#fff"/><path d="M7 11v4c0 1.500 2.200 3 5 3s5-1.500 5-3v-4"/></svg></span>
      <div><b>Sistem Informasi</b><small>LMS Multi Tenant</small></div>
    </div>
    <nav class="menu">
      <a href="{{ route('dashboard') }}" class="on"><svg class="i"><use href="#i-home"/></svg>Dashboard</a>
      <a href="#"><svg class="i"><use href="#i-school"/></svg>Manajemen Sekolah</a>
      <a href="#"><svg class="i"><use href="#i-users"/></svg>Pengguna</a>
      <a href="#"><svg class="i"><use href="#i-monitor"/></svg>Monitoring Sistem</a>
      <a href="#"><svg class="i"><use href="#i-quota"/></svg>Manajemen Kuota</a>
      <a href="#"><svg class="i"><use href="#i-audit"/></svg>Audit Log</a>
      <a href="#"><svg class="i"><use href="#i-users"/></svg>Profil</a>
    </nav>
    <form class="logout" method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"><svg class="i"><use href="#i-logout"/></svg>Logout</button>
    </form>
  </aside>

  <main class="main">
    <header class="top">
      <div>
        <h1>Dashboard Super Admin</h1>
        <p>Selamat datang, {{ $namaUser }}. Berikut adalah ringkasan sistem secara keseluruhan.</p>
      </div>
      <div class="user">
        <span class="avatar"><svg class="i"><use href="#i-users"/></svg></span>
        {{ $namaUser }}
        <svg class="i chev"><use href="#i-chev"/></svg>
      </div>
    </header>

    <section class="cards">
      @foreach ($stats as $s)
        <article class="card stat">
          <div class="row">
            <span class="ico {{ $s['tone'] }}"><svg class="i"><use href="#{{ $s['icon'] }}"/></svg></span>
            <div><div class="lb">{{ $s['label'] }}</div><div class="val">{{ $s['value'] }}</div></div>
          </div>
          <div class="foot">{{ $s['foot'] }}</div>
        </article>
      @endforeach
    </section>

    <section class="mid">
      <article class="card panel">
        <div class="hd">
          <h3>Statistik Penggunaan Sistem</h3>
          <div class="legend">
            <span><i style="background:var(--blue)"></i>Pengguna</span>
            <span><i style="background:var(--green)"></i>Kelas</span>
            <span><i style="background:var(--purple)"></i>Sekolah</span>
          </div>
        </div>
        <svg id="chart" viewBox="0 0 560 260" role="img" aria-label="Grafik statistik penggunaan sistem"></svg>
      </article>

      <article class="card panel">
        <div class="hd"><h3>Sekolah Terbaru</h3><a href="#" class="link">Lihat semua</a></div>
        <div class="tblwrap">
          <table class="tbl">
            <thead><tr><th>Nama Sekolah</th><th>Status</th><th>Pengguna</th><th>Tgl Daftar</th></tr></thead>
            <tbody>
              @foreach ($sekolah as $r)
                <tr>
                  <td>{{ $r['nama'] }}</td>
                  <td><span class="badge {{ $r['status'] === 'Aktif' ? 'b-aktif' : 'b-tangguh' }}">{{ $r['status'] }}</span></td>
                  <td>{{ $r['pengguna'] }}</td>
                  <td>{{ $r['tgl'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </article>
    </section>

    <section class="card panel">
      <div class="hd"><h3>Aktivitas Sistem Terbaru</h3><a href="#" class="link">Lihat semua</a></div>
      @foreach ($aktivitas as $a)
        <div class="act">
          <span class="dot {{ $a['tone'] }}"><svg class="i"><use href="#{{ $a['icon'] }}"/></svg></span>
          <span class="t">{{ $a['teks'] }}</span>
          <span class="w">{{ $a['waktu'] }}</span>
        </div>
      @endforeach
    </section>
  </main>
</div>

<script>
(function () {
  var data = @json($chart);
  var svg = document.getElementById('chart'), NS = 'http://www.w3.org/2000/svg';
  var W = 560, H = 260, L = 46, R = 14, T = 12, B = 28, YMAX = 10000, n = data.bulan.length;
  var series = [
    { key: 'pengguna', color: '#2f6fe4', area: true },
    { key: 'kelas',    color: '#1fae7a' },
    { key: 'sekolah',  color: '#7c5cf0' }
  ];
  function x(i) { return L + i * (W - L - R) / (n - 1); }
  function y(v) { return T + (H - T - B) * (1 - v / YMAX); }
  function el(name, attrs, text) {
    var e = document.createElementNS(NS, name);
    for (var k in attrs) e.setAttribute(k, attrs[k]);
    if (text != null) e.textContent = text;
    svg.appendChild(e); return e;
  }
  for (var g = 0; g <= YMAX; g += 2000) {
    el('line', { x1: L, x2: W - R, y1: y(g), y2: y(g), stroke: '#e6ecf5', 'stroke-width': 1 });
    el('text', { x: L - 8, y: y(g) + 3, 'text-anchor': 'end' }, g.toLocaleString('id-ID'));
  }
  data.bulan.forEach(function (b, i) { el('text', { x: x(i), y: H - 8, 'text-anchor': 'middle' }, b); });
  series.forEach(function (s) {
    var v = data[s.key], pts = v.map(function (val, i) { return x(i) + ',' + y(val); }).join(' ');
    if (s.area) {
      el('polygon', { points: x(0) + ',' + y(0) + ' ' + pts + ' ' + x(n - 1) + ',' + y(0), fill: '#2f6fe4', 'fill-opacity': .09 });
    }
    el('polyline', { points: pts, fill: 'none', stroke: s.color, 'stroke-width': 2.2, 'stroke-linejoin': 'round', 'stroke-linecap': 'round' });
    v.forEach(function (val, i) { el('circle', { cx: x(i), cy: y(val), r: 2.8, fill: '#fff', stroke: s.color, 'stroke-width': 1.6 }); });
  });
})();
</script>
</body>
</html>