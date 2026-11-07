<template>
  <div class="min-h-screen bg-gray-50 flex">
    
    <!-- ================= SIDEBAR (PUTIH) ================= -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-50 flex flex-col shadow-sm transition-transform duration-300 lg:translate-x-0" :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'">
      
      <!-- Logo -->
      <router-link to="/" class="flex items-center gap-3 px-6 h-16 border-b border-gray-100 shrink-0">
        <img :src="logo" alt="Logo" class="w-8 h-8 rounded-full object-cover" />
        <div class="flex flex-col leading-none">
          <span class="font-extrabold text-sm tracking-tight text-gray-900">DesaWisata</span>
          <span class="text-[9px] tracking-[0.25em] uppercase font-bold text-emerald-600 mt-0.5">Explorer</span>
        </div>
      </router-link>

      <!-- Menu Dinamis -->
      <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Menu Utama</p>
        
        <router-link 
          v-for="item in menuItems" 
          :key="item.label"
          :to="item.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200"
          :class="
            $route.path === item.to 
              ? 'bg-emerald-50 text-emerald-700' 
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          "
        >
          <!-- Ikon -->
          <svg 
            class="w-5 h-5 shrink-0" 
            :class="$route.path === item.to ? 'text-emerald-600' : 'text-gray-400'"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24" 
            v-html="item.iconPath"
          ></svg>
          {{ item.label }}
        </router-link>
      </div>

      <!-- Tombol Logout di Bawah -->
      <div class="p-4 border-t border-gray-100">
        <button @click="handleLogout" class="flex items-center gap-3 w-full px-3 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
          Keluar
        </button>
      </div>
    </aside>

    <!-- ================= MAIN CONTENT AREA ================= -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
      
      <!-- NAVBAR ATAS -->
      <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40">
        
        <div class="flex items-center gap-4">
          <button @click="mobileOpen = !mobileOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
          </button>
          <h1 class="font-bold text-gray-800 text-lg hidden sm:block">{{ pageTitle }}</h1>
        </div>

        <div class="flex items-center gap-4">
          <div class="text-right hidden sm:block">
            <p class="text-sm font-bold text-gray-800 leading-none">{{ userName }}</p>
            <p class="text-xs text-forest-600 font-semibold mt-1 uppercase tracking-wider">{{ role === 'kades' ? 'Kepala Desa' : 'Agensi' }}</p>
          </div>
          <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-700 font-bold">
            {{ userName.charAt(0) }}
          </div>
        </div>
      </header>

      <!-- KONTEN DINAMIS -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <slot />
      </main>

    </div>

    <!-- Overlay Mobile -->
    <div v-if="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

  </div>
</template>

<script>
import logo from '../assets/logo.png'

export default {
  name: 'DashboardLayout',
  props: {
    role: { type: String, required: true },
    userName: { type: String, default: 'Pengguna' },
    pageTitle: { type: String, default: 'Dashboard' }
  },
  data() {
    return {
      logo,
      mobileOpen: false
    }
  },
  computed: {
    menuItems() {
      if (this.role === 'kades') {
      return [
        { label: 'Ringkasan', to: '/kades/dashboard', iconPath: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />' },
        { label: 'Data Wisata Desa', to: '/kades/data-wisata', iconPath: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />' },
        { label: 'Moderasi & Persetujuan', to: '/kades/moderasi', iconPath: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' }
      ]
      }
      // MENU AGENSI
      return [
        { label: 'Dashboard', to: '/agensi/dashboard', iconPath: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />' },
        { label: 'Wisata Saya', to: '/agensi/wisata', iconPath: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />' },
        { label: 'Draft Promosi', to: '/agensi/promosi', iconPath: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />' },
        { label: 'Riwayat / History', to: '/agensi/riwayat', iconPath: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />' }
      ]
    }
  },
  methods: {
    handleLogout() {
      localStorage.removeItem('dw_user')
      this.$router.push('/login')
    }
  }
}
</script>