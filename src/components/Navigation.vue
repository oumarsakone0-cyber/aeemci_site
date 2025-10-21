<template>
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold">SOGETRAG</h1>
          </div>
          
          <div class="flex items-center space-x-4" v-if="user">
            <span class="text-gray-700">Bonjour, {{ user.nom }}</span>
            <button 
              @click="handleLogout"
              class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
            >
              Déconnexion
            </button>
          </div>
        </div>
      </div>
    </nav>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { logout, getCurrentUser, authStore } from '../router/index.js'
  
  const user = ref(null)
  
  onMounted(() => {
    user.value = getCurrentUser()
    
    // Écouter les changements d'authentification
    const checkAuth = () => {
      user.value = getCurrentUser()
    }
    
    // Vérifier périodiquement (optionnel)
    setInterval(checkAuth, 30000) // Toutes les 30 secondes
  })
  
  const handleLogout = async () => {
    if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
      await logout()
    }
  }
  </script>