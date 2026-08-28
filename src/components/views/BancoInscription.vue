<template>
  <div class="banco-page">
    <section class="hero">
      <div class="hero-copy">
        <span class="eyebrow">AEEMCI</span>
        <h1>Inscription à la sortie du Banco</h1>
        <p>Enregistre ta participation gratuitement en quelques secondes.</p>
      </div>
      <div class="hero-badge">
        <strong>Gratuit</strong>
        <span>Nom, prénom, sous-comité et contact</span>
      </div>
    </section>

    <main class="shell">
      <section v-if="!registered" class="form-card">
        <div class="section-head">
          <span>Formulaire</span>
          <h2>Informations du participant</h2>
        </div>

        <form @submit.prevent="submitRegistration" class="form-grid">
          <label>
            <span>Nom</span>
            <input v-model.trim="form.nom" type="text" placeholder="Votre nom" required @input="form.nom = form.nom.toUpperCase()" />
          </label>
          <label>
            <span>Prénom</span>
            <input v-model.trim="form.prenom" type="text" placeholder="Votre prénom" required />
          </label>
          <label>
            <span>Sous-comité</span>
            <input v-model.trim="form.sous_comite" type="text" placeholder="Ex: Cocody, Yopougon..." required />
          </label>
          <label>
            <span>Contact</span>
            <input v-model="form.contact" type="tel" placeholder="07 00 00 00 00" required @input="formatContact" />
          </label>

          <p v-if="error" class="alert error">{{ error }}</p>

          <button type="submit" :disabled="submitting">
            <span v-if="submitting" class="spinner"></span>
            {{ submitting ? 'Enregistrement...' : 'Valider mon inscription' }}
          </button>
        </form>
      </section>

      <section v-else class="success-card">
        <div class="success-mark">✓</div>
        <span class="eyebrow">Inscription confirmée</span>
        <h2>{{ registered.prenom }} {{ registered.nom }}</h2>
        <p>Ta participation à la sortie du Banco est enregistrée.</p>
        <div class="ticket">
          <span>Référence</span>
          <strong>{{ registered.reference }}</strong>
        </div>
        <div class="details">
          <p><span>Sous-comité</span><strong>{{ registered.sous_comite }}</strong></p>
          <p><span>Contact</span><strong>{{ displayContact(registered.contact) }}</strong></p>
        </div>
        <button type="button" class="ghost" @click="resetForm">Nouvelle inscription</button>
      </section>
    </main>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'

const API_URL = 'https://api.aeemci-ce.ci/banco.php'
const submitting = ref(false)
const error = ref('')
const registered = ref(null)

const form = reactive({
  nom: '',
  prenom: '',
  sous_comite: '',
  contact: '',
})

function cleanPhone(value) {
  return String(value || '').replace(/\D/g, '').slice(0, 10)
}

function spacedPhone(value) {
  const digits = cleanPhone(value)
  return digits.replace(/(\d{2})(?=\d)/g, '$1 ').trim()
}

function formatContact() {
  form.contact = spacedPhone(form.contact)
}

function displayContact(value) {
  return spacedPhone(value)
}

async function submitRegistration() {
  error.value = ''
  submitting.value = true
  try {
    const payload = {
      nom: form.nom.trim(),
      prenom: form.prenom.trim(),
      sous_comite: form.sous_comite.trim(),
      contact: cleanPhone(form.contact),
    }
    const res = await fetch(`${API_URL}?action=inscrire`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
    const data = await res.json().catch(() => ({}))
    if (!res.ok || !data.success) throw new Error(data.message || 'Inscription impossible pour le moment.')
    registered.value = data.data
  } catch (e) {
    error.value = e.message
  } finally {
    submitting.value = false
  }
}

function resetForm() {
  Object.assign(form, { nom: '', prenom: '', sous_comite: '', contact: '' })
  registered.value = null
  error.value = ''
}
</script>

<style scoped>
.banco-page {
  min-height: 100vh;
  background:
    radial-gradient(circle at 20% 10%, rgba(22, 163, 74, .18), transparent 28rem),
    radial-gradient(circle at 84% 18%, rgba(249, 115, 22, .18), transparent 24rem),
    linear-gradient(145deg, #f7fbf8 0%, #eef6f1 100%);
  padding: 96px 18px 48px;
  color: #10251b;
}
.hero,
.shell {
  max-width: 980px;
  margin: 0 auto;
}
.hero {
  min-height: 260px;
  display: grid;
  grid-template-columns: minmax(0, 1fr) 280px;
  align-items: center;
  gap: 28px;
}
.eyebrow {
  display: inline-flex;
  color: var(--green);
  font-size: .78rem;
  font-weight: 900;
  letter-spacing: .14em;
  text-transform: uppercase;
}
h1 {
  max-width: 650px;
  margin: 12px 0;
  font-size: clamp(2.2rem, 6vw, 4.8rem);
  line-height: .95;
  letter-spacing: 0;
}
.hero p {
  max-width: 560px;
  color: #526158;
  font-size: 1.05rem;
  line-height: 1.7;
}
.hero-badge {
  min-height: 180px;
  border: 1px solid rgba(21, 128, 61, .16);
  border-radius: 0;
  padding: 26px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  background: rgba(255, 255, 255, .76);
  box-shadow: var(--shadow);
}
.hero-badge strong {
  font-size: 2.6rem;
  color: var(--green);
}
.hero-badge span {
  color: #59665f;
}
.form-card,
.success-card {
  background: rgba(255, 255, 255, .92);
  border: 1px solid rgba(21, 128, 61, .14);
  border-radius: 0;
  box-shadow: var(--shadow);
  padding: clamp(22px, 4vw, 38px);
}
.section-head span {
  color: var(--gold);
  font-weight: 900;
  text-transform: uppercase;
  font-size: .75rem;
  letter-spacing: .14em;
}
.section-head h2,
.success-card h2 {
  margin: 8px 0 24px;
  font-size: clamp(1.5rem, 3vw, 2.1rem);
}
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 18px;
}
label {
  display: grid;
  gap: 8px;
  color: #334139;
  font-weight: 800;
}
input {
  width: 100%;
  border: 1px solid #d9e6dd;
  border-radius: 0;
  padding: 15px 16px;
  color: #10251b;
  background: #fbfdfc;
  font-size: 1rem;
  outline: none;
  transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}
input:focus {
  border-color: var(--green);
  box-shadow: 0 0 0 4px rgba(22, 163, 74, .12);
  transform: translateY(-1px);
}
.alert {
  grid-column: 1 / -1;
  margin: 0;
  padding: 13px 15px;
  border-radius: 0;
  font-weight: 700;
}
.error {
  color: #991b1b;
  background: #fef2f2;
  border: 1px solid #fecaca;
}
button {
  grid-column: 1 / -1;
  border: 0;
  border-radius: 0;
  padding: 16px 22px;
  color: #fff;
  background: linear-gradient(135deg, var(--green), var(--green));
  font-weight: 900;
  cursor: pointer;
  box-shadow: var(--shadow);
}
button:disabled {
  opacity: .65;
  cursor: wait;
}
.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, .5);
  border-top-color: white;
  border-radius: 999px;
  display: inline-block;
  margin-right: 8px;
  vertical-align: middle;
  animation: spin .8s linear infinite;
}
.success-card {
  max-width: 620px;
  margin: 0 auto;
  text-align: center;
}
.success-mark {
  width: 74px;
  height: 74px;
  border-radius: 0;
  margin: 0 auto 18px;
  display: grid;
  place-items: center;
  color: #fff;
  background: var(--green);
  font-size: 2.4rem;
  font-weight: 900;
}
.success-card p {
  color: #526158;
}
.ticket {
  margin: 24px auto;
  padding: 18px;
  border-radius: 0;
  background: var(--green-mist);
  border: 1px dashed var(--green-mist);
  display: grid;
  gap: 4px;
}
.ticket span,
.details span {
  color: #64746a;
  font-size: .82rem;
  font-weight: 800;
  text-transform: uppercase;
}
.ticket strong {
  color: var(--green);
  font-size: 1.45rem;
}
.details {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  text-align: left;
}
.details p {
  margin: 0;
  padding: 14px;
  border-radius: 0;
  background: #f8faf9;
  display: grid;
  gap: 5px;
}
.details strong {
  color: #10251b;
}
.ghost {
  margin-top: 22px;
  width: 100%;
  color: var(--green);
  background: #fff;
  border: 1px solid var(--green-mist);
  box-shadow: none;
}
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 760px) {
  .banco-page { padding-top: 82px; }
  .hero { grid-template-columns: 1fr; min-height: auto; padding-bottom: 24px; }
  .hero-badge { min-height: 130px; }
  .form-grid,
  .details { grid-template-columns: 1fr; }
}
</style>