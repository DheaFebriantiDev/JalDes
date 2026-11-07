<template>
  <div class="bg-white">
    
    <!-- ================= HERO ABOUT ================= -->
    <header class="relative bg-forest-50 pt-32 pb-20 overflow-hidden border-b border-forest-100">
      <!-- Ornamen Latar -->
      <div class="absolute inset-0 opacity-40">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-forest-200 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-emerald-200 blur-3xl"></div>
      </div>

      <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-forest-200 text-forest-800 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-6">
          Tentang Kami
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-forest-900 leading-tight mb-6">
          Menghubungkan Wisatawan <br class="hidden sm:block" />
          dengan <span class="text-emerald-600">Pengelola Desa</span>
        </h1>
        <p class="text-lg text-forest-700 max-w-2xl mx-auto leading-relaxed">
          DesaWisata Explorer adalah direktori wisata desa yang dibangun untuk mempromosikan keindahan, kuliner, dan kerajinan autentik langsung dari sumbernya.
        </p>
      </div>
    </header>

    <!-- ================= VISI & MISI ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        
        <!-- Gambar Kiri -->
        <div class="relative">
          <div class="absolute inset-0 bg-forest-600 translate-x-4 translate-y-4 rounded-3xl"></div>
          <img 
            src="https://images.unsplash.com/photo-1542718610-a1d656d1884c?q=80&w=1000&auto=format&fit=crop" 
            alt="Pemandangan Desa" 
            class="relative z-10 w-full h-[400px] object-cover rounded-3xl shadow-lg"
          />
        </div>

        <!-- Teks Kanan -->
        <div>
          <h2 class="text-3xl font-bold text-gray-900 mb-4">Mengapa DesaWisata Explorer Hadir?</h2>
          <p class="text-gray-600 leading-relaxed mb-6">
            Banyak potensi desa yang luar biasa — mulai dari air terjun tersembunyi, kuliner khas warisan nenek moyang, hingga kerajinan tangan yang dibuat dengan penuh cinta — namun belum terpublikasi dengan baik. 
          </p>
          <p class="text-gray-600 leading-relaxed mb-8">
            Kami hadir sebagai jembatan. Platform ini dirancang <b>tanpa sistem transaksi</b> di dalam web. Tugas kami hanya mempertemukan Anda dengan pengelola lokal, sehingga Anda bisa berinteraksi dan bertransaksi langsung dengan mereka.
          </p>

          <div class="flex flex-col gap-4">
            <!-- Poin 1 -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-full bg-forest-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-900">Tanpa Perantara</h4>
                <p class="text-sm text-gray-500">Hubungi pengelola desa langsung via WhatsApp.</p>
              </div>
            </div>
            <!-- Poin 2 -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-full bg-forest-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-900">Pemberdayaan Ekonomi Lokal</h4>
                <p class="text-sm text-gray-500">100% keuntungan kembali ke masyarakat desa.</p>
              </div>
            </div>
            <!-- Poin 3 -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-full bg-forest-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-900">Autentik & Alami</h4>
                <p class="text-sm text-gray-500">Menampilkan potensi asli desa tanpa rekayasa.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ================= CARA KERJA (3 LANGKAH) ================= -->
    <section class="bg-gray-50 py-20 border-y border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">Bagaimana Cara Kerjanya?</h2>
          <p class="text-gray-500 max-w-2xl mx-auto">Tiga langkah mudah untuk memulai petualangan Anda menjelajahi desa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          
          <!-- Langkah 1 -->
          <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center relative">
            <div class="w-16 h-16 bg-forest-100 text-forest-700 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
              1
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Pilih Desa</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
              Jelajahi katalog atau tonton Reels untuk menemukan desa yang menarik hati Anda.
            </p>
          </div>

          <!-- Langkah 2 -->
          <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center relative">
            <div class="w-16 h-16 bg-forest-100 text-forest-700 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
              2
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Lihat Potensi</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
              Cek destinasi wisata, kuliner khas, dan kerajinan yang ditawarkan oleh desa tersebut.
            </p>
          </div>

          <!-- Langkah 3 -->
          <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center relative">
            <div class="w-16 h-16 bg-forest-100 text-forest-700 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
              3
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Hubungi Pengelola</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
              Klik tombol WhatsApp untuk berkomunikasi langsung dengan pengelola lokal dan atur kunjungan Anda.
            </p>
          </div>

        </div>
      </div>
    </section>

    <!-- ================= CTA JOIN AGENCY ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
      <div class="bg-forest-800 rounded-3xl p-10 sm:p-16 text-center relative overflow-hidden">
        <!-- Ornamen -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-forest-600 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-600 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
        
        <div class="relative z-10">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
            Apakah Anda Pengelola Desa Wisata?
          </h2>
          <p class="text-forest-100 text-lg mb-8 max-w-2xl mx-auto">
            Bergabunglah dengan kami. Daftarkan potensi desa Anda secara gratis dan jangkau wisatawan dari seluruh Indonesia.
          </p>
          <router-link to="/register" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-8 py-4 rounded-full transition-all shadow-lg">
            Daftar Jadi Agensi Sekarang
          </router-link>
        </div>
      </div>
    </section>

  </div>
</template>

<script>
export default {
  name: 'AboutView'
}
</script>