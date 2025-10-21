<template>
  <div class="bg-cream">
    <AppHeader :isMenuOpen="isMenuOpen" @toggle-menu="toggleMenu" @close-menu="closeMenu" />
    <div >  
      <router-view />
    </div>
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Sidebar from './components/menu/Sidebar.vue'
import Navbar from './components/menu/Navbar.vue'
import AppHeader from './components/components/NavbarComponent.vue'
import Footer from './components/components/FooterComponent.vue'

const route = useRoute()
const sidebarOpen = ref(false)

// Computed pour vérifier si on est sur une page d'authentification
const isAuthPage = computed(() => {
  return route.name === 'login' || route.name === 'register'
})

const handleToggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

onMounted(() => {
  console.log('Referer:', document.referrer)
})
</script>

<style scoped>
.bg_cream {
  background-color: #EFEDE7;
}
</style>
