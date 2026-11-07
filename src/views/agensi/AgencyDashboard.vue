<template>
  <DashboardLayout role="agensi" userName="Agensi Travel" pageTitle="Dashboard Agensi">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Ringkasan Agensi</h2>
        <p class="text-sm text-gray-500">Pantau performa destinasi wisata yang Anda kelola.</p>
      </div>
      <!-- Tombol Aksi Cepat -->
      <div class="flex items-center gap-3">
        <router-link to="/agensi/promosi" class="inline-flex items-center gap-2 bg-forest-50 hover:bg-forest-100 text-forest-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors border border-forest-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
          Draft Promosi AI
        </router-link>
        <router-link to="/agensi/wisata" class="inline-flex items-center gap-2 bg-forest-600 hover:bg-forest-700 text-dark text-sm font-bold px-4 py-2.5 rounded-xl shadow-md transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
          Tambah Wisata
        </router-link>
      </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      
      <!-- Card 1: Total -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
          </div>
          <p class="text-sm font-bold text-gray-500">Total Wisata</p>
        </div>
        <p class="text-4xl font-extrabold text-gray-900">5</p>
      </div>

      <!-- Card 2: Approved -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <p class="text-sm font-bold text-gray-500">Tayang (Approved)</p>
        </div>
        <div class="flex items-end justify-between">
          <p class="text-4xl font-extrabold text-green-600">3</p>
          <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md">Aktif</span>
        </div>
      </div>

      <!-- Card 3: Pending -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <p class="text-sm font-bold text-gray-500">Menunggu Review</p>
        </div>
        <div class="flex items-end justify-between">
          <p class="text-4xl font-extrabold text-orange-500">2</p>
          <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-1 rounded-md">Cek Status</span>
        </div>
      </div>

    </div>

    <!-- Tabel Wisata Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900 text-lg">Aktivitas Wisata Terbaru</h3>
        <router-link to="/agensi/wisata" class="text-sm font-bold text-forest-600 hover:text-forest-700 transition-colors">
          Lihat Semua
        </router-link>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-50 text-gray-500 font-bold">
            <tr>
              <th class="px-6 py-4">Nama Wisata</th>
              <th class="px-6 py-4">Kategori</th>
              <th class="px-6 py-4">Tanggal Didaftarkan</th>
              <th class="px-6 py-4">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">Curug Ngebul</td>
              <td class="px-6 py-4"><span class="text-xs font-semibold text-emerald-700">Wisata Alam</span></td>
              <td class="px-6 py-4 text-gray-500">12 Okt 2026</td>
              <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Approved</span></td>
            </tr>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">Kopi Robusta Lereng Merapi</td>
              <td class="px-6 py-4"><span class="text-xs font-semibold text-orange-600">Kuliner</span></td>
              <td class="px-6 py-4 text-gray-500">14 Okt 2026</td>
              <td class="px-6 py-4"><span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">Pending</span></td>
            </tr>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">Anyaman Bambu</td>
              <td class="px-6 py-4"><span class="text-xs font-semibold text-blue-600">Kerajinan</span></td>
              <td class="px-6 py-4 text-gray-500">10 Okt 2026</td>
              <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Approved</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../../layouts/DashboardLayout.vue'
export default { 
  name: 'AgencyDashboard',
  components: { DashboardLayout }
}
</script>