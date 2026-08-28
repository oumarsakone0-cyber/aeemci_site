<template>
  <div class="rupture-app">
    <!-- Header -->
    <header class="app-header">
      <div class="header-wrapper">
        <div class="logo-section">
          <div class="logo-badge">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
            </svg>
          </div>
          <div class="logo-text">
            <h1>AEEMCI</h1>
            <p>Rupture du Samedi</p>
          </div>
        </div>
        <div class="event-badge">📅 Samedi 7 Mars 2025</div>
      </div>
    </header>

    <main class="form-main">
      <div class="form-container">

        <!-- Steps Nav -->
        <nav class="steps-nav">
          <div v-for="(step, idx) in steps" :key="idx"
               class="step-item"
               :class="{ active: navStep === idx, completed: navStep > idx }">
            <div class="step-circle">
              <span v-if="navStep > idx">✓</span>
              <span v-else>{{ step.icon }}</span>
            </div>
            <span class="step-label">{{ step.label }}</span>
          </div>
        </nav>

        <!-- Form Body -->
        <div class="form-body">

          <!-- ══ ÉTAPE 0 : Participation ══ -->
          <div v-show="currentStep === 0" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
              </svg>
              Confirmation de participation
            </h2>
            <div style="text-align:center;padding:1rem 0 2rem">
              <div style="font-size:5rem;line-height:1;margin-bottom:1.2rem">🌙</div>
              <p style="font-size:1.15rem;color:#6b7280;max-width:480px;margin:0 auto">
                Confirmez-vous votre participation à la
                <strong>rupture collective du Samedi 7 Mars 2025</strong>
                organisée par l'AEEMCI ?
              </p>
            </div>
            <div class="type-cards">
              <label class="type-card">
                <input v-model="form.participation" type="radio" value="oui">
                <div class="card-inner">
                  <div class="card-icon">✅</div>
                  <h3>Oui, je participe</h3>
                  <p>Je serai présent(e) insha'Allah</p>
                </div>
              </label>
              <label class="type-card">
                <input v-model="form.participation" type="radio" value="non">
                <div class="card-inner">
                  <div class="card-icon">❌</div>
                  <h3>Non, je ne participe pas</h3>
                  <p>Je ne pourrai pas être là</p>
                </div>
              </label>
            </div>
            <div class="form-actions">
              <div></div>
              <button class="btn btn-next" :disabled="!form.participation" @click="handleStep0">
                Suivant →
              </button>
            </div>
          </div>

          <!-- ══ REFUS ══ -->
          <div v-show="currentStep === -1" class="form-step" style="align-items:center;justify-content:center;text-align:center">
            <div style="font-size:5rem;margin-bottom:1rem">😔</div>
            <h2 style="font-size:2rem;font-weight:900;color:#1a1a1a;margin-bottom:.8rem">Dommage !</h2>
            <p style="color:#6b7280;font-size:1.1rem">Vous pourrez participer à la prochaine rupture insha'Allah.</p>
            <div class="form-actions" style="justify-content:center;margin-top:2rem">
              <button class="btn btn-back" @click="currentStep = 0">← Retour</button>
            </div>
          </div>

          <!-- ══ ÉTAPE 1 : Carte ? ══ -->
          <div v-show="currentStep === 1" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
              </svg>
              Avez-vous une carte de membre AEEMCI ?
            </h2>
            <div class="type-cards">
              <label class="type-card">
                <input v-model="form.a_carte" type="radio" value="oui">
                <div class="card-inner">
                  <div class="card-icon">🪪</div>
                  <h3>Oui, j'ai ma carte</h3>
                  <p>Je saisis mon matricule</p>
                </div>
              </label>
              <label class="type-card">
                <input v-model="form.a_carte" type="radio" value="non">
                <div class="card-inner">
                  <div class="card-icon">👤</div>
                  <h3>Non, pas de carte</h3>
                  <p>Je saisis mon nom et prénom</p>
                </div>
              </label>
            </div>
            <div class="form-actions">
              <button class="btn btn-back" @click="currentStep = 0">← Précédent</button>
              <button class="btn btn-next" :disabled="!form.a_carte" @click="handleStep1">
                Suivant →
              </button>
            </div>
          </div>

          <!-- ══ ÉTAPE 2A : Avec carte ══ -->
          <div v-show="currentStep === 2 && form.a_carte === 'oui'" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
              </svg>
              Votre matricule AEEMCI
            </h2>
            <div class="form-grid">
              <div class="form-field fullwidth">
                <label>Matricule <span class="req">*</span></label>
                <input
                v-model="form.matricule"
                @input="formatMatricule"
                type="text"
                placeholder="Ex: AEE-MCI-24-001"
                maxlength="14"
                >
              </div>
            </div>

            <div v-if="matriculeError" class="error-box">{{ matriculeError }}</div>

            <div v-if="membreInfo" class="membre-found-box">
              <span style="font-size:1.5rem">✅</span>
              <strong>{{ membreInfo.prenom }} {{ membreInfo.nom }}</strong>
              <span class="found-sub">Membre trouvé !</span>
            </div>

            <div v-if="membreInfo" class="form-field fullwidth" style="margin-top:.5rem">
              <label>Commune de départ <span class="req">*</span></label>
              <select v-model="form.commune">
                <option value="">-- Sélectionner votre commune --</option>
                <option v-for="c in communes" :key="c">{{ c }}</option>
              </select>
            </div>

            <div class="form-actions">
              <button class="btn btn-back" @click="currentStep = 1">← Précédent</button>
              <button v-if="!membreInfo" class="btn btn-next" :disabled="!form.matricule || isSearching" @click="rechercherMembre">
                <span v-if="isSearching" class="spinner-small"></span>
                {{ isSearching ? 'Recherche...' : 'Rechercher' }}
              </button>
              <button v-else class="btn btn-submit" :disabled="!form.commune || isSubmitting" @click="validerInscription">
                <span v-if="isSubmitting" class="spinner-small"></span>
                {{ isSubmitting ? 'Validation...' : 'Valider ma participation' }}
              </button>
            </div>
          </div>

          <!-- ══ ÉTAPE 2B : Sans carte ══ -->
          <div v-show="currentStep === 2 && form.a_carte === 'non'" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Vos informations
            </h2>
            <div class="form-grid">
              <div class="form-field">
                <label>Prénom <span class="req">*</span></label>
                <input
                  v-model="form.prenom"
                  @input="formatFirstUpper('prenom')"
                  type="text"
                  placeholder="Votre prénom"
                >
              </div>
              <div class="form-field">
                <label>Nom <span class="req">*</span></label>
                <input
                  v-model="form.nom"
                  @input="formatUpper('nom')"
                  type="text"
                  placeholder="VOTRE NOM"
                >
              </div>
              <div class="form-field fullwidth">
                <label>Commune de départ <span class="req">*</span></label>
                <select v-model="form.commune">
                  <option value="">-- Sélectionner votre commune --</option>
                  <option v-for="c in communes" :key="c">{{ c }}</option>
                </select>
              </div>
            </div>
            <div class="form-actions">
              <button class="btn btn-back" @click="currentStep = 1">← Précédent</button>
              <button class="btn btn-submit"
                :disabled="!form.prenom || !form.nom || !form.commune || isSubmitting"
                @click="validerInscription">
                <span v-if="isSubmitting" class="spinner-small"></span>
                {{ isSubmitting ? 'Validation...' : 'Valider ma participation' }}
              </button>
            </div>
          </div>

          <!-- ══ ÉTAPE 3 : Reçu ══ -->
          <div v-show="currentStep === 3" class="form-step">
            <div class="success-banner">
              <div style="font-size:3.5rem;margin-bottom:.6rem">✅</div>
              <h2>Participation confirmée !</h2>
              <p>Barakallahu fikoum — À ce samedi insha'Allah 🌙</p>
            </div>

            <div class="recu-card">
              <div class="recu-header">
                <div style="font-size:2.2rem">🌙</div>
                <div>
                  <h3>AEEMCI</h3>
                  <p>Reçu de participation</p>
                </div>
              </div>
              <div class="recu-event">
                <strong>Rupture du Samedi 7 Mars 2025</strong>
              </div>
              <div class="recu-info-list">
                <div class="recu-info-item">
                  <span class="ri-label">Participant(e)</span>
                  <span class="ri-value">{{ recu.prenom }} {{ recu.nom }}</span>
                </div>
                <div v-if="recu.matricule" class="recu-info-item">
                  <span class="ri-label">Matricule</span>
                  <span class="ri-value ri-mono">{{ recu.matricule }}</span>
                </div>
                <div class="recu-info-item">
                  <span class="ri-label">Commune de départ</span>
                  <span class="ri-value">{{ recu.commune }}</span>
                </div>
                <div class="recu-info-item">
                  <span class="ri-label">Référence</span>
                  <span class="ri-value ri-mono">{{ recu.reference }}</span>
                </div>
                <div class="recu-info-item">
                  <span class="ri-label">Enregistré le</span>
                  <span class="ri-value">{{ formatDate(recu.created_at) }}</span>
                </div>
              </div>
              <div class="recu-qr">
                <canvas ref="qrCanvas"></canvas>
                <small>Scanner pour vérifier</small>
              </div>
            </div>

            <div class="form-actions" style="justify-content:center;gap:1.2rem;flex-wrap:wrap">
              <button class="btn btn-submit" @click="downloadPDF">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                </svg>
                Télécharger le reçu PDF
              </button>
              <button class="btn btn-back" @click="restart">Nouvelle inscription</button>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'
import axios from 'axios'
import QRCode from 'qrcode'

const currentStep = ref(0)
const isSearching = ref(false)
const isSubmitting = ref(false)
const matriculeError = ref('')
const membreInfo = ref(null)
const recu = ref({})
const qrCanvas = ref(null)

const form = ref({
  participation: '',
  a_carte: '',
  matricule: '',
  prenom: '',
  nom: '',
  commune: ''
})

const steps = [
  { label: 'Participation', icon: '🌙' },
  { label: 'Carte',        icon: '2' },
  { label: 'Identité',     icon: '3' },
  { label: 'Reçu',         icon: '4' }
]

const navStep = computed(() => {
  if (currentStep.value === -1) return 0
  if (currentStep.value === 3)  return 3
  return currentStep.value
})

const communes = [
  'Abobo', 'Koumassi','Yopougon',
  'Je viens directement'
]

const formatUpper = (field) => {
  form.value[field] = form.value[field].toUpperCase()
}

const formatFirstUpper = (field) => {
  const v = form.value[field]
  if (v) form.value[field] = v.charAt(0).toUpperCase() + v.slice(1)
}

const formatDate = (d) => {
  if (!d) return ''
  return new Date(d).toLocaleDateString('fr-FR', {
    day: '2-digit', month: 'long', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  })
}

const formatMatricule = (e) => {
  // Garde uniquement lettres et chiffres, met en majuscule
  let val = e.target.value.replace(/[^A-Z0-9]/gi, '').toUpperCase()

  // Applique le masque XXX-XXX-XX-XXX
  let result = ''
  if (val.length > 0)  result += val.substring(0, 3)
  if (val.length > 3)  result += '-' + val.substring(3, 6)
  if (val.length > 6)  result += '-' + val.substring(6, 8)
  if (val.length > 8)  result += '-' + val.substring(8, 11)

  form.value.matricule = result
  resetMembreFound()
}

const handleStep0 = () => {
  if (form.value.participation === 'non') currentStep.value = -1
  else currentStep.value = 1
}

const handleStep1 = () => {
  form.value.matricule = ''
  form.value.prenom    = ''
  form.value.nom       = ''
  form.value.commune   = ''
  membreInfo.value     = null
  matriculeError.value = ''
  currentStep.value    = 2
}

const resetMembreFound = () => {
  membreInfo.value     = null
  matriculeError.value = ''
  form.value.commune   = ''
}

const rechercherMembre = async () => {
  if (!form.value.matricule) return
  isSearching.value    = true
  matriculeError.value = ''
  membreInfo.value     = null
  try {
    const res = await axios.get('https://api.aeemci-ce.ci/rupture.php', {
      params: { action: 'get_membre', matricule: form.value.matricule }
    })
    if (res.data.success) membreInfo.value = res.data.membre
    else matriculeError.value = res.data.message || 'Matricule introuvable'
  } catch {
    matriculeError.value = 'Erreur réseau. Vérifiez votre connexion.'
  } finally {
    isSearching.value = false
  }
}

const validerInscription = async () => {
  isSubmitting.value = true
  try {
    const payload = {
      commune:   form.value.commune,
      a_carte:   form.value.a_carte === 'oui' ? 1 : 0,
      prenom:    membreInfo.value ? membreInfo.value.prenom : form.value.prenom,
      nom:       membreInfo.value ? membreInfo.value.nom    : form.value.nom,
      matricule: form.value.a_carte === 'oui' ? form.value.matricule : null
    }
    const res = await axios.post('https://api.aeemci-ce.ci/rupture.php?action=inscrire', payload)
    if (res.data.success) {
      recu.value = res.data.participation
      currentStep.value = 3
      await nextTick()
      const qrContent = `${recu.value.reference}`
      await QRCode.toCanvas(qrCanvas.value, qrContent, { width: 150, margin: 1 })
    } else {
      alert(res.data.message || "Erreur lors de l'inscription")
    }
  } catch {
    alert('Erreur serveur. Veuillez réessayer.')
  } finally {
    isSubmitting.value = false
  }
}

const downloadPDF = () => {
  const p      = recu.value
  const qrImg  = qrCanvas.value?.toDataURL('image/png') ?? ''
  const html = `<!DOCTYPE html>
<html lang="fr"><head><meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Zain:wght@400;700;900&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: var(--display); background:var(--green-mist); padding:20px; }
.recu { max-width:400px; margin:0 auto; background:white; border:2px solid var(--line); border-radius: 0; overflow:hidden; }
.hdr  { background:linear-gradient(135deg,var(--green),var(--green)); color:white; padding:20px 24px; display:flex; gap:12px; align-items:center; }
.hdr h3 { font-size:1.5rem; font-weight:900; } .hdr p { opacity:.85; font-size:.85rem; }
.evt  { background:var(--surface); padding:10px 24px; text-align:center; font-weight:600; color:var(--ink-soft); }
.rows { padding:14px 24px; }
.row  { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f3f4f6; }
.lbl  { font-size:.85rem; color:var(--muted); }
.val  { font-size:.95rem; font-weight:700; color:var(--ink); }
.mono { font-family:monospace; }
.qr   { display:flex; flex-direction:column; align-items:center; gap:6px; padding:16px 24px 24px; }
.qr small { font-size:.75rem; color:var(--muted); }
.ftr  { text-align:center; padding:12px; font-size:.8rem; color:var(--green); font-style:italic; }
@media print { body { background:white; padding:0; } }
</style></head><body>
<div class="recu">
  <div class="hdr"><div style="font-size:2rem">🌙</div><div><h3>AEEMCI</h3><p>Reçu de participation</p></div></div>
  <div class="evt"><strong>Rupture du Samedi 7 Mars 2025</strong></div>
  <div class="rows">
    <div class="row"><span class="lbl">Participant(e)</span><span class="val">${p.prenom} ${p.nom}</span></div>
    ${p.matricule ? `<div class="row"><span class="lbl">Matricule</span><span class="val mono">${p.matricule}</span></div>` : ''}
    <div class="row"><span class="lbl">Commune de départ</span><span class="val">${p.commune}</span></div>
    <div class="row"><span class="lbl">Référence</span><span class="val mono">${p.reference}</span></div>
    <div class="row"><span class="lbl">Enregistré le</span><span class="val">${formatDate(p.created_at)}</span></div>
  </div>
  <div class="qr"><img src="${qrImg}" width="140" height="140"><small>Scanner pour vérifier</small></div>
  <div class="ftr">AEEMCI — Pour une identité islamique !</div>
</div>
<script>window.onload=()=>window.print()<\/script>
</body></html>`
  const win = window.open('', '_blank')
  win.document.write(html)
  win.document.close()
}

const restart = () => {
  currentStep.value    = 0
  membreInfo.value     = null
  recu.value           = {}
  matriculeError.value = ''
  form.value = { participation: '', a_carte: '', matricule: '', prenom: '', nom: '', commune: '' }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@300;400;600;700;900&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

.rupture-app {
  min-height: 100vh;
  background: linear-gradient(135deg, rgba(1,132,4,.05), rgba(251,146,60,.05));
  font-family: var(--display);
}

/* ── HEADER ─────────────────────────── */
.app-header {
  background: white;
  border-bottom: 2px solid var(--line);
  box-shadow: var(--shadow);
  position: sticky; top: 0; z-index: 100;
}
.header-wrapper {
  max-width: 1200px; margin: 0 auto;
  padding: 1.5rem 2rem;
  display: flex; justify-content: space-between; align-items: center;
}
.logo-section { display: flex; align-items: center; gap: 1rem; }
.logo-badge {
  width: 60px; height: 60px;
  background: linear-gradient(135deg, var(--green), var(--green));
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center; color: white;
}
.logo-text h1 { font-size: 2rem; font-weight: 900; color: var(--green); margin: 0; }
.logo-text p  { font-size: .95rem; color: var(--muted); margin: 0; }
.event-badge {
  background: linear-gradient(135deg, var(--gold-mist), var(--gold-mist));
  border: 1.5px solid var(--gold); border-radius: 0;
  padding: .5rem 1.4rem;
  font-weight: 700; color: #92400e; font-size: .95rem;
}

/* ── MAIN ───────────────────────────── */
.form-main { max-width: 1000px; margin: 3rem auto; padding: 0 1.5rem; }
.form-container {
  background: white; border-radius: 0;
  box-shadow: var(--shadow); overflow: hidden;
}

/* ── STEPS NAV ──────────────────────── */
.steps-nav {
  background: linear-gradient(135deg, rgba(1,132,4,.05), rgba(251,146,60,.05));
  padding: 2rem 1.5rem;
  display: flex; justify-content: space-between; gap: 1rem;
  overflow-x: auto;
}
.step-item {
  display: flex; flex-direction: column; align-items: center; gap: .5rem;
  min-width: 80px; opacity: .5; transition: all .3s;
}
.step-item.active, .step-item.completed { opacity: 1; }
.step-circle {
  width: 50px; height: 50px;
  background: white; border: 3px solid #d1d5db; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; color: var(--muted); transition: all .3s; font-size: 1.2rem;
}
.step-item.active .step-circle {
  background: var(--green); border-color: var(--green); color: white; transform: scale(1.1);
}
.step-item.completed .step-circle {
  background: var(--green); border-color: var(--green); color: white;
}
.step-label { font-size: .85rem; font-weight: 600; color: var(--muted); }
.step-item.active .step-label { color: var(--green); }

/* ── FORM BODY ──────────────────────── */
.form-body { padding: 3rem; }
.form-step { min-height: 400px; display: flex; flex-direction: column; gap: 2rem; }

.step-title {
  display: flex; align-items: center; gap: 1rem;
  font-size: 2rem; font-weight: 900; color: var(--ink);
  justify-content: center; margin-bottom: 1rem;
}
.step-title svg { color: var(--green); }

/* ── TYPE CARDS ─────────────────────── */
.type-cards { display: grid; grid-template-columns: repeat(2,1fr); gap: 2rem; }
.type-card  { cursor: pointer; }
.type-card input { display: none; }
.card-inner {
  background: linear-gradient(135deg, rgba(1,132,4,.05), rgba(251,146,60,.05));
  border: 3px solid transparent; border-radius: 0;
  padding: 3rem 2rem; text-align: center; transition: all .3s;
}
.type-card:hover .card-inner { transform: translateY(-8px); box-shadow: var(--shadow); }
.type-card input:checked + .card-inner {
  background: linear-gradient(135deg, rgba(1,132,4,.1), rgba(251,146,60,.1));
  border-color: var(--green);
}
.card-icon { font-size: 4rem; margin-bottom: 1rem; }
.card-inner h3 { font-size: 1.5rem; font-weight: 700; color: var(--green); margin-bottom: .5rem; }
.card-inner p  { color: var(--muted); }

/* ── FORM GRID ──────────────────────── */
.form-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 2rem; }
.form-field { display: flex; flex-direction: column; gap: .75rem; }
.form-field.fullwidth { grid-column: 1 / -1; }
.form-field label { font-weight: 600; color: var(--ink-soft); font-size: .95rem; }
.req { color: #dc2626; }
.form-field input,
.form-field select {
  padding: 1rem; border: 2px solid var(--line); border-radius: 0;
  font-size: 1rem; font-family: var(--display); transition: all .3s; background: white;
}
.form-field input:focus,
.form-field select:focus {
  outline: none; border-color: var(--green); box-shadow: 0 0 0 4px rgba(1,132,4,.1);
}

/* ── MEMBRE FOUND ───────────────────── */
.membre-found-box {
  display: flex; align-items: center; gap: 1rem;
  background: linear-gradient(135deg, var(--green-mist), var(--green-mist));
  border: 1.5px solid var(--green); border-radius: 0;
  padding: 1rem 1.5rem; color: var(--green);
}
.membre-found-box strong { font-size: 1.1rem; }
.found-sub { margin-left: auto; font-size: .85rem; color: var(--muted); }
.error-box {
  background: #fef2f2; border: 1.5px solid #fca5a5;
  border-radius: 0; padding: .8rem 1.2rem;
  color: #dc2626; font-size: .95rem;
}

/* ── SUCCESS ────────────────────────── */
.success-banner {
  background: linear-gradient(135deg, var(--green-mist), var(--green-mist));
  border-radius: 0; padding: 2.5rem; text-align: center;
}
.success-banner h2 { font-size: 2rem; font-weight: 900; color: var(--green); margin-bottom: .4rem; }
.success-banner p  { color: var(--green); font-size: 1.1rem; }

/* ── REÇU ───────────────────────────── */
.recu-card { border: 2px solid var(--line); border-radius: 0; overflow: hidden; }
.recu-header {
  display: flex; align-items: center; gap: 1rem;
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white; padding: 1.5rem 2rem;
}
.recu-header h3 { font-size: 1.6rem; font-weight: 900; }
.recu-header p  { opacity: .85; font-size: .9rem; }
.recu-event {
  background: var(--surface); padding: .9rem 2rem;
  text-align: center; font-size: 1rem; color: var(--ink-soft); font-weight: 600;
}
.recu-info-list { padding: 1.2rem 2rem; display: flex; flex-direction: column; gap: .6rem; }
.recu-info-item {
  display: flex; justify-content: space-between; align-items: center;
  padding: .55rem 0; border-bottom: 1px solid #f3f4f6;
}
.ri-label { font-size: .88rem; color: var(--muted); font-weight: 500; }
.ri-value { font-size: 1rem; color: var(--ink); font-weight: 700; }
.ri-mono  { font-family: 'Courier New', monospace; }
.recu-qr  { display: flex; flex-direction: column; align-items: center; gap: .4rem; padding: 1.2rem 2rem 2rem; }
.recu-qr canvas { border: 2px solid var(--line); border-radius: 0; }
.recu-qr small  { font-size: .78rem; color: var(--muted); }

/* ── ACTIONS ────────────────────────── */
.form-actions {
  display: flex; justify-content: space-between; align-items: center;
  margin-top: auto; padding-top: 2rem; border-top: 2px solid var(--line);
}
.btn {
  padding: 1rem 2rem; border-radius: 0;
  font-size: 1rem; font-weight: 700; border: none; cursor: pointer;
  transition: all .3s; font-family: var(--display);
  display: inline-flex; align-items: center; gap: .5rem;
}
.btn-back {
  background: white; color: var(--green); border: 2px solid var(--green);
}
.btn-back:hover { background: var(--green); color: white; }
.btn-next, .btn-submit {
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white; box-shadow: var(--shadow);
}
.btn-next:hover, .btn-submit:hover {
  transform: translateY(-3px); box-shadow: var(--shadow);
}
.btn:disabled { opacity: .6; cursor: not-allowed; transform: none !important; }
.spinner-small {
  width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,.3); border-top-color: white;
  border-radius: 50%; animation: spin .8s linear infinite; display: inline-block;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── RESPONSIVE ─────────────────────── */
@media (max-width: 768px) {
  .header-wrapper { flex-direction: column; gap: 1.5rem; padding: 1rem; }
  .form-body { padding: 2rem 1.5rem; }
  .type-cards, .form-grid { grid-template-columns: 1fr; }
  .form-actions { flex-direction: column; gap: 1rem; }
  .form-actions .btn { width: 100%; justify-content: center; }
  .step-title { font-size: 1.5rem; flex-direction: column; }
}
@media (prefers-reduced-motion: reduce) {
  * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
}
</style>