<template>
  <div class="bg-gray-50 min-h-screen pb-20 pt-28">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- ================= TOMBOL KEMBALI ================= -->
      <router-link 
        to="/katalog" 
        class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-forest-700 transition-colors mb-6 group"
      >
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Katalog
      </router-link>

      <!-- ================= KARTU DETAIL ================= -->
      <div v-if="destination" class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        
        <!-- Area Gambar -->
        <div class="relative h-64 sm:h-96 bg-gray-100">
          <img 
            v-if="destination.image"
            :src="destination.image" 
            :alt="destination.title" 
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-forest-100 to-emerald-100 text-forest-300">
            <svg class="w-24 h-24 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          
          <!-- Overlay Gelap di Bawah -->
          <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/60 to-transparent"></div>
          
          <!-- Label Kategori -->
          <span class="absolute top-6 left-6 px-4 py-1.5 rounded-full text-xs font-bold text-white shadow-lg backdrop-blur-md bg-forest-600/90">
            {{ destination.category || 'Kategori' }}
          </span>
        </div>

        <!-- Konten Teks -->
        <div class="p-6 sm:p-10 relative">
          
          <!-- Judul & Lokasi -->
          <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3">
              {{ destination.title }}
            </h1>
            <div class="flex items-center gap-2 text-gray-500 text-sm font-medium">
              <svg class="w-5 h-5 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              {{ destination.village }}
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="prose prose-forest max-w-none mb-10">
            <h3 class="text-lg font-bold text-gray-900 mb-3 border-b border-gray-100 pb-2">Tentang Destinasi</h3>
            <p class="text-gray-600 leading-relaxed text-base">
              {{ destination.description }}
            </p>
          </div>

          <!-- ================= TOMBOL AKSI ================= -->
          <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100">
            <!-- Tombol Maps -->
            <a 
              :href="destination.mapsUrl" 
              target="_blank" 
              rel="noopener noreferrer"
              class="flex-1 flex items-center justify-center gap-2 bg-forest-600 hover:bg-forest-700 text-white font-bold px-6 py-4 rounded-2xl shadow-lg hover:shadow-forest-500/30 transition-all transform hover:-translate-y-0.5"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Buka di Google Maps
            </a>

            <!-- Tombol WhatsApp -->
            <a 
              :href="destination.waUrl" 
              target="_blank" 
              rel="noopener noreferrer"
              class="flex-1 flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-6 py-4 rounded-2xl shadow-lg hover:shadow-green-500/30 transition-all transform hover:-translate-y-0.5"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
              Hubungi Pengelola
            </a>
          </div>

        </div>
      </div>
      
      <!-- ================= JIKA ID TIDAK DITEMUKAN ================= -->
      <div v-else class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300 mt-6">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Destinasi Tidak Ditemukan</h3>
        <p class="text-sm text-gray-500">Maaf, data destinasi dengan ID {{ id }} tidak tersedia.</p>
        <router-link to="/katalog" class="inline-block mt-4 bg-forest-600 text-white font-bold px-6 py-2 rounded-lg">Kembali ke Katalog</router-link>
      </div>

    </div>
  </div>
</template>

<script>
export default { 
  name: 'DetailView',
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      // Array Master (Database Dummy)
      // WAJIB SAMA PERSIS SAMA YANG ADA DI KATALOG / HOME
      allDestinations: [
        {
          id: 1,
          title: 'Curug Ngebul',
          category: 'Wisata Alam',
          village: 'Desa Sukamaju, Jawa Barat',
          description: 'Curug Ngebul adalah air terjun alami dengan pemandangan hutan hijau yang masih asri. Udara di sekitar curug sangat segar dan cocok untuk melepas penat dari hiruk pikuk kota. Terdapat jalur trekking ringan sepanjang 500 meter yang akan membawa Anda melewati hamparan sawah dan kebun warga. Di area bawah air terjun, tersedia kolam alami yang aman untuk berenang.',
          image: 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?q=80&w=1200&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-6.732023,108.545262',
          waUrl: 'https://wa.me/6281234567890?text=Halo%20saya%20tertarik%20dengan%20wisata%20Curug%20Ngebul'
        },
        {
          id: 2,
          title: 'Sate Maranggi Spesial',
          category: 'Kuliner Khas',
          village: 'Desa Cibodas, Jawa Barat',
          description: 'Sate Maranggi khas Cibodas ini dibuat dari daging sapi pilihan yang empuk. Bumbu rahasia turun temurun dari keluarga membuat rasanya sangat khas. Disajikan dengan acar dan nasi hangat, sangat cocok untuk makan siang atau malam.',
          image: 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?q=80&w=1200&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-6.805421,107.617121',
          waUrl: 'https://wa.me/6281234567891?text=Halo%20saya%20ingin%20memesan%20Sate%20Maranggi'
        },
        {
          id: 3,
          title: 'Anyaman Bambu Pak Karto',
          category: 'Kerajinan',
          village: 'Desa Wates, Yogyakarta',
          description: 'Kerajinan anyaman bambu ini dibuat langsung oleh tangan-tangan kreatif warga Desa Wates. Produk yang dihasilkan meliputi tas, tempat buah, dan hiasan dinding. Setiap produk dibuat dengan teliti menggunakan bambu pilihan yang ramah lingkungan.',
          image: 'https://images.unsplash.com/photo-1595079676339-1534801ad6cf?q=80&w=1200&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-7.901234,110.123456',
          waUrl: 'https://wa.me/6281234567892?text=Halo%20saya%20tertarik%20dengan%20anyaman%20bambu'
        }
      ]
    }
  },
  computed: {
    // Ambil data berdasarkan ID dari URL
    destination() {
      // Bandingin string ID (dari URL) dengan angka ID di array
      return this.allDestinations.find(d => String(d.id) === String(this.id)) || null
    }
  }
}
</script>