<template>
  <section class="band band--tight news-band">
    <div class="wrap">
      <div class="rule rv"></div>

      <div class="sec-head">
        <div>
          <div class="eyebrow rv">Actualités</div>
          <h2 class="rv">La vie de l'association.</h2>
        </div>
        <RouterLink class="link-more rv" to="/programmes">
          Agenda de l'association
          <svg width="15" height="10" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </RouterLink>
      </div>

      <div v-if="loading" class="state">Chargement des actualités…</div>
      <div v-else-if="!featuredArticle" class="state">Aucune actualité publiée pour le moment.</div>

      <div v-else class="news">
        <!-- Article mis en avant -->
        <a class="feature" href="#" @click.prevent="openDetail(featuredArticle)">
          <div class="frame wipe rv">
            <img v-if="featuredArticle.image" :src="featuredArticle.image" :alt="featuredArticle.title">
            <div v-else class="fallback">AEEMCI</div>
          </div>
          <div class="tagrow rv">
            <span class="pill" :class="toneOf(featuredArticle.type)">{{ getTypeLabel(featuredArticle.type) }}</span>
            <span class="date">{{ featuredArticle.date }}</span>
            <span v-if="featuredArticle.lieu" class="date">· {{ featuredArticle.lieu }}</span>
          </div>
          <h3 class="rv">{{ featuredArticle.title }}</h3>
          <p class="rv" v-html="truncateText(featuredArticle.excerpt, 240)"></p>
        </a>

        <!-- Les suivantes -->
        <div class="newslist">
          <a
            v-for="article in otherArticles" :key="article.id"
            class="nitem rv" href="#" @click.prevent="openDetail(article)"
          >
            <span class="nthumb">
              <img v-if="article.image" :src="article.image" :alt="article.title">
              <span v-else class="fallback">AEEMCI</span>
            </span>
            <span>
              <span class="date">{{ article.date }}</span>
              <h4>{{ article.title }}</h4>
            </span>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'

// URL de l'API
const API_URL = 'https://api.aeemci-ce.ci/api_mobile/aeemci_actualites_api.php'

// État réactif
const actualites = ref([])
const loading = ref(false)
const error = ref(null)
const showDetailModal = ref(false)
const selectedArticle = ref(null)
const showPhotoModal = ref(false)
const selectedPhoto = ref(null)

// Labels des types
const typeLabels = {
  'evenement': 'Événement',
  'annonce': 'Annonce',
  'activite': 'Activité',
  'communique': 'Communiqué'
}

const getTypeLabel = (type) => {
  return typeLabels[type] || type || 'Actualité'
}

// Une teinte par type : la couleur porte l'information.
const typeTones = {
  evenement: 'tone-clay',
  annonce: 'tone-gold',
  activite: 'tone-teal',
  communique: 'tone-plum'
}
const toneOf = (type) => typeTones[type] || 'tone-green'

// Formater une actualité depuis l'API
const formatActualite = (item) => {
  // Déterminer la date à afficher
  let dateDisplay = ''
  if (item.date_specifique) {
    dateDisplay = formatDate(item.date_specifique)
  } else if (item.date_debut) {
    dateDisplay = formatDate(item.date_debut)
    if (item.date_fin && item.date_fin !== item.date_debut) {
      dateDisplay += ' - ' + formatDate(item.date_fin)
    }
  } else if (item.created_at) {
    dateDisplay = formatDate(item.created_at)
  }

  // Parser les photos si c'est une chaîne JSON
  let photos = []
  if (item.photos) {
    try {
      photos = typeof item.photos === 'string' ? JSON.parse(item.photos) : item.photos
    } catch (e) {
      photos = []
    }
  }

  return {
    id: item.id,
    title: item.titre || 'Sans titre',
    type: item.type || 'annonce',
    date: dateDisplay,
    lieu: item.lieu || '',
    excerpt: item.texte_affichage || '',
    content: item.texte_detaille || '',
    image: photos.length > 0 ? (photos[0].url || photos[0].preview || photos[0]) : (item.image || null),
    photos: photos,
    statut: item.statut || 'actif'
  }
}

// Formater une date
const formatDate = (dateString) => {
  if (!dateString) return ''
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  } catch (e) {
    return dateString
  }
}

// Tronquer le texte
const truncateText = (text, maxLength = 150) => {
  if (!text) return ''
  // Supprimer les balises HTML pour le calcul de longueur
  const plainText = text.replace(/<[^>]*>/g, '')
  if (plainText.length <= maxLength) return text
  return plainText.substring(0, maxLength) + '...'
}

// Articles calculés
const featuredArticle = computed(() => {
  return actualites.value.length > 0 ? formatActualite(actualites.value[0]) : null
})

const otherArticles = computed(() => {
  return actualites.value.slice(1, 5).map(item => formatActualite(item))
})

// Charger les actualités depuis l'API
const loadActualites = async () => {
  loading.value = true
  error.value = null

  try {
    // Création d'une variable aléatoire pour éviter le cache
    const timestamp = Date.now()
    const randomStr = Math.random().toString(36).substring(2, 15)
    
    // URL avec action=list et variable aléatoire
    const url = `${API_URL}?action=latest&limit=4&_=${timestamp}${randomStr}`
    
    console.log('Chargement des actualités depuis :', url)
    
    const response = await fetch(url, {
      method: 'GET',
      headers: {
        'Accept': 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`)
    }

    const data = await response.json()
    console.log('Réponse API :', data)

    if (data.success && Array.isArray(data.data)) {
      // Filtrer seulement les actualités actives
      actualites.value = data.data.filter(item => item.statut === 'PUBLIÉ' || item.statut === 'actif')
      console.log('Actualités chargées :', actualites.value.length)
    } else {
      throw new Error(data.message || 'Format de réponse invalide')
    }

  } catch (err) {
    console.error('Erreur lors du chargement des actualités :', err)
    
    // CORRECTION: On met error a null pour permettre l'affichage du fallback
    error.value = null

    // Fallback : données locales si API échoue
    actualites.value = [
      {
        id: 1,
        titre: "l'Amir à Dakar",
        type: 'evenement',
        date_specifique: '2025-09-11',
        lieu: 'Dakar, Sénégal',
        texte_affichage: 'Cette rencontre a rassemblé les présidents des différentes AEEM pour discuter des enjeux majeurs du mouvement estudiantin musulman en Afrique de l\'Ouest.',
        texte_detaille: 'Cette rencontre a rassemblé les présidents des différentes AEEM pour discuter des enjeux majeurs du mouvement estudiantin musulman en Afrique de l\'Ouest. Des décisions importantes ont été prises concernant la coordination régionale.',
        photos: ['https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758208910/aeemci_photo_1758208905564_facfi.jpeg.jpg'],
        statut: 'actif'
      },
      {
        id: 2,
        titre: 'AEEMCI partenaire au FACFI',
        type: 'evenement',
        date_specifique: '2025-04-23',
        lieu: 'Abidjan',
        texte_affichage: "Le samedi 23 août, l'Amir de l'AEEMCI, Youssouf BAMBA, a représenté l'association lors du Forum Africain de la Culture et de la Foi Islamique (FACFI).",
        texte_detaille: "Le samedi 23 août, l'Amir de l'AEEMCI, Youssouf BAMBA, a représenté l'association lors du Forum Africain de la Culture et de la Foi Islamique (FACFI). Cette participation renforce le positionnement de l'AEEMCI dans le paysage islamique ivoirien.",
        photos: ['https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758208910/aeemci_photo_1758208905564_facfi.jpeg.jpg'],
        statut: 'actif'
      }
    ]
    
    console.log('Fallback activé avec', actualites.value.length, 'actualités')
  } finally {
    loading.value = false
  }
}

// Ouvrir le modal de détail
const openDetail = (article) => {
  window.location.href = `/detail_actualite?id=${article.id}`
}

// Fermer le modal de détail
const closeDetail = () => {
  showDetailModal.value = false
  selectedArticle.value = null
  document.body.style.overflow = ''
}

// Ouvrir une photo en plein écran
const openPhoto = (photo) => {
  selectedPhoto.value = photo
  showPhotoModal.value = true
}

// Fermer la photo
const closePhoto = () => {
  showPhotoModal.value = false
  selectedPhoto.value = null
}

// Charger les actualités au montage du composant
onMounted(() => {
  loadActualites()
})
</script>
<style scoped>
.news-band{ padding-top:0; }
.rule{ margin-bottom:var(--section); }

.state{
  padding:3.5rem 2rem; border:1px solid var(--line); background:var(--surface);
  color:var(--muted); text-align:center; font-size:.9rem; letter-spacing:.04em;
}

.news{ display:grid; grid-template-columns:1.15fr .85fr; gap:clamp(28px,4vw,68px); align-items:start; }

.feature{ display:block; }
.feature .frame{ aspect-ratio:16/10.2; }
.feature:hover .frame img{ transform:scale(1.09); }
.feature .frame::after{
  content:""; position:absolute; inset:0; opacity:0; transition:opacity .6s;
  background:linear-gradient(to top, rgba(5,30,20,.34), transparent 48%);
}
.feature:hover .frame::after{ opacity:1; }
.feature h3{ font-size:clamp(1.5rem,2.4vw,2.1rem); margin-top:1rem; line-height:1.16; transition:color .4s; }
.feature:hover h3{ color:var(--green); }
.feature p{ margin-top:.95rem; color:var(--ink-soft); font-size:.94rem; line-height:1.75; max-width:56ch; }
.feature p :deep(*){ margin:0; }

.tagrow{ display:flex; align-items:center; gap:1rem; margin-top:1.6rem; flex-wrap:wrap; }
.date{ font-size:.71rem; letter-spacing:.1em; color:var(--muted); font-weight:600; }

.fallback{
  width:100%; height:100%; display:grid; place-items:center;
  background:var(--green-mist); color:var(--green);
  font-family:var(--display); font-size:1.1rem; letter-spacing:.18em;
}

.newslist{ display:flex; flex-direction:column; border-top:1px solid var(--line); }
.nitem{
  display:grid; grid-template-columns:98px 1fr; gap:1.35rem; align-items:start;
  padding:1.55rem 0; border-bottom:1px solid var(--line); position:relative;
  transition:padding-left .55s var(--ease);
}
.nitem::before{
  content:""; position:absolute; left:0; top:0; bottom:0; width:1px; background:var(--gold);
  transform:scaleY(0); transform-origin:top; transition:transform .55s var(--ease);
}
.nitem:hover{ padding-left:1.15rem; }
.nitem:hover::before{ transform:scaleY(1); }
.nthumb{ aspect-ratio:1/1; overflow:hidden; background:var(--surface); display:block; }
.nthumb img{ width:100%; height:100%; object-fit:cover; transition:transform 1.1s var(--ease); }
.nitem:hover .nthumb img{ transform:scale(1.1); }
.nitem h4{ font-family:var(--sans); font-size:.95rem; font-weight:700; line-height:1.45; transition:color .4s; margin:0; }
.nitem:hover h4{ color:var(--green); }
.nitem .date{ display:block; margin-bottom:.5rem; font-size:.67rem; }

@media (max-width:1180px){ .news{ grid-template-columns:1fr; } }
@media (max-width:640px){ .nitem{ grid-template-columns:76px 1fr; } }
</style>
