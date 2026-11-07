<template>
  <div class="bg-gray-50 min-h-screen pb-20">
    
    <!-- ================= HEADER KATALOG ================= -->
    <div class="bg-white border-b border-gray-200 pt-28 pb-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">Jelajahi Potensi Desa</h1>
        <p class="text-gray-500 text-base">Pilih desa, temukan keindahan alam, kuliner, dan kerajinan autentiknya.</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
      
      <!-- ================= FILTER SECTION (DESA & KATEGORI) ================= -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 mb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
          
          <!-- Dropdown Pilih Desa -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Desa Wisata</label>
            <div class="relative">
              <select class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-xl focus:ring-forest-500 focus:border-forest-500 block p-4 pr-10 cursor-pointer">
                <option value="">Semua Desa</option>
                <option value="desa-sukamaju">Desa Sukamaju, Jawa Barat</option>
                <option value="desa-nglinggo">Desa Nglinggo, Yogyakarta</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
              </div>
            </div>
          </div>

          <!-- Input Pencarian -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Cari Nama Destinasi / Wisata</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
              </div>
              <input 
                type="text" 
                placeholder="Ketik nama tempat..." 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-xl focus:ring-forest-500 focus:border-forest-500 block w-full pl-11 p-4" 
              />
            </div>
          </div>

        </div>

        <!-- Filter Kategori (Tombol) -->
        <div class="mt-6 pt-6 border-t border-gray-100">
          <p class="text-sm font-bold text-gray-700 mb-3">Kategori Potensi:</p>
          <div class="flex flex-wrap gap-3">
            
            <!-- Tombol Semua Kategori -->
            <button 
              @click="setKategori('')"
              :class="[
                'inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold border-2 transition-colors duration-200',
                activeKategori === '' 
                  ? 'bg-forest-800 text-white border-forest-800 shadow-md hover:bg-forest-900' 
                  : 'bg-white text-gray-600 border-gray-200 hover:border-forest-400 hover:text-forest-700 hover:bg-forest-50'
              ]"
            >
              <!-- Typo Ikon Udah Dibenerin -->
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
              Semua Kategori
            </button>

            <!-- Tombol Wisata -->
            <button 
              @click="setKategori('wisata')"
              :class="[
                'inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold border-2 transition-colors duration-200',
                activeKategori === 'wisata' 
                  ? 'bg-forest-800 text-white border-forest-800 shadow-md hover:bg-forest-900' 
                  : 'bg-white text-gray-600 border-gray-200 hover:border-forest-400 hover:text-forest-700 hover:bg-forest-50'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              Wisata Alam
            </button>

            <!-- Tombol Kuliner -->
            <button 
              @click="setKategori('kuliner')"
              :class="[
                'inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold border-2 transition-colors duration-200',
                activeKategori === 'kuliner' 
                  ? 'bg-forest-800 text-white border-forest-800 shadow-md hover:bg-forest-900' 
                  : 'bg-white text-gray-600 border-gray-200 hover:border-forest-400 hover:text-forest-700 hover:bg-forest-50'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" /></svg>
              Kuliner Khas
            </button>

            <!-- Tombol Kerajinan -->
            <button 
              @click="setKategori('kerajinan')"
              :class="[
                'inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold border-2 transition-colors duration-200',
                activeKategori === 'kerajinan' 
                  ? 'bg-forest-800 text-white border-forest-800 shadow-md hover:bg-forest-900' 
                  : 'bg-white text-gray-600 border-gray-200 hover:border-forest-400 hover:text-forest-700 hover:bg-forest-50'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
              Kerajinan
            </button>

          </div>
        </div>
      </div>

      <!-- ================= HASIL KATALOG ================= -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">
          Menampilkan Hasil
        </h2>
        <!-- Counter Dinamis -->
        <span class="text-sm text-gray-500 font-medium">{{ filteredDestinations.length }} Destinasi Ditemukan</span>
      </div>

      <!-- Grid Card Dinamis -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <DestinationCard 
          v-for="dest in filteredDestinations" 
          :key="dest.id" 
          :item="dest" 
        />
      </div>

      <!-- Empty State -->
      <div v-if="filteredDestinations.length === 0" class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300 mt-6">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Destinasi tidak ditemukan</h3>
        <p class="text-sm text-gray-500">Coba ubah kata kunci atau pilih kategori lain.</p>
      </div>

    </div>
  </div>
</template>

<script>
import DestinationCard from '../../components/DestinationCard.vue'

export default {
  name: 'KatalogView',
  components: { DestinationCard },
  data() {
    return {
      activeKategori: '',
      // Data Dummy
      destinations: [
        {
          id: 1,
          title: 'Curug Ngebul',
          village: 'Desa Sukamaju, Jawa Barat',
          category: 'Wisata Alam',
          categorySlug: 'wisata',
          description: 'Air terjun alami dengan pemandangan hutan hijau dan udara segar pegunungan.',
          image: 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?q=80&w=800&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-6.732023,108.545262',
          waUrl: 'https://wa.me/6281234567890?text=Halo%20saya%20tertarik%20dengan%20wisata%20Curug%20Ngebul'
        },
        {
          id: 2,
          title: 'Sate Maranggi Spesial',
          village: 'Desa Cibodas, Jawa Barat',
          category: 'Kuliner Khas',
          categorySlug: 'kuliner',
          description: 'Sate daging sapi empuk dengan bumbu rahasia turun temurun, dibakar di atas bara arang.',
          image: 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?q=80&w=800&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-6.805421,107.617121',
          waUrl: 'https://wa.me/6281234567891?text=Halo%20saya%20ingin%20memesan%20Sate%20Maranggi'
        },
        {
          id: 3,
          title: 'Anyaman Bambu Pak Karto',
          village: 'Desa Wates, Yogyakarta',
          category: 'Kerajinan',
          categorySlug: 'kerajinan',
          description: 'Kerajinan anyaman bambu handmade berupa tas, tempat buah, dan hiasan rumah.',
          image: 'https://images.unsplash.com/photo-1595079676339-1534801ad6cf?q=80&w=800&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-7.901234,110.123456',
          waUrl: 'https://wa.me/6281234567892?text=Halo%20saya%20tertarik%20dengan%20anyaman%20bambu'
        }
      ]
    }
  },
  computed: {
    // Filter berdasarkan kategori
    filteredDestinations() {
      if (!this.activeKategori) {
        return this.destinations
      }
      return this.destinations.filter(
        (dest) => dest.categorySlug === this.activeKategori
      )
    }
  },
  mounted() {
    this.activeKategori = this.$route.query.kategori || ''
  },
  watch: {
    '$route.query.kategori'(newKategori) {
      this.activeKategori = newKategori || ''
    }
  },
  methods: {
    setKategori(kategori) {
      this.activeKategori = kategori
      this.$router.push({ 
        path: '/katalog', 
        query: kategori ? { kategori: kategori } : {} 
      })
    }
  }
}
</script>