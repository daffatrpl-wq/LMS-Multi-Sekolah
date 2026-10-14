<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Sekolah / Tenant Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('super-admin.tenants.store') }}" method="POST">
                    @csrf

                    <!-- Informasi Sekolah -->
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Sekolah / Tenant</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Sekolah</label>
                        <input type="text" name="school_name" value="{{ old('school_name') }}" required placeholder="Contoh: SMAN 1 Banyuwangi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('school_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Sekolah</label>
                        <textarea name="address" rows="3" required placeholder="Alamat lengkap sekolah..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Kontak Sekolah</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" required placeholder="admin@sekolah.sch.id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="my-6">

                    <!-- Informasi Akun Admin Sekolah Pertama (FR-SA-01) -->
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Akun Admin Sekolah Pertama</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap Admin Sekolah</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required placeholder="Nama Administrator" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('admin_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Admin (Digunakan untuk Login)</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required placeholder="admin.sekolah@gmail.com" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kuota Layanan (FR-SA-04) -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Maksimum Kuota Pengguna</label>
                            <input type="number" name="max_users" value="{{ old('max_users', 100) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Maksimum Kuota Kelas</label>
                            <input type="number" name="max_classes" value="{{ old('max_classes', 10) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>

                    <!-- Tombol Aksi (Kembali & Simpan) -->
                    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4 font-medium">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Simpan & Daftarkan Sekolah
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>