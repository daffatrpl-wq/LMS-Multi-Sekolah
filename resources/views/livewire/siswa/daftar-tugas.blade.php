<x-siswa-layout title="Tugas" subtitle="Daftar tugas yang perlu kamu kerjakan.">

<style>
.tools{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px}
.seg{display:inline-flex;background:#fff;border:1px solid var(--line);border-radius:10px;padding:3px;gap:2px}
.seg button{border:0;background:none;font:inherit;font-size:12px;font-weight:600;color:#41506b;padding:7px 24px;border-radius:8px;cursor:pointer}
.seg button.on{background:#2f6fe4;color:#fff;box-shadow:0 2px 6px rgba(47,111,228,.3)}
.seg button:focus-visible,.pg-b:focus-visible,.abtn:focus-visible,.search input:focus-visible{outline:2px solid #2f6fe4;outline-offset:1px}
.search input{width:260px;max-width:100%;height:34px;border:1px solid var(--line);background:#fff;border-radius:10px;padding:0 14px;font:inherit;font-size:12px;color:var(--ink)}
.tcard{padding:0;overflow:hidden}
.twrap{overflow-x:auto}
.tg{width:100%;border-collapse:collapse;font-size:12px;min-width:640px}
.tg th{background:#f0f4fa;text-align:left;font-weight:600;color:#41506b;padding:11px 14px;font-size:11.5px}
.tg td{padding:13px 14px;border-bottom:1px solid var(--line)}
.tg tr:last-child td{border-bottom:0}
.tj{display:flex;align-items:center;gap:10px;font-weight:500}
.fi{width:20px;height:20px;border-radius:5px;background:#e4edff;color:#2f6fe4;display:grid;place-items:center;flex:none}
.fi .i{width:12px;height:12px}
.bd{display:inline-block;min-width:62px;text-align:center;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;border:1px solid}
.bd.belum{background:#ffe6e6;color:#d93b3b;border-color:#f5b8b8}
.bd.selesai{background:#dff5e8;color:#1b9a58;border-color:#a9dfc0}
.abtn{display:inline-block;min-width:76px;text-align:center;padding:6px 16px;border-radius:8px;font-size:11.5px;font-weight:600;text-decoration:none;color:#2f56c8;background:#eaf1ff;border:1px solid #8db0e8}
.abtn:hover{background:#dce8ff}
.abtn.lihat{background:#f4f7fc;border-color:#c9d6ea;color:#41506b}
.empty td{text-align:center;color:var(--muted);padding:28px}
.pager{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;padding:12px 14px;border-top:1px solid var(--line)}
.pgs{display:flex;gap:6px}
.pg-b{min-width:28px;height:28px;border:1px solid var(--line);background:#fff;border-radius:7px;font:inherit;font-size:12px;color:#41506b;cursor:pointer}
.pg-b.on{background:#2f6fe4;border-color:#2f6fe4;color:#fff}
.pg-b:disabled{opacity:.45;cursor:default}
.info{font-size:11.5px;color:var(--muted)}
</style>

<div class="tools">
    <div class="seg" role="tablist" aria-label="Filter status tugas">
        <button type="button" class="on" data-tab="aktif">Aktif</button>
        <button type="button" data-tab="selesai">Selesai</button>
        <button type="button" data-tab="semua">Semua</button>
    </div>
    <label class="search"><input id="cari" type="search" placeholder="Cari tugas..." aria-label="Cari tugas"></label>
</div>

<section class="card tcard">
    <div class="twrap">
        <table class="tg">
            <thead>
                <tr><th>No</th><th>Judul Tugas</th><th>Mata Pelajaran</th><th>Deadline</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody id="rows"></tbody>
        </table>
    </div>
    <div class="pager">
        <div class="pgs" id="pgs"></div>
        <span class="info" id="info"></span>
    </div>
</section>

<script>
(function () {
  var data = @json($tugas), PER = 5, tab = 'aktif', q = '', page = 1;
  var rows = document.getElementById('rows'), pgs = document.getElementById('pgs'), info = document.getElementById('info');

  function el(tag, cls, text) {
    var e = document.createElement(tag);
    if (cls) e.className = cls;
    if (text != null) e.textContent = text;
    return e;
  }

  function filtered() {
    return data.filter(function (t) {
      var okTab = tab === 'semua' || (tab === 'aktif' ? t.status === 'belum' : t.status === 'selesai');
      var s = (t.judul + ' ' + t.mapel).toLowerCase();
      return okTab && s.indexOf(q) !== -1;
    });
  }

  function render() {
    var list = filtered(), total = list.length, pages = Math.max(1, Math.ceil(total / PER));
    if (page > pages) page = pages;
    var start = (page - 1) * PER, slice = list.slice(start, start + PER);

    rows.textContent = '';
    if (!slice.length) {
      var er = el('tr', 'empty'), ec = el('td', null, 'Tidak ada tugas ditemukan.');
      ec.colSpan = 6; er.appendChild(ec); rows.appendChild(er);
    }
    slice.forEach(function (t, i) {
      var tr = el('tr');
      tr.appendChild(el('td', null, String(start + i + 1)));

      var tdJ = el('td'), wrap = el('div', 'tj'), ic = el('span', 'fi');
      ic.innerHTML = '<svg class="i"><use href="#i-exam"/></svg>';
      wrap.appendChild(ic); wrap.appendChild(el('span', null, t.judul)); tdJ.appendChild(wrap);
      tr.appendChild(tdJ);

      tr.appendChild(el('td', null, t.mapel));
      tr.appendChild(el('td', null, t.tenggat));

      var tdS = el('td');
      tdS.appendChild(el('span', 'bd ' + t.status, t.status === 'belum' ? 'Belum' : 'Selesai'));
      tr.appendChild(tdS);

      var tdA = el('td'), a = el('a', 'abtn' + (t.status === 'selesai' ? ' lihat' : ''), t.status === 'selesai' ? 'Lihat' : 'Kerjakan');
      a.href = t.url; tdA.appendChild(a); tr.appendChild(tdA);
      rows.appendChild(tr);
    });

    pgs.textContent = '';
    var prev = el('button', 'pg-b', '‹'); prev.type = 'button'; prev.disabled = page === 1; prev.setAttribute('aria-label', 'Sebelumnya');
    prev.onclick = function () { page--; render(); }; pgs.appendChild(prev);
    for (var p = 1; p <= pages; p++) (function (n) {
      var b = el('button', 'pg-b' + (n === page ? ' on' : ''), String(n)); b.type = 'button';
      b.onclick = function () { page = n; render(); }; pgs.appendChild(b);
    })(p);
    var next = el('button', 'pg-b', '›'); next.type = 'button'; next.disabled = page === pages; next.setAttribute('aria-label', 'Berikutnya');
    next.onclick = function () { page++; render(); }; pgs.appendChild(next);

    info.textContent = total ? 'Menampilkan ' + (start + 1) + '-' + (start + slice.length) + ' dari ' + total + ' data' : 'Tidak ada data';
  }

  document.querySelectorAll('.seg button').forEach(function (b) {
    b.addEventListener('click', function () {
      document.querySelectorAll('.seg button').forEach(function (x) { x.classList.remove('on'); });
      b.classList.add('on'); tab = b.dataset.tab; page = 1; render();
    });
  });
  document.getElementById('cari').addEventListener('input', function (e) {
    q = e.target.value.trim().toLowerCase(); page = 1; render();
  });

  render();
})();
</script>

</x-siswa-layout>