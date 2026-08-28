<template>
  <section class="band band--tight prog-band">
    <div class="wrap">
      <div class="rule rv"></div>

      <div class="sec-head">
        <div>
          <div class="eyebrow rv">Agenda AEEMCI</div>
          <h2 class="rv">Les prochaines activités,<br>publiées par le SENACREX.</h2>
        </div>
        <RouterLink class="link-more rv" to="/programmes">
          Calendrier complet
          <svg width="15" height="10" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </RouterLink>
      </div>

      <div v-if="loading" class="state">Chargement des programmes…</div>
      <div v-else-if="!programmes.length" class="state">Aucun programme publié pour le moment.</div>

      <div v-else class="prog">
        <RouterLink
          v-for="programme in programmes" :key="programme.id"
          class="prow rv" :to="programme.to"
          @mouseenter="showPeek(programme.image, $event)"
          @mousemove="movePeek"
          @mouseleave="hidePeek"
        >
          <span class="pdate">
            <strong>{{ programme.day }}</strong>
            <span>{{ programme.month }} {{ programme.year }}</span>
          </span>

          <span class="pmain">
            <h3>{{ programme.title }}</h3>
            <span class="pmeta">
              <span>{{ programme.type }}</span>
              <span>{{ programme.lieu || 'Lieu à confirmer' }}</span>
              <span v-if="programme.heure">{{ programme.heure }}</span>
            </span>
          </span>

          <p>{{ programme.description }}</p>

          <span class="go">
            <svg width="15" height="10" viewBox="0 0 16 10" fill="none"><path d="M1 5h13M10 1l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
        </RouterLink>
      </div>
    </div>

    <!-- Vignette suivant le curseur -->
    <div class="peek" :class="{ show: peek.visible }" :style="{ left: peek.x + 'px', top: peek.y + 'px' }">
      <img v-if="peek.image" :src="peek.image" alt="" @error="hidePeek">
    </div>
  </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { prefersReducedMotion } from '../../composables/useReveal'

const API_URL = 'https://api.aeemci-ce.ci/api_mobile/aeemci_programmes_api.php'

const loading = ref(true)
const programmes = ref([])

const fallbackProgrammes = [
  {
    id: 'fallback-cima',
    titre: 'CIMA 2025',
    type: 'EVENEMENT',
    resume: "Conseil des Imams et Formateurs de l'AEEMCI.",
    lieu: 'À confirmer',
    date_debut: '2025-12-26',
    date_fin: '2025-12-28',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758212841/aeemci_photo_1758212838756_cima.jpeg.jpg',
  },
  {
    id: 'fallback-ag',
    titre: 'Assemblée Générale 2025',
    type: 'RENCONTRE',
    resume: "Réunion annuelle des membres de l'association.",
    lieu: 'À confirmer',
    date_debut: '2025-12-27',
    date_fin: '2025-12-28',
    image: 'https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758212759/aeemci_photo_1758212756670_ag.jpeg.jpg',
  },
]

function parseDate(value) {
  if (!value) return null
  const date = new Date(String(value).replace(' ', 'T'))
  return Number.isNaN(date.getTime()) ? null : date
}

function imageFromPhotos(photos) {
  if (!Array.isArray(photos) || !photos.length) return ''
  const first = photos[0]
  return typeof first === 'string' ? first : (first.url || first.preview || '')
}

function formatTime(item) {
  const start = String(item.heure_debut || '').slice(0, 5)
  const end = String(item.heure_fin || '').slice(0, 5)
  if (start && end) return `${start} - ${end}`
  return start || ''
}

function formatProgramme(item) {
  const start = parseDate(item.date_debut || item.date || item.created_at)
  const end = parseDate(item.date_fin)
  const sameYear = start && end && start.getFullYear() === end.getFullYear()
  return {
    id: item.id,
    slug: item.slug,
    type: item.type || 'PROGRAMME',
    title: item.titre || item.title || 'Programme AEEMCI',
    description: item.resume || item.excerpt || item.description || '',
    lieu: item.lieu || item.location || item.ville || '',
    image: item.image || imageFromPhotos(item.photos),
    day: start ? (end && end.getTime() !== start.getTime() ? `${start.getDate()}-${end.getDate()}` : String(start.getDate()).padStart(2, '0')) : '--',
    month: start ? start.toLocaleDateString('fr-FR', { month: 'short' }).replace('.', '').toUpperCase() : 'À VENIR',
    year: start ? String(start.getFullYear()) : '',
    heure: formatTime(item),
    to: item.slug || item.id ? `/programmes/${item.slug || item.id}` : '/programmes',
    sameYear,
  }
}

async function fetchProgrammes() {
  loading.value = true
  try {
    const res = await fetch(`${API_URL}?action=latest&limit=4&rand=${Date.now()}`)
    const data = await res.json()
    if (!res.ok || !data.success) throw new Error(data.message || 'Erreur API')
    programmes.value = (data.data || data.programmes || []).map(formatProgramme)
  } catch {
    programmes.value = fallbackProgrammes.map(formatProgramme)
  } finally {
    loading.value = false
  }
}

onMounted(fetchProgrammes)

/* Vignette flottante — désactivée au toucher et en mouvement réduit. */
const peek = reactive({ visible: false, image: '', x: 0, y: 0 })

function pointerFine() {
  return window.matchMedia && window.matchMedia('(pointer:fine)').matches
}

function showPeek(image, event) {
  if (!image || !pointerFine() || prefersReducedMotion()) return
  peek.image = image
  peek.visible = true
  movePeek(event)
}
function movePeek(event) {
  if (!peek.visible) return
  peek.x = event.clientX + 150
  peek.y = event.clientY
}
function hidePeek() {
  peek.visible = false
}
</script>
<style scoped>
.prog-band{ padding-top:0; }
.rule{ margin-bottom:var(--section); }

.state{
  padding:3.5rem 2rem; border:1px solid var(--line); background:var(--surface);
  color:var(--muted); text-align:center; font-size:.9rem; letter-spacing:.04em;
}

.prog{ border-top:1px solid var(--line-strong); }
.prow{
  display:grid; grid-template-columns:104px 1.15fr 1.5fr auto; gap:clamp(16px,3vw,44px);
  align-items:center; padding:2.1rem 0; border-bottom:1px solid var(--line);
  position:relative; transition:padding-left .6s var(--ease);
}
.prow::before{
  content:""; position:absolute; inset:0 -1.5rem; background:var(--surface);
  z-index:-1; opacity:0; transition:opacity .5s var(--ease);
}
.prow:hover{ padding-left:1.3rem; }
.prow:hover::before{ opacity:1; }

.pdate{ display:block; }
.pdate strong{
  display:block; font-family:var(--display); font-weight:400; font-size:2rem; line-height:1;
  color:var(--green); letter-spacing:-.02em; font-variant-numeric:tabular-nums;
}
.pdate > span{
  display:block; margin-top:.4rem; font-size:.62rem; letter-spacing:.16em;
  text-transform:uppercase; color:var(--muted); font-weight:700;
}

.prow h3{ font-size:clamp(1.2rem,1.95vw,1.62rem); transition:color .4s; margin:0; }
.prow:hover h3{ color:var(--green); }
.pmeta{ display:flex; gap:.9rem; margin-top:.55rem; flex-wrap:wrap; }
.pmeta span{ font-size:.72rem; color:var(--muted); letter-spacing:.05em; }
.pmeta span + span::before{ content:"· "; }
.prow p{ font-size:.89rem; color:var(--ink-soft); line-height:1.7; margin:0; }

.go{
  width:46px; height:46px; border:1px solid var(--line-strong); display:grid; place-items:center;
  color:var(--muted); transition:all .55s var(--ease);
}
.prow:hover .go{ border-color:var(--green); background:var(--green); color:#fff; transform:rotate(-45deg); }

.peek{
  position:fixed; width:270px; height:340px; z-index:50; pointer-events:none; overflow:hidden;
  opacity:0; transform:translate(-50%,-50%) scale(.92);
  transition:opacity .45s var(--ease), transform .45s var(--ease);
  box-shadow:0 30px 70px -30px rgba(6,40,25,.5);
}
.peek.show{ opacity:1; transform:translate(-50%,-50%) scale(1); }
.peek img{ width:100%; height:100%; object-fit:cover; }

@media (max-width:900px){
  .prow{ grid-template-columns:1fr; gap:.75rem; }
  .go{ display:none; }
  .peek{ display:none; }
  .pdate{ display:flex; align-items:baseline; gap:.6rem; }
  .pdate strong{ font-size:1.4rem; }
  .pdate > span{ margin-top:0; }
}
</style>
