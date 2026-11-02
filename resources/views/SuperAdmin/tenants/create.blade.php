@php
    $fields = fn (string $name) => $errors->has($name) ? 'bad' : '';
@endphp

<x-superadmin-layout title="Tambah Sekolah" subtitle="Daftarkan sekolah baru sebagai tenant dalam sistem.">

    @if (session('tenant_created'))
        @php $t = session('tenant_created'); @endphp
        <div class="ok" role="status">
            <b>Sekolah "{{ $t['sekolah'] }}" berhasil didaftarkan.</b><br>
            Akun Admin Sekolah dibuat. Catat dan berikan kepada admin (password hanya ditampilkan sekali):<br>
            Email: <code>{{ $t['email'] }}</code> &nbsp; Password sementara: <code>{{ $t['password'] }}</code>
        </div>
    @endif

    <form method="POST" action="{{ route('super-admin.tenants.store') }}" id="formSekolah" novalidate>
        @csrf

        <section class="fcard">
            <h3>Data Sekolah</h3>
            <div class="fgrid">
                <div class="fld">
                    <label for="name">Nama Sekolah <b>*</b></label>
                    <input id="name" name="name" type="text" class="{{ $fields('name') }}" value="{{ old('name') }}" placeholder="Contoh: SMAN 1 Banyuwangi" maxlength="255" required>
                    @error('name')<small class="err">{{ $message }}</small>@enderror
                </div>
                <div class="fld">
                    <label for="address">Alamat <b>*</b></label>
                    <input id="address" name="address" type="text" class="{{ $fields('address') }}" value="{{ old('address') }}" placeholder="Jl. Contoh No. 123, Banyuwangi" maxlength="255" required>
                    @error('address')<small class="err">{{ $message }}</small>@enderror
                </div>
                <div class="fld">
                    <label for="email">Email Sekolah <b>*</b></label>
                    <input id="email" name="email" type="email" class="{{ $fields('email') }}" value="{{ old('email') }}" placeholder="email@sekolah.sch.id" maxlength="255" required>
                    @error('email')<small class="err">{{ $message }}</small>@enderror
                </div>
                <div class="fld">
                    <label for="phone">No. Telepon <b>*</b></label>
                    <input id="phone" name="phone" type="tel" class="{{ $fields('phone') }}" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" maxlength="20" required>
                    @error('phone')<small class="err">{{ $message }}</small>@enderror
                </div>
            </div>
        </section>

        <section class="fcard">
            <h3>Admin Sekolah</h3>
            <div class="fgrid">
                <div class="fld">
                    <label for="admin_name">Nama Admin <b>*</b></label>
                    <input id="admin_name" name="admin_name" type="text" class="{{ $fields('admin_name') }}" value="{{ old('admin_name') }}" placeholder="Nama admin sekolah" maxlength="255" required>
                    @error('admin_name')<small class="err">{{ $message }}</small>@enderror
                </div>
                <div class="fld">
                    <label for="admin_email">Email Admin <b>*</b></label>
                    <input id="admin_email" name="admin_email" type="email" class="{{ $fields('admin_email') }}" value="{{ old('admin_email') }}" placeholder="admin@sekolah.sch.id" maxlength="255" required>
                    @error('admin_email')<small class="err">{{ $message }}</small>@enderror
                </div>
            </div>
        </section>

        <section class="fcard">
            <h3>Konfigurasi Kuota</h3>
            <div class="fgrid">
                <div class="fld">
                    <label for="max_users">Maks. Pengguna <b>*</b></label>
                    <input id="max_users" name="max_users" type="number" min="1" max="100000" class="{{ $fields('max_users') }}" value="{{ old('max_users', 500) }}" required>
                    @error('max_users')<small class="err">{{ $message }}</small>@enderror
                </div>
                <div class="fld">
                    <label for="max_classes">Maks. Kelas <b>*</b></label>
                    <input id="max_classes" name="max_classes" type="number" min="1" max="10000" class="{{ $fields('max_classes') }}" value="{{ old('max_classes', 100) }}" required>
                    @error('max_classes')<small class="err">{{ $message }}</small>@enderror
                </div>
            </div>
            <div class="factions">
                <a href="{{ route('super-admin.tenants.index') }}" class="bt ghost">Batal</a>
                <button type="submit" class="bt pri" id="btnSimpan">Daftarkan Sekolah</button>
            </div>
        </section>
    </form>

<script>
  document.getElementById('formSekolah').addEventListener('submit', function (e) {
    var f = e.target;
    if (!f.checkValidity()) { e.preventDefault(); f.reportValidity(); return; }
    var b = document.getElementById('btnSimpan'); b.disabled = true; b.textContent = 'Menyimpan...';
  });
</script>

</x-superadmin-layout>