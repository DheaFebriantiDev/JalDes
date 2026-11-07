<template>
  <DashboardLayout role="kades" userName="Pak Kades" pageTitle="Dashboard Kepala Desa">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Ringkasan Desa</h2>
        <p class="text-sm text-gray-500">Pantau aktivitas dan potensi wisata di desa Anda hari ini.</p>
      </div>
      <router-link to="/kades/moderasi" class="inline-flex items-center gap-2 bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-bold px-4 py-2.5 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        Tinjau Usulan (3)
      </router-link>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
      
      <!-- Card 1 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
          </div>
          <p class="text-sm font-bold text-gray-500">Total Wisata Tayang</p>
        </div>
        <div class="flex items-end justify-between">
          <p class="text-4xl font-extrabold text-gray-900">12</p>
          <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">+2 minggu ini</span>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <p class="text-sm font-bold text-gray-500">Menunggu Persetujuan</p>
        </div>
        <div class="flex items-end justify-between">
          <p class="text-4xl font-extrabold text-orange-500">3</p>
          <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-1 rounded-md">Perlu Review</span>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
          </div>
          <p class="text-sm font-bold text-gray-500">Agensi Terdaftar</p>
        </div>
        <div class="flex items-end justify-between">
          <p class="text-4xl font-extrabold text-blue-600">5</p>
          <span class="text-xs font-bold text-gray-400">Aktif</span>
        </div>
      </div>

    </div>

    <!-- Tabel Usulan Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900 text-lg">Usulan Wisata Terbaru</h3>
        <router-link to="/kades/moderasi" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
          Lihat Semua
        </router-link>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-50 text-gray-500 font-bold">
            <tr>
              <th class="px-6 py-4">Nama Destinasi</th>
              <th class="px-6 py-4">Agensi Pengusul</th>
              <th class="px-6 py-4">Kategori</th>
              <th class="px-6 py-4">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">Air Terjun Tersembunyi</td>
              <td class="px-6 py-4 text-gray-600">Pesona Alam Travel</td>
              <td class="px-6 py-4"><span class="text-xs font-semibold text-emerald-700">Wisata Alam</span></td>
              <td class="px-6 py-4"><span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">Menunggu</span></td>
            </tr>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">Sate Maranggi Spesial</td>
              <td class="px-6 py-4 text-gray-600">Kuliner Desa ID</td>
              <td class="px-6 py-4"><span class="text-xs font-semibold text-orange-600">Kuliner</span></td>
              <td class="px-6 py-4"><span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">Menunggu</span></td>
            </tr>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">Anyaman Bambu Kreatif</td>
              <td class="px-6 py-4 text-gray-600">Kriya Nusantara</td>
              <td class="px-6 py-4"><span class="text-xs font-semibold text-blue-600">Kerajinan</span></td>
              <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Disetujui</span></td>
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
  name: 'KadesDashboard',
  components: { DashboardLayout }
}
</script>