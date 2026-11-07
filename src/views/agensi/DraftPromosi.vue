<template>
  <DashboardLayout role="agensi" userName="Agensi Travel" pageTitle="Draft Promosi">
    
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-1">Buat Draft Promosi</h2>
      <p class="text-sm text-gray-500">Gunakan AI untuk membuat caption menarik untuk destinasi Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Form Input -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Destinasi</label>
          <select class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest-500">
            <option>Curug Ngebul</option>
            <option>Kopi Robusta</option>
          </select>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-2">Gaya Bahasa</label>
          <select class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-forest-500">
            <option>Santai & Menarik</option>
            <option>Formal & Informatif</option>
            <option>Puitis & Estetik</option>
          </select>
        </div>
        <button class="w-full bg-forest-600 hover:bg-forest-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-colors mt-2">
          Generate Teks
        </button>
      </div>

      <!-- Hasil -->
      <div class="bg-forest-50 rounded-2xl border border-forest-200 p-6 relative">
        <h3 class="font-bold text-forest-900 mb-3 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
          Hasil AI
        </h3>
        <div class="bg-white rounded-xl p-4 text-sm text-gray-700 leading-relaxed min-h-[150px] border border-forest-100 shadow-sm">
          "Temukan kesegaran alam tersembunyi di Curug Ngebul! Rasakan sensasi gemericik air terjun yang jernih, dikelilingi hutan hijau yang asri. Cocok untuk healing akhir pekan bersama keluarga. Yuk, rencanakan liburanmu sekarang! 🌿💧"
        </div>
        <button class="absolute bottom-6 right-6 bg-white text-forest-700 text-xs font-bold px-4 py-2 rounded-lg border border-forest-200 shadow-sm hover:bg-forest-100 transition-colors">
          Copy Teks
        </button>
      </div>
    </div>

  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../../layouts/DashboardLayout.vue'
export default { 
  name: 'DraftPromosi',
  components: { DashboardLayout }
}
</script>