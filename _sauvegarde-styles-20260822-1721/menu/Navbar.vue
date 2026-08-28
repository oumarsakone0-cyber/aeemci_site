<template>
    <nav class="bg-cream border-gray-200 px-4 lg:px-6 py-2.5 w-full fixed top-0 left-0 right-0 z-30 shadow-sm">
      <div class="flex flex-wrap justify-end items-center mx-auto">
        <!-- Right Side Icons - Tous les éléments alignés à droite -->
        <div class="flex items-center space-x-3">
          <!-- Mobile menu button - maintenant à droite -->
          <button 
            @click="toggleSidebar"
            type="button" 
            class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
          >
            <span class="sr-only">Open main menu</span>
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
          </button>

          <!-- Notifications -->
          <div class="relative">
            <button 
              @click="showNotifications = !showNotifications"
              type="button" 
              class="flex items-center justify-center w-10 h-10 text-sm rounded-full hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 relative"
            >
              <span class="sr-only">Voir les notifications</span>
              <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
              </svg>
              <!-- Badge de notification -->
              <span v-if="unreadNotifications > 0" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                {{ unreadNotifications > 9 ? '9+' : unreadNotifications }}
              </span>
            </button>

            <!-- Dropdown Notifications -->
            <div v-if="showNotifications" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
              <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                  <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                  <button 
                    @click="markAllAsRead"
                    class="text-sm text-orange hover:text-orange-600"
                  >
                    Tout marquer comme lu
                  </button>
                </div>
              </div>
              <div class="max-h-64 overflow-y-auto">
                <div v-if="notifications.length === 0" class="p-4 text-center text-gray-500">
                  Aucune notification
                </div>
                <div v-else>
                  <div 
                    v-for="notification in notifications" 
                    :key="notification.id"
                    :class="[
                      'p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer',
                      !notification.read ? 'bg-blue-50' : ''
                    ]"
                    @click="markAsRead(notification.id)"
                  >
                    <div class="flex items-start space-x-3">
                      <div :class="[
                        'w-2 h-2 rounded-full mt-2',
                        !notification.read ? 'bg-blue-500' : 'bg-gray-300'
                      ]"></div>
                      <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                        <p class="text-sm text-gray-600">{{ notification.message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ formatTime(notification.time) }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="p-3 border-t border-gray-200">
                <button class="w-full text-center text-sm text-orange hover:text-orange-600">
                  Voir toutes les notifications
                </button>
              </div>
            </div>
          </div>

          <!-- User Menu -->
          <div class="relative">
            <button 
              @click="showUserMenu = !showUserMenu"
              type="button" 
              class="flex items-center space-x-3 text-sm rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 p-2"
              id="user-menu-button"
            >
              <span class="sr-only">Open user menu</span>
              <!-- Avatar -->
              <div class="w-8 h-8 bg-orange rounded-full flex items-center justify-center text-white font-medium">
                {{ userInitials }}
              </div>
              <!-- User Info -->
              <div class="hidden md:block text-left">
                <div class="text-sm font-medium text-gray-900">{{ displayName }}</div>
                <div class="text-xs text-gray-500">{{ displayRole }}</div>
              </div>
              <!-- Chevron -->
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
              <!-- User Info Header -->
              <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-orange rounded-full flex items-center justify-center text-white font-medium">
                    {{ userInitials }}
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ displayName }}</div>
                    <div class="text-xs text-gray-500">{{ currentUser.email }}</div>
                  </div>
                </div>
                <!-- Boutique Info -->
                <div v-if="currentBoutique" class="mt-2 pt-2 border-t border-gray-100">
                  <div class="text-xs text-gray-600">
                    <span class="font-medium">{{ currentBoutique.name }}</span>
                    <span class="block">{{ currentBoutique.business_type }}</span>
                  </div>
                </div>
              </div>

              <!-- Menu Items -->
              <div class="py-2">
                <button 
                  @click="goToProfile"
                  class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Mon profil
                </button>

                <button 
                  @click="goToSettings"
                  class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Paramètres
                </button>

                <button 
                  @click="goToHelp"
                  class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Aide & Support
                </button>

                <div class="border-t border-gray-200 my-2"></div>

                <button 
                  @click="logout"
                  class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                >
                  <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  Se déconnecter
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Confirmation Modal -->
      <div v-if="showLogoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
          <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
              <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Confirmer la déconnexion</h3>
          </div>
          <p class="text-sm text-gray-600 mb-6">
            Êtes-vous sûr de vouloir vous déconnecter ? Vous devrez vous reconnecter pour accéder à votre compte.
          </p>
          <div class="flex space-x-3">
            <button 
              @click="showLogoutModal = false"
              class="flex-1 py-2 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              @click="confirmLogout"
              class="flex-1 py-2 px-4 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              Se déconnecter
            </button>
          </div>
        </div>
      </div>
    </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const emit = defineEmits(['toggle-sidebar'])

// Reactive data
const showUserMenu = ref(false)
const showNotifications = ref(false)
const showLogoutModal = ref(false)

// User data - récupérée depuis localStorage
const currentUser = ref({
  id: null,
  full_name: 'Utilisateur',
  email: 'user@example.com',
  phone: null,
  role: 'Utilisateur',
  avatar: null
})

// Boutique data - récupérée depuis localStorage
const currentBoutique = ref({
  id: null,
  name: 'Ma Boutique',
  business_type: 'Commerce',
  market: null,
  slug: null
})

// Notifications data (gardées comme avant)
const notifications = ref([
  {
    id: 1,
    title: 'Nouvelle commande',
    message: 'Vous avez reçu une nouvelle commande #12345',
    time: new Date(Date.now() - 5 * 60 * 1000),
    read: false
  },
  {
    id: 2,
    title: 'Stock faible',
    message: 'Le produit "iPhone 14" a un stock faible (2 unités)',
    time: new Date(Date.now() - 30 * 60 * 1000),
    read: false
  },
  {
    id: 3,
    title: 'Paiement reçu',
    message: 'Paiement de 50,000 FCFA reçu pour la commande #12340',
    time: new Date(Date.now() - 2 * 60 * 60 * 1000),
    read: true
  }
])

// Computed properties
const userInitials = computed(() => {
  const name = currentUser.value.full_name || currentUser.value.name
  if (!name) return 'U'
  return name
    .split(' ')
    .map(name => name.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const displayName = computed(() => {
  return currentUser.value.full_name || currentUser.value.name || 'Utilisateur'
})

const displayRole = computed(() => {
  return currentUser.value.role || currentBoutique.value.business_type || 'Utilisateur'
})

const unreadNotifications = computed(() => {
  return notifications.value.filter(n => !n.read).length
})

// Methods pour charger les données utilisateur
const loadUserData = () => {
  try {
    // Utiliser la même approche que le dashboard
    const userData = localStorage.getItem('user')
    if (userData) {
      const user = JSON.parse(userData)
      currentUser.value = user
      
      // Prendre la première boutique disponible comme dans le dashboard
      if (user.boutiques && user.boutiques.length > 0) {
        currentBoutique.value = user.boutiques[0]
      }
      
      console.log('✅ Données utilisateur chargées:', currentUser.value)
      console.log('✅ Données boutique chargées:', currentBoutique.value)
    } else {
      console.warn('⚠️ Aucune donnée utilisateur trouvée dans localStorage')
    }
  } catch (error) {
    console.error('❌ Erreur lors du chargement des données utilisateur:', error)
  }
}

// Methods
const toggleSidebar = () => {
  emit('toggle-sidebar')
}

const logout = () => {
  showUserMenu.value = false
  showLogoutModal.value = true
}

const confirmLogout = () => {
  // Utiliser la même approche de nettoyage que le dashboard
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user')  // Utiliser 'user' comme dans le dashboard
  localStorage.removeItem('rememberMe')
  localStorage.removeItem('saved_identifier')
  
  // Optionnel: Supprimer d'autres données de session
  sessionStorage.clear()
  
  // Rediriger vers la page de connexion
  router.push('/login')
  
  showLogoutModal.value = false
  
  console.log('✅ Déconnexion réussie')
}

const goToProfile = () => {
  showUserMenu.value = false
  console.log('Redirection vers le profil')
  // router.push('/profile')
}

const goToSettings = () => {
  showUserMenu.value = false
  console.log('Redirection vers les paramètres')
  // router.push('/settings')
}

const goToHelp = () => {
  showUserMenu.value = false
  console.log('Redirection vers l\'aide')
  // router.push('/help')
}

const markAsRead = (notificationId) => {
  const notification = notifications.value.find(n => n.id === notificationId)
  if (notification) {
    notification.read = true
  }
}

const markAllAsRead = () => {
  notifications.value.forEach(notification => {
    notification.read = true
  })
}

const formatTime = (time) => {
  const now = new Date()
  const diff = now - time
  const minutes = Math.floor(diff / (1000 * 60))
  const hours = Math.floor(diff / (1000 * 60 * 60))
  const days = Math.floor(diff / (1000 * 60 * 60 * 24))

  if (minutes < 1) return 'À l\'instant'
  if (minutes < 60) return `Il y a ${minutes} min`
  if (hours < 24) return `Il y a ${hours}h`
  if (days < 7) return `Il y a ${days}j`
  
  return time.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short'
  })
}

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    showUserMenu.value = false
    showNotifications.value = false
  }
}

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  
  // Charger les données utilisateur au montage du composant
  loadUserData()
  
  // Écouter les changements dans localStorage (si l'utilisateur se connecte dans un autre onglet)
  window.addEventListener('storage', (e) => {
    if (e.key === 'user' || e.key === 'auth_token') {  // Utiliser 'user' comme dans le dashboard
      loadUserData()
    }
  })
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  window.removeEventListener('storage', loadUserData)
})
</script>

<style scoped>
.bg-cream {
  background-color: #EFEDE7;
}

.bg-orange {
  background-color: #F65A11;
}

.text-orange {
  color: #F65A11;
}

.hover\:text-orange-600:hover {
  color: #e54a0a;
}

.focus\:ring-orange:focus {
  --tw-ring-color: rgba(246, 90, 17, 0.5);
}

.focus\:border-orange:focus {
  border-color: #F65A11;
}

/* Animation pour les dropdowns */
.relative > div {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.shadow-sm {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
