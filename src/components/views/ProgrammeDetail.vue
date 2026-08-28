<script setup>
import { computed, onMounted, ref } from 'vue'
import { RouterLink, useRoute } from 'vue-router'

const API_URL = 'https://api.aeemci-ce.ci/api_mobile/aeemci_programmes_api.php'
const route = useRoute()

const programme = ref(null)
const loading = ref(true)
const error = ref('')

const photos = computed(() => parsePhotos(programme.value?.photos))
const mainImage = computed(() => programme.value?.image || photoUrl(photos.value[0]))

onMounted(fetchProgramme)

async function fetchProgramme() {
  loading.value = true
  error.value = ''
  try {
    const slug = encodeURIComponent(route.params.slug)
    const res = await fetch(`${API_URL}?action=get&slug=${slug}&rand=${Date.now()}`)
    const data = await res.json()
    if (!res.ok || !data.success) throw new Error(data.message || 'Programme introuvable')
    programme.value = data.data || null
  } catch (e) {
    error.value = e.message || 'Impossible de charger ce programme.'
  } finally {
    loading.value = false
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
  if (!item?.date_debut) return 'Date à préciser'
  const start = new Date(`${item.date_debut}T00:00:00`)
  const startText = start.toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })
  if (!item.date_fin || item.date_fin === item.date_debut) return startText
  const end = new Date(`${item.date_fin}T00:00:00`)
  return `${startText} au ${end.toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}`
}

function formatTime(item) {
  const start = String(item?.heure_debut || '').slice(0, 5)
  const end = String(item?.heure_fin || '').slice(0, 5)
  if (start && end) return `${start} - ${end}`
  return start || 'Heure à préciser'
}
</script>

<template>
  <main class="detail-page">
    <div v-if="loading" class="state">Chargement du programme...</div>
    <div v-else-if="error" class="state state-error">
      {{ error }}
      <RouterLink to="/programmes">Retour aux programmes</RouterLink>
    </div>

    <article v-else-if="programme" class="programme">
      <section class="hero">
        <img v-if="mainImage" :src="mainImage" :alt="programme.titre" />
        <div class="hero-overlay">
          <RouterLink to="/programmes" class="back">← Programmes</RouterLink>
          <span class="type">{{ programme.type || 'Programme' }}</span>
          <h1>{{ programme.titre }}</h1>
          <p>{{ programme.resume }}</p>
        </div>
      </section>

      <section class="body">
        <aside class="info">
          <div>
            <span>Date</span>
            <strong>{{ formatDate(programme) }}</strong>
          </div>
          <div>
            <span>Heure</span>
            <strong>{{ formatTime(programme) }}</strong>
          </div>
          <div>
            <span>Lieu</span>
            <strong>{{ programme.lieu || 'À préciser' }}</strong>
            <small v-if="programme.ville">{{ programme.ville }}</small>
          </div>
          <div v-if="programme.organisateur">
            <span>Organisation</span>
            <strong>{{ programme.organisateur }}</strong>
          </div>
          <a
            v-if="programme.action_url"
            :href="programme.action_url"
            class="cta"
            target="_blank"
            rel="noopener"
          >
            {{ programme.action_label || 'Participer' }}
          </a>
        </aside>

        <div class="content">
          <h2>Détails du programme</h2>
          <div class="description" v-html="programme.description || programme.resume || 'Les informations détaillées seront ajoutées bientôt.'"></div>

          <div v-if="photos.length > 1" class="gallery">
            <h3>Galerie</h3>
            <div>
              <img v-for="(photo, index) in photos.slice(1)" :key="index" :src="photoUrl(photo)" :alt="programme.titre" />
            </div>
          </div>
        </div>
      </section>
    </article>
  </main>
</template>

<style scoped>
.detail-page {
  min-height: 100vh;
  background: #f6faf7;
  color: #12251b;
}

.state {
  width: min(900px, calc(100% - 32px));
  margin: 8rem auto;
  background: white;
  border: 1px solid #e3ede7;
  border-radius: 0;
  padding: 2rem;
  display: grid;
  gap: 1rem;
}

.state-error {
  color: #b91c1c;
}

.hero {
  position: relative;
  min-height: 560px;
  display: flex;
  align-items: end;
  overflow: hidden;
  background: linear-gradient(135deg, var(--green), var(--green));
}

.hero img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.hero::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(8, 47, 31, .24), rgba(8, 47, 31, .88));
}

.hero-overlay {
  position: relative;
  z-index: 1;
  width: min(1120px, calc(100% - 32px));
  margin: 0 auto;
  padding: 7rem 0 4rem;
  color: white;
}

.back,
.type {
  display: inline-flex;
  align-items: center;
  height: 34px;
  padding: 0 .85rem;
  border-radius: 999px;
  background: rgba(255, 255, 255, .16);
  color: white;
  text-decoration: none;
  font-weight: 800;
  margin: 0 .45rem .9rem 0;
}

.type {
  background: var(--gold);
}

.hero h1 {
  max-width: 850px;
  font-size: clamp(2.25rem, 6vw, 5.2rem);
  line-height: .95;
  margin: .3rem 0 1rem;
}

.hero p {
  max-width: 720px;
  color: rgba(255, 255, 255, .88);
  font-size: 1.08rem;
  line-height: 1.7;
}

.body {
  width: min(1120px, calc(100% - 32px));
  margin: -2rem auto 0;
  position: relative;
  z-index: 2;
  display: grid;
  grid-template-columns: 320px minmax(0, 1fr);
  gap: 1rem;
  padding-bottom: 5rem;
}

.info,
.content {
  background: white;
  border: 1px solid #e3ede7;
  border-radius: 0;
  box-shadow: var(--shadow);
}

.info {
  padding: 1rem;
  align-self: start;
  display: grid;
  gap: .8rem;
}

.info div {
  border-bottom: 1px solid #eef4f0;
  padding: .35rem 0 .85rem;
}

.info span {
  display: block;
  color: #6c8175;
  font-size: .78rem;
  text-transform: uppercase;
  font-weight: 900;
  letter-spacing: .08em;
  margin-bottom: .25rem;
}

.info strong {
  display: block;
  line-height: 1.35;
}

.info small {
  display: block;
  color: #607469;
  margin-top: .15rem;
}

.cta {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  height: 48px;
  border-radius: 0;
  background: var(--green);
  color: white;
  text-decoration: none;
  font-weight: 900;
}

.content {
  padding: clamp(1.2rem, 4vw, 2rem);
}

.content h2 {
  font-size: 2rem;
  margin: 0 0 1rem;
}

.description {
  color: #32483c;
  line-height: 1.85;
  font-size: 1.03rem;
}

.gallery {
  margin-top: 2rem;
}

.gallery div {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: .75rem;
}

.gallery img {
  width: 100%;
  aspect-ratio: 4 / 3;
  object-fit: cover;
  border-radius: 0;
}

@media (max-width: 860px) {
  .hero {
    min-height: 460px;
  }

  .body {
    grid-template-columns: 1fr;
  }
}
</style>
