<script setup>
import { computed, onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'

const API_URL = 'https://api.aeemci-ce.ci/api_mobile/aeemci_programmes_api.php'

const programmes = ref([])
const loading = ref(true)
const error = ref('')
const search = ref('')
const type = ref('')

const filteredProgrammes = computed(() => {
  const needle = search.value.trim().toLowerCase()
  return programmes.value.filter((item) => {
    const matchType = !type.value || item.type === type.value
    const matchText = !needle || [item.titre, item.resume, item.lieu, item.ville, item.type]
      .some((value) => String(value || '').toLowerCase().includes(needle))
    return matchType && matchText
  })
})

const types = computed(() => [...new Set(programmes.value.map((item) => item.type).filter(Boolean))])

onMounted(fetchProgrammes)

async function fetchProgrammes() {
  loading.value = true
  error.value = ''
  try {
    const res = await fetch(`${API_URL}?action=latest&limit=50&include_past=1&rand=${Date.now()}`)
    const data = await res.json()
    if (!res.ok || !data.success) throw new Error(data.message || 'Chargement impossible')
    programmes.value = (data.data || []).map(normalizeProgramme)
  } catch (e) {
    error.value = e.message || 'Impossible de charger les programmes.'
    programmes.value = []
  } finally {
    loading.value = false
  }
}

function normalizeProgramme(item) {
  const photos = parsePhotos(item.photos)
  return {
    ...item,
    id: Number(item.id),
    slug: item.slug || item.id,
    image: item.image || photoUrl(photos[0]),
    photos,
  }
}

function parsePhotos(value) {
  if (!value) return []
  if (Array.isArray(value)) return value
  if (typeof value === 'string') {
    try {
      const parsed = JSON.parse(value)
      return Array.isArray(parsed) ? parsed : []
    } catch {
      return value.trim() ? [{ url: value.trim() }] : []
    }
  }
  return []
}

function photoUrl(photo) {
  if (!photo) return ''
  return typeof photo === 'string' ? photo : (photo.url || photo.preview || '')
}

function formatDate(item) {
  if (!item.date_debut) return 'Date à préciser'
  const start = new Date(`${item.date_debut}T00:00:00`)
  const startText = start.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })
  if (!item.date_fin || item.date_fin === item.date_debut) return startText
  const end = new Date(`${item.date_fin}T00:00:00`)
  return `${startText} au ${end.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })}`
}

function formatTime(item) {
  const start = String(item.heure_debut || '').slice(0, 5)
  const end = String(item.heure_fin || '').slice(0, 5)
  if (start && end) return `${start} - ${end}`
  return start || 'Heure à préciser'
}
</script>

<template>
  <main class="programmes-page">
    <section class="hero">
      <div class="hero-inner">
        <span class="eyebrow">AEEMCI</span>
        <h1>Programmes à venir</h1>
        <p>Retrouvez les activités, formations, rencontres et rendez-vous importants de l'association.</p>
      </div>
    </section>

    <section class="content">
      <div class="toolbar">
        <div>
          <p class="toolbar-kicker">Agenda</p>
          <h2>{{ filteredProgrammes.length }} programme{{ filteredProgrammes.length > 1 ? 's' : '' }}</h2>
        </div>
        <div class="filters">
          <input v-model="search" type="search" placeholder="Rechercher un programme..." />
          <select v-model="type">
            <option value="">Tous les types</option>
            <option v-for="item in types" :key="item" :value="item">{{ item }}</option>
          </select>
        </div>
      </div>

      <div v-if="loading" class="state">Chargement des programmes...</div>
      <div v-else-if="error" class="state state-error">{{ error }}</div>
      <div v-else-if="!filteredProgrammes.length" class="state">Aucun programme disponible pour le moment.</div>

      <div v-else class="grid">
        <article v-for="item in filteredProgrammes" :key="item.id" class="card">
          <RouterLink :to="`/programmes/${item.slug || item.id}`" class="media">
            <img v-if="item.image" :src="item.image" :alt="item.titre" />
            <div v-else class="media-placeholder">{{ item.type || 'AEEMCI' }}</div>
          </RouterLink>
          <div class="card-body">
            <div class="meta">
              <span>{{ item.type || 'Programme' }}</span>
              <span>{{ formatDate(item) }}</span>
            </div>
            <h3>{{ item.titre }}</h3>
            <p>{{ item.resume || 'Les détails de ce programme seront bientôt disponibles.' }}</p>
            <div class="details">
              <span>{{ item.lieu || 'Lieu à préciser' }}</span>
              <span>{{ formatTime(item) }}</span>
            </div>
            <RouterLink :to="`/programmes/${item.slug || item.id}`" class="more">Voir le détail</RouterLink>
          </div>
        </article>
      </div>
    </section>
  </main>
</template>

<style scoped>
.programmes-page {
  background: #f6faf7;
  color: #13251c;
  min-height: 100vh;
}

.hero {
  background:
    linear-gradient(135deg, rgba(8, 96, 55, .92), rgba(21, 128, 61, .78)),
    url('https://api.aeemci-ce.ci/cloud/medias/senafoi/photos/20260623_084857_5de23679.jpg') center/cover;
  padding: 8rem 1.25rem 4.5rem;
}

.hero-inner,
.content {
  width: min(1120px, calc(100% - 32px));
  margin: 0 auto;
}

.eyebrow,
.toolbar-kicker {
  color: var(--gold);
  font-weight: 900;
  letter-spacing: .14em;
  text-transform: uppercase;
  font-size: .78rem;
}

.hero h1 {
  color: white;
  font-size: clamp(2.3rem, 7vw, 5rem);
  line-height: .95;
  margin: .6rem 0 1rem;
}

.hero p {
  color: rgba(255, 255, 255, .88);
  max-width: 640px;
  font-size: 1.08rem;
  line-height: 1.7;
}

.content {
  padding: 3rem 0 5rem;
}

.toolbar {
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.toolbar h2 {
  margin: .3rem 0 0;
  font-size: 2rem;
}

.filters {
  display: flex;
  gap: .75rem;
  flex-wrap: wrap;
}

.filters input,
.filters select {
  height: 44px;
  border: 1px solid #dbe7df;
  background: #fff;
  border-radius: 0;
  padding: 0 1rem;
  color: #18362a;
  min-width: 210px;
  outline: none;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 1rem;
}

.card {
  background: #fff;
  border: 1px solid #e3ede7;
  border-radius: 0;
  overflow: hidden;
  box-shadow: var(--shadow);
}

.media {
  display: block;
  height: 220px;
  background: #e9f5ee;
  overflow: hidden;
}

.media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .35s ease;
}

.card:hover .media img {
  transform: scale(1.04);
}

.media-placeholder {
  height: 100%;
  display: grid;
  place-items: center;
  color: var(--green);
  font-weight: 900;
}

.card-body {
  padding: 1.15rem;
}

.meta,
.details {
  display: flex;
  flex-wrap: wrap;
  gap: .45rem;
  color: #5f756a;
  font-size: .82rem;
}

.meta span:first-child {
  color: var(--green);
  font-weight: 800;
}

.card h3 {
  margin: .65rem 0 .5rem;
  font-size: 1.2rem;
  line-height: 1.25;
}

.card p {
  color: #506458;
  line-height: 1.6;
  min-height: 76px;
}

.more {
  display: inline-flex;
  margin-top: 1rem;
  color: #0f7a3f;
  font-weight: 900;
  text-decoration: none;
}

.state {
  background: #fff;
  border: 1px solid #e3ede7;
  border-radius: 0;
  padding: 2rem;
  color: #5f756a;
}

.state-error {
  color: #b91c1c;
}

@media (max-width: 900px) {
  .toolbar {
    align-items: stretch;
    flex-direction: column;
  }

  .grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 620px) {
  .hero {
    padding-top: 6.5rem;
  }

  .grid {
    grid-template-columns: 1fr;
  }

  .filters input,
  .filters select {
    width: 100%;
  }
}
</style>
