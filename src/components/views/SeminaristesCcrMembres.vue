<template>
  <div class="sr-page">
    <header class="sr-hero">
      <div>
        <span class="eyebrow">SENAFOI 2026</span>
        <h1>Inscription des séminaristes CCR</h1>
        <p>Chaque secrétariat peut enregistrer jusqu'à 2 inscrits. Les inscriptions sont gratuites, sauf départ depuis Abidjan.</p>
      </div>
      <div class="hero-card">
        <strong>{{ selectedSecretariat || 'Sélectionnez un secrétariat' }}</strong>
        <span>{{ members.length }}/2 inscrits enregistrés</span>
      </div>
    </header>

    <main class="sr-layout">
      <section class="panel selector-panel">
        <label>
          <span>Secrétariat régional</span>
          <select v-model="selectedSecretariat" @change="loadMembers">
            <option value="">Choisir un secrétariat</option>
            <option v-for="secretariat in secretariats" :key="secretariat" :value="secretariat">{{ secretariat }}</option>
          </select>
        </label>

        <div v-if="paymentMessage" class="alert success">{{ paymentMessage }}</div>
        <div v-if="error" class="alert error">{{ error }}</div>
        <div v-if="success" class="alert success">{{ success }}</div>

        <form v-if="selectedSecretariat" class="member-form" @submit.prevent="submitMember">
          <div class="form-head">
            <h2>Ajouter un séminariste CCR</h2>
            <span :class="{ full: quotaFull }">{{ members.length }}/2</span>
          </div>

          <div class="two-cols">
            <label><span>Nom</span><input v-model="form.nom" required placeholder="Nom" /></label>
            <label><span>Prénom</span><input v-model="form.prenom" required placeholder="Prénom" /></label>
          </div>

          <div class="two-cols">
            <label><span>Poste</span><input v-model="form.poste" required placeholder="Ex: Secrétaire adjoint" /></label>
            <label><span>Contact</span><input v-model="form.contact" required inputmode="tel" placeholder="0700000000" /></label>
          </div>

          <div class="photo-row">
            <div class="photo-preview">
              <img v-if="form.photo" :src="form.photo" alt="" />
              <span v-else>{{ initials }}</span>
            </div>
            <label class="upload-button">
              <input type="file" accept="image/*" @change="uploadPhoto" />
              <strong>{{ uploading ? `Upload ${uploadProgress}%` : 'Ajouter une photo' }}</strong>
              <small>JPG/PNG, maximum 5 Mo</small>
            </label>
          </div>

          <div class="transport-box">
            <label class="switch-line">
              <input v-model="form.depart_abidjan" type="checkbox" />
              <span>Départ depuis Abidjan</span>
              <strong v-if="form.depart_abidjan">16 000 FCFA</strong>
            </label>
            <label v-if="form.depart_abidjan"><span>Numéro Wave</span><input v-model="form.numero_wave" inputmode="tel" placeholder="Numéro Wave pour le paiement" /></label>
            <small v-if="form.depart_abidjan" class="payment-note">Le séminariste CCR sera enregistré dans la liste uniquement après confirmation du paiement Wave.</small>
          </div>

          <button class="primary-btn" :disabled="saving || quotaFull || uploading">
            {{ saving ? 'Enregistrement...' : form.depart_abidjan ? 'Enregistrer et payer Wave' : 'Enregistrer gratuitement' }}
          </button>
        </form>
      </section>

      <section class="panel list-panel">
        <div class="list-head">
          <div>
            <h2>Séminaristes CCR enregistrés</h2>
            <p v-if="selectedSecretariat">{{ selectedSecretariat }} · quota 2 inscrits</p>
            <p v-else>Choisissez un secrétariat pour afficher sa liste.</p>
          </div>
          <button v-if="selectedSecretariat" class="soft-btn" type="button" @click="loadMembers">Actualiser</button>
        </div>

        <div v-if="loading" class="state">Chargement...</div>
        <div v-else-if="!selectedSecretariat" class="state">Aucun secrétariat sélectionné.</div>
        <div v-else-if="!members.length" class="state">Aucun membre enregistré pour le moment.</div>
        <div v-else class="member-list">
          <article v-for="member in members" :key="member.id" class="member-card">
            <img v-if="member.photo" :src="member.photo" alt="" />
            <div v-else class="avatar">{{ member.prenom?.[0] }}{{ member.nom?.[0] }}</div>
            <div class="member-main">
              <strong>{{ member.prenom }} {{ member.nom }}</strong>
              <span>{{ member.poste }} · {{ member.contact }}</span>
              <small>{{ member.matricule }}</small>
            </div>
            <div class="member-side">
              <span v-if="Number(member.depart_abidjan) === 1" class="pay" :class="member.statut_paiement">{{ paymentLabel(member) }}</span>
              <button class="delete-btn" type="button" @click="deleteMember(member)">Supprimer</button>
            </div>
          </article>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'

const API_URL = 'https://api.aeemci-ce.ci/senafoi/seminaristes_ccr.php'
const UPLOAD_URL = 'https://api.aeemci-ce.ci/cloud/upload.php'
const CLOUD_API_KEY = 'SAKONE25_04_2026_medias'

const secretariats = [
  'ABIDJAN NORD', 'ABIDJAN SUD', 'ABIDJAN EST', 'ABIDJAN OUEST',
  'AGNEBY-TIASSA ET ME', 'BAGOUE', 'BELIER', 'BERE', 'BAFING', 'BOUNKANI',
  'CAVALLY', 'FOLON', 'GBEKE', 'GBOKLE', 'GOH', 'GONTOUGO',
  'GRANDS-PONTS', "N'ZI", 'HAUT-SASSANDRA', 'IFFOU', 'INDENIE-DJUABLIN',
  'KABADOUGOU', 'LOH-DJIBOUA', 'NAWA', 'MORONOU', 'PORO', 'WORODOUGOU',
  'MARAHOUE', 'TCHOLOGO', 'TONPKI', 'SAN-PEDRO', 'SUD COMOE', 'GUEMON', 'HAMBOL',
]

const selectedSecretariat = ref('')
const members = ref([])
const loading = ref(false)
const saving = ref(false)
const uploading = ref(false)
const uploadProgress = ref(0)
const error = ref('')
const success = ref('')
const paymentMessage = ref('')
const form = reactive(defaultForm())

const quotaFull = computed(() => members.value.length >= 2)
const initials = computed(() => `${form.prenom?.[0] || ''}${form.nom?.[0] || ''}`.toUpperCase() || 'CCR')

function defaultForm() {
  return { nom: '', prenom: '', poste: '', contact: '', photo: '', photo_id: '', depart_abidjan: false, numero_wave: '' }
}

async function fetchJson(url, options = {}) {
  const res = await fetch(url, options)
  const data = await res.json().catch(() => ({}))
  if (!res.ok || !data.success) throw new Error(data.message || data.error || `HTTP ${res.status}`)
  return data
}

async function loadMembers() {
  if (!selectedSecretariat.value) {
    members.value = []
    return
  }
  localStorage.setItem('seminaristes_ccr_secretariat', selectedSecretariat.value)
  loading.value = true
  error.value = ''
  try {
    const data = await fetchJson(`${API_URL}?action=list&secretariat=${encodeURIComponent(selectedSecretariat.value)}&rand=${Date.now()}`)
    members.value = data.data || []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function uploadPhoto(event) {
  const file = event.target.files?.[0]
  if (!file) return
  if (!file.type.startsWith('image/')) {
    error.value = 'Veuillez choisir une image valide.'
    return
  }
  if (file.size > 5 * 1024 * 1024) {
    error.value = 'La photo ne doit pas dépasser 5 Mo.'
    return
  }
  uploading.value = true
  uploadProgress.value = 0
  error.value = ''
  try {
    const body = new FormData()
    body.append('file', file)
    body.append('folder', 'senafoi')
    body.append('subfolder', 'photos')
    const data = await new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest()
      xhr.upload.onprogress = (evt) => {
        if (evt.lengthComputable) uploadProgress.value = Math.round((evt.loaded * 100) / evt.total)
      }
      xhr.onload = () => {
        try {
          const json = JSON.parse(xhr.responseText || '{}')
          if (xhr.status >= 200 && xhr.status < 300 && json.success) resolve(json)
          else reject(new Error(json.error || json.message || 'Upload impossible'))
        } catch {
          reject(new Error('Réponse upload invalide'))
        }
      }
      xhr.onerror = () => reject(new Error('Connexion upload impossible'))
      xhr.open('POST', UPLOAD_URL)
      xhr.setRequestHeader('X-API-Key', CLOUD_API_KEY)
      xhr.send(body)
    })
    form.photo = data.url
    form.photo_id = data.public_id || data.filename || ''
  } catch (e) {
    error.value = e.message || "Erreur lors de l'upload de la photo."
  } finally {
    uploading.value = false
    event.target.value = ''
  }
}

async function submitMember() {
  if (!selectedSecretariat.value || quotaFull.value) return
  saving.value = true
  error.value = ''
  success.value = ''
  try {
    const payload = { ...form, secretariat: selectedSecretariat.value, numero_wave: form.numero_wave || form.contact }
    const data = await fetchJson(`${API_URL}?action=create`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
    if (data.wave_launch_url) {
      window.location.href = data.wave_launch_url
      return
    }
    success.value = data.message || 'Séminariste CCR enregistré.'
    Object.assign(form, defaultForm())
    await loadMembers()
  } catch (e) {
    error.value = e.message
  } finally {
    saving.value = false
  }
}

async function deleteMember(member) {
  if (!confirm(`Supprimer ${member.prenom} ${member.nom} ?`)) return
  error.value = ''
  try {
    await fetchJson(`${API_URL}?action=delete`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: member.id, secretariat: selectedSecretariat.value }),
    })
    success.value = 'Séminariste CCR supprimé. Vous pouvez le remplacer.'
    await loadMembers()
  } catch (e) {
    error.value = e.message
  }
}

function paymentLabel(member) {
  if (member.statut_paiement === 'PAYE') return 'Payé'
  if (member.statut_paiement === 'EN_ATTENTE') return 'Wave en attente'
  if (member.statut_paiement === 'ECHEC') return 'Échec'
  return 'Gratuit'
}

onMounted(() => {
  const params = new URLSearchParams(window.location.search)
  selectedSecretariat.value = params.get('secretariat') || localStorage.getItem('seminaristes_ccr_secretariat') || ''
  const payment = params.get('payment')
  const matricule = params.get('matricule')
  if (payment === 'success') paymentMessage.value = `Paiement confirmé pour ${matricule}.`
  if (payment === 'pending') error.value = `Paiement non confirmé pour ${matricule}. Le séminariste CCR ne sera affiché qu'après confirmation Wave.`
  if (payment === 'error') error.value = `Paiement non finalisé pour ${matricule}.`
  loadMembers()
})
</script>

<style scoped>
.sr-page { min-height: 100vh; background: #f5f7fb; color: #132019; padding: 28px; }
.sr-hero { display: flex; justify-content: space-between; gap: 20px; align-items: end; max-width: 1180px; margin: 0 auto 18px; padding: 26px; border-radius: 0; background: linear-gradient(135deg, #0f5132, #198754); color: #fff; }
.eyebrow { font-size: 12px; font-weight: 900; letter-spacing: .08em; text-transform: uppercase; color: var(--green-mist); }
.sr-hero h1 { margin: 8px 0; font-size: clamp(28px, 5vw, 48px); line-height: 1; letter-spacing: -0.03em; }
.sr-hero p { margin: 0; max-width: 660px; color: var(--green-mist); }
.hero-card { min-width: 230px; display: grid; gap: 8px; padding: 16px; border-radius: 0; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25); }
.hero-card strong { font-size: 16px; }
.hero-card span { color: var(--green-mist); font-weight: 800; }
.sr-layout { max-width: 1180px; margin: 0 auto; display: grid; grid-template-columns: 430px minmax(0, 1fr); gap: 16px; align-items: start; }
.panel { background: #fff; border: 1px solid var(--line); border-radius: 0; box-shadow: var(--shadow); padding: 18px; }
label { display: grid; gap: 7px; color: var(--ink-soft); font-size: 12px; font-weight: 900; }
input, select { width: 100%; box-sizing: border-box; border: 1px solid #dfe4ea; border-radius: 0; padding: 12px; font: inherit; background: var(--surface); color: var(--ink); }
.member-form { display: grid; gap: 13px; margin-top: 16px; }
.form-head, .list-head, .switch-line, .member-card { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.form-head h2, .list-head h2 { margin: 0; font-size: 19px; color: var(--ink); }
.form-head span { padding: 7px 10px; border-radius: 999px; background: var(--green-mist); color: var(--green); font-weight: 900; }
.form-head span.full { background: #fef2f2; color: #b91c1c; }
.two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.photo-row { display: grid; grid-template-columns: 84px minmax(0, 1fr); gap: 12px; align-items: center; }
.photo-preview, .avatar { display: grid; place-items: center; overflow: hidden; background: #e8f5ee; color: var(--green); font-weight: 900; }
.photo-preview { width: 84px; height: 84px; border-radius: 0; }
.photo-preview img, .member-card img { width: 100%; height: 100%; object-fit: cover; }
.upload-button { padding: 13px; border: 1px dashed var(--green-2); border-radius: 0; cursor: pointer; background: var(--green-mist); }
.upload-button input { display: none; }
.upload-button small { color: var(--muted); }
.transport-box { display: grid; gap: 10px; padding: 13px; border-radius: 0; background: var(--surface); border: 1px solid #eef0f4; }
.payment-note { color: #92400e; font-size: 12px; font-weight: 800; line-height: 1.45; }
.switch-line { display: grid; grid-template-columns: auto minmax(0, 1fr) auto; font-size: 13px; }
.switch-line input { width: 18px; height: 18px; }
.primary-btn, .soft-btn, .delete-btn { border: 0; border-radius: 0; min-height: 42px; padding: 0 14px; font-weight: 900; cursor: pointer; }
.primary-btn { background: #0f5132; color: #fff; }
.primary-btn:disabled { opacity: .55; cursor: not-allowed; }
.soft-btn { background: var(--green-mist); color: var(--green); }
.delete-btn { min-height: 34px; background: #fef2f2; color: #b91c1c; }
.alert { margin-top: 12px; padding: 12px; border-radius: 0; font-weight: 800; font-size: 13px; }
.alert.error { background: #fef2f2; color: #b91c1c; }
.alert.success { background: var(--green-mist); color: var(--green); }
.closed-card { display: grid; gap: 8px; margin-top: 16px; padding: 18px; border: 1px solid #fed7aa; border-radius: 0; background: linear-gradient(135deg, var(--gold-mist), #fff); color: #431407; }
.closed-card span { width: max-content; padding: 6px 10px; border-radius: 999px; background: var(--gold-mist); color: #c2410c; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em; }
.closed-card strong { color: var(--ink); font-size: 17px; line-height: 1.3; }
.closed-card p { margin: 0; color: var(--muted); font-size: 13px; line-height: 1.5; }
.list-head p { margin: 5px 0 0; color: var(--muted); font-size: 13px; }
.state { min-height: 220px; display: grid; place-items: center; color: var(--muted); font-weight: 800; }
.member-list { display: grid; gap: 10px; margin-top: 15px; max-height: 690px; overflow-y: auto; padding-right: 4px; }
.member-card { padding: 12px; border: 1px solid #eef0f4; border-radius: 0; background: #fbfcfd; }
.member-card img, .avatar { flex: 0 0 54px; width: 54px; height: 54px; border-radius: 0; }
.member-main { min-width: 0; flex: 1; }
.member-main strong, .member-main span, .member-main small { display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
.member-main strong { color: var(--ink); }
.member-main span { color: var(--ink-soft); font-size: 13px; }
.member-main small { color: var(--green); font-weight: 900; }
.member-side { display: grid; gap: 8px; justify-items: end; }
.pay { padding: 6px 9px; border-radius: 999px; background: #f3f4f6; color: var(--ink-soft); font-size: 11px; font-weight: 900; }
.pay.PAYE, .pay.GRATUIT { background: var(--green-mist); color: var(--green); }
.pay.EN_ATTENTE { background: var(--gold-mist); color: #b45309; }
.pay.ECHEC { background: #fef2f2; color: #b91c1c; }
@media (max-width: 900px) {
  .sr-page { padding: 12px; }
  .sr-hero, .sr-layout { grid-template-columns: 1fr; }
  .sr-hero { display: grid; }
}
@media (max-width: 560px) {
  .two-cols, .photo-row { grid-template-columns: 1fr; }
  .member-card { align-items: flex-start; }
  .member-side { justify-items: stretch; }
}
</style>
