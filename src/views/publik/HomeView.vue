<template>
  <div class="bg-white">
    <!-- ================= HERO SECTION (VIDEO BACKGROUND) ================= -->
    <!-- h-screen biar full layar, items-center biar teks di tengah vertikal -->
    <header class="relative h-screen w-full overflow-hidden flex items-center pt-20">
      
      <!-- Video Background -->
      <div class="absolute inset-0 z-0">
        <video 
          autoplay 
          loop 
          muted 
          playsinline 
          class="w-full h-full object-cover"
poster="https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=2070&auto=format&fit=crop"
>
  <source src="https://videos.pexels.com/video-files/2169880/2169880-uhd_2560_1440_30fps.mp4" type="video/mp4" />
        </video>
        
        <!-- Overlay Gradient: Bawah lebih gelap, atas transparan -->
        <div class="absolute inset-0 bg-gradient-to-t from-forest-900/95 via-forest-900/60 to-black/30"></div>
      </div>

      <!-- Konten Hero (Layout Rata Kiri ala Majalah) -->
      <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="max-w-3xl text-left">
          
          <!-- Badge Glassmorphism -->
          <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-semibold tracking-wider uppercase px-4 py-2 rounded-full mb-8 animate-fade-in-up">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Direktori Wisata Desa
          </div>

          <!-- Judul Besar -->
          <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white leading-[1.1] mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
            Pesona Autentik <br />
            <span class="text-emerald-400">Desa Indonesia</span>
          </h1>

          <!-- Subjudul -->
          <p class="text-lg sm:text-xl text-gray-200 max-w-xl mb-10 animate-fade-in-up leading-relaxed" style="animation-delay: 0.2s;">
            Temukan keindahan alam, kuliner khas, dan kerajinan tangan terbaik langsung dari pengelola desa. Tanpa perantara, tanpa transaksi di web.
          </p>

          <!-- Tombol Aksi -->
          <div class="flex flex-wrap items-center gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
            <router-link to="/jelajah" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-8 py-4 rounded-full transition-all shadow-lg hover:shadow-emerald-500/25 group">
              Mulai Jelajah
              <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </router-link>
            <router-link to="/register" class="inline-flex items-center justify-center bg-white/10 backdrop-blur-md border border-white/30 hover:bg-white/20 text-white font-bold px-8 py-4 rounded-full transition-all">
              Daftar Pengelola
            </router-link>
          </div>

        </div>
      </div>

      <!-- Panah ke bawah (Scroll Indicator) -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce text-white/50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
      </div>
    </header>

    <!-- ================= STATISTIK ================= -->
    <section class="relative z-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12">
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
        <div class="pt-4 sm:pt-0">
          <p class="text-4xl font-extrabold text-forest-700">50+</p>
          <p class="text-sm text-gray-500 font-medium mt-1">Desa Terdaftar</p>
        </div>
        <div class="pt-4 sm:pt-0">
          <p class="text-4xl font-extrabold text-forest-700">200+</p>
          <p class="text-sm text-gray-500 font-medium mt-1">Destinasi & Potensi</p>
        </div>
        <div class="pt-4 sm:pt-0">
          <p class="text-4xl font-extrabold text-forest-700">100%</p>
          <p class="text-sm text-gray-500 font-medium mt-1">Langsung ke Pengelola</p>
        </div>
      </div>
    </section>

    <!-- ================= DESTINASI PILIHAN ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
          <h2 class="text-3xl font-bold text-gray-900">Destinasi Pilihan</h2>
          <p class="text-gray-500 mt-2">Jelajahi keindahan tersembunyi dari desa-desa di Indonesia.</p>
        </div>
        <router-link to="/katalog" class="inline-flex items-center gap-2 text-sm font-bold text-forest-700 hover:text-forest-800 transition-colors group">
          Lihat Semua Destinasi
          <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </router-link>
      </div>

      <!-- Grid Card -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <DestinationCard v-for="n in 6" :key="n" />
      </div>
    </section>

    <!-- ================= CTA (CALL TO ACTION) ================= -->
    <!-- Background section dibikin putih bersih biar fokus -->
    <section class="bg-white border-t border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        
        <!-- Kotak CTA Hijau Gelap (Bersih tanpa ornamen blur) -->
        <div class="bg-forest-800 rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row items-center relative">
          
          <!-- Teks CTA -->
          <div class="relative z-10 p-8 sm:p-12 md:w-3/5 text-center md:text-left">
            <h2 class="text-3xl sm:text-4xl font-bold text-dark mb-4">
              Punya Potensi Wisata di Desa Anda?
            </h2>
            <p class="text-forest-100 text-base sm:text-lg mb-8 max-w-lg mx-auto md:mx-0">
              Bergabunglah sebagai Agensi atau Pengelola. Daftarkan destinasi, kuliner, atau kerajinan desa Anda dan jangkau lebih banyak wisatawan secara gratis.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
              <router-link to="/register" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-8 py-3.5 rounded-full transition-colors shadow-lg">
                Daftar Jadi Agensi
              </router-link>
              <router-link to="/login" class="w-full sm:w-auto bg-transparent border-2 border-white/30 hover:border-white text-white font-bold px-8 py-3.5 rounded-full transition-colors">
                Login Pengelola
              </router-link>
            </div>
          </div>

          <!-- Gambar Ilustrasi (Kanannya) -->
          <div class="hidden md:block md:w-2/5 relative z-10 p-6">
             <img 
               src="https://images.unsplash.com/photo-1599839619722-39751411ea63?q=80&w=1000&auto=format&fit=crop" 
               alt="Pengelola Desa" 
               class="rounded-2xl shadow-lg object-cover h-72 w-full transform hover:scale-105 transition-transform duration-500"
             />
          </div>

        </div>
      </div>
    </section>
  </div>
</template>

<script>
import DestinationCard from '../../components/DestinationCard.vue'

export default {
  name: 'HomeView',
  components: { 
    DestinationCard 
  }
}
</script>

<style scoped>
/* Animasi Fade In Up (khusus untuk hero) */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
  opacity: 0;
}
</style>