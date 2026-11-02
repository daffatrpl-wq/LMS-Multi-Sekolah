@php
    $cur   = $schools->currentPage();
    $last  = $schools->lastPage();
    $from  = max(1, $cur - 1);
    $to    = min($last, $from + 2);
    $from  = max(1, $to - 2);

    $label = ['active' => 'Aktif', 'suspended' => 'Ditangguhkan', 'inactive' => 'Nonaktif'];
    $kelas = ['active' => 'b-aktif', 'suspended' => 'b-tangguh', 'inactive' => 'b-non'];
@endphp

<x-superadmin-layout title="Manajemen Sekolah" subtitle="Kelola data sekolah (tenant) yang terdaftar di sistem.">

<style>
.tools2{display:flex;gap:14px;align-items:center;margin-bottom:14px;flex-wrap:wrap}
.sbox{flex:1;min-width:220px;position:relative}
.sbox input{width:100%;height:40px;border:1px solid var(--line);background:#fff;border-radius:10px;padding:0 14px 0 38px;font:inherit;font-size:12.5px;color:var(--ink)}
.sbox input:focus-visible{outline:2px solid #2f6fe4}
.sbox svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#8a99b0;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round}
.bt-add{background:#1f6bff;color:#fff;font-weight:600;font-size:12.5px;padding:10px 20px;border-radius:9px;text-decoration:none;white-space:nowrap;box-shadow:0 3px 8px rgba(31,107,255,.3)}
.bt-add:focus-visible{outline:2px solid #1f6bff;outline-offset:2px}
.tcard{padding:0;overflow:visible}
.twrap{overflow-x:auto}
.tg{width:100%;border-collapse:collapse;font-size:12px;min-width:720px}
.tg th{background:#f0f4fa;text-align:left;font-weight:600;color:#41506b;padding:12px 14px;font-size:11.5px}
.tg td{padding:14px;border-bottom:1px solid var(--line)}
.tg tr:last-child td{border-bottom:0}
.b-non{background:#eef1f5;border-color:#cfd7e3;color:#5b6a80}
.lihat{display:inline-block;padding:6px 20px;border:1px solid #2f6fe4;color:#2f6fe4;background:#fff;border-radius:8px;font-size:11.5px;font-weight:600;text-decoration:none}
.lihat:hover{background:#eaf1ff}
.aksi{display:flex;align-items:center;gap:10px}
.kb{position:relative}
.kb summary{list-style:none;cursor:pointer;width:24px;height:28px;display:grid;place-items:center;border-radius:6px;color:#41506b;font-size:16px;line-height:1}
.kb summary::-webkit-details-marker{display:none}
.kb summary:hover{background:#eef3fb}
.kb .dd{right:0;top:calc(100% + 4px);min-width:150px}
.empty td{text-align:center;color:var(--muted);padding:34px}
.pager{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;padding:14px}
.pgs{display:flex;gap:6px}
.pg-b{min-width:30px;height:30px;display:grid;place-items:center;border:1px solid var(--line);background:#fff;border-radius:7px;font-size:12px;color:#41506b;text-decoration:none}
.pg-b.on{background:#2f6fe4;border-color:#2f6fe4;color:#fff}
.pg-b.off{opacity:.4;pointer-events:none}
.info{font-size:11.5px;color:var(--muted)}
.flash{background:#e7f7ee;border:1px solid #9edbb8;color:#14633b;border-radius:12px;padding:12px 16px;margin-bottom:14px;font-size:12.5px}
</style>

@if (session('status'))
    <div class="flash" role="status">{{ session('status') }}</div>
@endif

<form class="tools2" method="GET" action="{{ route('super-admin.tenants.index') }}">
    <label class="sbox">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.500-4.500"/></svg>
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama sekolah..." aria-label="Cari nama sekolah">
    </label>
    <a href="{{ route('super-admin.tenants.create') }}" class="bt-add">+ Tambah Sekolah</a>
</form>

<section class="card tcard">
    <div class="twrap">
        <table class="tg">
            <thead>
                <tr><th>No</th><th>Nama Sekolah</th><th>Admin Sekolah</th><th>Pengguna</th><th>Status</th><th>Kuota</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($schools as $s)
                    <tr>
                        <td>{{ $schools->firstItem() + $loop->index }}</td>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->admin_name ?? '–' }}</td>
                        <td>{{ number_format($s->users_count, 0, ',', '.') }}</td>
                        <td><span class="badge {{ $kelas[$s->status] ?? 'b-non' }}">{{ $label[$s->status] ?? ucfirst($s->status) }}</span></td>
                        <td>{{ number_format($s->max_users, 0, ',', '.') }}</td>
                        <td>
                            <div class="aksi">
                                <a href="{{ route('super-admin.tenants.show', $s->id) }}" class="lihat">Lihat</a>
                                <details class="kb">
                                    <summary aria-label="Menu aksi {{ $s->name }}">⋮</summary>
                                    <div class="dd">
                                        <a href="{{ route('super-admin.tenants.edit', $s->id) }}">Edit</a>
                                        <form method="POST" action="{{ route('super-admin.tenants.status', $s->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit">{{ $s->status === 'active' ? 'Tangguhkan' : 'Aktifkan' }}</button>
                                        </form>
                                    </div>
                                </details>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty">
                        <td colspan="7">
                            {{ $q !== '' ? 'Tidak ada sekolah yang cocok dengan "'.$q.'".' : 'Belum ada sekolah terdaftar.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pager">
        <div class="pgs">
            <a class="pg-b {{ $schools->onFirstPage() ? 'off' : '' }}" href="{{ $schools->previousPageUrl() ?? '#' }}" aria-label="Sebelumnya">‹</a>
            @for ($p = $from; $p <= $to; $p++)
                <a class="pg-b {{ $p === $cur ? 'on' : '' }}" href="{{ $schools->url($p) }}">{{ $p }}</a>
            @endfor
            <a class="pg-b {{ $schools->hasMorePages() ? '' : 'off' }}" href="{{ $schools->nextPageUrl() ?? '#' }}" aria-label="Berikutnya">›</a>
        </div>
        <span class="info">
            @if ($schools->total())
                Menampilkan {{ $schools->firstItem() }}-{{ $schools->lastItem() }} dari {{ $schools->total() }} data
            @else
                Tidak ada data
            @endif
        </span>
    </div>
</section>

<script>
  // Tutup menu ⋮ saat klik di luar
  document.addEventListener('click', function (e) {
    document.querySelectorAll('details.kb[open]').forEach(function (d) { if (!d.contains(e.target)) d.removeAttribute('open'); });
  });
</script>

</x-superadmin-layout>