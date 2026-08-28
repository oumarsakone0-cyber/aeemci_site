<template>
  <div class="receipt-page">
    <main class="card">
      <div v-if="loading" class="state"><span class="loader"></span><h1>Vérification du paiement...</h1><p>Nous consultons Wave pour confirmer votre inscription.</p></div>
      <div v-else-if="error" class="state"><div class="bad">!</div><h1>Inscription introuvable</h1><p>{{ error }}</p><button @click="newRegistration">Nouvelle inscription</button></div>
      <template v-else>
        <header class="success-head" :class="{ waiting: stand.statut_paiement !== 'PAYE' }">
          <div class="mark">{{ stand.statut_paiement === 'PAYE' ? '✓' : '…' }}</div>
          <span>{{ stand.statut_paiement === 'PAYE' ? 'Paiement confirmé' : 'Paiement en attente' }}</span>
          <h1>{{ stand.reference_stand }}</h1>
          <p>{{ stand.statut_paiement === 'PAYE' ? 'Votre stand est validé pour le SENAFOI 2026.' : 'Si vous venez de payer, actualisez cette page dans quelques secondes.' }}</p>
        </header>

        <section class="summary">
          <p><span>Stand</span><strong>{{ stand.nom_stand }}</strong></p>
          <p><span>Responsable</span><strong>{{ stand.nom_responsable }}</strong></p>
          <p><span>Contact</span><strong>{{ formatPhone(stand.contact_responsable) }}</strong></p>
          <p><span>Formule</span><strong>{{ stand.formule_label }}</strong></p>
          <p><span>Accompagnateurs</span><strong>{{ stand.nb_accompagnateurs }}</strong></p>
          <p><span>Montant</span><strong class="amount">{{ stand.montant_total_formate }}</strong></p>
        </section>

        <section v-if="stand.accompagnateurs?.length" class="companions"><h2>Accompagnateurs</h2><span v-for="acc in stand.accompagnateurs" :key="acc.nom + acc.contact">{{ acc.nom }} <small>{{ formatPhone(acc.contact) }}</small></span></section>

        <div class="actions">
          <button v-if="stand.statut_paiement !== 'PAYE'" @click="loadStand">Revérifier</button>
          <a v-if="stand.wave_launch_url && stand.statut_paiement !== 'PAYE'" :href="stand.wave_launch_url">Payer maintenant</a>
          <button @click="downloadReceipt">Télécharger le reçu PDF</button>
        </div>
      </template>
    </main>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const API_URL = 'https://api.aeemci-ce.ci/senafoi/stands_senafoi26.php'
const route = useRoute()
const loading = ref(true)
const error = ref('')
const stand = ref(null)

onMounted(loadStand)

async function loadStand() {
  loading.value = true
  error.value = ''
  try {
    const ref = route.params.reference
    const res = await fetch(`${API_URL}?action=get&reference=${encodeURIComponent(ref)}&t=${Date.now()}`)
    const data = await res.json().catch(() => ({}))
    if (!res.ok || !data.success) throw new Error(data.message || 'Impossible de récupérer votre inscription.')
    stand.value = data.data
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
function formatPhone(value) { const d = String(value || '').replace(/\D/g, '').slice(0, 10); return d ? d.replace(/(\d{2})(?=\d)/g, '$1 ').trim() : value }
function newRegistration() { window.location.href = 'https://www.aeemci-ce.ci/inscription_stands' }
async function downloadReceipt() {
  const { jsPDF } = await import('jspdf')
  const doc = new jsPDF('p', 'mm', 'a4')
  const s = stand.value
  doc.setFillColor(21, 128, 61); doc.rect(0, 0, 210, 34, 'F')
  doc.setTextColor(255,255,255); doc.setFont('helvetica','bold'); doc.setFontSize(18); doc.text('AEEMCI - SENAFOI 2026', 105, 14, { align:'center' })
  doc.setFontSize(12); doc.text('RECU D\'INSCRIPTION STAND', 105, 24, { align:'center' })
  doc.setTextColor(20,35,28); doc.setFontSize(11); doc.text(`Référence : ${s.reference_stand}`, 16, 48); doc.text(`Date : ${new Date().toLocaleDateString('fr-FR')}`, 150, 48)
  const rows = [['Stand', s.nom_stand], ['Responsable', s.nom_responsable], ['Contact', formatPhone(s.contact_responsable)], ['Formule', s.formule_label], ['Accompagnateurs', String(s.nb_accompagnateurs)], ['Montant payé', s.montant_total_formate], ['Statut', s.statut_paiement]]
  let y = 64; rows.forEach(([k,v]) => { doc.setFont('helvetica','normal'); doc.text(`${k} :`, 18, y); doc.setFont('helvetica','bold'); doc.text(String(v || 'N/A'), 70, y); y += 9 })
  if (s.accompagnateurs?.length) { y += 4; doc.setFontSize(12); doc.text('Accompagnateurs', 18, y); y += 8; doc.setFontSize(10); s.accompagnateurs.forEach((a, i) => { doc.text(`${i+1}. ${a.nom} - ${formatPhone(a.contact)}`, 22, y); y += 7 }) }
  doc.setFillColor(240,253,244); doc.rect(15, 250, 180, 20, 'F'); doc.setTextColor(21,128,61); doc.setFont('helvetica','bold'); doc.text('AEEMCI, pour une identité islamique !', 105, 262, { align:'center' })
  doc.save(`recu_stand_${s.reference_stand}.pdf`)
}
</script>

<style scoped>
.receipt-page{min-height:100vh;background:radial-gradient(circle at 20% 10%,rgba(22,163,74,.18),transparent 30rem),#f6fbf8;padding:60px 18px;color:#10251b}.card{max-width:880px;margin:0 auto;background:#fff;border:1px solid #dcece2;border-radius: 0;padding:clamp(22px,4vw,38px);box-shadow: var(--shadow)}.state{text-align:center;padding:50px 16px}.loader{width:42px;height:42px;border:4px solid var(--green-mist);border-top-color:var(--green);border-radius:999px;display:inline-block;animation:spin .8s linear infinite}.bad,.mark{width:68px;height:68px;border-radius: 0;margin:0 auto 15px;display:grid;place-items:center;color:#fff;font-weight:900;font-size:2rem}.bad{background:#dc2626}.success-head{text-align:center;background:var(--green-mist);border:1px solid var(--green-mist);border-radius: 0;padding:28px}.success-head.waiting{background:var(--gold-mist);border-color:#fed7aa}.mark{background:var(--green)}.waiting .mark{background:var(--gold)}.success-head span{font-weight:900;color:var(--green);text-transform:uppercase;font-size:.78rem;letter-spacing:.12em}.waiting span{color:var(--gold)}.success-head h1{font-size:2.8rem;margin:6px 0}.success-head p{color:#64746a}.summary{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:22px}.summary p{margin:0;background:#f8faf9;border:1px solid #e5eee8;border-radius: 0;padding:16px;display:grid;gap:6px}.summary span{font-size:.75rem;color:#64746a;text-transform:uppercase;font-weight:900}.amount{color:var(--green)}.companions{margin-top:22px}.companions h2{margin:0 0 10px}.companions span{display:inline-flex;gap:8px;margin:4px;padding:9px 12px;border-radius:999px;background:var(--green-mist);color:var(--green);font-weight:900}.companions small{color:#64746a}.actions{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-top:28px}.actions button,.actions a,.state button{border:0;border-radius: 0;padding:14px 18px;background:var(--green);color:white;font-weight:900;text-decoration:none;cursor:pointer}@keyframes spin{to{transform:rotate(360deg)}}@media(max-width:760px){.summary{grid-template-columns:1fr}.success-head h1{font-size:2rem}}
</style>