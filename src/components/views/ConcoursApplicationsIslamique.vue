<template>
  <main class="contest-page">
    <section class="hero">
      <div class="hero-copy">
        <span class="tag">AEEMCI · Concours national 2026</span>
        <h1>Concours de création d'applications islamiques</h1>
        <p>
          Propose une application utile à la communauté musulmane : apprentissage, gestion,
          éducation, organisation ou toute idée numérique à impact.
        </p>
        <div class="hero-points">
          <span>Finale au SENAFOI</span>
          <span>Présentation PDF requise</span>
        </div>
      </div>
      <aside class="hero-card">
        <strong>Critères clés</strong>
        <ul>
          <li>Pertinence et impact communautaire</li>
          <li>Originalité de la solution</li>
          <li>Fonctionnalité et expérience utilisateur</li>
        </ul>
      </aside>
    </section>

    <section v-if="successData" class="success-card">
      <div>
        <span class="success-kicker">Candidature enregistrée</span>
        <h2>{{ successData.reference }}</h2>
        <p>
          Merci {{ successData.responsable_prenom }}. Ton projet
          <strong>{{ successData.titre_projet }}</strong> est bien reçu.
        </p>
      </div>
      <button type="button" class="btn secondary" @click="resetForm">Nouvelle candidature</button>
    </section>

    <form v-else class="form-shell" @submit.prevent="submitForm">
      <div class="form-intro">
        <span>Inscription</span>
        <h2>Déposer mon projet</h2>
        <p>Remplis uniquement l'essentiel. La présentation du projet permettra au jury de comprendre ton idée.</p>
      </div>

      <div v-if="error" class="alert">{{ error }}</div>

      <div class="grid two">
        <label>
          <span>Nom du responsable *</span>
          <input v-model.trim="form.responsable_nom" type="text" required placeholder="Ex: KONE" />
        </label>
        <label>
          <span>Prénom(s) *</span>
          <input v-model.trim="form.responsable_prenom" type="text" required placeholder="Ex: Aminata" />
        </label>
        <label>
          <span>Contact WhatsApp *</span>
          <input v-model.trim="form.contact" type="tel" required placeholder="07 00 00 00 00" />
        </label>
        <label>
          <span>Email</span>
          <input v-model.trim="form.email" type="email" placeholder="adresse@email.com" />
        </label>
      </div>

      <div class="grid two">
        <label>
          <span>Titre du projet *</span>
          <input v-model.trim="form.titre_projet" type="text" required placeholder="Nom de l'application" />
        </label>
        <label>
          <span>Type de solution</span>
          <select v-model="form.type_projet">
            <option value="">Choisir</option>
            <option>Application mobile</option>
            <option>Application web</option>
            <option>Plateforme éducative</option>
            <option>Gestion d'activités islamiques</option>
            <option>Autre solution digitale</option>
          </select>
        </label>
      </div>

      <label>
        <span>Résumé du projet *</span>
        <textarea
          v-model.trim="form.resume"
          required
          maxlength="700"
          placeholder="Explique simplement ce que fait l'application et à qui elle rend service."
        ></textarea>
        <small>{{ form.resume.length }}/700 caractères</small>
      </label>

      <div class="grid">
        <label>
          <span>Public cible</span>
          <input v-model.trim="form.public_cible" type="text" placeholder="Enfants, élèves, mosquées, bureaux..." />
        </label>
      </div>

      <div class="grid">
        <label>
          <span>Besoin / problème résolu</span>
          <input v-model.trim="form.probleme_resolu" type="text" placeholder="Quel besoin communautaire ?" />
        </label>
      </div>

      <label class="upload-zone" :class="{ ready: uploadedFile.url }">
        <input type="file" accept=".pdf,.ppt,.pptx,application/pdf" @change="uploadPresentation" />
        <span>{{ uploadLabel }}</span>
        <small>PDF conseillé. La présentation doit expliquer le projet et quelques captures si possible.</small>
      </label>

      <button class="btn primary" type="submit" :disabled="submitting || uploading">
        {{ submitting ? 'Enregistrement...' : 'Envoyer ma candidature' }}
      </button>
    </form>
  </main>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'

const API_URL = 'https://api.aeemci-ce.ci/senafoi/concours_applications_islamiques.php'
const UPLOAD_URL = 'https://api.aeemci-ce.ci/senafoi/concours_upload_presentation.php'

const form = reactive({
  responsable_nom: '',
  responsable_prenom: '',
  contact: '',
  email: '',
  titre_projet: '',
  type_projet: '',
  public_cible: '',
  resume: '',
  probleme_resolu: '',
})

const uploadedFile = reactive({ url: '', public_id: '', name: '' })
const uploading = ref(false)
const submitting = ref(false)
const error = ref('')
const successData = ref(null)

const uploadLabel = computed(() => {
  if (uploading.value) return 'Téléchargement en cours...'
  if (uploadedFile.url) return `Fichier prêt: ${uploadedFile.name || 'présentation'}`
  return 'Télécharger la présentation du projet *'
})

async function uploadPresentation(event) {
  const file = event.target.files?.[0]
  if (!file) return
  error.value = ''
  uploading.value = true
  try {
    const body = new FormData()
    body.append('file', file)
    const response = await fetch(UPLOAD_URL, { method: 'POST', body })
    const data = await response.json().catch(() => ({}))
    if (!response.ok || !data.success) throw new Error(data.error || data.message || 'Upload impossible')
    uploadedFile.url = data.url
    uploadedFile.public_id = data.public_id || ''
    uploadedFile.name = file.name
  } catch (e) {
    error.value = e.message || 'Impossible de télécharger le fichier.'
    uploadedFile.url = ''
  } finally {
    uploading.value = false
  }
}

async function submitForm() {
  if (!uploadedFile.url) {
    error.value = 'Télécharge la présentation du projet avant de valider.'
    return
  }
  submitting.value = true
  error.value = ''
  try {
    const response = await fetch(`${API_URL}?action=create`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        ...form,
        presentation_url: uploadedFile.url,
        presentation_id: uploadedFile.public_id,
        presentation_name: uploadedFile.name,
      }),
    })
    const data = await response.json().catch(() => ({}))
    if (!response.ok || !data.success) throw new Error(data.message || 'Enregistrement impossible')
    successData.value = data.data
    window.scrollTo({ top: 0, behavior: 'smooth' })
  } catch (e) {
    error.value = e.message || 'Erreur pendant l’enregistrement.'
  } finally {
    submitting.value = false
  }
}

function resetForm() {
  Object.assign(form, {
    responsable_nom: '',
    responsable_prenom: '',
    contact: '',
    email: '',
    titre_projet: '',
    type_projet: '',
    public_cible: '',
    resume: '',
    probleme_resolu: '',
  })
  Object.assign(uploadedFile, { url: '', public_id: '', name: '' })
  successData.value = null
}
</script>

<style scoped>
.contest-page {
  min-height: 100vh;
  background:
    radial-gradient(circle at 12% 8%, rgba(22, 163, 74, .16), transparent 30%),
    radial-gradient(circle at 90% 20%, rgba(245, 158, 11, .16), transparent 34%),
    var(--surface);
  padding: 34px 18px 48px;
  color: var(--ink);
}
.hero, .form-shell, .success-card { width: min(1060px, 100%); margin: 0 auto; }
.hero { display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 20px; align-items: stretch; margin-bottom: 20px; }
.hero-copy, .hero-card, .form-shell, .success-card { background: rgba(255,255,255,.88); border: 1px solid rgba(226,232,240,.9); border-radius: 0; box-shadow: var(--shadow); }
.hero-copy { padding: 34px; }
.hero-card { padding: 24px; }
.tag, .form-intro span, .success-kicker { display: inline-flex; border-radius: 999px; background: var(--green-mist); color: var(--green); padding: 7px 11px; font-size: 12px; font-weight: 900; text-transform: uppercase; }
h1, h2, p { margin: 0; }
h1 { margin-top: 16px; font-size: clamp(32px, 5vw, 58px); line-height: .94; letter-spacing: -.05em; }
.hero-copy p { max-width: 680px; margin-top: 18px; color: var(--ink-soft); font-size: 16px; line-height: 1.65; }
.hero-points { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 22px; }
.hero-points span { background: var(--gold-mist); color: #c2410c; border: 1px solid #fed7aa; border-radius: 999px; padding: 8px 11px; font-size: 12px; font-weight: 900; }
.hero-card strong { display: block; margin-bottom: 12px; font-size: 18px; }
.hero-card ul { margin: 0; padding-left: 20px; color: var(--ink-soft); line-height: 1.8; }
.form-shell { padding: 26px; display: grid; gap: 16px; }
.form-intro h2 { margin-top: 10px; font-size: 28px; letter-spacing: -.03em; }
.form-intro p { margin-top: 6px; color: var(--muted); }
.grid { display: grid; gap: 14px; }
.grid.two { grid-template-columns: repeat(2, minmax(0, 1fr)); }
label { display: grid; gap: 7px; color: var(--ink-soft); font-size: 13px; font-weight: 900; }
input, select, textarea { width: 100%; box-sizing: border-box; border: 1px solid #dbe4ee; border-radius: 0; background: #fff; color: var(--ink); padding: 12px 13px; font: inherit; }
textarea { min-height: 116px; resize: vertical; }
small { color: var(--muted); font-weight: 600; }
input:focus, select:focus, textarea:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 4px rgba(22,163,74,.12); }
.segmented { display: grid; grid-template-columns: repeat(2, 86px); border: 1px solid #d1d5db; border-radius: 0; overflow: hidden; background: #fff; }
.segmented button { border: 0; background: #fff; padding: 11px; font-weight: 900; cursor: pointer; }
.segmented button.active { background: var(--green); color: #fff; }
.upload-zone { cursor: pointer; border: 1.5px dashed var(--muted); border-radius: 0; padding: 20px; background: var(--surface); text-align: center; }
.upload-zone.ready { border-color: var(--green); background: var(--green-mist); color: var(--green); }
.upload-zone input { display: none; }
.upload-zone span { display: block; font-size: 15px; }
.upload-zone small { display: block; margin-top: 6px; }
.btn { border: 0; border-radius: 0; min-height: 46px; padding: 0 18px; font-weight: 900; cursor: pointer; }
.btn.primary { background: var(--green); color: #fff; justify-self: start; }
.btn.secondary { background: #fff; color: var(--green); border: 1px solid var(--green-mist); }
.btn:disabled { opacity: .6; cursor: wait; }
.alert { border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; padding: 12px 14px; border-radius: 0; font-weight: 800; }
.success-card { padding: 28px; display: flex; justify-content: space-between; align-items: center; gap: 18px; }
.success-card h2 { margin: 10px 0; font-size: 34px; }
.success-card p { color: var(--ink-soft); }
@media (max-width: 760px) {
  .hero, .grid.two { grid-template-columns: 1fr; }
  .hero-copy, .form-shell, .success-card { padding: 22px; }
  .success-card { align-items: stretch; flex-direction: column; }
  .segmented { grid-template-columns: repeat(2, 1fr); }
  .btn.primary { width: 100%; }
}
</style>

