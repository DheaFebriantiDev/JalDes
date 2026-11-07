<template>
  <header
    :class="[
      'fixed top-0 left-0 w-full z-50 transition-all duration-300',
      isDarkNav ? 'bg-white shadow-md' : 'bg-transparent'
    ]"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 md:h-20 gap-4">

        <!-- LOGO -->
        <router-link to="/" class="flex items-center gap-2.5 shrink-0 group">
          <img
            :src="logo"
            alt="DesaWisata Explorer"
            class="w-10 h-10 md:w-11 md:h-11 rounded-full object-cover transition-transform duration-300 group-hover:scale-105"
          />
          <div class="flex flex-col leading-none">
            <span
              :class="[
                'font-extrabold tracking-tight text-base md:text-lg transition-colors',
                isDarkNav ? 'text-forest-800' : 'text-white'
              ]"
            >
              DesaWisata
            </span>
            <span
              :class="[
                'text-[10px] tracking-[0.25em] uppercase font-bold mt-0.5 transition-colors',
                isDarkNav ? 'text-forest-600' : 'text-emerald-200'
              ]"
            >
              Explorer
            </span>
          </div>
        </router-link>

        <!-- SEARCH BAR -->
        <transition name="search">
          <div v-if="isDarkNav" class="hidden md:flex flex-1 max-w-md relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
              </svg>
            </span>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari destinasi desa..."
              class="w-full pl-9 pr-3 py-2 rounded-full bg-gray-100 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-400 focus:bg-white"
            />
          </div>
        </transition>

        <!-- MENU DESKTOP -->
        <nav :class="['hidden lg:flex items-center gap-6', isDarkNav ? '' : 'ml-auto']">
          <router-link to="/" :class="menuClass">Beranda<span :class="underlineClass"></span></router-link>
          <router-link to="/katalog" :class="menuClass">Semua Wisata<span :class="underlineClass"></span></router-link>
          <router-link to="/katalog?kategori=wisata" :class="menuClass">Wisata Alam<span :class="underlineClass"></span></router-link>
          <router-link to="/katalog?kategori=kuliner" :class="menuClass">Kuliner Khas<span :class="underlineClass"></span></router-link>
          <router-link to="/katalog?kategori=kerajinan" :class="menuClass">Kerajinan<span :class="underlineClass"></span></router-link>
          <router-link to="/tentang" :class="menuClass">Tentang<span :class="underlineClass"></span></router-link>

          <div :class="['h-5 w-px', isDarkNav ? 'bg-gray-300' : 'bg-white/30']"></div>

          <!-- Login -->
          <router-link
            to="/login"
            :class="[
              'text-xs font-bold uppercase tracking-wide px-4 py-2 rounded-full border transition-colors',
              isDarkNav
                ? 'border-forest-600 text-forest-700 hover:bg-forest-600 hover:text-white'
                : 'border-white text-white hover:bg-white/15 hover:border-white'
            ]"
          >
            Login
          </router-link>
        </nav>

        <!-- Hamburger -->
        <button
          @click="mobileOpen = !mobileOpen"
          :class="[
            'lg:hidden w-10 h-10 flex items-center justify-center rounded-full transition-colors',
            isDarkNav ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'
          ]"
          aria-label="Toggle menu"
        >
          <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Search mobile -->
      <transition name="search">
        <div v-if="isDarkNav" class="md:hidden pb-3">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
              </svg>
            </span>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari destinasi desa..."
              class="w-full pl-9 pr-3 py-2 rounded-full bg-gray-100 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-400 focus:bg-white"
            />
          </div>
        </div>
      </transition>
    </div>

    <!-- Mobile drawer -->
    <transition name="slide">
      <div
        v-if="mobileOpen"
        :class="[
          'lg:hidden border-t',
          isDarkNav ? 'bg-white border-gray-100' : 'bg-forest-800 border-white/10'
        ]"
      >
        <div class="px-4 py-4 space-y-1">
          <router-link to="/" @click="mobileOpen = false" :class="mobileLinkClass">Beranda</router-link>
          <router-link to="/katalog" @click="mobileOpen = false" :class="mobileLinkClass">Semua Wisata</router-link>
          <router-link to="/katalog?kategori=wisata" @click="mobileOpen = false" :class="mobileLinkClass">Wisata Alam</router-link>
          <router-link to="/katalog?kategori=kuliner" @click="mobileOpen = false" :class="mobileLinkClass">Kuliner Khas</router-link>
          <router-link to="/katalog?kategori=kerajinan" @click="mobileOpen = false" :class="mobileLinkClass">Kerajinan</router-link>
          <router-link to="/tentang" @click="mobileOpen = false" :class="mobileLinkClass">Tentang</router-link>

          <div :class="['h-px my-2', isDarkNav ? 'bg-gray-200' : 'bg-white/20']"></div>

          <router-link
            to="/login"
            @click="mobileOpen = false"
            class="block text-center mt-2 bg-forest-600 hover:bg-forest-700 text-white text-sm font-bold uppercase tracking-wide px-4 py-3 rounded-full"
          >
            Login Admin / Agensi
          </router-link>
        </div>
      </div>
    </transition>
  </header>
</template>

<script>
import logo from '../assets/logo.png'

export default {
  name: 'Navbar',
  data() {
    return {
      logo,
      scrolled: false,
      mobileOpen: false,
      searchQuery: ''
    }
  },
  computed: {
    isHome() {
      return this.$route.path === '/'
    },
    isAuthPage() {
      return this.$route.path === '/login' || this.$route.path === '/register'
    },
    isDarkNav() {
      // 1. Kalau di halaman Login/Register
      if (this.isAuthPage) {
        // Pas di-scroll baru jadi putih, kalau di atas transparan
        return this.scrolled
      }
      // 2. Kalau di Home, transparan pas belum scroll, putih pas di-scroll
      if (this.isHome) {
        return this.scrolled
      }
      // 3. Halaman lain (Katalog, Tentang), SELALU putih
      return true
    },
    menuClass() {
      return [
        'text-sm font-medium transition-colors relative group py-2',
        this.isDarkNav ? 'text-gray-700 hover:text-forest-700' : 'text-white/90 hover:text-white'
      ]
    },
    underlineClass() {
      return [
        'absolute bottom-0 left-0 w-0 h-0.5 group-hover:w-full transition-all duration-300',
        this.isDarkNav ? 'bg-emerald-500' : 'bg-white'
      ]
    },
    mobileLinkClass() {
      return [
        'block px-3 py-3 rounded-lg text-sm font-semibold transition-colors',
        this.isDarkNav 
          ? 'text-gray-700 hover:bg-forest-50 hover:text-forest-700' 
          : 'text-white hover:bg-white/10'
      ]
    }
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll)
    this.handleScroll()
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll() {
      this.scrolled = window.scrollY > 20
    }
  }
}
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active { transition: all 0.25s ease; }
.slide-enter-from,
.slide-leave-to { opacity: 0; transform: translateY(-8px); }

.search-enter-active,
.search-leave-active { transition: all 0.35s ease; }
.search-enter-from { opacity: 0; transform: translateX(20px); }
.search-leave-to { opacity: 0; transform: translateX(20px); }
</style>