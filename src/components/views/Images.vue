<template>
  <div class="mediatheque-page">
    <!-- Hero Section -->
    <section class="mediatheque-hero">
      <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        
        <!-- Motifs islamiques -->
        <div class="islamic-patterns">
          <div v-for="n in 8" :key="`star-${n}`" class="islamic-pattern star" :style="{ 
            left: `${Math.random() * 100}%`, 
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`
          }"></div>
          <div v-for="n in 5" :key="`crescent-${n}`" class="islamic-pattern crescent" :style="{ 
            left: `${Math.random() * 100}%`, 
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`
          }"></div>
          <div v-for="n in 10" :key="`geometric-${n}`" class="islamic-pattern geometric" :style="{ 
            left: `${Math.random() * 100}%`, 
            top: `${Math.random() * 100}%`,
            animationDelay: `${Math.random() * 5}s`
          }"></div>
        </div>
      </div>
      
      <div class="hero-content">
        <div class="hero-icon">📸</div>
        <h1 class="hero-title">
          Médiathèque <span class="highlight">AEEMCI</span>
        </h1>
        <p class="hero-subtitle">
          Découvrez notre collection de photos d'événements
        </p>
      </div>
    </section>

    <!-- Introduction -->
    <section class="intro-section">
      <div class="container">
        <div class="intro-card">
          <div class="intro-icon">🎬</div>
          <p class="intro-text">
            Bienvenue dans la médiathèque de l'AEEMCI, votre espace dédié à la découverte visuelle 
            de nos activités, événements et moments forts. Explorez notre riche collection de photos 
            qui témoignent de l'engagement et du dynamisme de notre association.
          </p>
        </div>
      </div>
    </section>

    <!-- Filtre -->
    <section class="filter-section">
      <div class="container">
        <div class="filter-card">
          <div class="filter-header">
            <svg class="filter-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span class="filter-label">Filtrer par événement</span>
          </div>
          
          <div class="filter-options">
            <button 
              v-for="event in events" 
              :key="event.value"
              @click="selectedEvent = event.value"
              :class="['filter-btn', { active: selectedEvent === event.value }]"
            >
              <span class="filter-btn-icon">{{ event.icon }}</span>
              <span class="filter-btn-text">{{ event.label }}</span>
              <span class="filter-btn-count">{{ getEventCount(event.value) }}</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Résultats -->
    <section class="results-section">
      <div class="container">
        <div class="results-header">
          <h2 class="results-title">
            {{ filteredPhotos.length }} {{ filteredPhotos.length > 1 ? 'Photos' : 'Photo' }}
          </h2>
          <div class="results-info">
            {{ selectedEvent === 'all' ? 'Tous les événements' : events.find(e => e.value === selectedEvent)?.label }}
          </div>
        </div>
      </div>
    </section>

    <!-- Galerie -->
    <section class="gallery-section">
      <div class="container">
        <div class="gallery-grid">
          <div 
            v-for="(photo, index) in filteredPhotos" 
            :key="photo.id"
            class="gallery-item"
            @click="openLightbox(index)"
          >
            <div class="gallery-image">
              <img :src="photo.thumbnail" :alt="photo.caption" />
              <div class="gallery-overlay">
                <div class="overlay-content">
                  <div class="photo-date">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                      <line x1="16" y1="2" x2="16" y2="6"></line>
                      <line x1="8" y1="2" x2="8" y2="6"></line>
                      <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>{{ photo.date }}</span>
                  </div>
                  <p class="photo-caption">{{ photo.caption }}</p>
                  <div class="photo-category">{{ getCategoryLabel(photo.category) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredPhotos.length === 0" class="empty-state">
          <div class="empty-icon">📁</div>
          <h3 class="empty-title">Aucune photo trouvée</h3>
          <p class="empty-text">Essayez de sélectionner un autre événement</p>
          <button @click="selectedEvent = 'all'" class="btn-reset">Réinitialiser les filtres</button>
        </div>
      </div>
    </section>

    <!-- Lightbox -->
    <Transition name="lightbox">
      <div v-if="lightboxOpen" class="lightbox" @click="closeLightbox">
        <div class="lightbox-content" @click.stop>
          <button class="lightbox-close" @click="closeLightbox">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
          
          <div class="lightbox-image-container">
            <img :src="currentPhoto.full" :alt="currentPhoto.caption" class="lightbox-img" />
          </div>
          
          <div class="lightbox-info">
            <div class="lightbox-header">
              <div class="lightbox-category">{{ getCategoryLabel(currentPhoto.category) }}</div>
              <div class="lightbox-date">{{ currentPhoto.date }}</div>
            </div>
            <p class="lightbox-caption">{{ currentPhoto.caption }}</p>
          </div>
          
          <button class="lightbox-nav prev" @click.stop="prevImage">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
          <button class="lightbox-nav next" @click.stop="nextImage">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </button>
          
          <div class="lightbox-counter">
            {{ currentIndex + 1 }} / {{ filteredPhotos.length }}
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

// Events
const events = ref([
  { value: 'all', label: 'Tous les événements', icon: '🌐' },
  { value: 'senafoi', label: 'SENAFOI', icon: '📚' },
  { value: 'conferences', label: 'Conférences', icon: '🎤' },
  { value: 'formations', label: 'Formations', icon: '🎓' },
  { value: 'actions-sociales', label: 'Actions Sociales', icon: '❤️' }
])

const selectedEvent = ref('all')

// Photos
const photos = ref([
  {
    id: 1,
    thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/490196253_3453405321457455_8738441569432249276_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeHLCI6NM5uQbMaddVvT-05SU1H-NnS43hFTUf42dLjeEeaV0tY1P7bZ-kzJ4tGpPflJEd-q5jopN6W0cFwj5mvY&_nc_ohc=uDYCtFhDiggQ7kNvwEVevgN&_nc_oc=Adk0pyMEDFTWEdP9mK5hSlMF_X0ERhsc-7L2biLJYX4ebeFZmfkuXuwQrg-egIqSejQ&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=pnvhv4wPSsUUOb0hQqKoFA&oh=00_AfGtyn_7eOhNluiN0El6j0vNmfwLhdVFhpWab-7ssLFZEQ&oe=680F1183',
    full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/490196253_3453405321457455_8738441569432249276_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeHLCI6NM5uQbMaddVvT-05SU1H-NnS43hFTUf42dLjeEeaV0tY1P7bZ-kzJ4tGpPflJEd-q5jopN6W0cFwj5mvY&_nc_ohc=uDYCtFhDiggQ7kNvwEVevgN&_nc_oc=Adk0pyMEDFTWEdP9mK5hSlMF_X0ERhsc-7L2biLJYX4ebeFZmfkuXuwQrg-egIqSejQ&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=pnvhv4wPSsUUOb0hQqKoFA&oh=00_AfGtyn_7eOhNluiN0El6j0vNmfwLhdVFhpWab-7ssLFZEQ&oe=680F1183',
    caption: 'Séminaire SENAFOI 2023',
    date: '15/08/2023',
    category: 'senafoi',
    year: 2023
  },
  {
    id: 2,
    thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t1.6435-9/197506105_1022086955282712_7942794850543615823_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeE5G7aqmaBh86BYMGbKhvWwKRnNGhAdYg4pGc0aEB1iDgeyNAofR0D62-9SYkUHCObMhCm4BT2sn3ljy1UPsqhb&_nc_ohc=ZbhfB3TIkMkQ7kNvwGzGbSb&_nc_oc=AdmpeoKzu2UCf3h3KeJoQFA_TSyNa4kchJi1MPC0Mbv9uwU2bjpZJd9QRoJzucv1Jno&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=b5NuQDdDnFiNvF69_NrK2w&oh=00_AfHRV5Pa6LwYpCVBRz7QIwNFCy3795tAsl5oRDCfRVKJUQ&oe=6830A06D',
    full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t1.6435-9/197506105_1022086955282712_7942794850543615823_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeE5G7aqmaBh86BYMGbKhvWwKRnNGhAdYg4pGc0aEB1iDgeyNAofR0D62-9SYkUHCObMhCm4BT2sn3ljy1UPsqhb&_nc_ohc=ZbhfB3TIkMkQ7kNvwGzGbSb&_nc_oc=AdmpeoKzu2UCf3h3KeJoQFA_TSyNa4kchJi1MPC0Mbv9uwU2bjpZJd9QRoJzucv1Jno&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=b5NuQDdDnFiNvF69_NrK2w&oh=00_AfHRV5Pa6LwYpCVBRz7QIwNFCy3795tAsl5oRDCfRVKJUQ&oe=6830A06D',
    caption: 'Conférence sur l\'éducation islamique',
    date: '20/03/2024',
    category: 'conferences',
    year: 2024
  },
  {
    id: 3,
    thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/491970025_975113301450989_9055158565514544768_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFDzzJ74J5r03B7bfNQrlPp1GNmp2nwOKrUY2anafA4qlDxRuWvJUpecfCHKl-osJGb69wOFosAHPby4q8-VIgb&_nc_ohc=8gJAYTWXD8QQ7kNvwGzMH05&_nc_oc=Admyf7L5LMzlzvI2IMF7ka-cUoEHwyprz5UglaBmZW6BGUsJZNKFpL-irKTX9vDim7I&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=TOQ6Ue-QZg6mizGoplo3Vw&oh=00_AfEgWnbn7eC28uck5ye1oeFlbVgO-7qdyk1yJUITeijp9g&oe=680F0F14',
    full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/491970025_975113301450989_9055158565514544768_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFDzzJ74J5r03B7bfNQrlPp1GNmp2nwOKrUY2anafA4qlDxRuWvJUpecfCHKl-osJGb69wOFosAHPby4q8-VIgb&_nc_ohc=8gJAYTWXD8QQ7kNvwGzMH05&_nc_oc=Admyf7L5LMzlzvI2IMF7ka-cUoEHwyprz5UglaBmZW6BGUsJZNKFpL-irKTX9vDim7I&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=TOQ6Ue-QZg6mizGoplo3Vw&oh=00_AfEgWnbn7eC28uck5ye1oeFlbVgO-7qdyk1yJUITeijp9g&oe=680F0F14',
    caption: 'Formation en leadership islamique',
    date: '12/01/2024',
    category: 'formations',
    year: 2024
  },
  {
    id: 4,
    thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/478403972_942003521378083_4019226809911013937_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeENuI4QvGM2nDSItQGEKi5KVHyBsxr0LLlUfIGzGvQsufnZTS9UvkZgM124negPgK5eUPbDdGLhBVKLLKG7U_2w&_nc_ohc=22CQJktk9h4Q7kNvwFNfwD8&_nc_oc=Adly1T5rld9Yl6zqcrQ3gj4c1GLcptWQeVsRadouIs5ugxxE7MN9W-PS98gVB37G2lI&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=dbqLp9Q_yFvnyyVjzoPz9Q&oh=00_AfGiLN3ZYMScjEtus4MuQ_A3cFOgvCzG6ReufvngDOJLmA&oe=680F03C5',
    full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/478403972_942003521378083_4019226809911013937_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeENuI4QvGM2nDSItQGEKi5KVHyBsxr0LLlUfIGzGvQsufnZTS9UvkZgM124negPgK5eUPbDdGLhBVKLLKG7U_2w&_nc_ohc=22CQJktk9h4Q7kNvwFNfwD8&_nc_oc=Adly1T5rld9Yl6zqcrQ3gj4c1GLcptWQeVsRadouIs5ugxxE7MN9W-PS98gVB37G2lI&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=dbqLp9Q_yFvnyyVjzoPz9Q&oh=00_AfGiLN3ZYMScjEtus4MuQ_A3cFOgvCzG6ReufvngDOJLmA&oe=680F03C5',
    caption: 'Concours de récitation du Coran',
    date: '25/12/2023',
    category: 'formations',
    year: 2023
  },
  {
    id: 5,
    thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/482032246_1821894445299192_5407720889437853097_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeEUVwuXWo_WaP_iiKYDLEB16HRyT2Dz743odHJPYPPvjafLUy-l7W5gAsDaPOli5r_BBrUWVq83cDtwXAJ1GQQZ&_nc_ohc=adL00ma2GPoQ7kNvwFzTH3_&_nc_oc=AdkYny9Ya9sIJbNIwLId0YclSg2Lr1LzYUnPvs26gdfTLxiz9CvRrlcwCk59DyLmOh4&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=_MvS7yMidRAO0w8EVzRTIA&oh=00_AfHgwfdQP6ajgj7SNSebI5TEIR1OtF-r9kDxxPloc6xr9w&oe=680F01E2',
    full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/482032246_1821894445299192_5407720889437853097_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeEUVwuXWo_WaP_iiKYDLEB16HRyT2Dz743odHJPYPPvjafLUy-l7W5gAsDaPOli5r_BBrUWVq83cDtwXAJ1GQQZ&_nc_ohc=adL00ma2GPoQ7kNvwFzTH3_&_nc_oc=AdkYny9Ya9sIJbNIwLId0YclSg2Lr1LzYUnPvs26gdfTLxiz9CvRrlcwCk59DyLmOh4&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=_MvS7yMidRAO0w8EVzRTIA&oh=00_AfHgwfdQP6ajgj7SNSebI5TEIR1OtF-r9kDxxPloc6xr9w&oe=680F01E2',
    caption: 'Séminaire SENAFOI 2022',
    date: '18/08/2022',
    category: 'senafoi',
    year: 2022
  },
  {
    id: 6,
    thumbnail: 'https://scontent.fabj1-1.fna.fbcdn.net/v/t39.30808-6/480784758_1248895170572912_7590366694065536393_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGA6X_FS4AHBecU2dMhHUbVq0EGkziSpqarQQaTOJKmpisnuI6TgmzRK4PgX2CdcZ_hEpMDy2GsQ-A08h9BA7TO&_nc_ohc=OZ8DylP5bb8Q7kNvwE1nLPm&_nc_oc=AdmLfcU-J32yQ97HBNQ0bAunNgEt6rJEI6s-U1-31XSu4dN_k_K9Yxo0W7OZoFB47W0&_nc_zt=23&_nc_ht=scontent.fabj1-1.fna&_nc_gid=XFGsWEiLOGWWvThosvR8Tg&oh=00_AfF95xZUQMw8_N5Sh0zf_zuHQBDpCtgnDAN9HUS_cYNXhA&oe=680864F4',
    full: 'https://scontent.fabj1-1.fna.fbcdn.net/v/t39.30808-6/480784758_1248895170572912_7590366694065536393_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGA6X_FS4AHBecU2dMhHUbVq0EGkziSpqarQQaTOJKmpisnuI6TgmzRK4PgX2CdcZ_hEpMDy2GsQ-A08h9BA7TO&_nc_ohc=OZ8DylP5bb8Q7kNvwE1nLPm&_nc_oc=AdmLfcU-J32yQ97HBNQ0bAunNgEt6rJEI6s-U1-31XSu4dN_k_K9Yxo0W7OZoFB47W0&_nc_zt=23&_nc_ht=scontent.fabj1-1.fna&_nc_gid=XFGsWEiLOGWWvThosvR8Tg&oh=00_AfF95xZUQMw8_N5Sh0zf_zuHQBDpCtgnDAN9HUS_cYNXhA&oe=680864F4',
    caption: 'Action sociale à Yopougon',
    date: '15/02/2024',
    category: 'actions-sociales',
    year: 2024
  }
])

// Filtered photos
const filteredPhotos = computed(() => {
  if (selectedEvent.value === 'all') {
    return photos.value
  }
  return photos.value.filter(photo => photo.category === selectedEvent.value)
})

// Lightbox
const lightboxOpen = ref(false)
const currentIndex = ref(0)

const currentPhoto = computed(() => filteredPhotos.value[currentIndex.value] || {})

const openLightbox = (index) => {
  currentIndex.value = index
  lightboxOpen.value = true
  document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
  lightboxOpen.value = false
  document.body.style.overflow = 'auto'
}

const nextImage = () => {
  currentIndex.value = (currentIndex.value + 1) % filteredPhotos.value.length
}

const prevImage = () => {
  currentIndex.value = (currentIndex.value - 1 + filteredPhotos.value.length) % filteredPhotos.value.length
}

// Helper functions
const getEventCount = (eventValue) => {
  if (eventValue === 'all') return photos.value.length
  return photos.value.filter(p => p.category === eventValue).length
}

const getCategoryLabel = (category) => {
  const event = events.value.find(e => e.value === category)
  return event ? event.label : category
}

// Keyboard navigation
const handleKeydown = (e) => {
  if (!lightboxOpen.value) return
  
  if (e.key === 'Escape') closeLightbox()
  else if (e.key === 'ArrowRight') nextImage()
  else if (e.key === 'ArrowLeft') prevImage()
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap');

:root {
  --primary: var(--green);
  --primary-dark: var(--green);
  --secondary: var(--gold);
  --accent: var(--gold);
  --text-primary: var(--ink);
  --text-secondary: var(--ink-soft);
  --background: var(--surface);
}

.mediatheque-page {
  background: var(--background);
  min-height: 100vh;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* Hero */
.mediatheque-hero {
  position: relative;
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  padding: 6rem 2rem;
  text-align: center;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.hero-pattern {
  position: absolute;
  width: 100%;
  height: 100%;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.1;
}

.hero-glow-1, .hero-glow-2 {
  position: absolute;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  filter: blur(120px);
  opacity: 0.15;
}

.hero-glow-1 {
  top: -300px;
  left: -200px;
  background: radial-gradient(circle, var(--gold), transparent 70%);
  animation: float-glow 20s ease-in-out infinite;
}

.hero-glow-2 {
  bottom: -300px;
  right: -200px;
  background: radial-gradient(circle, var(--gold), transparent 70%);
  animation: float-glow 25s ease-in-out infinite reverse;
}

@keyframes float-glow {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(50px, -50px); }
}

.islamic-patterns {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.islamic-pattern {
  position: absolute;
  opacity: 0.15;
  animation: float-pattern 30s infinite linear;
}

.islamic-pattern.star {
  width: 30px;
  height: 30px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
  background-size: contain;
}

.islamic-pattern.crescent {
  width: 25px;
  height: 25px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
  background-size: contain;
}

.islamic-pattern.geometric {
  width: 40px;
  height: 40px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0 L100 50 L50 100 L0 50 Z'/%3E%3C/svg%3E");
  background-size: contain;
}

@keyframes float-pattern {
  0% { transform: translateY(0) rotate(0deg); }
  100% { transform: translateY(100vh) rotate(360deg); }
}

.hero-content {
  position: relative;
  z-index: 1;
  max-width: 800px;
  margin: 0 auto;
}

.hero-icon {
  font-size: 5rem;
  margin-bottom: 1.5rem;
  animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-15px); }
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 900;
  margin-bottom: 1rem;
  font-family: var(--display);
}

.highlight {
  background: linear-gradient(135deg, var(--gold), var(--gold));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  font-size: 1.25rem;
  opacity: 0.95;
}

/* Intro */
.intro-section {
  padding: 6rem 0 3rem;
}

.intro-card {
  background: white;
  border-radius: 0;
  padding: 3rem;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1,132,4,0.1);
  text-align: center;
}

.intro-icon {
  font-size: 4rem;
  margin-bottom: 1.5rem;
}

.intro-text {
  font-size: 1.15rem;
  line-height: 1.8;
  color: var(--text-secondary);
  max-width: 900px;
  margin: 0 auto;
}

/* Filter */
.filter-section {
  padding: 0 0 4rem;
}

.filter-card {
  background: white;
  border-radius: 0;
  padding: 3rem;
  box-shadow: var(--shadow);
  border: 2px solid rgba(1,132,4,0.1);
}

.filter-header {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 2rem;
}

.filter-icon {
  color: var(--green);
}

.filter-label {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text-primary);
}

.filter-options {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  justify-content: center;
}

.filter-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border: 2px solid rgba(1,132,4,0.2);
  border-radius: 0;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1rem;
  font-weight: 600;
  color: var(--text-primary);
}

.filter-btn:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
  border-color: var(--green);
}

.filter-btn.active {
  background: linear-gradient(135deg, var(--green), var(--green));
  border-color: var(--green);
  color: white;
  transform: translateY(-4px);
}

.filter-btn-icon {
  font-size: 1.5rem;
}

.filter-btn-count {
  background: rgba(255,255,255,0.2);
  padding: 0.25rem 0.75rem;
  border-radius: 0;
  font-size: 0.85rem;
  font-weight: 700;
}

.filter-btn.active .filter-btn-count {
  background: rgba(255,255,255,0.25);
}

/* Results */
.results-section {
  padding: 0 0 2rem;
}

.results-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.results-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--text-primary);
}

.results-info {
  font-size: 1.1rem;
  color: var(--text-secondary);
  font-weight: 600;
}

/* Gallery */
.gallery-section {
  padding: 0 0 6rem;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 2rem;
}

.gallery-item {
  border-radius: 0;
  overflow: hidden;
  box-shadow: var(--shadow);
  cursor: pointer;
  transition: all 0.3s ease;
}

.gallery-item:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow);
}

.gallery-image {
  position: relative;
  overflow: hidden;
}

.gallery-image img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.gallery-item:hover .gallery-image img {
  transform: scale(1.1);
}

.gallery-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
  padding: 1.5rem;
  color: white;
  transform: translateY(0);
  transition: all 0.3s ease;
}

.overlay-content {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.photo-date {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  opacity: 0.9;
}

.photo-caption {
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.4;
  margin: 0;
}

.photo-category {
  display: inline-block;
  background: rgba(1,132,4,0.8);
  padding: 0.4rem 0.8rem;
  border-radius: 0;
  font-size: 0.85rem;
  font-weight: 600;
  align-self: flex-start;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 6rem 2rem;
}

.empty-icon {
  font-size: 6rem;
  margin-bottom: 2rem;
  opacity: 0.5;
}

.empty-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 1rem;
}

.empty-text {
  font-size: 1.1rem;
  color: var(--text-secondary);
  margin-bottom: 2rem;
}

.btn-reset {
  padding: 1rem 2rem;
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  border: none;
  border-radius: 0;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-reset:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow);
}

/* Lightbox */
.lightbox {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.95);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 2rem;
}

.lightbox-content {
  position: relative;
  max-width: 1200px;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.lightbox-close {
  position: absolute;
  top: -60px;
  right: 0;
  background: rgba(255,255,255,0.1);
  border: 2px solid rgba(255,255,255,0.2);
  color: white;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.lightbox-close:hover {
  background: rgba(255,255,255,0.2);
  transform: rotate(90deg);
}

.lightbox-image-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.lightbox-img {
  max-width: 100%;
  max-height: 70vh;
  object-fit: contain;
  border-radius: 0;
}

.lightbox-info {
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255,255,255,0.2);
  border-radius: 0;
  padding: 2rem;
  color: white;
}

.lightbox-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.lightbox-category {
  background: rgba(1,132,4,0.8);
  padding: 0.5rem 1rem;
  border-radius: 0;
  font-weight: 600;
}

.lightbox-date {
  font-size: 1.1rem;
  font-weight: 600;
}

.lightbox-caption {
  font-size: 1.3rem;
  line-height: 1.6;
  margin: 0;
}

.lightbox-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.1);
  border: 2px solid rgba(255,255,255,0.2);
  color: white;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.lightbox-nav:hover {
  background: rgba(255,255,255,0.2);
  transform: translateY(-50%) scale(1.1);
}

.lightbox-nav.prev {
  left: 2rem;
}

.lightbox-nav.next {
  right: 2rem;
}

.lightbox-counter {
  position: absolute;
  bottom: -60px;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255,255,255,0.2);
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 0;
  font-weight: 700;
  font-size: 1.1rem;
}

/* Transitions */
.lightbox-enter-active,
.lightbox-leave-active {
  transition: opacity 0.3s ease;
}

.lightbox-enter-from,
.lightbox-leave-to {
  opacity: 0;
}

/* Responsive */
@media (max-width: 1024px) {
  .gallery-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .gallery-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }
  
  .filter-options {
    flex-direction: column;
  }
  
  .filter-btn {
    justify-content: space-between;
  }
  
  .results-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .lightbox-nav {
    width: 50px;
    height: 50px;
  }
  
  .lightbox-nav.prev {
    left: 1rem;
  }
  
  .lightbox-nav.next {
    right: 1rem;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .gallery-grid {
    grid-template-columns: 1fr;
  }
  
  .intro-card,
  .filter-card {
    padding: 2rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>