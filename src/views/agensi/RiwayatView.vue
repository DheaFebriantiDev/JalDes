<template>
  <DashboardLayout role="agensi" userName="Agensi Travel" pageTitle="Riwayat Aktivitas">
    
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-1">Riwayat / History</h2>
      <p class="text-sm text-gray-500">Catatan aktivitas pendaftaran dan moderasi wisata Anda.</p>
    </div>

    <!-- Timeline Sederhana -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
      <div class="relative border-l-2 border-forest-200 ml-4 space-y-8 pb-4">
        
        <!-- Timeline Item -->
        <div class="relative pl-8">
          <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-green-500 border-4 border-white shadow"></div>
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-1">
            <h4 class="font-bold text-gray-900">Wisata "Curug Ngebul" Disetujui</h4>
            <span class="text-xs font-semibold text-gray-400">Hari ini, 10:30</span>
          </div>
          <p class="text-sm text-gray-600">Oleh: Kepala Desa Sukamaju.</p>
        </div>

        <!-- Timeline Item -->
        <div class="relative pl-8">
          <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-orange-400 border-4 border-white shadow"></div>
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-1">
            <h4 class="font-bold text-gray-900">Mengajukan Wisata "Kopi Robusta"</h4>
            <span class="text-xs font-semibold text-gray-400">Kemarin, 14:15</span>
          </div>
          <p class="text-sm text-gray-600">Status: Menunggu Moderasi.</p>
        </div>

        <!-- Timeline Item -->
        <div class="relative pl-8">
          <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 border-4 border-white shadow"></div>
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-1">
            <h4 class="font-bold text-gray-900">Membuat Draft Promosi AI</h4>
            <span class="text-xs font-semibold text-gray-400">12 Okt 2026</span>
          </div>
          <p class="text-sm text-gray-600">Untuk destinasi Curug Ngebul.</p>
        </div>

      </div>
    </div>

  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../../layouts/DashboardLayout.vue'
export default { 
  name: 'RiwayatView',
  components: { DashboardLayout }
}
</script>