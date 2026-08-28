<template>
  <div class="sc">
    <header class="sc-tete">
      <div>
        <span class="sc-eyebrow">SENAFOI 2026</span>
        <h1>Sorties provisoires</h1>
      </div>
      <div class="sc-compteur">
        <strong>{{ dehors }}</strong>
        <small>dehors</small>
      </div>
    </header>

    <main class="sc-corps">
      <!-- ── Lecteur ────────────────────────────────────────────────── -->
      <section class="sc-bloc">
        <div class="sc-cadre" :class="{ actif: scanEnCours }">
          <video ref="vue" playsinline muted></video>
          <div v-if="!scanEnCours" class="sc-repos">
            <svg viewBox="0 0 24 24" width="42" height="42" fill="none" stroke="currentColor" stroke-width="1.6">
              <rect x="3" y="3" width="7" height="7" rx="1" /><rect x="14" y="3" width="7" height="7" rx="1" />
              <rect x="3" y="14" width="7" height="7" rx="1" /><path d="M14 14h3v3h-3zM19 19h2v2h-2zM14 19h2v2h-2zM19 14h2v2h-2z" />
            </svg>
            <p>Lecteur à l'arrêt</p>
          </div>
          <div v-else class="sc-mire"></div>
        </div>

        <div class="sc-duo">
          <button v-if="!scanEnCours" class="sc-btn sc-btn--vert" @click="demarrer">Démarrer le scan</button>
          <button v-else class="sc-btn" @click="arreter">Arrêter</button>
        </div>

        <form class="sc-duo" @submit.prevent="chercher(saisieManuelle)">
          <input v-model="saisieManuelle" class="sc-input" placeholder="Ou saisir le matricule" />
          <button class="sc-btn" :disabled="chargement || !saisieManuelle.trim()">Chercher</button>
        </form>

        <label class="sc-champ">
          <span>Agent au poste</span>
          <input v-model="agent" placeholder="Votre nom" @change="memoriserAgent" />
        </label>
      </section>

      <!-- ── Résultat ───────────────────────────────────────────────── -->
      <section class="sc-bloc">
        <div v-if="chargement" class="sc-etat">Recherche…</div>

        <div v-else-if="erreur" class="sc-carte sc-carte--erreur">
          <strong>{{ erreur }}</strong>
          <button class="sc-btn" @click="reinitialiser">Scanner à nouveau</button>
        </div>

        <template v-else-if="personne">
          <div class="sc-carte" :class="dehors_personne ? 'sc-carte--dehors' : 'sc-carte--dedans'">
            <div class="sc-bandeau">
              {{ termine ? (dernierSens === 'retour' ? 'Retour enregistré' : 'Sortie enregistrée')
                         : (dehors_personne ? 'Actuellement dehors' : 'Présent sur le camp') }}
            </div>

            <div class="sc-identite">
              <img v-if="personne.photo" :src="personne.photo" alt="" />
              <span v-else class="sc-initiales">{{ initiales }}</span>
              <div>
                <strong>{{ personne.prenom }} {{ personne.nom }}</strong>
                <span class="sc-categorie">{{ personne.categorie }}</span>
                <small v-if="personne.detail">{{ personne.detail }}</small>
                <small class="sc-mono">{{ personne.matricule }}</small>
              </div>
            </div>

            <p v-if="dehors_personne && sortie" class="sc-info">
              Sorti à <strong>{{ heureCourte(sortie.heure_sortie) }}</strong>
              <template v-if="sortie.retour_prevu"> · retour annoncé à <strong>{{ sortie.retour_prevu }}</strong></template>
              <template v-if="sortie.motif"><br />Motif : {{ sortie.motif }}</template>
            </p>
          </div>

          <!-- Formulaire de sortie -->
          <div v-if="!dehors_personne && !termine" class="sc-carte sc-form">
            <div class="sc-bandeau">Enregistrer la sortie</div>
            <div class="sc-form-corps">
              <div class="sc-duo">
                <label class="sc-champ">
                  <span>Heure de sortie</span>
                  <input v-model="form.heure_sortie" type="time" />
                </label>
                <label class="sc-champ">
                  <span>Retour prévu</span>
                  <input v-model="form.retour_prevu" type="time" />
                </label>
              </div>

              <label class="sc-champ">
                <span>Motif de la sortie</span>
                <input v-model="form.motif" placeholder="Ex : rendez-vous médical" />
              </label>

              <label class="sc-champ">
                <span>Destination</span>
                <input v-model="form.destination" placeholder="Où va la personne ?" />
              </label>

              <div class="sc-billet">
                <div class="sc-apercu">
                  <img v-if="form.billet_photo" :src="form.billet_photo" alt="" />
                  <span v-else>BILLET</span>
                </div>
                <label class="sc-upload">
                  <!-- capture=environment ouvre directement l'appareil photo -->
                  <input type="file" accept="image/*" capture="environment" @change="photographierBillet" />
                  <strong>{{ televersement ? `Envoi ${progression}%` : 'Photographier le billet' }}</strong>
                  <small>Facultatif, mais recommandé</small>
                </label>
              </div>

              <button class="sc-btn sc-btn--vert sc-btn--large" :disabled="enregistrement || televersement" @click="enregistrerSortie">
                {{ enregistrement ? 'Enregistrement…' : 'Valider la sortie' }}
              </button>
            </div>
          </div>

          <!-- Formulaire de retour -->
          <div v-if="dehors_personne && !termine" class="sc-carte sc-form">
            <div class="sc-bandeau">Enregistrer le retour</div>
            <div class="sc-form-corps">
              <img v-if="sortie?.billet_photo" :src="sortie.billet_photo" class="sc-billet-vu" alt="Billet de sortie" />
              <label class="sc-champ">
                <span>Heure d'entrée</span>
                <input v-model="form.heure_retour" type="time" />
              </label>
              <button class="sc-btn sc-btn--vert sc-btn--large" :disabled="enregistrement" @click="enregistrerRetour">
                {{ enregistrement ? 'Enregistrement…' : 'Valider le retour' }}
              </button>
            </div>
          </div>

          <button class="sc-btn sc-btn--large" @click="reinitialiser">Scanner le suivant</button>
        </template>

        <div v-else class="sc-etat sc-etat--vide">
          Visez le QR code d'un badge.
          <small>
            Le même lecteur gère tout le monde : séminaristes, SR, CCR, commissions,
            formateurs, stands, imarat, invités… Un badge déjà sorti bascule
            automatiquement sur l'écran de retour.
          </small>
        </div>

        <div v-if="journal.length" class="sc-journal">
          <h2>Derniers passages</h2>
          <ul>
            <li v-for="j in journal" :key="j.cle">
              <span class="sc-point" :class="j.sens"></span>
              <div>
                <strong>{{ j.nom }}</strong>
                <small>{{ j.sens === 'sortie' ? 'sortie' : 'retour' }} · {{ j.heure }}</small>
              </div>
            </li>
          </ul>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
/**
 * Poste des sorties provisoires du camp.
 *
 * Page nue, pensée pour un téléphone tenu au portail. Un seul scan :
 * si la personne est sur le camp on enregistre sa sortie (heure, motif,
 * photo du billet), si elle est déjà dehors on enregistre son retour.
 */
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue'
import { BrowserMultiFormatReader } from '@zxing/library'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_sorties_camp_api.php'
const UPLOAD_URL = 'https://api.aeemci-ce.ci/cloud/upload.php'
const CLOUD_API_KEY = 'SAKONE25_04_2026_medias'

const vue = ref(null)
const scanEnCours = ref(false)
const chargement = ref(false)
const enregistrement = ref(false)
const televersement = ref(false)
const progression = ref(0)
const erreur = ref('')

const personne = ref(null)
const sortie = ref(null)
const dehors_personne = ref(false)
const termine = ref(false)
const dernierSens = ref('')

const saisieManuelle = ref('')
const agent = ref('')
const journal = ref([])
const dehors = ref(0)

const form = reactive({
  heure_sortie: '',
  retour_prevu: '',
  motif: '',
  destination: '',
  billet_photo: '',
  billet_photo_id: '',
  heure_retour: '',
})

let lecteur = null
let dernierCode = ''
let dernierMoment = 0

const initiales = computed(() => {
  const p = personne.value
  if (!p) return '?'
  return `${(p.prenom || '')[0] || ''}${(p.nom || '')[0] || ''}`.toUpperCase() || '?'
})

function heureMaintenant() {
  const d = new Date()
  return `${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`
}

function heureCourte(valeur) {
  if (!valeur) return '—'
  const d = new Date(String(valeur).replace(' ', 'T'))
  if (Number.isNaN(d.getTime())) return valeur
  return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

/* ─── Lecteur ─────────────────────────────────────────────────────────── */

async function demarrer() {
  erreur.value = ''
  try {
    lecteur = new BrowserMultiFormatReader()
    scanEnCours.value = true
    await lecteur.decodeFromConstraints(
      { video: { facingMode: 'environment' } },
      vue.value,
      (resultat) => {
        if (!resultat) return
        const texte = String(resultat.getText() || '').trim()
        const maintenant = Date.now()
        if (texte === dernierCode && maintenant - dernierMoment < 3000) return
        dernierCode = texte
        dernierMoment = maintenant
        bip()
        chercher(texte)
      }
    )
  } catch (e) {
    scanEnCours.value = false
    erreur.value = `Caméra indisponible : ${e.message}. Saisissez le matricule à la main.`
  }
}

function arreter() {
  try { lecteur?.reset() } catch { /* déjà arrêté */ }
  lecteur = null
  scanEnCours.value = false
}

function bip(frequence = 880) {
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)()
    const osc = ctx.createOscillator()
    const gain = ctx.createGain()
    osc.frequency.value = frequence
    osc.connect(gain); gain.connect(ctx.destination)
    gain.gain.setValueAtTime(0.08, ctx.currentTime)
    gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.18)
    osc.start(); osc.stop(ctx.currentTime + 0.18)
  } catch { /* audio bloqué */ }
}

/* ─── Recherche ───────────────────────────────────────────────────────── */

function extraireMatricule(texte) {
  const brut = String(texte || '').trim()
  const parametre = brut.match(/matricule=([A-Za-z0-9_-]+)/i)
  if (parametre) return parametre[1].toUpperCase()
  return (brut.split(/[/?#\s]+/).filter(Boolean).pop() || brut).toUpperCase()
}

async function chercher(texte) {
  const matricule = extraireMatricule(texte)
  if (!matricule) return

  chargement.value = true
  erreur.value = ''
  personne.value = null
  sortie.value = null
  termine.value = false

  try {
    const res = await fetch(`${API}?action=rechercher&matricule=${encodeURIComponent(matricule)}&rand=${Date.now()}`)
    const data = await res.json().catch(() => ({}))
    if (!data.success) {
      erreur.value = data.message || `Matricule « ${matricule} » introuvable.`
      bip(220)
      return
    }
    personne.value = data.personne
    sortie.value = data.sortie || null
    dehors_personne.value = Boolean(data.dehors)

    // Les heures sont pré-remplies à l'instant présent : l'agent n'a
    // qu'à corriger si le passage est saisi après coup.
    form.heure_sortie = heureMaintenant()
    form.heure_retour = heureMaintenant()
    form.retour_prevu = ''
    form.motif = ''
    form.destination = ''
    form.billet_photo = ''
    form.billet_photo_id = ''

    bip(dehors_personne.value ? 660 : 880)
  } catch (e) {
    erreur.value = `Connexion impossible : ${e.message}`
  } finally {
    chargement.value = false
    saisieManuelle.value = ''
  }
}

/* ─── Billet de sortie ────────────────────────────────────────────────── */

async function photographierBillet(evenement) {
  const fichier = evenement.target.files?.[0]
  if (!fichier) return
  if (!fichier.type.startsWith('image/')) { erreur.value = 'Choisissez une image.'; return }
  if (fichier.size > 8 * 1024 * 1024) { erreur.value = 'Photo trop lourde (8 Mo maximum).'; return }

  televersement.value = true
  progression.value = 0
  erreur.value = ''
  try {
    const corps = new FormData()
    corps.append('file', fichier)
    corps.append('folder', 'senafoi')
    corps.append('subfolder', 'photos')
    const data = await new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest()
      xhr.upload.onprogress = (e) => {
        if (e.lengthComputable) progression.value = Math.round((e.loaded * 100) / e.total)
      }
      xhr.onload = () => {
        try {
          const json = JSON.parse(xhr.responseText || '{}')
          if (xhr.status >= 200 && xhr.status < 300 && json.success) resolve(json)
          else reject(new Error(json.error || json.message || 'Upload impossible'))
        } catch { reject(new Error('Réponse upload invalide')) }
      }
      xhr.onerror = () => reject(new Error('Connexion upload impossible'))
      xhr.open('POST', UPLOAD_URL)
      xhr.setRequestHeader('X-API-Key', CLOUD_API_KEY)
      xhr.send(corps)
    })
    form.billet_photo = data.url
    form.billet_photo_id = data.public_id || data.filename || ''
  } catch (e) {
    erreur.value = e.message
  } finally {
    televersement.value = false
    evenement.target.value = ''
  }
}

/* ─── Enregistrement ──────────────────────────────────────────────────── */

async function enregistrerSortie() {
  if (!personne.value) return
  enregistrement.value = true
  erreur.value = ''
  try {
    const res = await fetch(`${API}?action=sortir&rand=${Date.now()}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ matricule: personne.value.matricule, agent: agent.value, ...form }),
    })
    const data = await res.json().catch(() => ({}))
    if (!data.success) throw new Error(data.message || `HTTP ${res.status}`)

    sortie.value = data.sortie || null
    dehors_personne.value = true
    termine.value = true
    dernierSens.value = 'sortie'
    dehors.value += 1
    ajouterJournal('sortie')
  } catch (e) {
    erreur.value = e.message
  } finally {
    enregistrement.value = false
  }
}

async function enregistrerRetour() {
  if (!personne.value) return
  enregistrement.value = true
  erreur.value = ''
  try {
    const res = await fetch(`${API}?action=retour&rand=${Date.now()}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        matricule: personne.value.matricule,
        agent: agent.value,
        heure_retour: form.heure_retour,
      }),
    })
    const data = await res.json().catch(() => ({}))
    if (!data.success) throw new Error(data.message || `HTTP ${res.status}`)

    sortie.value = data.sortie || null
    dehors_personne.value = false
    termine.value = true
    dernierSens.value = 'retour'
    dehors.value = Math.max(0, dehors.value - 1)
    ajouterJournal('retour')
  } catch (e) {
    erreur.value = e.message
  } finally {
    enregistrement.value = false
  }
}

function ajouterJournal(sens) {
  const p = personne.value
  if (!p) return
  journal.value.unshift({
    cle: `${p.matricule}-${Date.now()}`,
    nom: `${p.prenom} ${p.nom}`.trim(),
    sens,
    heure: heureMaintenant(),
  })
  journal.value = journal.value.slice(0, 12)
}

function reinitialiser() {
  personne.value = null
  sortie.value = null
  termine.value = false
  erreur.value = ''
  dernierCode = ''
}

function memoriserAgent() {
  try { localStorage.setItem('sortie_camp_agent', agent.value) } catch { /* indisponible */ }
}

onMounted(async () => {
  try { agent.value = localStorage.getItem('sortie_camp_agent') || '' } catch { /* ignore */ }
  try {
    const data = await (await fetch(`${API}?action=stats&rand=${Date.now()}`)).json()
    if (data.success) dehors.value = data.dehors || 0
  } catch { /* le compteur reste à zéro */ }
})

onBeforeUnmount(arreter)
</script>

<style scoped>
.sc { min-height: 100vh; background: #f5f7fb; color: var(--ink); padding: 14px;
      padding-bottom: calc(24px + env(safe-area-inset-bottom, 0px)); }

.sc-tete { display: flex; justify-content: space-between; align-items: center; gap: 12px;
           max-width: 1100px; margin: 0 auto 14px; padding: 16px 18px; border-radius: 0;
           background: linear-gradient(135deg, #7c2d12, #c2410c); color: #fff; }
.sc-eyebrow { font-size: 10.5px; font-weight: 900; letter-spacing: .1em; color: #fed7aa; }
.sc-tete h1 { margin: 3px 0 0; font-size: clamp(19px, 4vw, 28px); line-height: 1.1; }
.sc-compteur { text-align: right; }
.sc-compteur strong { display: block; font-size: 28px; line-height: 1; }
.sc-compteur small { color: #fed7aa; font-size: 10.5px; font-weight: 700; text-transform: uppercase; }

.sc-corps { display: grid; gap: 14px; max-width: 1100px; margin: 0 auto; align-items: start; }
@media (min-width: 860px) { .sc-corps { grid-template-columns: 1fr 1fr; } }

.sc-bloc { display: grid; gap: 10px; }

.sc-cadre { position: relative; aspect-ratio: 4/3; border-radius: 0; overflow: hidden;
            background: var(--ink); display: grid; place-items: center; }
.sc-cadre video { width: 100%; height: 100%; object-fit: cover; }
.sc-repos { position: absolute; color: var(--muted); display: grid; place-items: center; gap: 6px; }
.sc-repos p { margin: 0; font-size: 13px; }
.sc-mire { position: absolute; width: 62%; aspect-ratio: 1; border: 3px solid rgba(255,255,255,.85);
           border-radius: 0; box-shadow: 0 0 0 2000px rgba(15,23,42,.35); }

.sc-duo { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.sc-btn { height: 46px; padding: 0 16px; border: 1px solid var(--line); background: #fff;
          border-radius: 0; font-size: 14.5px; font-weight: 700; cursor: pointer;
          font-family: inherit; color: var(--ink-soft); }
.sc-btn--vert { background: var(--green); border-color: var(--green); color: #fff; }
.sc-btn--large { grid-column: 1 / -1; width: 100%; height: 52px; }
.sc-btn:disabled { opacity: .5; cursor: not-allowed; }
.sc-input { height: 46px; border: 1px solid var(--line); border-radius: 0; padding: 0 12px;
            font-size: 16px; font-family: inherit; width: 100%; }

.sc-champ { display: grid; gap: 5px; }
.sc-champ > span { font-size: 11px; font-weight: 800; color: var(--muted);
                   text-transform: uppercase; letter-spacing: .03em; }
.sc-champ input { height: 46px; border: 1px solid var(--line); border-radius: 0; padding: 0 12px;
                  font-size: 16px; font-family: inherit; width: 100%; }

.sc-etat { padding: 30px 16px; text-align: center; color: var(--muted); background: #fff;
           border: 1px dashed var(--line); border-radius: 0; font-size: 14px; }
.sc-etat small { display: block; margin-top: 8px; font-size: 11.5px; line-height: 1.55; }

.sc-carte { background: #fff; border: 1px solid var(--line); border-radius: 0; overflow: hidden; }
.sc-bandeau { padding: 10px 16px; font-size: 11.5px; font-weight: 900; letter-spacing: .09em;
              text-transform: uppercase; background: var(--surface); color: var(--ink-soft); }
.sc-carte--dehors .sc-bandeau { background: #c2410c; color: #fff; }
.sc-carte--dedans .sc-bandeau { background: var(--green); color: #fff; }
.sc-carte--erreur { border-color: #fecaca; padding: 18px; display: grid; gap: 12px; text-align: center; }
.sc-carte--erreur strong { color: #991b1b; font-size: 14px; line-height: 1.5; }

.sc-identite { display: flex; gap: 13px; align-items: center; padding: 16px; }
.sc-identite img { width: 68px; height: 68px; border-radius: 0; object-fit: cover; flex-shrink: 0; }
.sc-initiales { width: 68px; height: 68px; border-radius: 0; background: var(--gold-mist); color: #7c2d12;
                display: grid; place-items: center; font-size: 22px; font-weight: 800; flex-shrink: 0; }
.sc-identite strong { display: block; font-size: 17px; line-height: 1.25; }
.sc-categorie { display: inline-block; margin-top: 5px; padding: 3px 10px; border-radius: 999px;
                background: var(--gold-mist); color: #7c2d12; font-size: 11px; font-weight: 800; }
.sc-identite small { display: block; margin-top: 4px; color: var(--muted); font-size: 12px; }
.sc-mono { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
.sc-info { margin: 0; padding: 0 16px 14px; color: #92400e; font-size: 13px; line-height: 1.55; }

.sc-form-corps { display: grid; gap: 12px; padding: 16px; }
.sc-billet { display: flex; gap: 12px; align-items: center; }
.sc-apercu { width: 74px; height: 74px; border-radius: 0; background: var(--surface); display: grid;
             place-items: center; overflow: hidden; flex-shrink: 0; }
.sc-apercu img { width: 100%; height: 100%; object-fit: cover; }
.sc-apercu span { font-size: 9.5px; font-weight: 800; color: var(--muted); letter-spacing: .08em; }
.sc-upload { flex: 1; border: 1px dashed var(--line); border-radius: 0; padding: 12px 14px;
             cursor: pointer; background: var(--surface); }
.sc-upload input { display: none; }
.sc-upload strong { display: block; font-size: 13.5px; color: var(--green); }
.sc-upload small { color: var(--muted); font-size: 11px; }
.sc-billet-vu { width: 100%; max-height: 220px; object-fit: contain; border-radius: 0;
                background: var(--surface); }

.sc-journal { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 14px 16px; }
.sc-journal h2 { margin: 0 0 10px; font-size: 11.5px; font-weight: 800; color: var(--muted);
                 text-transform: uppercase; letter-spacing: .05em; }
.sc-journal ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
.sc-journal li { display: flex; align-items: center; gap: 10px; }
.sc-journal strong { display: block; font-size: 13px; }
.sc-journal small { color: var(--muted); font-size: 11px; }
.sc-point { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.sc-point.sortie { background: #c2410c; }
.sc-point.retour { background: var(--green); }
</style>
