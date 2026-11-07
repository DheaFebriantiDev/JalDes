<template>
  <DashboardLayout role="agensi" userName="Agensi Travel" pageTitle="Wisata Saya">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Daftar Wisata</h2>
        <p class="text-sm text-gray-500">Kelola destinasi yang Anda daftarkan.</p>
      </div>
      <button class="inline-flex items-center gap-2 bg-forest-600 hover:bg-forest-700 text-dark text-sm font-bold px-5 py-2.5 rounded-xl shadow-md transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Tambah Wisata
      </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 font-medium">
          <tr>
            <th class="px-6 py-4">Nama Wisata</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr>
            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
              <div class="flex flex-col items-center justify-center">
                <p>Belum ada data wisata. Silakan tambah wisata baru.</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../../layouts/DashboardLayout.vue'
export default { 
  name: 'WisataSaya',
  components: { DashboardLayout }
}
</script>