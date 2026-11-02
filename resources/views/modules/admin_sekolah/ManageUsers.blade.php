<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Halaman -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna Sekolah</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data akun guru dan siswa yang terdaftar di institusi Anda.</p>
            </div>
            <div>
                <button wire:click="$dispatch('open-modal-tambah-user')" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold shadow-sm hover:bg-indigo-700 transition">
                    + Tambah Pengguna Baru
                </button>
            </div>
        </div>

        <!-- Filter & Pencarian -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="w-full md:w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..." class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="w-full md:w-auto flex gap-3">
                <select wire:model.live="roleFilter" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Peran (Role)</option>
                    <option value="teacher">Guru</option>
                    <option value="student">Siswa</option>
                </select>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Terdaftar Sejak</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                        {{ $user->role === 'guru' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Edit</button>
                                    <button class="text-red-600 hover:text-red-900 font-medium text-xs">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                                    Tidak ada data pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</div>