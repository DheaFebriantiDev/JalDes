import { createRouter, createWebHistory } from 'vue-router'

// Import Views Publik
import HomeView from '../views/publik/HomeView.vue'
import KatalogView from '../views/publik/KatalogView.vue'
import DetailView from '../views/publik/DetailView.vue'
import JelajahView from '../views/publik/JelajahView.vue'
import AboutView from '../views/publik/AboutView.vue'
import LoginView from '../views/auth/LoginView.vue'
import RegisterView from '../views/auth/RegisterView.vue'

// Import Agensi
import AgencyDashboard from '../views/agensi/AgencyDashboard.vue'
import WisataSaya from '../views/agensi/WisataSaya.vue'
import DraftPromosi from '../views/agensi/DraftPromosi.vue'
import RiwayatView from '../views/agensi/RiwayatView.vue'

// Import Kades
import KadesDashboard from '../views/kades/KadesDashboard.vue'
import KadesModerasi from '../views/kades/KadesModerasi.vue'
import KadesDataWisata from '../views/kades/KadesDataWisata.vue' // <--- INI YANG KURANG TADI

const routes = [
  // ================= PUBLIK =================
  { path: '/', name: 'home', component: HomeView },
  { path: '/katalog', name: 'katalog', component: KatalogView },
  { path: '/destinasi/:id', name: 'detail', component: DetailView, props: true },
  { path: '/jelajah', name: 'jelajah', component: JelajahView },
  { path: '/about', name: 'about', component: AboutView },

  // ================= AUTH =================
  { path: '/login', name: 'login', component: LoginView },
  { path: '/register', name: 'register', component: RegisterView },

  // ================= AGENSI =================
  { path: '/agensi/dashboard', name: 'agensi-dashboard', component: AgencyDashboard },
  { path: '/agensi/wisata', name: 'agensi-wisata', component: WisataSaya },
  { path: '/agensi/promosi', name: 'agensi-promosi', component: DraftPromosi },
  { path: '/agensi/riwayat', name: 'agensi-riwayat', component: RiwayatView },

  // ================= KADES =================
  { path: '/kades/dashboard', name: 'kades-dashboard', component: KadesDashboard },
  { path: '/kades/data-wisata', name: 'kades-data-wisata', component: KadesDataWisata }, 
  { path: '/kades/moderasi', name: 'kades-moderasi', component: KadesModerasi },

  // Fallback jika rute tidak ditemukan
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router