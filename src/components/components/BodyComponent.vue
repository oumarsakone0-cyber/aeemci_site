<template>
  <div>
    <!-- Nouveau Marquee avec défilement droite vers gauche -->
    <div class="marquee-container">
      <div class="marquee-header"></div>
      <div class="marquee" aria-label="Annonces défilantes">
        <div class="marquee-content">
          <div class="marquee-track" :class="{ paused: isPaused }">
            <span class="marquee-item" v-for="(msg, index) in duplicatedMessages" :key="`${index}-${msg}`">
              {{ msg }}
            </span>
          </div>
        </div>
        <button class="marquee-toggle" @click="toggleMarquee" :aria-label="isPaused ? 'Reprendre le défilement' : 'Mettre en pause'">
          <svg v-if="isPaused" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M8 5v14l11-7z"/>
          </svg>
          <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Main content -->
    <main class="main-content">
      <div class="container">
        <div class="content-wrapper">
          <!-- Colonne principale -->
          <div class="main-column">
            <NewsSection />
            <ProgramsSection />
            <FormationsSection />
            <LibrarySection />
            <IdentitySection />
          </div>

          <!-- Sidebar -->
          <AppSidebar />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

import NewsSection from '../accueil/NewsSection.vue'
import ProgramsSection from '../accueil/ProgramsSection.vue'
import FormationsSection from '../accueil/FormationsSection.vue'
import LibrarySection from '../accueil/LibrarySection.vue'
import IdentitySection from '../accueil/IdentitySection.vue'
import AppSidebar from '../accueil/AppSidebar.vue'

// Router
const router = useRouter()

// Marquee
const isPaused = ref(false)
const messages = ref([
  'AEEMCI, pour une identité islamique !',
  'AEEMCI, pour une identité islamique !',
  'AEEMCI, pour une identité islamique !'
])

// Dupliquer les messages pour un défilement continu
const duplicatedMessages = computed(() => {
  return [...messages.value, ...messages.value, ...messages.value]
})

const toggleMarquee = () => {
  isPaused.value = !isPaused.value
}

// Horloge (optionnelle si tu veux afficher l'heure)
const currentTime = ref('')
let timeInterval

const updateTime = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

// Sections, données factices
const navigationItems = ref([
  { id: 'espace_organe', name: 'Espace organes', icon: '🏛️' },
  { id: 'carte_membre', name: 'Carte de membre', icon: '🪪' },
  { id: 'admissions', name: 'Nouveaux bacheliers', icon: '🎓' },
  { id: 'stages', name: 'Stage / Emploi', icon: '💼' },
  { id: 'evenements', name: 'Événements', icon: '📅' }
])

const activeNavItem = ref('accueill')

const handleNavItem = (link) => {
  activeNavItem.value = link
  if (link) router.push(link)
}

onMounted(() => {
  updateTime()
  timeInterval = setInterval(updateTime, 1000)
})

onUnmounted(() => {
  if(timeInterval) clearInterval(timeInterval)
})
</script>

<style scoped>
/* Nouveau Marquee avec défilement droite vers gauche */
.marquee-container {
}

.marquee-header {
  height: 4px;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
  border-radius: 2px 2px 0 0;
}

.marquee {
  display: flex;
  align-items: center;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border-radius: 0 0 12px 12px;
  padding: 12px 16px;
  box-shadow: 
    0 4px 6px -1px rgba(0, 0, 0, 0.1),
    0 2px 4px -1px rgba(0, 0, 0, 0.06);
  border: 1px solid rgba(148, 163, 184, 0.2);
  position: relative;
  overflow: hidden;
}

.marquee::before,
.marquee::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 40px;
  pointer-events: none;
  z-index: 2;
}

.marquee::before {
  left: 0;
  background: linear-gradient(to right, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
}

.marquee::after {
  right: 50px;
  background: linear-gradient(to left, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
}

.marquee-content {
  flex: 1;
  overflow: hidden;
  mask-image: linear-gradient(to right, transparent, black 40px, black calc(100% - 40px), transparent);
}

.marquee-track {
  display: flex;
  gap: 60px;
  animation: scroll-left 40s linear infinite;
  will-change: transform;
}

.marquee-track.paused {
  animation-play-state: paused;
}

.marquee-item {
  white-space: nowrap;
  font-weight: 600;
  font-size: 15px;
  color: #1e293b;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.marquee-toggle {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
  border: 1px solid rgba(148, 163, 184, 0.3);
  border-radius: 8px;
  cursor: pointer;
  color: #475569;
  transition: all 0.2s ease;
  flex-shrink: 0;
  margin-left: 12px;
}

.marquee-toggle:hover {
  background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
  border-color: rgba(148, 163, 184, 0.5);
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.marquee-toggle:active {
  transform: translateY(0);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@keyframes scroll-left {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(-100%);
  }
}

/* Respect des préférences d'accessibilité */
@media (prefers-reduced-motion: reduce) {
  .marquee-track {
    animation-duration: 90s;
  }
}

/* Main content layout */
.main-content {
  padding: 2rem 0;
  background: linear-gradient(135deg, #dcfce7 0%, #f9fafb 100%);
}

.container {
  max-width: 1400px;
  margin: 0 auto;   
  padding: 0 20px;
}

.content-wrapper {
  display: flex;
  flex-wrap: wrap;
  gap: 1.2rem;
}

.main-column {
  flex: 0 0 calc(66.666% - 0.6rem);
  width: calc(66.666% - 0.6rem);
}

@media (max-width: 992px) {
  .content-wrapper { 
    flex-direction: column; 
  }
  
  .main-column { 
    flex: 0 0 100% !important; 
    width: 100% !important; 
  }
  
  .marquee {
    padding: 10px 12px;
  }
  
  .marquee-item {
    font-size: 14px;
  }
}

@media (max-width: 640px) {
  .marquee::after {
    right: 45px;
  }
  
  .marquee-toggle {
    width: 32px;
    height: 32px;
  }
}
</style>