<template>
  <div class="flex flex-col min-h-screen">
    <Navbar v-if="showNavbar" />
    <main class="flex-1">
      <router-view />
    </main>
    <Footer v-if="showNavbar" />
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'

export default {
  name: 'App',
  components: { Navbar, Footer },
  computed: {
    showNavbar() {
      const path = this.$route.path
      

      const isDashboard = path.startsWith('/agensi') || path.startsWith('/kades')
      const isReels = path === '/jelajah'
      
      return !isDashboard && !isReels
    }
  }
}
</script>