<template>
  <section class="actualites animate-slide-up">
    <div class="section-header">
      <h2><span class="highlight">A</span>ctualités</h2>
      <span class="section-icon">📰</span>
    </div>
    
    <!-- Loading state -->
    <div v-if="loading" class="loading-state">
      <div class="loading-spinner"></div>
      <p>Chargement des actualités...</p>
    </div>
    
    <!-- Error state -->
    <div v-else-if="error" class="error-state">
      <p>❌ Erreur lors du chargement des actualités: {{ error }}</p>
      <button @click="loadActualites" class="btn btn-sm">Réessayer</button>
    </div>
    
    <!-- Featured article (most recent) -->
    <div v-else-if="featuredArticle" class="news-item featured">
      <div class="news-content">
        <h3 style="font-weight: bold; font-size: 17px">{{ featuredArticle.title }}</h3>
        <div class="news-meta">
          <span class="icon-calendar">📅</span>
          <span>{{ formatDate(featuredArticle.date) }}</span>
        </div>
        <p>{{ featuredArticle.excerpt }}</p>
        <div class="event-details" v-if="featuredArticle.content">
          <div class="detail-item">
            <strong>Contenu :</strong> {{ truncateText(featuredArticle.content, 200) }}
          </div>
        </div>
        <a :href="`/detail_actualite?id=${featuredArticle.id}`" class="btn btn-sm">Lire plus</a>
      </div>
      <div class="news-image" v-if="featuredArticle.image">
        <img :src="featuredArticle.image" :alt="featuredArticle.title" class="img-responsive" />
      </div>
    </div>
    
    <!-- Other articles carousel -->
    <NewsCarousel v-if="otherArticles.length > 0" :articles="otherArticles" />
    
    <!-- No articles state -->
    <div v-else-if="!loading && !error && actualites.length === 0" class="no-articles">
      <p>Aucune actualité disponible pour le moment.</p>
    </div>
    
    <div class="view-all-news">
      <a href="/actualites" class="btn btn-outline btn-sm">Voir toutes les actualités</a>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import NewsCarousel from './NewsCarousel.vue'
import { actualitesApi, actualiteUtils } from '@/services/actualitesApi.js'

// État réactif
const actualites = ref([])
const loading = ref(false)
const error = ref(null)

// Articles calculés
const featuredArticle = computed(() => {
  return actualites.value.length > 0 ? actualiteUtils.formatActualite(actualites.value[0]) : null
})

const otherArticles = computed(() => {
  return actualites.value.slice(1, 5).map(actualite => actualiteUtils.formatActualite(actualite))
})

// Fonctions utilitaires
const formatDate = (dateString) => {
  return actualiteUtils.formatDate(dateString)
}

const truncateText = (text, maxLength = 150) => {
  return actualiteUtils.truncateText(text, maxLength)
}

// Charger les actualités
const loadActualites = async () => {
  try {
    loading.value = true
    error.value = null
    
    console.log('🔄 Chargement des actualités...')
    const data = await actualitesApi.getLatestActualites()
    
    actualites.value = data || []
    console.log('✅ Actualités chargées:', actualites.value.length)
    
  } catch (err) {
    console.error('❌ Erreur lors du chargement des actualités:', err)
    error.value = err.message || 'Erreur lors du chargement des actualités'
    
    // Fallback vers les données statiques en cas d'erreur
    actualites.value = [
      {
        id: 1,
        titre: 'l\'Amir à Dakar',
        date_publication: '2025-09-11',
        resume: 'Cette rencontre a rassemblé les présidents des différentes AEEM, qui ont partagé leurs expériences et leurs réflexions afin de renforcer la coopération entre elles.',
        contenu: 'Cette rencontre a rassemblé les présidents des différentes AEEM, qui ont partagé leurs expériences et leurs réflexions afin de renforcer la coopération entre elles. À l\'issue des assises, les instances de l\'AEEMS ont été renouvelées pour un mandat de deux ans, et le frère Issa Baboucar DIEDHIOU a été désigné comme nouveau président de l\'association. Quant à l\'Amir de l\'AEEMCI, il a regagné Abidjan sain et sauf, porté par vos invocations.',
        image_url: '../../assets/senegalag.jpeg'
      },
      {
        id: 2,
        titre: 'AEEMCI partenaire au FACFI',
        date_publication: '2025-04-23',
        resume: 'Le samedi 23 août, l\'Amir de l\'AEEMCI, Youssouf BAMBA, a participé au FACFI (Forum des Acteurs de la Finance Islamique), un cadre d\'échanges riche...',
        contenu: 'Le samedi 23 août, l\'Amir de l\'AEEMCI, Youssouf BAMBA, a participé au FACFI (Forum des Acteurs de la Finance Islamique), un cadre d\'échanges riche en enseignements et en opportunités de collaboration.',
        image_url: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758208910/aeemci_photo_1758208905564_facfi.jpeg.jpg'
      },
      {
        id: 3,
        titre: 'SENAFOI Yakro 2025',
        date_publication: '2025-08-03',
        resume: 'Séminaire National de Formation Islamique et Manageriale dans l\'optique de permetre aux jeunes musulmans de Côte d\'Ivoire de se former durant les vacances scolaires...',
        contenu: 'Séminaire National de Formation Islamique et Manageriale dans l\'optique de permetre aux jeunes musulmans de Côte d\'Ivoire de se former durant les vacances scolaires.',
        image_url: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758208953/aeemci_photo_1758208952364_senafoia.jpeg.jpg'
      },
      {
        id: 4,
        titre: 'Séminaire Régionaux',
        date_publication: '2025-04-10',
        resume: 'Dans le but de contribuer à la formation des jeunes musulmans pendant les congés de paques, plusieurs séminaires régionaux ont été organisés sur le territoir national...',
        contenu: 'Dans le but de contribuer à la formation des jeunes musulmans pendant les congés de paques, plusieurs séminaires régionaux ont été organisés sur le territoir national.',
        image_url: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758209236/aeemci_photo_1758209228825_regionaux.jpeg.jpg'
      },
      {
        id: 5,
        titre: 'Rupture Collective du Jeûne',
        date_publication: '2024-06-28',
        resume: 'Le Comité Exécutif de l\'AEEMCI a organisé une grande rupture collective du jeûne à l\'occasion du mois béni de Ramadan 1447H / 2025...',
        contenu: 'Le Comité Exécutif de l\'AEEMCI a organisé une grande rupture collective du jeûne à l\'occasion du mois béni de Ramadan 1447H / 2025.',
        image_url: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758209305/aeemci_photo_1758209303650_rupture.jpeg.jpg'
      }
    ]
  } finally {
    loading.value = false
  }
}

// Charger les actualités au montage du composant
onMounted(() => {
  loadActualites()
})
</script>

<style scoped>
/* News Section */
.actualites {
  margin-bottom: 1.8rem;
  background-color: white;
  border-radius: 8px;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.2rem;
  border-bottom: 2px solid #16a34a;
  padding-bottom: 0.4rem;
}

.section-header h2 {
  font-size: 1.3rem;
  font-weight: 700;
}

.highlight {
  color: #16a34a;
  font-weight: 800;
}

.section-icon {
  color: #16a34a;
  font-size: 1rem;
}

.news-item.featured {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.2rem;
  background-color: var(--background-alt);
  border-radius: 6px;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
  margin-bottom: 1.2rem;
}

.news-item.featured:hover {
  transform: translateY(-3px);
}

.news-meta {
  display: flex;
  align-items: center;
  color: var(--text-light);
  font-size: 0.75rem;
  margin-bottom: 0.6rem;
}

.icon-calendar {
  margin-right: 0.3rem;
}

.event-details {
  background-color: rgba(26, 95, 63, 0.1);
  padding: 0.6rem;
  border-radius: 4px;
  margin: 0.8rem 0;
}

.detail-item {
  margin-bottom: 0.4rem;
  font-size: 0.8rem;
}

.img-responsive {
  display: block;
  width: 100%;
  height: auto;
  border-radius: 4px;
  transition: transform 0.5s ease;
}

.btn {
  display: inline-block;
  padding: 0.3rem 0.7rem;
  border-radius: 2px;
  font-weight: 600;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  font-size: 0.75rem;
  letter-spacing: 0.3px;
  text-decoration: none;
  background-color: #16a34a;
  color: white;
}

.btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
}

.btn-outline {
  background-color: transparent;
  border: 1px solid #16a34a;
  color: #16a34a;
}

.btn-outline:hover {
  background-color: #16a34a;
  color: white;
}

.view-all-news {
  text-align: center;
  margin-top: 1.2rem;
}

.animate-slide-up {
  opacity: 0;
  transform: translateY(20px);
  animation: slideUp 0.6s ease forwards;
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Loading and Error States */
.loading-state {
  text-align: center;
  padding: 2rem;
  color: var(--text-light);
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #16a34a;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-state {
  text-align: center;
  padding: 2rem;
  background-color: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 6px;
  color: #dc2626;
  margin-bottom: 1rem;
}

.error-state p {
  margin-bottom: 1rem;
}

.no-articles {
  text-align: center;
  padding: 2rem;
  color: var(--text-light);
  font-style: italic;
}

/* Responsive */
@media (max-width: 992px) {
  .news-item.featured {
    grid-template-columns: 1fr;
  }
}
</style>
