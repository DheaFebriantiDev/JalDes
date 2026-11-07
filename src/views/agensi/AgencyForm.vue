<template>
  <div class="min-h-screen bg-gray-50">
    <div class="bg-emerald-900 text-white px-4 py-4">
      <div class="max-w-4xl mx-auto flex items-center justify-between">
        <h1 class="font-bold text-lg">Tambah Wisata Baru</h1>
        <router-link to="/agensi/dashboard" class="text-sm bg-white text-emerald-900 px-3 py-1.5 rounded-md font-semibold">
          ← Kembali
        </router-link>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10">
      <form class="bg-white rounded-2xl shadow p-6 space-y-5 border border-gray-100">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Destinasi</label>
          <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="Contoh: Curug Ngebul" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400">
              <option>Pilih kategori</option>
              <option>Wisata Alam</option>
              <option>Kuliner Khas</option>
              <option>Kerajinan</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="Desa, Kabupaten" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
          <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="Ceritakan keunikan destinasi..."></textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Link Google Maps</label>
            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="https://maps.google.com/..." />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Link WhatsApp Pengelola</label>
            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="https://wa.me/62..." />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
          <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="https://..." />
        </div>

        <div class="flex gap-3">
          <button type="button" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm">
            Simpan & Ajukan
          </button>
          <router-link to="/agensi/dashboard" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-lg text-sm">
            Batal
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default { name: 'AgencyForm' }
</script>