<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard Super Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy:#0e3a74; --navy-2:#0a2c58; --blue:#2563eb; --green:#10b981; --purple:#8b5cf6; --orange:#f59e0b; --red:#ef4444;
  --bg:#f1f5fb; --card:#fff; --line:#e6ecf5; --ink:#172b4d; --muted:#6b7a90;
}
*{box-sizing:border-box}
body{margin:0;font-family:'Poppins',system-ui,sans-serif;background:var(--bg);color:var(--ink);font-size:13px}
.app{display:grid;grid-template-columns:240px 1fr;min-height:100vh}

/* Sidebar */
.sidebar{background:linear-gradient(180deg,var(--navy),var(--navy-2));color:#fff;display:flex;flex-direction:column;padding:18px 14px;position:sticky;top:0;height:100vh}
.brand{display:flex;align-items:center;gap:10px;padding:4px 6px 20px}
.brand .logo{width:36px;height:36px;border-radius:50%;background:#2f6fd6;display:grid;place-items:center}
.brand b{display:block;font-size:.85rem;line-height:1.2}
.brand small{font-size:.65rem;opacity:.75}
.menu{list-style:none;margin:0;padding:0;display:grid;gap:4px}
.menu a{display:flex;align-items:center;gap:12px;color:#dbe6f7;text-decoration:none;padding:10px 12px;border-radius:8px;font-size:.82rem}
.menu a:hover{background:rgba(255,255,255,.08)}
.menu a.active{background:#fff;color:var(--navy);font-weight:600}
.menu svg,.logout svg{width:18px;height:18px;flex:none;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.logout{margin-top:auto}
.logout button{width:100%;display:flex;align-items:center;gap:12px;background:none;border:0;color:#dbe6f7;font:inherit;font-size:.82rem;padding:10px 12px;border-radius:8px;cursor:pointer;text-align:left}
.logout button:hover{background:rgba(255,255,255,.08)}

/* Main */
.main{padding:22px 24px 30px;min-width:0}
.top{display:flex;justify-content:space-between;align-items:flex-start;gap:12px}
.top h1{margin:0;font-size:1.4rem;font-weight:600}
.top p{margin:6px 0 18px;color:var(--muted);font-size:.8rem}
.user{display:flex;align-items:center;gap:10px;background:var(--card);border:1px solid var(--line);border-radius:999px;padding:5px 12px 5px 6px;font-size:.8rem}
.avatar{width:30px;height:30px;border-radius:50%;background:var(--navy);display:grid;place-items:center;color:#fff}
.avatar svg{width:16px;height:16px;fill:none;stroke:#fff;stroke-width:2}

.card{background:var(--card);border:1px solid var(--line);border-radius:12px;box-shadow:0 2px 8px rgba(20,50,100,.05)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px}
.stat{padding:14px 16px}
.stat .head{display:flex;align-items:center;gap:12px}
.chip{width:42px;height:42px;border-radius:10px;display:grid;place-items:center}
.chip svg{width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.blue{background:#e3edff;color:var(--blue)} .green{background:#d9f5ea;color:#0f9f6e}
.orange{background:#fdecd0;color:#d98206} .purple{background:#ebe3ff;color:#6d3fe0} .red{background:#fde0e0;color:#d93636}
.stat small{display:block;color:var(--muted);font-size:.72rem}
.stat strong{font-size:1.5rem;font-weight:600;line-height:1.2}
.stat .note{margin-top:10px;padding-top:8px;border-top:1px solid var(--line);font-size:.66rem;color:var(--muted);text-align:center}

.row{display:grid;grid-template-columns:1.15fr 1fr;gap:14px;margin-bottom:16px}
.panel{padding:14px 16px}
.panel h3{margin:0;font-size:.85rem;font-weight:600}
.panel .bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;gap:8px;flex-wrap:wrap}
.panel .bar a{font-size:.7rem;color:var(--blue);text-decoration:none;font-weight:500}
.legend{display:flex;gap:14px;font-size:.7rem;color:var(--muted)}
.legend i{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:5px}
#chart{width:100%;height:auto;display:block}

table{width:100%;border-collapse:collapse;font-size:.74rem}
th{background:#f3f7fd;text-align:left;padding:9px 10px;font-weight:600;color:#33445f}
td{padding:11px 10px;border-bottom:1px solid var(--line)}
tr:last-child td{border-bottom:0}
.badge{display:inline-block;padding:2px 10px;border-radius:6px;font-size:.68rem;font-weight:500;border:1px solid}
.badge.aktif{background:#e1f7ec;color:#0f9f6e;border-color:#9fe0c3}
.badge.nonaktif{background:#eef1f5;color:#5b6678;border-color:#cdd5e0}
.flash{background:#e1f7ec;border:1px solid #9fe0c3;color:#0b7a55;padding:10px 14px;border-radius:10px;margin-bottom:14px;font-size:.78rem}
.badge.tangguh{background:#fdecd0;color:#c77405;border-color:#f3c47a}
.table-wrap{overflow-x:auto}

.acts{list-style:none;margin:0;padding:0}
.acts li{display:flex;align-items:center;gap:12px;padding:9px 0;font-size:.76rem}
.acts li .dot{width:26px;height:26px;border-radius:50%;display:grid;place-items:center;flex:none}
.acts li .dot svg{width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.acts li span.t{margin-left:auto;color:var(--muted);font-size:.68rem;white-space:nowrap}

@media (max-width:1100px){.stats{grid-template-columns:repeat(2,1fr)}.row{grid-template-columns:1fr}}
@media (max-width:820px){
  .app{grid-template-columns:64px 1fr}
  .sidebar{padding:14px 8px}
  .brand div,.menu a span,.logout button span{display:none}
  .menu a,.logout button{justify-content:center;padding:10px}
  .brand{justify-content:center;padding-bottom:16px}
  .main{padding:16px 14px}
}
@media (max-width:520px){.stats{grid-template-columns:1fr}.user span{display:none}}
</style>
</head>
<body>
@php
  $icons = [
    'dashboard' => 'M3 3h7v9H3zM14 3h7v5h-7zM14 12h7v9h-7zM3 16h7v5H3z',
    'school'    => 'M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6',
    'users'     => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21c0-4 3.5-6 8-6s8 2 8 6',
    'monitor'   => 'M3 4h18v12H3zM8 21h8M12 16v5',
    'quota'     => 'M12 3a9 9 0 1 0 9 9h-9zM15 3.6A9 9 0 0 1 20.4 9H15z',
    'audit'     => 'M6 3h9l4 4v14H6zM14 3v5h5M9 13h6M9 17h6',
    'check'     => 'M4 4h16v16H4zM8 12l3 3 5-6',
    'class'     => 'M3 5h18v11H3zM8 21h8M12 16v5M7 9h10',
    'alert'     => 'M12 3 2 20h20zM12 10v5M12 18h.01',
    'info'      => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zM12 11v5M12 8h.01',
    'plus'      => 'M12 5v14M5 12h14',
  ];
  $menu = [
    ['Dashboard', 'dashboard', route('super-admin.dashboard'), true],
    ['Manajemen Sekolah', 'school', route('super-admin.tenants.index'), false],
    ['Pengguna', 'users', '#', false],
    ['Monitoring Sistem', 'monitor', '#', false],
    ['Manajemen Kuota', 'quota', '#', false],
    ['Audit Log', 'audit', '#', false],
    ['Profil', 'users', route('profile.edit'), false],
  ];
  $actIcon = ['orange' => 'plus', 'blue' => 'info', 'red' => 'alert'];
@endphp

<div class="app">
  <aside class="sidebar">
    <div class="brand">
      <span class="logo"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-5 9 5-9 5z"/><path d="M7 11.500V16c0 1.200 2.200 2.500 5 2.500s5-1.300 5-2.500v-4.500"/></svg></span>
      <div><b>Sistem Informasi</b><small>LMS Multi Tenant</small></div>
    </div>
    <ul class="menu">
      @foreach ($menu as [$label, $icon, $url, $active])
        <li><a href="{{ $url }}" class="{{ $active ? 'active' : '' }}">
          <svg viewBox="0 0 24 24"><path d="{{ $icons[$icon] }}"/></svg><span>{{ $label }}</span>
        </a></li>
      @endforeach
    </ul>
    <form class="logout" method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">
        <svg viewBox="0 0 24 24"><path d="M9 21H5V3h4M16 17l5-5-5-5M21 12H9"/></svg><span>Logout</span>
      </button>
    </form>
  </aside>

  <main class="main">
    <div class="top">
      <div>
        <h1>Dashboard Super Admin</h1>
        <p>Selamat datang, {{ auth()->user()->name ?? 'Super Admin' }}. Berikut adalah ringkasan sistem secara keseluruhan.</p>
      </div>
      <div class="user">
        <span class="avatar"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.500-6 8-6s8 2 8 6"/></svg></span>
        <span>{{ auth()->user()->name ?? 'Super Admin' }}</span>
      </div>
    </div>

    @if (session('success'))
      <div class="flash" role="status">{{ session('success') }}</div>
    @endif

    <section class="stats">
      @foreach ($stats as $s)
        <div class="card stat">
          <div class="head">
            <span class="chip {{ $s['tone'] }}"><svg viewBox="0 0 24 24"><path d="{{ $icons[$s['icon']] }}"/></svg></span>
            <div><small>{{ $s['label'] }}</small><strong>{{ $s['value'] }}</strong></div>
          </div>
          <div class="note">{{ $s['note'] }}</div>
        </div>
      @endforeach
    </section>

    <section class="row">
      <div class="card panel">
        <div class="bar">
          <h3>Statistik Penggunaan Sistem</h3>
          <div class="legend">
            <span><i style="background:var(--blue)"></i>Pengguna</span>
            <span><i style="background:var(--green)"></i>Kelas</span>
            <span><i style="background:var(--purple)"></i>Sekolah</span>
          </div>
        </div>
        <svg id="chart" viewBox="0 0 560 250" role="img" aria-label="Grafik statistik penggunaan sistem"></svg>
      </div>

      <div class="card panel">
        <div class="bar"><h3>Sekolah Terbaru</h3><span><a href="{{ route('super-admin.tenants.create') }}">+ Tambah Sekolah</a> &nbsp;&middot;&nbsp; <a href="{{ route('super-admin.tenants.index') }}">Lihat semua</a></span></div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Nama Sekolah</th><th>Status</th><th>Pengguna</th><th>Tgl Daftar</th></tr></thead>
            <tbody>
              @foreach ($schools as $sc)
                <tr>
                  <td>{{ $sc['nama'] }}</td>
                  <td><span class="badge {{ $sc['badge'] }}">{{ $sc['status'] }}</span></td>
                  <td>{{ $sc['pengguna'] }}</td>
                  <td>{{ $sc['tgl'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="card panel">
      <div class="bar"><h3>Aktivitas Sistem Terbaru</h3></div>
      <ul class="acts">
        @foreach ($activities as $a)
          <li>
            <span class="dot {{ $a['tone'] }}"><svg viewBox="0 0 24 24"><path d="{{ $icons[$actIcon[$a['tone']]] }}"/></svg></span>
            {{ $a['text'] }}
            <span class="t">{{ $a['time'] }}</span>
          </li>
        @endforeach
      </ul>
    </section>
  </main>
</div>

<script>
(function () {
  var data = @json($chart);
  var svg = document.getElementById('chart'), NS = 'http://www.w3.org/2000/svg';
  var W = 560, H = 250, L = 46, R = 12, T = 10, B = 28;
  var peak = Math.max.apply(null, data.pengguna.concat(data.kelas, data.sekolah, [1]));
  var raw = peak / 5, pow = Math.pow(10, Math.floor(Math.log(raw) / Math.LN10)), m = raw / pow;
  var step = (m <= 1 ? 1 : m <= 2 ? 2 : m <= 5 ? 5 : 10) * pow;
  var max = Math.ceil(peak / step) * step;
  var series = [
    { key: 'pengguna', color: '#2563eb', area: true },
    { key: 'kelas',    color: '#10b981' },
    { key: 'sekolah',  color: '#8b5cf6' }
  ];

  function el(name, attrs, text) {
    var n = document.createElementNS(NS, name);
    for (var k in attrs) n.setAttribute(k, attrs[k]);
    if (text) n.textContent = text;
    svg.appendChild(n);
    return n;
  }
  function x(i) { return L + i * (W - L - R) / (data.labels.length - 1); }
  function y(v) { return T + (1 - v / max) * (H - T - B); }

  for (var v = 0; v <= max; v += step) {
    el('line', { x1: L, x2: W - R, y1: y(v), y2: y(v), stroke: '#e6ecf5', 'stroke-width': 1 });
    el('text', { x: L - 8, y: y(v) + 3, 'text-anchor': 'end', 'font-size': 9, fill: '#6b7a90' }, v.toLocaleString('id-ID'));
  }
  data.labels.forEach(function (lb, i) {
    el('text', { x: x(i), y: H - 8, 'text-anchor': 'middle', 'font-size': 9, fill: '#6b7a90' }, lb);
  });

  series.forEach(function (s) {
    var pts = data[s.key].map(function (v, i) { return x(i) + ',' + y(v); });
    if (s.area) {
      el('polygon', { points: x(0) + ',' + y(0) + ' ' + pts.join(' ') + ' ' + x(data.labels.length - 1) + ',' + y(0), fill: s.color, opacity: .08 });
    }
    el('polyline', { points: pts.join(' '), fill: 'none', stroke: s.color, 'stroke-width': 2, 'stroke-linejoin': 'round' });
    data[s.key].forEach(function (v, i) {
      var c = el('circle', { cx: x(i), cy: y(v), r: 3, fill: '#fff', stroke: s.color, 'stroke-width': 2 });
      var t = document.createElementNS(NS, 'title');
      t.textContent = s.key + ' ' + data.labels[i] + ': ' + v.toLocaleString('id-ID');
      c.appendChild(t);
    });
  });
})();
</script>
</body>
</html>