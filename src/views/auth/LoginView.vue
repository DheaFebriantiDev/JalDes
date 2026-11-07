<template>
  <div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-gray-900 pt-20 pb-10">
    
    <!-- ================= BACKGROUND VIDEO FULL SCREEN ================= -->
    <div class="absolute inset-0 z-0">
      <!-- VIDEO MUTER OTOMATIS -->
      <video 
        autoplay 
        loop 
        muted 
        playsinline 
        class="w-full h-full object-cover object-center"
        poster="https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=2070&auto=format&fit=crop"
      >
        <!-- Ganti link video ini dengan video desa asli lo nanti -->
      <source src="https://videos.pexels.com/video-files/2169880/2169880-uhd_2560_1440_30fps.mp4" type="video/mp4" />
      </video>
      
      <!-- Overlay Gelap Global biar teks kiri kebaca -->
      <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <!-- ================= LAYOUT UTAMA ================= -->
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex h-full items-center justify-between gap-12">
      
      <!-- ================= PANEL KIRI (TEKS QUOTE) ================= -->
      <div class="hidden lg:flex flex-col justify-center w-1/2 text-white">
        <!-- Judul -->
        <h2 class="text-5xl lg:text-6xl font-extrabold leading-[1.1] mb-6 drop-shadow-xl">
          Kelola Potensi Desa, <br /> Jemput Wisatawan.
        </h2>
        <p class="text-gray-200 text-xl drop-shadow-lg max-w-md leading-relaxed">
          Platform direktori wisata desa tanpa perantara. Daftarkan destinasi, kuliner, dan kerajinan Anda dengan mudah.
        </p>
      </div>

      <!-- ================= PANEL KANAN (KARTU PUTIH SOLID) ================= -->
      <div class="w-full lg:w-[450px] bg-white rounded-3xl shadow-2xl p-8 sm:p-10 relative">
        
        <!-- Header Form -->
        <div class="text-center mb-8">
          <!-- Logo di Mobile -->
          <div class="lg:hidden inline-flex items-center gap-2 mb-4">
            <img :src="logo" alt="Logo" class="w-10 h-10 rounded-full object-cover" />
            <span class="font-extrabold text-lg text-gray-900">DesaWisata</span>
          </div>

          <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Selamat Datang</h2>
          <p class="text-gray-500 text-sm">Silakan masuk ke akun pengelola Anda.</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-5">
          
          <!-- Input Email -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">Email</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
              </div>
              <input 
                v-model="email"
                type="email" 
                required
                placeholder="email@desa.com" 
                class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-500 focus:border-forest-500 focus:bg-white transition-all" 
              />
            </div>
          </div>

          <!-- Input Password -->
          <div>
            <div class="flex justify-between items-center mb-1.5">
              <label class="block text-sm font-bold text-gray-700">Kata Sandi</label>
              <a href="#" class="text-xs font-semibold text-forest-600 hover:text-forest-700">Lupa sandi?</a>
            </div>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
              </div>
              <input 
                v-model="password"
                :type="showPassword ? 'text' : 'password'" 
                required
                placeholder="••••••••" 
                class="block w-full pl-11 pr-11 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-500 focus:border-forest-500 focus:bg-white transition-all" 
              />
              <!-- Ikon Mata -->
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-forest-600 transition-colors"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
              </button>
            </div>
          </div>

          <!-- Tombol Submit -->
          <button 
            type="submit" 
            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-forest-600 hover:bg-forest-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-forest-500 transition-all mt-6"
          >
            Masuk ke Dashboard
          </button>
        </form>

        <!-- ================= TOMBOL SIMULASI ================= -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <p class="text-xs text-center text-gray-500 mb-3 font-semibold uppercase tracking-wider">Atau coba simulasi:</p>
          <div class="grid grid-cols-2 gap-3">
            <button 
              @click="simulateLogin('agensi')"
              class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2.5 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
              Agensi
            </button>
            <button 
              @click="simulateLogin('kades')"
              class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2.5 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4 text-forest-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              Kepala Desa
            </button>
          </div>
        </div>

        <!-- Link Bawah -->
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Belum punya akun? 
            <router-link to="/register" class="font-bold text-forest-600 hover:text-forest-700 hover:underline">
              Daftar di sini
            </router-link>
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import logo from '../../assets/logo.png'

export default { 
  name: 'LoginView',
  data() {
    return {
      logo,
      email: '',
      password: '',
      showPassword: false
    }
  },
  methods: {
    handleLogin() {
      const isKades = this.email.toLowerCase().includes('kades')
      alert(`Login Berhasil! (Simulasi sebagai ${isKades ? 'Kepala Desa' : 'Agensi'})`)
      
      if (isKades) {
        this.$router.push('/kades/dashboard')
      } else {
        this.$router.push('/agensi/dashboard')
      }
    },
    
    simulateLogin(role) {
      if (role === 'kades') {
        this.$router.push('/kades/dashboard')
      } else {
        this.$router.push('/agensi/dashboard')
      }
    }
  }
}
</script>