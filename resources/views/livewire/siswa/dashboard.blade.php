<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Banner Sambutan -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 overflow-hidden shadow-sm sm:rounded-lg p-6 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                    <p class="mt-1 text-blue-100">Tetap semangat belajar dan pastikan semua tugasmu dikumpulkan tepat waktu.</p>
                </div>
                <div class="hidden md:block">
                    <!-- Tombol Gabung Kelas / Aksi Cepat -->
                    <a href="#" class="bg-white text-indigo-600 font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-50 transition">
                        + Gabung Kelas Baru
                    </a>
                </div>
            </div>

            <!-- Statistik Ringkas (Grid 3 Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Kelas Diikuti -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium">Kelas Aktif</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">4</div>
                    <div class="text-xs text-gray-400 mt-1">Mata pelajaran semester ini</div>
                </div>

                <!-- Card 2: Tugas Pending -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500 text-sm font-medium">Tugas Belum Dikerjakan</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">2</div>
                    <div class="text-xs text-red-500 mt-1">Segera kumpulkan sebelum deadline</div>
                </div>

                <!-- Card 3: Kehadiran -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-medium">Kehadiran (Presensi)</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">95%</div>
                    <div class="text-xs text-green-600 mt-1">Kehadiran sangat baik</div>
                </div>
            </div>

            <!-- Konten Utama: Daftar Kelas & Tugas Mendatang -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Kolom Kiri: Daftar Kelas yang Diikuti (2 Span) -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-bold text-gray-800">Kelas Saya</h4>
                        <a href="#" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="space-y-4">
                        <!-- Item Kelas 1 -->
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                            <div>
                                <h5 class="font-semibold text-gray-800">Matematika Lanjut</h5>
                                <p class="text-sm text-gray-500">Pengampu: Budi Santoso, S.Pd.</p>
                            </div>
                            <a href="#" class="px-4 py-2 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-md hover:bg-indigo-100">Buka Kelas</a>
                        </div>

                        <!-- Item Kelas 2 -->
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                            <div>
                                <h5 class="font-semibold text-gray-800">Pemrograman Web Dasar</h5>
                                <p class="text-sm text-gray-500">Pengampu: Siti Aminah, M.Kom.</p>
                            </div>
                            <a href="#" class="px-4 py-2 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-md hover:bg-indigo-100">Buka Kelas</a>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Tugas Mendatang (1 Span) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Tenggat Tugas</h4>

                    <div class="space-y-4">
                        <!-- Tugas 1 -->
                        <div class="p-3 border-l-4 border-red-500 bg-red-50 rounded-r-lg">
                            <span class="text-xs font-semibold text-red-600">Hari ini, 23:59</span>
                            <h5 class="font-medium text-gray-800 text-sm mt-1">Latihan Soal Turunan Fungsi</h5>
                            <p class="text-xs text-gray-500 mt-1">Matematika Lanjut</p>
                        </div>

                        <!-- Tugas 2 -->
                        <div class="p-3 border-l-4 border-yellow-500 bg-yellow-50 rounded-r-lg">
                            <span class="text-xs font-semibold text-yellow-600">Besok, 12:00</span>
                            <h5 class="font-medium text-gray-800 text-sm mt-1">Membuat Layout CRUD Laravel</h5>
                            <p class="text-xs text-gray-500 mt-1">Pemrograman Web Dasar</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>