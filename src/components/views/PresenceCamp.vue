<template>
  <div class="pc">
    <header class="pc-tete">
      <div>
        <span class="pc-eyebrow">SENAFOI 2026</span>
        <h1>Présence sur le camp</h1>
      </div>
      <div class="pc-compteur">
        <strong>{{ compteurSession }}</strong>
        <small>scannés ici</small>
      </div>
    </header>

    <main class="pc-corps">
      <!-- ── Lecteur ────────────────────────────────────────────────── -->
      <section class="pc-lecteur">
        <div class="pc-cadre" :class="{ actif: scanEnCours }">
          <video ref="vue" playsinline muted></video>
          <div v-if="!scanEnCours" class="pc-repos">
            <svg viewBox="0 0 24 24" width="46" height="46" fill="none" stroke="currentColor" stroke-width="1.6">
              <rect x="3" y="3" width="7" height="7" rx="1" /><rect x="14" y="3" width="7" height="7" rx="1" />
              <rect x="3" y="14" width="7" height="7" rx="1" /><path d="M14 14h3v3h-3zM19 19h2v2h-2zM14 19h2v2h-2zM19 14h2v2h-2z" />
            </svg>
            <p>Le lecteur est à l'arrêt</p>
          </div>
          <div v-else class="pc-mire"></div>
        </div>

        <div class="pc-boutons">
          <button v-if="!scanEnCours" class="pc-btn pc-btn--vert" @click="demarrer">Démarrer le scan</button>
          <button v-else class="pc-btn" @click="arreter">Arrêter</button>
        </div>

        <form class="pc-manuel" @submit.prevent="chercher(saisieManuelle)">
          <input v-model="saisieManuelle" placeholder="Ou saisir le matricule à la main" />
          <button class="pc-btn" :disabled="chargement || !saisieManuelle.trim()">Chercher</button>
        </form>

        <label class="pc-agent">
          <span>Agent au poste</span>
          <input v-model="agent" placeholder="Votre nom, pour la traçabilité" @change="memoriserAgent" />
        </label>
      </section>

      <!-- ── Résultat ───────────────────────────────────────────────── -->
      <section class="pc-resultat">
        <div v-if="chargement" class="pc-etat">Recherche…</div>

        <div v-else-if="erreur" class="pc-carte pc-carte--erreur">
          <strong>{{ erreur }}</strong>
          <button class="pc-btn" @click="reinitialiser">Scanner à nouveau</button>
        </div>

        <div v-else-if="personne" class="pc-carte" :class="etatCarte">
          <div class="pc-bandeau">
            {{ deja ? 'Déjà scanné' : (marque ? 'Présent' : 'Trouvé') }}
          </div>

          <div class="pc-identite">
            <img v-if="personne.photo" :src="personne.photo" alt="" />
            <span v-else class="pc-initiales">{{ initiales }}</span>
            <div>
              <strong>{{ personne.prenom }} {{ personne.nom }}</strong>
              <span class="pc-categorie">{{ personne.categorie }}</span>
              <small v-if="personne.detail">{{ personne.detail }}</small>
              <small class="pc-mono">{{ personne.matricule }}</small>
            </div>
          </div>

          <p v-if="deja" class="pc-info">
            Marqué présent {{ momentLisible }}<template v-if="scan?.agent"> par {{ scan.agent }}</template>.
          </p>

          <div class="pc-actions">
            <button
              v-if="!deja && !marque"
              class="pc-btn pc-btn--vert pc-btn--large"
              :disabled="enregistrement"
              @click="marquerPresent"
            >{{ enregistrement ? 'Enregistrement…' : 'Marquer présent' }}</button>
            <button class="pc-btn pc-btn--large" @click="reinitialiser">Scanner le suivant</button>
          </div>
        </div>

        <div v-else class="pc-etat pc-etat--vide">
          Visez le QR code d'un badge.
          <small>Séminaristes, SR, CCR, commissions, stands, imarat, formateurs, invités, aînés, compétiteurs — le même lecteur les reconnaît tous.</small>
        </div>

        <div v-if="historique.length" class="pc-historique">
          <h2>Derniers scans</h2>
          <ul>
            <li v-for="h in historique" :key="h.cle">
              <span class="pc-point" :class="{ deja: h.deja }"></span>
              <div>
                <strong>{{ h.nom }}</strong>
                <small>{{ h.categorie }} · {{ h.heure }}</small>
              </div>
              <em v-if="h.deja">déjà</em>
            </li>
          </ul>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
/**
 * Poste de scan « présence sur le camp ».
 *
 * Page volontairement nue (ni en-tête ni pied de page du site) : elle est
 * destinée à un téléphone ou une tablette tenue à l'entrée du camp.
 *
 * Un seul lecteur pour toutes les catégories : le matricule est résolu côté
 * serveur, qui cherche dans toutes les tables portant un matricule.
 */
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { BrowserMultiFormatReader } from '@zxing/library'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_presence_camp_api.php'

const vue = ref(null)
const scanEnCours = ref(false)
const chargement = ref(false)
const enregistrement = ref(false)
const erreur = ref('')

const personne = ref(null)
const scan = ref(null)
const deja = ref(false)
const marque = ref(false)

const saisieManuelle = ref('')
const agent = ref('')
const historique = ref([])
const compteurSession = ref(0)

let lecteur = null
let dernierCode = ''
let dernierMoment = 0

const initiales = computed(() => {
  const p = personne.value
  if (!p) return '?'
  return `${(p.prenom || '')[0] || ''}${(p.nom || '')[0] || ''}`.toUpperCase() || '?'
})

const etatCarte = computed(() => {
  if (deja.value) return 'pc-carte--deja'
  if (marque.value) return 'pc-carte--ok'
  return ''
})

const momentLisible = computed(() => {
  const t = scan.value?.scanne_le
  if (!t) return ''
  const d = new Date(t.replace(' ', 'T'))
  if (Number.isNaN(d.getTime())) return `le ${t}`
  return `le ${d.toLocaleDateString('fr-FR')} à ${d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}`
})

/* ─── Lecteur QR ───────────────────────────────────────────────────────── */

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
        // Un QR reste dans le champ plusieurs secondes : on ignore les
        // répétitions rapprochées du même code.
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
    erreur.value = `Caméra indisponible : ${e.message}. Autorisez l'accès, ou saisissez le matricule à la main.`
  }
}

function arreter() {
  try { lecteur?.reset() } catch { /* le lecteur était déjà arrêté */ }
  lecteur = null
  scanEnCours.value = false
}

/** Court signal sonore : l'agent n'a pas à regarder l'écran à chaque scan. */
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
  } catch { /* audio bloqué : sans importance */ }
}

/* ─── Recherche et marquage ───────────────────────────────────────────── */

/** Un QR peut contenir une URL : on n'en garde que le matricule. */
function extraireMatricule(texte) {
  const brut = String(texte || '').trim()
  const parametre = brut.match(/matricule=([A-Za-z0-9_-]+)/i)
  if (parametre) return parametre[1].toUpperCase()
  const dernier = brut.split(/[/?#\s]+/).filter(Boolean).pop() || brut
  return dernier.toUpperCase()
}

async function chercher(texte) {
  const matricule = extraireMatricule(texte)
  if (!matricule) return

  chargement.value = true
  erreur.value = ''
  personne.value = null
  scan.value = null
  deja.value = false
  marque.value = false

  try {
    const res = await fetch(`${API}?action=rechercher&matricule=${encodeURIComponent(matricule)}&rand=${Date.now()}`)
    const data = await res.json().catch(() => ({}))
    if (!data.success) {
      erreur.value = data.message || `Matricule « ${matricule} » introuvable.`
      bip(220)
      return
    }
    personne.value = data.personne
    scan.value = data.scan || null
    deja.value = Boolean(data.deja)
    if (deja.value) {
      bip(330)
      ajouterHistorique(true)
    }
  } catch (e) {
    erreur.value = `Connexion impossible : ${e.message}`
  } finally {
    chargement.value = false
    saisieManuelle.value = ''
  }
}

async function marquerPresent() {
  if (!personne.value) return
  enregistrement.value = true
  erreur.value = ''
  try {
    const res = await fetch(`${API}?action=marquer&rand=${Date.now()}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ matricule: personne.value.matricule, agent: agent.value }),
    })
    const data = await res.json().catch(() => ({}))
    if (!data.success) throw new Error(data.message || `HTTP ${res.status}`)

    scan.value = data.scan || null
    if (data.deja) {
      deja.value = true
      ajouterHistorique(true)
    } else {
      marque.value = true
      compteurSession.value += 1
      ajouterHistorique(false)
    }
  } catch (e) {
    erreur.value = e.message
  } finally {
    enregistrement.value = false
  }
}

function ajouterHistorique(estDeja) {
  const p = personne.value
  if (!p) return
  historique.value.unshift({
    cle: `${p.matricule}-${Date.now()}`,
    nom: `${p.prenom} ${p.nom}`.trim(),
    categorie: p.categorie,
    heure: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }),
    deja: estDeja,
  })
  historique.value = historique.value.slice(0, 12)
}

function reinitialiser() {
  personne.value = null
  scan.value = null
  deja.value = false
  marque.value = false
  erreur.value = ''
  dernierCode = ''
}

function memoriserAgent() {
  try { localStorage.setItem('presence_camp_agent', agent.value) } catch { /* stockage indisponible */ }
}

onMounted(() => {
  try { agent.value = localStorage.getItem('presence_camp_agent') || '' } catch { /* ignore */ }
})

onBeforeUnmount(arreter)
</script>

<style scoped>
.pc { min-height: 100vh; background: #f5f7fb; color: var(--ink); padding: 16px;
      font-family: inherit; }

.pc-tete { display: flex; justify-content: space-between; align-items: center; gap: 14px;
           max-width: 1100px; margin: 0 auto 16px; padding: 18px 20px; border-radius: 0;
           background: linear-gradient(135deg, #0f5132, #198754); color: #fff; }
.pc-eyebrow { font-size: 11px; font-weight: 900; letter-spacing: .1em; color: var(--green-mist); }
.pc-tete h1 { margin: 4px 0 0; font-size: clamp(20px, 4vw, 30px); line-height: 1.1; }
.pc-compteur { text-align: right; }
.pc-compteur strong { display: block; font-size: 30px; line-height: 1; }
.pc-compteur small { color: var(--green-mist); font-size: 11px; font-weight: 700; text-transform: uppercase; }

.pc-corps { display: grid; grid-template-columns: minmax(300px, 1fr) minmax(300px, 1fr);
            gap: 16px; max-width: 1100px; margin: 0 auto; align-items: start; }
@media (max-width: 820px) { .pc-corps { grid-template-columns: 1fr; } }

.pc-lecteur, .pc-resultat { display: grid; gap: 12px; }

.pc-cadre { position: relative; aspect-ratio: 4/3; border-radius: 0; overflow: hidden;
            background: var(--ink); display: grid; place-items: center; }
.pc-cadre video { width: 100%; height: 100%; object-fit: cover; }
.pc-repos { position: absolute; color: var(--muted); display: grid; place-items: center; gap: 8px; }
.pc-repos p { margin: 0; font-size: 13px; }
.pc-mire { position: absolute; width: 62%; aspect-ratio: 1; border: 3px solid rgba(255,255,255,.85);
           border-radius: 0; box-shadow: 0 0 0 2000px rgba(15,23,42,.35); }

.pc-boutons { display: flex; gap: 8px; }
.pc-btn { flex: 1; height: 44px; padding: 0 16px; border: 1px solid var(--line); background: #fff;
          border-radius: 0; font-size: 14px; font-weight: 700; cursor: pointer;
          font-family: inherit; color: var(--ink-soft); }
.pc-btn--vert { background: var(--green); border-color: var(--green); color: #fff; }
.pc-btn--large { height: 50px; font-size: 15px; }
.pc-btn:disabled { opacity: .5; cursor: not-allowed; }

.pc-manuel { display: flex; gap: 8px; }
.pc-manuel input { flex: 1; height: 44px; border: 1px solid var(--line); border-radius: 0;
                   padding: 0 12px; font-size: 14px; font-family: inherit; }
.pc-manuel button { flex: 0 0 auto; }

.pc-agent { display: grid; gap: 5px; }
.pc-agent span { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; }
.pc-agent input { height: 40px; border: 1px solid var(--line); border-radius: 0; padding: 0 12px;
                  font-size: 13px; font-family: inherit; }

.pc-etat { padding: 34px 18px; text-align: center; color: var(--muted); background: #fff;
           border: 1px dashed var(--line); border-radius: 0; font-size: 14px; }
.pc-etat small { display: block; margin-top: 8px; font-size: 11.5px; line-height: 1.5; }

.pc-carte { background: #fff; border: 1px solid var(--line); border-radius: 0; overflow: hidden; }
.pc-bandeau { padding: 11px 18px; font-size: 12px; font-weight: 900; letter-spacing: .1em;
              text-transform: uppercase; background: var(--surface); color: var(--ink-soft); }
.pc-carte--ok .pc-bandeau { background: var(--green); color: #fff; }
.pc-carte--deja .pc-bandeau { background: var(--gold); color: #fff; }
.pc-carte--erreur { border-color: #fecaca; padding: 20px; display: grid; gap: 14px; text-align: center; }
.pc-carte--erreur strong { color: #991b1b; font-size: 14px; line-height: 1.5; }

.pc-identite { display: flex; gap: 14px; align-items: center; padding: 18px; }
.pc-identite img { width: 74px; height: 74px; border-radius: 0; object-fit: cover; flex-shrink: 0; }
.pc-initiales { width: 74px; height: 74px; border-radius: 0; background: var(--green-mist); color: #0f5132;
                display: grid; place-items: center; font-size: 24px; font-weight: 800; flex-shrink: 0; }
.pc-identite strong { display: block; font-size: 18px; line-height: 1.25; }
.pc-categorie { display: inline-block; margin-top: 5px; padding: 3px 10px; border-radius: 999px;
                background: var(--green-mist); color: #0f5132; font-size: 11px; font-weight: 800; }
.pc-identite small { display: block; margin-top: 4px; color: var(--muted); font-size: 12px; }
.pc-mono { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }

.pc-info { margin: 0; padding: 0 18px 14px; color: #92400e; font-size: 13px; }
.pc-actions { display: grid; gap: 8px; padding: 0 18px 18px; }

.pc-historique { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 14px 16px; }
.pc-historique h2 { margin: 0 0 10px; font-size: 12px; font-weight: 800; color: var(--muted);
                    text-transform: uppercase; letter-spacing: .05em; }
.pc-historique ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
.pc-historique li { display: flex; align-items: center; gap: 10px; }
.pc-historique strong { display: block; font-size: 13px; }
.pc-historique small { color: var(--muted); font-size: 11px; }
.pc-historique em { margin-left: auto; font-size: 11px; font-weight: 800; color: #b45309;
                    font-style: normal; }
.pc-point { width: 9px; height: 9px; border-radius: 50%; background: var(--green); flex-shrink: 0; }
.pc-point.deja { background: var(--gold); }
</style>
