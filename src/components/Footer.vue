<template>
  <footer class="bg-forest-900 text-forest-100">
    <!-- Bagian Atas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

        <!-- KOLOM 1: Brand -->
        <div class="lg:col-span-2">
          <router-link to="/" class="flex items-center gap-3 mb-4">
            <img
              :src="logo"
              alt="DesaWisata Explorer"
              class="w-12 h-12 rounded-full object-cover"
            />
            <div class="flex flex-col leading-none">
              <!-- FIX: text-dark-forest-100 -> text-white -->
              <span class="font-extrabold tracking-tight text-lg text-white">
                DesaWisata
              </span>
              <span class="text-[10px] tracking-[0.25em] uppercase font-bold text-forest-300 mt-1">
                Explorer
              </span>
            </div>
          </router-link>
          <p class="text-sm text-forest-200/80 leading-relaxed max-w-sm">
            Platform promosi wisata desa. Menghubungkan wisatawan langsung dengan pengelola lokal — tanpa transaksi di dalam web.
          </p>

          <!-- Sosial Media -->
          <div class="flex items-center gap-3 mt-6">
            <a
              v-for="s in socials"
              :key="s.name"
              :href="s.url"
              target="_blank"
              rel="noopener noreferrer"
              :aria-label="s.name"
              class="w-9 h-9 rounded-full bg-forest-800 hover:bg-forest-600 flex items-center justify-center transition-colors"
            >
              <svg class="w-4 h-4 text-forest-200" fill="currentColor" viewBox="0 0 24 24" v-html="s.icon"></svg>
            </a>
          </div>
        </div>

        <!-- KOLOM 2: Navigasi -->
        <div>
          <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-4">
            Navigasi
          </h4>
          <ul class="space-y-3 text-sm">
            <li>
              <router-link to="/" class="text-forest-200/80 hover:text-white transition-colors">
                Beranda
              </router-link>
            </li>
            <li>
              <router-link to="/katalog" class="text-forest-200/80 hover:text-white transition-colors">
                Jelajah Wisata
              </router-link>
            </li>
            <li>
              <router-link to="/katalog" class="text-forest-200/80 hover:text-white transition-colors">
                Kuliner Khas
              </router-link>
            </li>
            <li>
              <router-link to="/katalog" class="text-forest-200/80 hover:text-white transition-colors">
                Kerajinan
              </router-link>
            </li>
          </ul>
        </div>

        <!-- KOLOM 3: Kontak -->
        <div>
          <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-4">
            Kontak Pengelola
          </h4>
          <ul class="space-y-3 text-sm">
            <!-- Alamat (Map Pin Icon) -->
            <li class="flex items-start gap-2.5 text-forest-200/80">
              <svg class="w-4 h-4 shrink-0 mt-0.5 text-forest-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>Jl. Raya Desa No. 1, Indonesia</span>
            </li>
            <!-- Email (Mail Icon) -->
            <li class="flex items-start gap-2.5 text-forest-200/80">
              <svg class="w-4 h-4 shrink-0 mt-0.5 text-forest-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <a href="mailto:halo@desawisata.id" class="hover:text-white transition-colors">
                halo@desawisata.id
              </a>
            </li>
            <!-- Telepon (Phone Icon) -->
            <li class="flex items-start gap-2.5 text-forest-200/80">
              <svg class="w-4 h-4 shrink-0 mt-0.5 text-forest-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <a href="tel:+6281234567890" class="hover:text-white transition-colors">
                +62 812-3456-7890
              </a>
            </li>
          </ul>

          <!-- Tombol Hubungi Admin (Chat Icon) -->
          <a
            href="#"
            class="inline-flex items-center gap-2 mt-6 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-colors shadow-sm"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            Hubungi Admin
          </a>
        </div>
      </div>
    </div>

    <!-- Bagian Bawah -->
    <div class="border-t border-forest-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-forest-300/70">
          © {{ new Date().getFullYear() }} DesaWisata Explorer. Dibuat untuk promosi desa.
        </p>
        <div class="flex items-center gap-4 text-xs text-forest-300/70">
          <router-link to="/" class="hover:text-white transition-colors">Kebijakan Privasi</router-link>
          <span>•</span>
          <router-link to="/" class="hover:text-white transition-colors">Syarat & Ketentuan</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import logo from '../assets/logo.png'

export default {
  name: 'Footer',
  data() {
    return {
      logo,
      socials: [
        {
          name: 'Instagram',
          url: '#',
          icon: '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>'
        },
        {
          name: 'Facebook',
          url: '#',
          icon: '<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.323-1.325z"/>'
        },
        {
          name: 'YouTube',
          url: '#',
          icon: '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136c-1.871-.5-9.376-.5-9.376-.5s-7.505 0-9.376.5a3.016 3.016 0 0 0-2.122 2.136c-.5 1.87-.5 5.77-.5 5.77s0 3.9.5 5.77a3.016 3.016 0 0 0 2.122 2.136c1.871.5 9.376.5 9.376.5s7.505 0 9.376-.5a3.016 3.016 0 0 0 2.122-2.136c.5-1.87.5-5.77.5-5.77s0-3.9-.5-5.77zm-13.998 9.77v-7.912l6.27 3.956-6.27 3.956z"/>'
        },
        {
          name: 'TikTok',
          url: '#',
          icon: '<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>'
        }
      ]
    }
  }
}
</script>