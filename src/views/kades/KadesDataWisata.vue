<template>
  <DashboardLayout role="kades" userName="Pak Kades" pageTitle="Data Wisata Desa">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Data Wisata Desa</h2>
        <p class="text-sm text-gray-500">Daftar destinasi yang sudah disetujui dan tayang.</p>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 font-medium">
          <tr>
            <th class="px-6 py-4">Nama Wisata</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Agensi Pengelola</th>
            <th class="px-6 py-4">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <!-- Dummy Data -->
          <tr>
            <td class="px-6 py-4 font-medium text-gray-900">Curug Ngebul</td>
            <td class="px-6 py-4">Wisata Alam</td>
            <td class="px-6 py-4">Pesona Alam Travel</td>
            <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Tayang</span></td>
          </tr>
          <tr>
            <td class="px-6 py-4 font-medium text-gray-900">Sate Maranggi</td>
            <td class="px-6 py-4">Kuliner</td>
            <td class="px-6 py-4">Kuliner Desa ID</td>
            <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Tayang</span></td>
          </tr>
        </tbody>
      </table>
    </div>

  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../../layouts/DashboardLayout.vue'
export default { 
  name: 'KadesDataWisata',
  components: { DashboardLayout }
}
</script>