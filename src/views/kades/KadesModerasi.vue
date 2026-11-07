<template>
  <DashboardLayout role="kades" userName="Pak Kades" pageTitle="Moderasi & Persetujuan">
    
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-1">Persetujuan Wisata Baru</h2>
      <p class="text-sm text-gray-500">Tinjau usulan destinasi dari agensi sebelum ditayangkan ke publik.</p>
    </div>

    <!-- Daftar Usulan (Kartu) -->
    <div class="space-y-4">
      
      <!-- Kartu 1 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6 items-start md:items-center">
        <img src="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?q=80&w=400&auto=format&fit=crop" alt="Wisata" class="w-full md:w-32 h-32 object-cover rounded-xl" />
        
        <div class="flex-1">
          <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full mb-2 inline-block">Wisata Alam</span>
          <h3 class="text-xl font-bold text-gray-900 mb-1">Air Terjun Tersembunyi</h3>
          <p class="text-sm text-gray-500 mb-2">Diusulkan oleh: <b>Pesona Alam Travel</b></p>
          <p class="text-sm text-gray-600 line-clamp-2">Air terjun dengan pemandangan eksotis dan akses trekking yang menantang...</p>
        </div>

        <div class="flex md:flex-col gap-2 w-full md:w-auto mt-4 md:mt-0">
          <button class="flex-1 md:w-32 bg-green-500 hover:bg-green-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition-colors">
            Setujui
          </button>
          <button class="flex-1 md:w-32 bg-red-100 hover:bg-red-200 text-red-700 font-bold py-2.5 rounded-xl transition-colors">
            Tolak
          </button>
        </div>
      </div>

      <!-- Kartu 2 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6 items-start md:items-center">
        <img src="https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?q=80&w=400&auto=format&fit=crop" alt="Kuliner" class="w-full md:w-32 h-32 object-cover rounded-xl" />
        
        <div class="flex-1">
          <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full mb-2 inline-block">Kuliner</span>
          <h3 class="text-xl font-bold text-gray-900 mb-1">Sate Maranggi Spesial</h3>
          <p class="text-sm text-gray-500 mb-2">Diusulkan oleh: <b>Kuliner Desa ID</b></p>
          <p class="text-sm text-gray-600 line-clamp-2">Sate daging sapi empuk dengan bumbu rahasia turun temurun...</p>
        </div>

        <div class="flex md:flex-col gap-2 w-full md:w-auto mt-4 md:mt-0">
          <button class="flex-1 md:w-32 bg-green-500 hover:bg-green-600 text-white font-bold py-2.5 rounded-xl shadow-sm transition-colors">
            Setujui
          </button>
          <button class="flex-1 md:w-32 bg-red-100 hover:bg-red-200 text-red-700 font-bold py-2.5 rounded-xl transition-colors">
            Tolak
          </button>
        </div>
      </div>

    </div>

  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../../layouts/DashboardLayout.vue'
export default { 
  name: 'KadesModerasi',
  components: { DashboardLayout }
}
</script>