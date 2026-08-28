<template>
  <div class="cs">
    <!-- ══ En-tête propre à la page ══════════════════════════════════ -->
    <header class="cs-tete">
      <div class="cs-tete-in">
        <div>
          <span class="cs-eyebrow">AEEMCI · SENAFOI 2026 — Korhogo</span>
          <h1>Critiques du SENAFOI</h1>
          <p>Ce que les participants ont dit, dans l'ordre où ils l'ont dit.</p>
        </div>
        <div class="cs-total">
          <strong>{{ total }}</strong>
          <small>critique{{ total > 1 ? 's' : '' }}</small>
        </div>
      </div>
    </header>

    <main class="cs-corps">
      <!-- Filtres : une seule ligne collée en haut, la recherche se déplie -->
      <div class="cs-filtres">
        <div class="cs-ligne">
          <div class="cs-puces">
            <button :class="{ on: theme === '' }" @click="choisirTheme('')">
              Toutes <em>{{ total }}</em>
            </button>
            <button
              v-for="t in themesAffiches" :key="t.theme"
              :class="{ on: theme === t.theme }"
              :style="{ '--accent': couleur(t.theme) }"
              @click="choisirTheme(t.theme)"
            >{{ libelle(t.theme) }} <em>{{ t.total }}</em></button>
          </div>

          <button
            class="cs-loupe" :class="{ on: outilsOuverts || recherche }"
            :aria-label="outilsOuverts ? 'Fermer la recherche' : 'Rechercher'"
            @click="outilsOuverts = !outilsOuverts"
          >
            <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2.3">
              <circle cx="11" cy="11" r="7" /><line x1="21" y1="21" x2="16.6" y2="16.6" />
            </svg>
          </button>
        </div>

        <div v-if="outilsOuverts" class="cs-outils">
          <input
            ref="champRecherche" v-model="recherche" class="cs-search"
            placeholder="Rechercher un mot…" type="search"
          />
          <div class="cs-tri">
            <button :class="{ on: tri === 'recent' }" @click="tri = 'recent'">Plus récentes</button>
            <button :class="{ on: tri === 'ancien' }" @click="tri = 'ancien'">Plus anciennes</button>
          </div>
        </div>
      </div>

      <div v-if="erreur" class="cs-alerte">{{ erreur }}</div>
      <div v-else-if="chargement" class="cs-vide">Chargement…</div>
      <div v-else-if="!liste.length" class="cs-vide">
        Aucune critique{{ theme ? ' pour ce thème' : '' }}.
        <small>Les avis déposés apparaissent ici automatiquement.</small>
      </div>

      <!-- Liste -->
      <div v-else class="cs-liste">
        <article
          v-for="c in liste" :key="c.id"
          class="cs-carte"
          :style="{ '--accent': couleur(c.theme) }"
        >
          <header>
            <span class="cs-theme">{{ libelle(c.theme) }}</span>
            <time>{{ dateLisible(c.created_at) }}</time>
          </header>
          <p>{{ c.critique }}</p>
          <footer>
            <span class="cs-anonyme">Contribution anonyme</span>
            <button class="cs-lien" :disabled="exportEnCours === c.id" @click="exporterImage(c)">
              {{ exportEnCours === c.id ? 'Préparation…' : 'Télécharger en image' }}
            </button>
          </footer>
        </article>
      </div>

      <p v-if="liste.length" class="cs-pied">
        {{ liste.length }} critique{{ liste.length > 1 ? 's' : '' }} affichée{{ liste.length > 1 ? 's' : '' }}
        <template v-if="theme"> · thème {{ libelle(theme) }}</template>
      </p>
    </main>

    <!-- Retour en haut : la liste peut être longue sur un téléphone -->
    <button v-show="montrerHaut" class="cs-haut" aria-label="Revenir en haut" @click="remonter">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.6">
        <path d="M12 19V5M5 12l7-7 7 7" />
      </svg>
    </button>
  </div>
</template>

<script setup>
/**
 * Mur public des critiques du SENAFOI 2026.
 *
 * Page autonome, sans l'en-tête ni le pied de page du site. Elle ne lit que
 * le thème, le texte et la date : le statut de traitement et les notes
 * internes restent réservés à l'administration.
 */
import { ref, computed, onMounted, onBeforeUnmount, nextTick, watch } from 'vue'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_avis_api.php'

const LIBELLES = {
  FORMATION: 'Formation',
  RESTAURATION: 'Restauration',
  SECURITE: 'Sécurité',
  INNOVATION: 'Innovation',
  PROPRETE: 'Propreté',
  "COMITE D'ORGANISATION": "Comité d'organisation",
  AUTRE: 'Autre',
}
const COULEURS = {
  FORMATION: '#2563eb',
  RESTAURATION: '#ea580c',
  SECURITE: '#dc2626',
  INNOVATION: '#7c3aed',
  PROPRETE: '#0891b2',
  "COMITE D'ORGANISATION": '#c026d3',
  AUTRE: '#475569',
}

const chargement = ref(true)
const erreur = ref('')
const critiques = ref([])
const parTheme = ref([])
const total = ref(0)
const theme = ref('')
const recherche = ref('')
const tri = ref('recent')
const exportEnCours = ref(null)

const outilsOuverts = ref(false)
const champRecherche = ref(null)
const montrerHaut = ref(false)

// La recherche n'apparaît qu'à la demande : sur un téléphone, chaque ligne
// gagnée en haut de page est une critique de plus à l'écran.
watch(outilsOuverts, async (ouvert) => {
  if (!ouvert) { recherche.value = ''; return }
  await nextTick()
  champRecherche.value?.focus()
})

function surDefilement() {
  montrerHaut.value = window.scrollY > 700
}

function remonter() {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function libelle(t) { return LIBELLES[t] || t }
function couleur(t) { return COULEURS[t] || '#475569' }

const themesAffiches = computed(() => parTheme.value.filter((t) => Number(t.total) > 0))

const liste = computed(() => {
  const q = recherche.value.trim().toLowerCase()
  const filtrees = critiques.value.filter((c) => {
    if (theme.value && c.theme !== theme.value) return false
    if (!q) return true
    return String(c.critique || '').toLowerCase().includes(q)
  })
  // Le serveur renvoie déjà du plus récent au plus ancien : on n'inverse
  // que si l'utilisateur demande l'ordre chronologique.
  return tri.value === 'ancien' ? [...filtrees].reverse() : filtrees
})

async function charger() {
  chargement.value = true
  erreur.value = ''
  try {
    const res = await fetch(`${API}?action=liste_publique&rand=${Date.now()}`)
    const data = await res.json()
    if (!data.success) throw new Error(data.message || 'Chargement impossible')
    critiques.value = data.critiques || []
    parTheme.value = data.par_theme || []
    total.value = data.total || critiques.value.length
  } catch (e) {
    erreur.value = `${e.message} — si le problème persiste, l'API des avis n'est peut-être pas à jour.`
  } finally {
    chargement.value = false
  }
}

function choisirTheme(t) {
  theme.value = t
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function dateLisible(valeur) {
  if (!valeur) return ''
  const d = new Date(String(valeur).replace(' ', 'T'))
  if (Number.isNaN(d.getTime())) return valeur
  return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long' })
    + ' à ' + d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

/* ─── Export d'une critique en image ──────────────────────────────────── */

/** Découpe un texte en lignes qui tiennent dans une largeur donnée. */
function decouper(ctx, texte, largeurMax) {
  const lignes = []
  for (const paragraphe of String(texte).split('\n')) {
    let ligne = ''
    for (const mot of paragraphe.split(/\s+/)) {
      const essai = ligne ? `${ligne} ${mot}` : mot
      if (ctx.measureText(essai).width > largeurMax && ligne) {
        lignes.push(ligne)
        ligne = mot
      } else {
        ligne = essai
      }
    }
    lignes.push(ligne)
  }
  return lignes
}

function coinsArrondis(ctx, x, y, l, h, r) {
  ctx.beginPath()
  ctx.moveTo(x + r, y)
  ctx.lineTo(x + l - r, y)
  ctx.quadraticCurveTo(x + l, y, x + l, y + r)
  ctx.lineTo(x + l, y + h - r)
  ctx.quadraticCurveTo(x + l, y + h, x + l - r, y + h)
  ctx.lineTo(x + r, y + h)
  ctx.quadraticCurveTo(x, y + h, x, y + h - r)
  ctx.lineTo(x, y + r)
  ctx.quadraticCurveTo(x, y, x + r, y)
  ctx.closePath()
}

async function exporterImage(c) {
  exportEnCours.value = c.id
  try {
    const T = 1080
    const MARGE = 72
    const accent = couleur(c.theme)

    // Première passe : on mesure le texte pour choisir la hauteur du visuel.
    const mesure = document.createElement('canvas').getContext('2d')
    let taille = 44
    mesure.font = `500 ${taille}px system-ui, -apple-system, "Segoe UI", sans-serif`
    let lignes = decouper(mesure, c.critique, T - MARGE * 2 - 56)
    while (lignes.length > 14 && taille > 26) {
      taille -= 3
      mesure.font = `500 ${taille}px system-ui, -apple-system, "Segoe UI", sans-serif`
      lignes = decouper(mesure, c.critique, T - MARGE * 2 - 56)
    }

    const interligne = Math.round(taille * 1.5)
    const hauteurTexte = lignes.length * interligne
    const H = Math.max(1080, 470 + hauteurTexte + 190)

    const cv = document.createElement('canvas')
    cv.width = T
    cv.height = H
    const ctx = cv.getContext('2d')

    // Fond
    ctx.fillStyle = '#f7f9fc'
    ctx.fillRect(0, 0, T, H)

    // Bandeau
    const degrade = ctx.createLinearGradient(0, 0, T, 300)
    degrade.addColorStop(0, '#0f5132')
    degrade.addColorStop(1, '#198754')
    ctx.fillStyle = degrade
    ctx.fillRect(0, 0, T, 300)

    ctx.fillStyle = 'rgba(255,255,255,.78)'
    ctx.font = '700 26px system-ui, sans-serif'
    ctx.fillText('AEEMCI · SENAFOI 2026 — KORHOGO', MARGE, 108)

    ctx.fillStyle = '#fff'
    ctx.font = '800 66px system-ui, sans-serif'
    ctx.fillText('Critique du SENAFOI', MARGE, 190)

    // Pastille du thème
    ctx.font = '800 26px system-ui, sans-serif'
    const libelleTheme = libelle(c.theme).toUpperCase()
    const largeurPastille = ctx.measureText(libelleTheme).width + 56
    ctx.fillStyle = accent
    coinsArrondis(ctx, MARGE, 224, largeurPastille, 54, 27)
    ctx.fill()
    ctx.fillStyle = '#fff'
    ctx.fillText(libelleTheme, MARGE + 28, 259)

    // Carte du message
    const carteY = 360
    const carteH = hauteurTexte + 120
    ctx.fillStyle = '#fff'
    coinsArrondis(ctx, MARGE, carteY, T - MARGE * 2, carteH, 34)
    ctx.fill()
    ctx.fillStyle = accent
    coinsArrondis(ctx, MARGE, carteY, 10, carteH, 5)
    ctx.fill()

    // Guillemet d'ouverture, en filigrane
    ctx.fillStyle = `${accent}22`
    ctx.font = '800 130px Georgia, serif'
    ctx.fillText('“', MARGE + 34, carteY + 118)

    ctx.fillStyle = '#1f2937'
    ctx.font = `500 ${taille}px system-ui, -apple-system, "Segoe UI", sans-serif`
    let y = carteY + 86
    for (const ligne of lignes) {
      ctx.fillText(ligne, MARGE + 56, y)
      y += interligne
    }

    // Pied
    ctx.fillStyle = '#94a3b8'
    ctx.font = '500 26px system-ui, sans-serif'
    ctx.fillText(dateLisible(c.created_at), MARGE, carteY + carteH + 62)
    ctx.fillText('Contribution anonyme', MARGE, carteY + carteH + 102)

    ctx.fillStyle = '#0f5132'
    ctx.font = '700 26px system-ui, sans-serif'
    const signature = 'AEEMCI, pour une identité islamique !'
    ctx.fillText(signature, T - MARGE - ctx.measureText(signature).width, carteY + carteH + 102)

    const lien = document.createElement('a')
    lien.download = `critique_${String(c.theme).toLowerCase().replace(/[^a-z0-9]+/g, '_')}_${c.id}.png`
    lien.href = cv.toDataURL('image/png')
    lien.click()
  } catch (e) {
    erreur.value = `Export impossible : ${e.message}`
  } finally {
    exportEnCours.value = null
  }
}

onMounted(() => {
  charger()
  window.addEventListener('scroll', surDefilement, { passive: true })
})
onBeforeUnmount(() => window.removeEventListener('scroll', surDefilement))
</script>

<style scoped>
/* Pensé pour le téléphone : tout est dimensionné pour un écran de 360 px,
   les écrans larges ne font qu'élargir la colonne. */

.cs { min-height: 100vh; background: #f5f7fb; color: var(--ink);
      padding-bottom: calc(40px + env(safe-area-inset-bottom, 0px));
      -webkit-text-size-adjust: 100%;
      -webkit-tap-highlight-color: rgba(21, 128, 61, .12); }

/* ── En-tête ──────────────────────────────────────────────────────── */
.cs-tete { background: linear-gradient(135deg, #0f5132, #198754); color: #fff;
           padding-top: env(safe-area-inset-top, 0px); }
.cs-tete-in { max-width: 900px; margin: 0 auto; padding: 18px 16px 20px;
              display: flex; align-items: center; justify-content: space-between; gap: 14px; }
.cs-eyebrow { font-size: 9.5px; font-weight: 800; letter-spacing: .09em;
              text-transform: uppercase; color: var(--green-mist); }
.cs-tete h1 { margin: 5px 0 3px; font-size: 25px; line-height: 1.1; letter-spacing: -.02em; }
.cs-tete p { margin: 0; color: var(--green-mist); font-size: 12.5px; line-height: 1.4; }
.cs-total { text-align: right; flex-shrink: 0; }
.cs-total strong { display: block; font-size: 30px; line-height: 1; }
.cs-total small { color: var(--green-mist); font-size: 9.5px; font-weight: 800;
                  text-transform: uppercase; letter-spacing: .05em; }

/* ── Corps ────────────────────────────────────────────────────────── */
.cs-corps { max-width: 900px; margin: 0 auto; padding: 12px 12px 0; }

/* ── Filtres collés en haut ───────────────────────────────────────── */
.cs-filtres { position: sticky; top: 0; z-index: 10; margin: -12px -12px 12px;
              padding: 10px 12px; background: rgba(245, 247, 251, .95);
              backdrop-filter: blur(10px); border-bottom: 1px solid rgba(226, 232, 240, .8); }
.cs-ligne { display: flex; align-items: center; gap: 8px; }

.cs-puces { flex: 1; min-width: 0; display: flex; gap: 6px; overflow-x: auto;
            scroll-snap-type: x proximity; padding: 2px 0;
            scrollbar-width: none; -ms-overflow-style: none; }
.cs-puces::-webkit-scrollbar { display: none; }
.cs-puces button { --accent: var(--ink-soft); flex-shrink: 0; scroll-snap-align: start;
                   display: inline-flex; align-items: center; gap: 6px;
                   min-height: 40px; padding: 0 14px; border-radius: 999px;
                   border: 1px solid var(--line); background: #fff; font-size: 13px;
                   font-weight: 700; cursor: pointer; font-family: inherit;
                   color: var(--ink-soft); white-space: nowrap; }
.cs-puces button em { font-style: normal; font-size: 11px; font-weight: 800; opacity: .55; }
.cs-puces button.on { background: var(--accent); border-color: var(--accent); color: #fff; }
.cs-puces button.on em { opacity: .85; }

.cs-loupe { flex-shrink: 0; width: 40px; height: 40px; border-radius: 0;
            border: 1px solid var(--line); background: #fff; color: var(--ink-soft);
            display: grid; place-items: center; cursor: pointer; }
.cs-loupe.on { background: #0f5132; border-color: #0f5132; color: #fff; }

.cs-outils { display: grid; gap: 8px; margin-top: 9px; }
.cs-search { width: 100%; height: 46px; border: 1px solid var(--line); border-radius: 0;
             padding: 0 13px; font-size: 16px; font-family: inherit; background: #fff;
             color: var(--ink); -webkit-appearance: none; appearance: none; }
.cs-search:focus { outline: none; border-color: var(--green);
                   box-shadow: 0 0 0 3px rgba(21, 128, 61, .12); }
.cs-tri { display: grid; grid-template-columns: 1fr 1fr; gap: 4px;
          background: #eef2f7; border-radius: 0; padding: 3px; }
.cs-tri button { min-height: 38px; border: 0; border-radius: 0; background: none;
                 font-size: 12.5px; font-weight: 700; color: var(--muted); cursor: pointer;
                 font-family: inherit; }
.cs-tri button.on { background: #fff; color: #0f5132; box-shadow: var(--shadow); }

/* ── États ────────────────────────────────────────────────────────── */
.cs-alerte { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;
             border-radius: 0; padding: 13px 15px; font-weight: 600; font-size: 13.5px;
             line-height: 1.5; }
.cs-vide { padding: 48px 18px; text-align: center; color: var(--muted); background: #fff;
           border: 1px dashed var(--line); border-radius: 0; font-size: 14.5px; }
.cs-vide small { display: block; margin-top: 8px; font-size: 12.5px; }

/* ── Cartes ───────────────────────────────────────────────────────── */
.cs-liste { display: grid; gap: 10px; }
.cs-carte { --accent: var(--ink-soft); background: #fff; border: 1px solid var(--line);
            border-left: 4px solid var(--accent); border-radius: 0;
            padding: 14px 15px; display: grid; gap: 9px; }
.cs-carte > header { display: flex; align-items: center; justify-content: space-between;
                     gap: 8px; }
.cs-theme { display: inline-block; padding: 4px 10px; border-radius: 999px;
            background: color-mix(in srgb, var(--accent) 12%, #fff);
            color: var(--accent); font-size: 10.5px; font-weight: 800;
            text-transform: uppercase; letter-spacing: .04em; }
.cs-carte time { color: var(--muted); font-size: 11.5px; text-align: right; }
.cs-carte p { margin: 0; font-size: 15.5px; line-height: 1.6; white-space: pre-line;
              color: var(--ink); overflow-wrap: anywhere; }
.cs-carte > footer { display: grid; gap: 9px; padding-top: 9px;
                     border-top: 1px solid var(--surface); }
.cs-anonyme { color: var(--muted); font-size: 11.5px; font-style: italic; }
.cs-lien { width: 100%; min-height: 44px; border: 1px solid var(--line); background: #fff;
           border-radius: 0; padding: 0 14px; font-size: 13.5px; font-weight: 700;
           color: var(--accent); cursor: pointer; font-family: inherit; }
.cs-lien:disabled { opacity: .5; cursor: not-allowed; }

.cs-pied { margin: 16px 0 0; text-align: center; color: var(--muted); font-size: 12px; }

/* ── Retour en haut ───────────────────────────────────────────────── */
.cs-haut { position: fixed; right: 14px; z-index: 20;
           bottom: calc(16px + env(safe-area-inset-bottom, 0px));
           width: 48px; height: 48px; border-radius: 50%; border: 0;
           background: #0f5132; color: #fff; display: grid; place-items: center;
           cursor: pointer; box-shadow: var(--shadow); }

/* ══ Écran moyen ═════════════════════════════════════════════════ */
@media (min-width: 560px) {
  .cs-tete-in { padding: 26px 18px 28px; align-items: flex-end; }
  .cs-tete h1 { font-size: 34px; }
  .cs-tete p { font-size: 14px; }
  .cs-total strong { font-size: 38px; }
  .cs-corps { padding: 16px 18px 0; }
  .cs-filtres { margin: -16px -18px 16px; padding: 14px 18px; }
  .cs-outils { grid-template-columns: 1fr 260px; align-items: center; }
  .cs-carte { padding: 17px 20px; }
  .cs-carte > footer { grid-template-columns: 1fr auto; align-items: center; }
  .cs-lien { width: auto; }
}

/* ══ Écran large ═════════════════════════════════════════════════ */
@media (min-width: 900px) {
  .cs-tete h1 { font-size: 40px; }
  .cs-haut { right: 28px; bottom: 28px; }
}
</style>
