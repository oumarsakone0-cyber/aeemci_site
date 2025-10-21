<template>
  <header class="dashboard-header">
    <div class="header-container">
      <!-- Left section with title and user info -->
      <div class="header-left">
        <div class="title-section">
          <h1 class="secretariat-title" v-if="userData?.type_organe == 1">
            Espace Secrétariat : <span style="color: green">{{ userData?.organe }}</span>
          </h1>
          <h1 class="secretariat-title" v-if="userData?.type_organe == 2">
            Espace Secrétariat : <span style="color: green">{{ userData?.organe }}</span>
          </h1>
          <h1 class="secretariat-title" v-if="userData?.type_organe == 3">
            Espace Secrétariat : <span style="color: green">{{ userData?.organe }}</span>
          </h1>
          <p class="secretariat-subtitle">Tableau de bord administratif</p>
        </div>
        <div class="user-info">
          <div class="user-avatar">
            <img 
              v-if="userData?.photo" 
              :src="userData.photo" 
              :alt="`Photo de ${userData.prenom} ${userData.nom}`"
              class="avatar-image"
            />
            <div v-else class="avatar-placeholder">
              {{ getInitials(userData?.prenom, userData?.nom) }}
            </div>
            <div class="status-indicator"></div>
          </div>
          <div class="user-details">
            <h2 class="user-name">{{ userData?.prenom }} {{ userData?.nom }}</h2>
            <p class="user-position">{{ userData?.poste }}</p>
            <p class="user-matricule">Matricule: {{ userData?.matricule }}</p>
            <p class="last-login">Dernière connexion: {{ formatLastLogin(lastLogin) }}</p>
          </div>
        </div>
      </div>

      <!-- Right section with stats and logout -->
      <div class="header-right">
        <div class="stats-section">
          <div class="stat-card members">
            <div class="stat-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">{{ membresCount }}</div>
              <div class="stat-label">Membres</div>
            </div>
          </div>
          <div class="stat-card activities">
            <div class="stat-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">{{ activitesCount }}</div>
              <div class="stat-label">Activités</div>
            </div>
          </div>
          <div class="stat-card committees">
            <div class="stat-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-number">{{ sousComitesCount }}</div>
              <div class="stat-label">Sous-comités</div>
            </div>
          </div>
        </div>
        <div class="user-menu" ref="userMenu">
          <button class="user-menu-trigger" @click="toggleUserMenu">
            <svg class="settings-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            </svg>
          </button>
          <div v-if="showUserMenu" class="user-menu-dropdown">
            <button class="menu-item" @click="handleProfile">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Mon Profil
            </button>
            <button class="menu-item" @click="handleSettings">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
              </svg>
              Paramètres
            </button>
            <div class="menu-divider"></div>
            <button class="menu-item logout" @click="handleLogout">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
              Déconnexion
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  userData: {
    type: Object,
    default: () => ({
      prenom: 'Jean',
      nom: 'KOUAME',
      poste: 'Secrétaire Général',
      matricule: 'MAT001',
      photo: null
    })
  },
  secretariatName: {
    type: String,
    default: 'Secrétariat Principal'
  },
  membresCount: {
    type: Number,
    default: 0
  },
  activitesCount: {
    type: Number,
    default: 0
  },
  sousComitesCount: {
    type: Number,
    default: 0
  },
  lastLogin: {
    type: Date,
    default: () => new Date()
  }
})

const emit = defineEmits(['logout', 'profile', 'settings'])

const showUserMenu = ref(false)
const userMenu = ref(null)

const getInitials = (prenom, nom) => {
  if (!prenom || !nom) return 'U'
  return `${prenom.charAt(0)}${nom.charAt(0)}`.toUpperCase()
}

const formatLastLogin = (date) => {
  if (!date) return 'Jamais'
  const now = new Date()
  const diff = now - date
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days = Math.floor(diff / 86400000)
  
  if (minutes < 1) return 'À l\'instant'
  if (minutes < 60) return `Il y a ${minutes} min`
  if (hours < 24) return `Il y a ${hours}h`
  return `Il y a ${days} jour${days > 1 ? 's' : ''}`
}

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const handleProfile = () => {
  showUserMenu.value = false
  emit('profile')
}

const handleSettings = () => {
  showUserMenu.value = false
  emit('settings')
}

const handleLogout = () => {
  showUserMenu.value = false
  emit('logout')
}

const handleClickOutside = (event) => {
  if (userMenu.value && !userMenu.value.contains(event.target)) {
    showUserMenu.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.dashboard-header {
  background: var(--color-background);
  border-bottom: 1px solid var(--color-border);
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}

.header-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1.5rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 2rem;
  flex: 1;
}

.title-section {
  min-width: 0;
}

.secretariat-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--color-foreground);
  margin: 0;
  line-height: 1.2;
}

.secretariat-subtitle {
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.875rem;
  color: var(--color-muted-foreground);
  margin: 0.25rem 0 0 0;
  font-weight: 400;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.75rem 1rem;
  background: var(--color-card);
  border-radius: var(--radius-lg);
  border: 1px solid var(--color-border);
  transition: all 0.2s ease;
}

.user-info:hover {
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
  transform: translateY(-1px);
}

.user-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
  position: relative;
}

.avatar-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  width: 100%;
  height: 100%;
  background: var(--color-primary);
  color: var(--color-primary-foreground);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 1.125rem;
}

.status-indicator {
  position: absolute;
  bottom: 2px;
  right: 2px;
  width: 12px;
  height: 12px;
  background: #10b981;
  border: 2px solid var(--color-background);
  border-radius: 50%;
}

.user-details {
  min-width: 0;
}

.user-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-foreground);
  margin: 0 0 0.25rem 0;
  line-height: 1.2;
}

.user-position {
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.875rem;
  color: var(--color-secondary);
  margin: 0 0 0.125rem 0;
  font-weight: 500;
}

.user-matricule {
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.75rem;
  color: var(--color-muted-foreground);
  margin: 0;
}

.last-login {
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.75rem;
  color: var(--color-muted-foreground);
  margin: 0.125rem 0 0 0;
  font-style: italic;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.stats-section {
  display: flex;
  gap: 1rem;
}

.stat-card {
  background: var(--color-card);
  padding: 1rem 1.25rem;
  border-radius: var(--radius-lg);
  text-align: center;
  min-width: 100px;
  border: 1px solid var(--color-border);
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  transition: all 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.stat-card.members {
  border-left: 4px solid #3b82f6;
}

.stat-card.activities {
  border-left: 4px solid #10b981;
}

.stat-card.committees {
  border-left: 4px solid #f59e0b;
}

.stat-icon {
  width: 24px;
  height: 24px;
  color: var(--color-primary);
  flex-shrink: 0;
}

.stat-icon svg {
  width: 100%;
  height: 100%;
}

.stat-content {
  text-align: left;
}

.stat-number {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-foreground);
  line-height: 1;
}

.stat-label {
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.75rem;
  color: var(--color-muted-foreground);
  margin-top: 0.25rem;
}

.user-menu {
  position: relative;
}

.user-menu-trigger {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: var(--color-card);
  border: 1px solid var(--color-border);
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.2s ease;
}

.user-menu-trigger:hover {
  background: var(--color-primary);
  color: var(--color-primary-foreground);
  transform: rotate(90deg);
}

.settings-icon {
  width: 18px;
  height: 18px;
}

.user-menu-dropdown {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  background: var(--color-card);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
  min-width: 180px;
  z-index: 50;
  overflow: hidden;
  animation: slideDown 0.2s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%;
  padding: 0.75rem 1rem;
  background: none;
  border: none;
  text-align: left;
  font-family: 'Source Sans Pro', sans-serif;
  font-size: 0.875rem;
  color: var(--color-foreground);
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.menu-item:hover {
  background: var(--color-muted);
}

.menu-item.logout {
  color: #ef4444;
}

.menu-item.logout:hover {
  background: #fef2f2;
}

.menu-item svg {
  width: 16px;
  height: 16px;
}

.menu-divider {
  height: 1px;
  background: var(--color-border);
  margin: 0.5rem 0;
}

/* Enhanced responsive design with multiple breakpoints */
/* Tablette (768px - 1024px) */
@media (max-width: 1024px) {
  .header-container {
    padding: 1.25rem 1.5rem;
    gap: 1.5rem;
  }
  
  .secretariat-title {
    font-size: 2rem;
  }
  
  .stats-section {
    gap: 0.75rem;
  }
  
  .stat-card {
    padding: 0.75rem 1rem;
    min-width: 90px;
  }
  
  .stat-number {
    font-size: 1.25rem;
  }
}

/* Tablette portrait et mobile large (481px - 768px) */
@media (max-width: 768px) {
  .header-container {
    flex-direction: column;
    gap: 1.25rem;
    padding: 1rem 1.25rem;
    align-items: stretch;
  }
  
  .header-left {
    flex-direction: column;
    gap: 1rem;
    width: 100%;
    align-items: center;
  }
  
  .header-right {
    flex-direction: column;
    gap: 1rem;
    width: 100%;
    align-items: center;
  }
  
  .title-section {
    text-align: center;
    width: 100%;
  }
  
  .secretariat-title {
    font-size: 1.75rem;
    text-align: center;
  }
  
  .secretariat-subtitle {
    font-size: 0.875rem;
    text-align: center;
  }
  
  .user-info {
    width: 100%;
    max-width: 400px;
    justify-content: center;
    padding: 1rem;
  }
  
  .stats-section {
    width: 100%;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.75rem;
  }
  
  .stat-card {
    flex: 1;
    min-width: 120px;
    max-width: 150px;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem 0.75rem;
  }
  
  .stat-content {
    text-align: center;
  }
  
  .stat-icon {
    width: 20px;
    height: 20px;
  }
  
  .stat-number {
    font-size: 1.5rem;
  }
  
  .stat-label {
    font-size: 0.75rem;
  }
  
  .user-menu-dropdown {
    right: 50%;
    transform: translateX(50%);
    min-width: 200px;
  }
}

/* Mobile (320px - 480px) */
@media (max-width: 480px) {
  .header-container {
    padding: 0.75rem 1rem;
    gap: 1rem;
  }
  
  .secretariat-title {
    font-size: 1.5rem;
    line-height: 1.3;
  }
  
  .secretariat-subtitle {
    font-size: 0.8rem;
  }
  
  .user-info {
    flex-direction: column;
    text-align: center;
    gap: 0.75rem;
    padding: 1rem 0.75rem;
  }
  
  .user-avatar {
    width: 60px;
    height: 60px;
  }
  
  .user-details {
    width: 100%;
  }
  
  .user-name {
    font-size: 1.125rem;
    text-align: center;
  }
  
  .user-position,
  .user-matricule,
  .last-login {
    text-align: center;
  }
  
  .stats-section {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .stat-card {
    width: 100%;
    max-width: none;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
  }
  
  .stat-content {
    text-align: right;
  }
  
  .stat-number {
    font-size: 1.25rem;
  }
  
  .stat-label {
    font-size: 0.75rem;
    margin-top: 0;
  }
  
  .user-menu-trigger {
    width: 44px;
    height: 44px;
  }
  
  .settings-icon {
    width: 20px;
    height: 20px;
  }
}

/* Très petit mobile (max 320px) */
@media (max-width: 320px) {
  .header-container {
    padding: 0.5rem 0.75rem;
  }
  
  .secretariat-title {
    font-size: 1.25rem;
  }
  
  .user-avatar {
    width: 50px;
    height: 50px;
  }
  
  .user-name {
    font-size: 1rem;
  }
  
  .stat-card {
    padding: 0.5rem 0.75rem;
  }
  
  .stat-number {
    font-size: 1.125rem;
  }
}
</style>
