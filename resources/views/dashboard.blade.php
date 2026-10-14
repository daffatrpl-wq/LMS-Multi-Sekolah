<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Super Admin - Manajemen Tenant') }}
            </h2>
            <a href="{{ route('super-admin.tenants.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Tambah Sekolah Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-6">
            
            <!-- Notifikasi Pesan Sukses -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistik Global (FR-SA-03) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm font-medium">Total Tenant / Sekolah Terdaftar</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $globalStats['total_tenants'] ?? 0 }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm font-medium">Tenant Aktif</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $globalStats['active_tenants'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Tabel Daftar Sekolah / Tenant -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Sekolah / Tenant</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sekolah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>

                       <tbody class="bg-white divide-y divide-gray-200">
    @forelse($tenants ?? [] as $tenant)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $tenant->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $tenant->contact_email }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tenant->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($tenant->status) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $tenant->max_users }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('super-admin.tenants.edit', $tenant->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
            <form action="{{ route('super-admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus sekolah ini?')">Hapus</button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data sekolah yang terdaftar.</td>
    </tr>
    @endforelse
</tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
  
</x-app-layout>