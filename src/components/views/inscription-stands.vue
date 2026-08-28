<template>
  <div class="stand-page">
    <header class="topbar">
      <div class="brand"><span>AEEMCI</span><strong>SENAFOI 2026</strong></div>
      <button type="button" @click="goHome">Accueil</button>
    </header>

    <main class="wrap">
      <section class="hero">
        <div>
          <span class="eyebrow">Espaces commerciaux</span>
          <h1>Inscription des stands</h1>
          <p>
            Réservez votre stand pour le SENAFOI Korhogo 2026, ajoutez vos accompagnateurs si vous
            venez avec le convoi et payez directement par Wave.
          </p>
        </div>
        <aside>
          <p>Stand Korhogo <strong>50 000 FCFA</strong></p>
          <p>Stand avec convoi <strong>100 000 FCFA</strong></p>
          <p>Accompagnateur <strong>80 000 FCFA</strong></p>
        </aside>
      </section>

      <section v-if="step === 'form'" class="panel form-panel">
        <div class="section-title">
          <span>1</span>
          <div>
            <h2>Informations du stand</h2>
            <p>Les champs essentiels uniquement.</p>
          </div>
        </div>

        <form @submit.prevent="showSummary" class="grid-form">
          <label>
            <span>Nom du stand</span>
            <input v-model.trim="form.nom_stand" required placeholder="Ex: Saveurs du Nord" />
          </label>

          <label>
            <span>Nom du responsable</span>
            <input v-model.trim="form.nom_responsable" required placeholder="Nom complet" />
          </label>

          <label class="full">
            <span>Produits du stand</span>
            <textarea
              v-model.trim="form.produits_stand"
              required
              placeholder="Ex: boissons, grillades, pâtisseries, tenues, accessoires..."
            ></textarea>
          </label>

          <label>
            <span>Contact du responsable</span>
            <input
              v-model="form.contact_responsable"
              required
              type="tel"
              placeholder="07 00 00 00 00"
              @input="formatPhone"
            />
          </label>

          <div class="choice-group">
            <span>Formule</span>
            <label class="choice" :class="{ active: form.formule === 'korhogo' }">
              <input v-model="form.formule" type="radio" value="korhogo" />
              <strong>Stand Korhogo</strong>
              <small>50 000 FCFA</small>
            </label>
            <label class="choice" :class="{ active: form.formule === 'convoi' }">
              <input v-model="form.formule" type="radio" value="convoi" />
              <strong>Stand + convoi AEEMCI</strong>
              <small>100 000 FCFA</small>
            </label>
          </div>

          <div v-if="form.formule === 'convoi'" class="companions">
            <div class="section-title mini">
              <span>2</span>
              <div>
                <h2>Accompagnateurs</h2>
                <p>Optionnel, 80 000 FCFA par accompagnateur. Maximum 3 personnes.</p>
              </div>
            </div>

            <div v-for="(acc, index) in form.accompagnateurs" :key="index" class="acc-row">
              <input v-model.trim="acc.nom" placeholder="Nom accompagnateur" />
              <input v-model="acc.contact" placeholder="Contact" @input="formatAccPhone(index)" />
              <button type="button" class="icon-btn" @click="removeAcc(index)">×</button>
            </div>

            <button
              type="button"
              class="add-btn"
              :disabled="form.accompagnateurs.length >= MAX_ACCOMPAGNATEURS"
              @click="addAcc"
            >
              {{ form.accompagnateurs.length >= MAX_ACCOMPAGNATEURS ? 'Maximum 3 accompagnateurs' : 'Ajouter un accompagnateur' }}
            </button>
          </div>

          <div v-else class="companions muted">
            <div class="section-title mini">
              <span>2</span>
              <div>
                <h2>Accompagnateurs</h2>
                <p>Aucun accompagnateur n'est possible pour un stand Korhogo.</p>
              </div>
            </div>
          </div>

          <div v-if="error" class="alert">{{ error }}</div>
          <button class="primary" type="submit">Voir le résumé</button>
        </form>
      </section>

      <section v-else class="panel summary-panel">
        <div class="section-title">
          <span>3</span>
          <div>
            <h2>Résumé avant paiement</h2>
            <p>Vérifiez les informations avant génération du lien Wave.</p>
          </div>
        </div>

        <div class="summary-grid">
          <p><span>Stand</span><strong>{{ form.nom_stand }}</strong></p>
          <p><span>Responsable</span><strong>{{ form.nom_responsable }}</strong></p>
          <p><span>Contact</span><strong>{{ form.contact_responsable }}</strong></p>
          <p><span>Formule</span><strong>{{ formuleLabel }}</strong></p>
          <p class="wide"><span>Produits</span><strong>{{ form.produits_stand }}</strong></p>
          <p><span>Accompagnateurs</span><strong>{{ validAcc.length }}</strong></p>
          <p><span>Total</span><strong class="amount">{{ money(totalAmount) }}</strong></p>
        </div>

        <div v-if="validAcc.length" class="acc-list">
          <h3>Accompagnateurs</h3>
          <span v-for="acc in validAcc" :key="acc.nom + acc.contact">
            {{ acc.nom }} <small>{{ acc.contact }}</small>
          </span>
        </div>

        <div v-if="error" class="alert">{{ error }}</div>
        <div class="actions">
          <button type="button" class="secondary" :disabled="submitting" @click="step = 'form'">Modifier</button>
          <button type="button" class="primary" :disabled="submitting" @click="submitPayment">
            <span v-if="submitting" class="spinner"></span>{{ submitting ? 'Création du paiement...' : 'Confirmer et payer par Wave' }}
          </button>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { computed, reactive, ref, watch } from 'vue'

const API_URL = 'https://api.aeemci-ce.ci/senafoi/stands_senafoi26.php'
const MAX_ACCOMPAGNATEURS = 3

const step = ref('form')
const submitting = ref(false)
const error = ref('')

const form = reactive({
  nom_stand: '',
  nom_responsable: '',
  produits_stand: '',
  contact_responsable: '',
  formule: 'korhogo',
  accompagnateurs: [],
})

const validAcc = computed(() => {
  if (form.formule !== 'convoi') return []
  return form.accompagnateurs.filter((a) => a.nom.trim())
})
const standAmount = computed(() => form.formule === 'convoi' ? 100000 : 50000)
const totalAmount = computed(() => standAmount.value + validAcc.value.length * 80000)
const formuleLabel = computed(() => form.formule === 'convoi' ? 'Stand avec convoi AEEMCI' : 'Stand Korhogo')

watch(() => form.formule, (formule) => {
  error.value = ''
  if (formule === 'korhogo') {
    form.accompagnateurs = []
  } else if (form.accompagnateurs.length > MAX_ACCOMPAGNATEURS) {
    form.accompagnateurs = form.accompagnateurs.slice(0, MAX_ACCOMPAGNATEURS)
  }
})

function digits(v) { return String(v || '').replace(/\D/g, '').slice(0, 10) }
function spaced(v) { return digits(v).replace(/(\d{2})(?=\d)/g, '$1 ').trim() }
function money(v) { return new Intl.NumberFormat('fr-FR').format(v) + ' FCFA' }
function formatPhone() { form.contact_responsable = spaced(form.contact_responsable) }
function formatAccPhone(i) { form.accompagnateurs[i].contact = spaced(form.accompagnateurs[i].contact) }
function removeAcc(i) { form.accompagnateurs.splice(i, 1) }
function goHome() { window.location.href = 'https://www.aeemci-ce.ci/' }

function addAcc() {
  if (form.formule !== 'convoi') {
    error.value = 'Les accompagnateurs sont uniquement disponibles avec la formule stand + convoi AEEMCI.'
    return
  }
  if (form.accompagnateurs.length >= MAX_ACCOMPAGNATEURS) {
    error.value = 'Maximum 3 accompagnateurs pour la formule stand + convoi AEEMCI.'
    return
  }
  error.value = ''
  form.accompagnateurs.push({ nom: '', contact: '' })
}

function showSummary() {
  error.value = ''
  if (!form.nom_stand || !form.nom_responsable || !form.produits_stand || !digits(form.contact_responsable)) {
    error.value = 'Merci de renseigner le nom du stand, le responsable, les produits du stand et son contact.'
    return
  }
  if (form.formule === 'korhogo') {
    form.accompagnateurs = []
  }
  if (form.formule === 'convoi' && validAcc.value.length > MAX_ACCOMPAGNATEURS) {
    error.value = 'Maximum 3 accompagnateurs pour la formule stand + convoi AEEMCI.'
    return
  }
  step.value = 'summary'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

async function submitPayment() {
  error.value = ''
  submitting.value = true
  try {
    const res = await fetch(`${API_URL}?action=create`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nom_stand: form.nom_stand,
        nom_responsable: form.nom_responsable,
        produits_stand: form.produits_stand,
        contact_responsable: digits(form.contact_responsable),
        formule: form.formule,
        accompagnateurs: validAcc.value.map((a) => ({ nom: a.nom, contact: digits(a.contact) })),
      }),
    })
    const data = await res.json().catch(() => ({}))
    if (!res.ok || !data.success || !data.wave_launch_url) throw new Error(data.message || 'Impossible de créer le paiement Wave.')
    window.location.href = data.wave_launch_url
  } catch (e) {
    error.value = e.message
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.stand-page { min-height: 100vh; background: radial-gradient(circle at 12% 8%, rgba(22,163,74,.18), transparent 30rem), radial-gradient(circle at 85% 10%, rgba(249,115,22,.2), transparent 24rem), #f6fbf8; color: #10251b; padding-bottom: 48px; }
.topbar { height: 74px; max-width: 1100px; margin: 0 auto; padding: 0 18px; display:flex; align-items:center; justify-content:space-between; }
.brand { display:grid; gap:2px; } .brand span{font-weight:900;color:var(--green)}.brand strong{font-size:.85rem;color:#64746a}.topbar button,.secondary{border:1px solid var(--green-mist);background:#fff;color:var(--green);border-radius: 0;padding:12px 16px;font-weight:900;cursor:pointer}
.wrap { max-width: 1040px; margin: 0 auto; padding: 14px 18px; display:grid; gap:24px; }
.hero { min-height:260px; display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:center; }
.eyebrow { color:var(--gold); font-weight:900; letter-spacing:.14em; text-transform:uppercase; font-size:.75rem; }
h1{font-size:clamp(2.4rem,6vw,5rem);line-height:.95;margin:10px 0}
.hero p{color:#526158;font-size:1.08rem;line-height:1.7;max-width:650px}
.hero aside{background:rgba(255,255,255,.86);border:1px solid rgba(21,128,61,.14);border-radius: 0;padding:22px;box-shadow: var(--shadow);display:grid;gap:12px}
.hero aside p{margin:0;display:flex;justify-content:space-between;gap:14px}
.hero aside strong{color:var(--green);white-space:nowrap}
.panel { background:rgba(255,255,255,.94); border:1px solid rgba(21,128,61,.14); border-radius: 0; padding:clamp(22px,4vw,36px); box-shadow: var(--shadow); }
.section-title{display:flex;gap:14px;align-items:flex-start;margin-bottom:22px}
.section-title span{width:38px;height:38px;border-radius: 0;background:var(--green);color:#fff;display:grid;place-items:center;font-weight:900}
.section-title h2{margin:0;font-size:1.45rem}
.section-title p{margin:4px 0 0;color:#64746a}
.mini{margin-top:12px}
.grid-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
label{display:grid;gap:8px;font-weight:900;color:#334139}
.full { grid-column: 1 / -1; }
input, textarea{border:1px solid #d9e6dd;border-radius: 0;padding:0 15px;background:#fbfdfc;outline:0;font-size:1rem;font:inherit}
input{height:50px}
textarea{min-height:92px;padding-top:14px;resize:vertical}
input:focus, textarea:focus{border-color:var(--green);box-shadow:0 0 0 4px rgba(22,163,74,.12)}
.choice-group,.companions,.primary,.alert{grid-column:1/-1}
.choice-group{display:grid;gap:10px}
.choice{border:2px solid #e4eee8;border-radius: 0;padding:16px;display:flex;align-items:center;gap:12px}
.choice input{width:18px;height:18px}
.choice small{margin-left:auto;color:var(--green);font-weight:900}
.choice.active{border-color:var(--green);background:var(--green-mist)}
.companions.muted{background:#f8faf9;border:1px solid #e5eee8;border-radius: 0;padding:16px}
.companions.muted .section-title{margin-bottom:0}
.acc-row{display:grid;grid-template-columns:1fr 180px 44px;gap:10px;margin-bottom:10px}
.icon-btn{border:0;border-radius: 0;background:#fee2e2;color:#991b1b;font-size:1.4rem;font-weight:900}
.add-btn{border:1px dashed var(--green-mist);background:var(--green-mist);color:var(--green);border-radius: 0;padding:13px 16px;font-weight:900}
.add-btn:disabled{opacity:.62;cursor:not-allowed}
.primary{border:0;background:linear-gradient(135deg,var(--green),var(--green));color:#fff;border-radius: 0;padding:16px 22px;font-weight:900;cursor:pointer;box-shadow: var(--shadow)}
.primary:disabled,.secondary:disabled{opacity:.65;cursor:wait}
.alert{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;border-radius: 0;padding:13px 15px;font-weight:800}
.summary-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.summary-grid p{margin:0;background:#f8faf9;border:1px solid #e5eee8;border-radius: 0;padding:16px;display:grid;gap:6px}
.summary-grid .wide{grid-column:span 2}
.summary-grid span{color:#64746a;text-transform:uppercase;font-size:.75rem;font-weight:900}
.amount{color:var(--green);font-size:1.25rem}
.acc-list{margin-top:20px;display:flex;flex-wrap:wrap;gap:10px}
.acc-list h3{width:100%;margin:0}
.acc-list span{padding:9px 12px;border-radius:999px;background:var(--green-mist);color:var(--green);font-weight:900}
.acc-list small{color:#64746a}
.actions{display:flex;justify-content:flex-end;gap:12px;margin-top:24px}
.spinner{width:16px;height:16px;border:2px solid rgba(255,255,255,.45);border-top-color:white;border-radius:999px;display:inline-block;margin-right:8px;vertical-align:middle;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
@media(max-width:760px){
  .hero,.grid-form,.summary-grid{grid-template-columns:1fr}
  .summary-grid .wide{grid-column:auto}
  .acc-row{grid-template-columns:1fr}
  .actions{flex-direction:column}
  .topbar{height:auto;padding-top:16px}
  .wrap{padding-top:0}
}
</style>
