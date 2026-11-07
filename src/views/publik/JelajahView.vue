<template>
  <div class="flex h-screen w-full bg-black overflow-hidden relative">
    
    <!-- ================= SIDEBAR KIRI (Desktop Only) ================= -->
    <aside class="hidden lg:flex flex-col w-64 bg-forest-900 text-white z-50 shadow-2xl border-r border-forest-800">
      
      <!-- Logo -->
      <router-link to="/" class="flex items-center gap-3 px-6 h-20 border-b border-forest-800 shrink-0">
        <img :src="logo" alt="Logo" class="w-10 h-10 rounded-full object-cover" />
        <div class="flex flex-col leading-none">
          <span class="font-extrabold text-lg tracking-tight">DesaWisata</span>
          <span class="text-[10px] tracking-[0.25em] uppercase font-bold text-emerald-400 mt-0.5">Explorer</span>
        </div>
      </router-link>

      <!-- Menu Navigasi -->
      <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
        <p class="px-3 text-xs font-bold text-forest-400 uppercase tracking-wider mb-4">Menu</p>
        
        <router-link to="/" class="flex items-center gap-3 px-3 py-3 rounded-xl text-forest-100 hover:bg-forest-800 hover:text-white transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
          Beranda
        </router-link>

        <router-link to="/katalog" class="flex items-center gap-3 px-3 py-3 rounded-xl text-forest-100 hover:bg-forest-800 hover:text-white transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
          Katalog Wisata
        </router-link>

        <!-- Menu Aktif (Reels) -->
        <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-md">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16v16H4V4zm14 2H6v12h12V6zm-6 3l4 2-4 2V9z"/></svg>
          Reels Desa
        </div>

        <router-link to="/about" class="flex items-center gap-3 px-3 py-3 rounded-xl text-forest-100 hover:bg-forest-800 hover:text-white transition-colors mt-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          Tentang Kami
        </router-link>
      </div>

      <!-- Tombol Login di Bawah -->
      <div class="p-4 border-t border-forest-800">
        <router-link to="/login" class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
          Login Pengelola
        </router-link>
      </div>
    </aside>

    <!-- ================= AREA VIDEO REELS (KANAN) ================= -->
    <main class="flex-1 relative h-full bg-black">
      
      <!-- HEADER MOBILE -->
      <div class="lg:hidden absolute top-0 left-0 right-0 z-50 px-4 py-4 pointer-events-none bg-gradient-to-b from-black/80 to-transparent flex justify-between items-center">
        <router-link to="/" class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white pointer-events-auto">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </router-link>
        <h1 class="text-white font-bold text-lg drop-shadow-md">Reels Desa</h1>
        <router-link to="/katalog" class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white pointer-events-auto">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        </router-link>
      </div>

      <!-- AREA SCROLL SNAP -->
      <div class="h-full w-full overflow-y-scroll snap-y snap-mandatory scroll-smooth hide-scrollbar">
        
        <!-- LOOPING CARD (Data Dummy) -->
        <div 
          v-for="(item, index) in reelsData" 
          :key="item.id" 
          class="h-full w-full snap-start flex items-center justify-center p-0 lg:p-6 relative"
        >
          
          <!-- Container Video -->
          <div class="relative w-full h-full lg:max-h-[90vh] lg:w-auto lg:aspect-[9/16] overflow-hidden lg:rounded-3xl bg-gray-900 shadow-2xl group">
            
            <!-- GAMBAR/VIDEO -->
            <img 
              :src="item.image" 
              :alt="item.title" 
              class="w-full h-full object-cover"
            />

            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/10 to-transparent pointer-events-none"></div>

            <!-- ================= TOMBOL AKSI (Kanan Bawah) ================= -->
            <div class="absolute right-4 bottom-28 flex flex-col items-center gap-5 z-20">
              
              <!-- Tombol Maps (Link ke Google Maps) -->
              <a 
                :href="item.mapsUrl" 
                target="_blank" 
                rel="noopener noreferrer"
                class="flex flex-col items-center gap-1 group/btn"
              >
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/30 rounded-full flex items-center justify-center text-white group-hover/btn:bg-forest-500 group-hover/btn:border-forest-500 transition-colors">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <span class="text-white text-[10px] font-semibold drop-shadow">Maps</span>
              </a>

              <!-- Tombol WhatsApp (Link ke WA) -->
              <a 
                :href="item.waUrl" 
                target="_blank" 
                rel="noopener noreferrer"
                class="flex flex-col items-center gap-1 group/btn"
              >
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/30 rounded-full flex items-center justify-center text-white group-hover/btn:bg-green-500 group-hover/btn:border-green-500 transition-colors">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </div>
                <span class="text-white text-[10px] font-semibold drop-shadow">Chat</span>
              </a>
            </div>

            <!-- INFORMASI TEKS -->
            <div class="absolute bottom-6 left-4 right-20 z-20 text-white pointer-events-none">
              <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold mb-2 border border-white/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ item.village }}
              </div>
              <h2 class="text-2xl font-bold mb-1 leading-tight drop-shadow-lg">{{ item.title }}</h2>
              <p class="text-sm text-gray-200 line-clamp-2 mb-3 drop-shadow">
                {{ item.description }}
              </p>
              <span class="inline-block bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                {{ item.category }}
              </span>
            </div>

            <!-- Petunjuk Swipe (Cuma di card pertama) -->
            <div v-if="index === 0" class="absolute inset-0 flex items-center justify-center pointer-events-none z-30 opacity-70 animate-pulse">
              <div class="flex flex-col items-center text-white/60">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                <span class="text-xs font-semibold tracking-widest uppercase">Swipe Up</span>
              </div>
            </div>

          </div>

          <!-- ================= INDIKATOR TITIK (Kanan Tengah - Desktop Only) ================= -->
          <!-- Bikin user tau ini video ke berapa -->
          <div class="hidden lg:flex absolute right-6 top-1/2 -translate-y-1/2 flex-col gap-2 z-30">
            <div 
              v-for="(_, i) in reelsData" 
              :key="i" 
              class="w-2 h-2 rounded-full transition-all duration-300"
              :class="i === index ? 'bg-white h-6' : 'bg-white/30 hover:bg-white/60'"
            ></div>
          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<script>
import logo from '../../assets/logo.png'

export default {
  name: 'JelajahView',
  data() {
    return {
      logo,
      // Data Dummy (Nanti tinggal dihubungin ke database)
      reelsData: [
        {
          id: 1,
          title: 'Curug Ngebul',
          village: 'Desa Sukamaju',
          category: 'Wisata Alam',
          description: 'Air terjun alami dengan pemandangan hutan hijau dan udara segar pegunungan.',
          image: 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?q=80&w=800&auto=format&fit=crop',
          // GANTI KOORDINAT DI BAWAH SESUAI LOKASI ASLI
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-6.732023,108.545262',
          // GANTI NOMOR WA DI BAWAH
          waUrl: 'https://wa.me/6281234567890?text=Halo%20saya%20tertarik%20dengan%20wisata%20Curug%20Ngebul'
        },
        {
          id: 2,
          title: 'Sate Maranggi Spesial',
          village: 'Desa Cibodas',
          category: 'Kuliner Khas',
          description: 'Sate daging sapi empuk dengan bumbu rahasia turun temurun, dibakar di atas bara arang.',
          image: 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?q=80&w=800&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-6.805421,107.617121',
          waUrl: 'https://wa.me/6281234567891?text=Halo%20saya%20ingin%20memesan%20Sate%20Maranggi'
        },
        {
          id: 3,
          title: 'Anyaman Bambu Pak Karto',
          village: 'Desa Wates',
          category: 'Kerajinan',
          description: 'Kerajinan anyaman bambu handmade berupa tas, tempat buah, dan hiasan rumah.',
          image: 'https://images.unsplash.com/photo-1595079676339-1534801ad6cf?q=80&w=800&auto=format&fit=crop',
          mapsUrl: 'https://www.google.com/maps/search/?api=1&query=-7.901234,110.123456',
          waUrl: 'https://wa.me/6281234567892?text=Halo%20saya%20tertarik%20dengan%20anyaman%20bambu'
        }
      ]
    }
  }
}
</script>

<style scoped>
/* Sembunyikan scrollbar biar bersih */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>