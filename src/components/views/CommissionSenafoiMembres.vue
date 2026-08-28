<template>
  <div class="app-container">
    <header class="app-header">
      <div class="header-content">
        <div class="header-left">
          <div class="logo">
            <div class="logo-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
              </svg>
            </div>
            <div class="logo-text">
              <h1>AEEMCI</h1>
              <p>Commissions du Séminaire National de Formation Islamique</p>
            </div>
          </div>
        </div>
        <div class="header-right">
          <div class="year-badge">SENAFOI 26</div>
        </div>
      </div>
    </header>

    <main class="main-content">
      <div class="form-wrapper">
        <section v-if="currentView === 'home'" class="step-content">
          <div class="welcome-card">
            <div class="senafoi-banner-hero">
              <img :src="senafoiBanner" alt="SENAFOI Korhogo 2026" class="senafoi-banner-img" />
            </div>

            <div class="quota-panel">
              <div class="quota-copy">
                <span class="quota-label">Commissions SENAFOI</span>
                <strong>{{ stats.total.toLocaleString('fr-FR') }} membre{{ stats.total > 1 ? 's' : '' }} inscrit{{ stats.total > 1 ? 's' : '' }}</strong>
                <small>{{ activeCommissions }} commission{{ activeCommissions > 1 ? 's' : '' }} active{{ activeCommissions > 1 ? 's' : '' }} · {{ stats.depart_abidjan || 0 }} départ{{ (stats.depart_abidjan || 0) > 1 ? 's' : '' }} Abidjan</small>
              </div>
              <div class="quota-available">
                <span>{{ stats.total }}</span>
                <small>inscrits</small>
              </div>
            </div>

            <div class="choice-section">
              <h3>Que souhaitez-vous faire ?</h3>
              <p class="choice-subtitle">Inscrivez un membre de commission ou consultez la liste déjà enregistrée.</p>

              <div class="choice-buttons">
                <button v-if="commissionFormVisible" type="button" class="choice-btn choice-btn-primary" @click="currentView = 'form'">
                  <div class="choice-icon choice-icon-primary">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                  </div>
                  <div class="choice-content">
                    <span class="choice-title">S'inscrire dans une commission</span>
                    <span class="choice-desc">Ouvert temporairement jusqu'au {{ commissionCloseLabel }}</span>
                  </div>
                  <svg class="choice-arrow" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                </button>

                <div v-else class="closed-card">
                  <span>Formulaire fermé</span>
                  <strong>Les inscriptions des membres de commission sont clôturées.</strong>
                  <p>La consultation des membres déjà enregistrés reste disponible.</p>
                </div>

                <button type="button" class="choice-btn choice-btn-secondary" @click="showMembers">
                  <div class="choice-icon choice-icon-secondary">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                  </div>
                  <div class="choice-content">
                    <span class="choice-title">Voir les inscrits</span>
                    <span class="choice-desc">Filtrer par commission et départ Abidjan</span>
                  </div>
                  <svg class="choice-arrow" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="welcome-footer">
              <p>Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08</p>
              <p class="footer-slogan">AEEMCI, pour une identité islamique !</p>
            </div>
          </div>
        </section>

        <section v-else-if="commissionFormVisible && currentView === 'form'" class="step-content">
          <div class="step-card">
            <div class="step-hero">
              <div class="step-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div class="step-hero-text">
                <h2>Inscription membre de commission</h2>
                <p>Renseignez les informations du membre. Aucun paiement n'est demandé.</p>
              </div>
            </div>

            <div v-if="error" class="alert alert-error">{{ error }}</div>
            <div v-if="success" class="alert alert-success">{{ success }}</div>

            <form class="registration-form" @submit.prevent="submitMember">
              <div class="form-grid">
                <div class="form-group">
                  <label>Nom</label>
                  <input v-model="form.nom" required placeholder="Nom de famille" />
                </div>
                <div class="form-group">
                  <label>Prénom</label>
                  <input v-model="form.prenom" required placeholder="Prénom" />
                </div>
                <div class="form-group">
                  <label>Contact</label>
                  <input v-model="form.contact" required inputmode="tel" placeholder="Ex: 0700000000" />
                </div>
                <div class="form-group">
                  <label>Commission</label>
                  <select v-model="form.commission" required class="commission-filter">
                    <option value="">Choisir une commission</option>
                    <option v-for="commission in commissions" :key="commission" :value="commission">{{ commission }}</option>
                  </select>
                </div>
              </div>

              <div class="transport-card">
                <label class="toggle-line">
                  <input v-model="form.depart_abidjan" type="checkbox" />
                  <span class="toggle-control"></span>
                  <span>
                    <strong>Départ depuis Abidjan</strong>
                    <small>Cette information sert uniquement à organiser les cars. Le membre de commission ne paie rien.</small>
                  </span>
                  <b>Gratuit</b>
                </label>
              </div>

              <div class="photo-row">
                <div class="photo-preview">
                  <img v-if="form.photo" :src="form.photo" alt="" />
                  <span v-else>{{ initials }}</span>
                </div>
                <label class="upload-button">
                  <input type="file" accept="image/*" @change="uploadPhoto" />
                  <strong>{{ uploading ? `Upload ${uploadProgress}%` : 'Ajouter une photo' }}</strong>
                  <small>Photo utilisée sur le reçu, le badge et les listes.</small>
                </label>
              </div>

              <div class="step-nav">
                <button type="button" class="btn btn-outline" @click="currentView = 'home'">Retour</button>
                <button type="submit" class="btn btn-primary" :disabled="saving || uploading">
                  {{ saving ? 'Enregistrement...' : 'Enregistrer et générer le reçu' }}
                </button>
              </div>
            </form>
          </div>
        </section>

        <section v-else class="step-content">
          <div class="results-card">
            <div class="results-header">
              <div>
                <span class="quota-label">Liste des commissions</span>
                <h2>Membres inscrits</h2>
                <p>{{ filteredMembers.length }} résultat{{ filteredMembers.length > 1 ? 's' : '' }} affiché{{ filteredMembers.length > 1 ? 's' : '' }}</p>
              </div>
              <button class="btn btn-outline" type="button" @click="currentView = 'home'">Retour</button>
            </div>

            <div class="summary-row">
              <article><span>Total</span><strong>{{ stats.total }}</strong></article>
              <article><span>Départ Abidjan</span><strong>{{ stats.depart_abidjan || 0 }}</strong></article>
              <article><span>Commissions</span><strong>{{ activeCommissions }}</strong></article>
            </div>

            <div class="filters">
              <select v-model="selectedCommission" class="commission-filter" @change="loadMembers">
                <option value="">Toutes les commissions</option>
                <option v-for="commission in commissions" :key="commission" :value="commission">{{ commission }}</option>
              </select>
              <input v-model="search" placeholder="Rechercher par nom, matricule ou contact..." />
              <button class="btn btn-primary" type="button" @click="refresh">Actualiser</button>
            </div>

            <div class="commission-stats">
              <button v-for="item in commissionCounts" :key="item.commission" type="button" :class="{ active: selectedCommission === item.commission }" @click="setCommission(item.commission)">
                <span>{{ item.commission }}</span>
                <strong>{{ item.total }}</strong>
              </button>
            </div>

            <div v-if="loading" class="state">Chargement...</div>
            <div v-else-if="!filteredMembers.length" class="state">Aucun membre inscrit pour le moment.</div>
            <div v-else class="members-list">
              <article v-for="member in filteredMembers" :key="member.id" class="member-card">
                <img v-if="member.photo" :src="member.photo" alt="" />
                <div v-else class="avatar">{{ initialsFor(member) }}</div>
                <div class="member-main">
                  <strong>{{ member.prenom }} {{ member.nom }}</strong>
                  <span>{{ member.commission }} · {{ member.contact }}</span>
                  <small>{{ member.matricule }} <b v-if="Number(member.depart_abidjan) === 1">· Départ Abidjan</b></small>
                </div>
              </article>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, reactive, ref } from 'vue'
import senafoiBanner from '../../assets/senafoi-2026-banner.jpeg'

const COMMISSION_FORM_CLOSE_AT = '2026-08-31T23:59:00Z'

const API_URL = 'https://api.aeemci-ce.ci/senafoi/commissions_senafoi.php'
const UPLOAD_URL = 'https://api.aeemci-ce.ci/cloud/upload.php'
const CLOUD_API_KEY = 'SAKONE25_04_2026_medias'

const commissions = [
  'AMIR', '1er Vice-Amir', '2ème Vice-Amir', 'Vice-Amirate',
  'ADMINISTRATION', 'CADRE DE VIE', 'COMMUNICATION', 'FINANCE',
  'FORMATION', 'INFORMATIQUE', 'PEPINIERE', 'PROTOCOLE',
  'RESTAURATION', 'SANTE', 'SECURITE', 'TRANSPORT',
  'MANAGER GENERAL', 'PCO', 'PCO-A',
  'PCA', 'Membre du CA', 'CCC', 'CCC1', 'CCC2', 'Membre du conseil Consultatif',
]

const currentView = ref('home')
const members = ref([])
const stats = ref({ total: 0, depart_abidjan: 0, commissions: [] })
const selectedCommission = ref('')
const search = ref('')
const loading = ref(false)
const saving = ref(false)
const uploading = ref(false)
const uploadProgress = ref(0)
const error = ref('')
const success = ref('')
const form = reactive(defaultForm())
const nowTs = ref(Date.now())
let commissionTimer = null

const initials = computed(() => `${form.prenom?.[0] || ''}${form.nom?.[0] || ''}`.toUpperCase() || 'SF')
const activeCommissions = computed(() => stats.value.commissions?.filter((item) => Number(item.total) > 0).length || 0)
const commissionCloseAt = computed(() => new Date(COMMISSION_FORM_CLOSE_AT))
const commissionFormVisible = computed(() => nowTs.value < commissionCloseAt.value.getTime())
const commissionCloseLabel = computed(() => {
  return commissionCloseAt.value.toLocaleString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
})
const filteredMembers = computed(() => {
  const needle = search.value.trim().toLowerCase()
  if (!needle) return members.value
  return members.value.filter((member) => [member.nom, member.prenom, member.matricule, member.contact, member.commission]
    .some((value) => String(value || '').toLowerCase().includes(needle)))
})
const commissionCounts = computed(() => {
  const indexed = new Map((stats.value.commissions || []).map((item) => [item.commission, Number(item.total) || 0]))
  return commissions.map((commission) => ({ commission, total: indexed.get(commission) || 0 })).filter((item) => item.total > 0)
})

function defaultForm() {
  return { nom: '', prenom: '', contact: '', commission: '', photo: '', photo_id: '', depart_abidjan: false }
}

async function fetchJson(url, options = {}) {
  const res = await fetch(url, options)
  const data = await res.json().catch(() => ({}))
  if (!res.ok || !data.success) throw new Error(data.message || data.error || `HTTP ${res.status}`)
  return data
}

async function loadMembers() {
  loading.value = true
  error.value = ''
  try {
    const qs = selectedCommission.value ? `&commission=${encodeURIComponent(selectedCommission.value)}` : ''
    const data = await fetchJson(`${API_URL}?action=list${qs}&rand=${Date.now()}`)
    members.value = data.data || []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function loadStats() {
  const data = await fetchJson(`${API_URL}?action=stats&rand=${Date.now()}`)
  stats.value = data.data || { total: 0, depart_abidjan: 0, commissions: [] }
}

async function refresh() {
  await Promise.all([loadMembers(), loadStats()])
}

async function showMembers() {
  currentView.value = 'list'
  await refresh()
}

function setCommission(commission) {
  selectedCommission.value = selectedCommission.value === commission ? '' : commission
  loadMembers()
}

async function uploadPhoto(event) {
  const file = event.target.files?.[0]
  if (!file) return
  if (!file.type.startsWith('image/') || file.size > 5 * 1024 * 1024) {
    error.value = 'Veuillez choisir une image valide de moins de 5 Mo.'
    return
  }
  uploading.value = true
  uploadProgress.value = 0
  error.value = ''
  try {
    const body = new FormData()
    body.append('file', file)
    body.append('folder', 'senafoi')
    body.append('subfolder', 'commissions')
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
  if (!commissionFormVisible.value) {
    currentView.value = 'home'
    error.value = 'Le formulaire est fermé.'
    return
  }
  saving.value = true
  error.value = ''
  success.value = ''
  try {
    const data = await fetchJson(`${API_URL}?action=create`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form),
    })
    success.value = data.message || 'Membre enregistré.'
    Object.assign(form, defaultForm())
    await refresh()
    if (data.data) downloadReceipt(data.data)
  } catch (e) {
    error.value = e.message
  } finally {
    saving.value = false
  }
}

function initialsFor(member) {
  return `${member.prenom?.[0] || ''}${member.nom?.[0] || ''}`.toUpperCase()
}

function safeName(value) {
  return String(value || 'recu').normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9_-]+/gi, '_')
}

async function loadImage(url) {
  if (!url) return null
  try {
    const image = new Image()
    image.crossOrigin = 'anonymous'
    image.src = url
    await new Promise((resolve, reject) => {
      image.onload = resolve
      image.onerror = reject
    })
    return image
  } catch {
    return null
  }
}

async function downloadReceipt(member) {
  const { jsPDF } = await import('jspdf')
  const QRCode = await import('qrcode')
  const doc = new jsPDF('p', 'mm', 'a4')
  const pageWidth = doc.internal.pageSize.getWidth()
  const receiptNo = `RC-${member.matricule || Date.now()}`
  const date = new Date(String(member.created_at || Date.now()).replace(' ', 'T')).toLocaleDateString('fr-FR')
  const qrData = await QRCode.toDataURL(`SENAFOI 2026 | COMMISSION | ${member.matricule} | ${member.prenom} ${member.nom}`)

  doc.setFillColor(246, 248, 250)
  doc.rect(0, 0, pageWidth, 297, 'F')
  doc.setFillColor(20, 83, 45)
  doc.roundedRect(12, 12, pageWidth - 24, 52, 5, 5, 'F')

  const banner = await loadImage(senafoiBanner)
  if (banner) doc.addImage(banner, 'JPEG', 18, 18, 58, 38)

  doc.setTextColor(255, 255, 255)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(19)
  doc.text('RECU OFFICIEL D\'INSCRIPTION', pageWidth - 18, 29, { align: 'right' })
  doc.setFontSize(11)
  doc.text('Membre de commission SENAFOI 2026', pageWidth - 18, 39, { align: 'right' })
  doc.setFillColor(255, 255, 255)
  doc.roundedRect(pageWidth - 64, 45, 46, 11, 2, 2, 'F')
  doc.setTextColor(20, 83, 45)
  doc.setFontSize(9)
  doc.text(receiptNo, pageWidth - 41, 52, { align: 'center' })

  doc.setFillColor(255, 255, 255)
  doc.roundedRect(12, 72, pageWidth - 24, 154, 5, 5, 'F')
  doc.setDrawColor(226, 232, 240)
  doc.roundedRect(12, 72, pageWidth - 24, 154, 5, 5)

  const avatar = await loadImage(member.photo)
  if (avatar) doc.addImage(avatar, 'JPEG', 24, 88, 36, 36)
  else {
    doc.setFillColor(220, 252, 231)
    doc.circle(42, 106, 18, 'F')
    doc.setTextColor(20, 83, 45)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(16)
    doc.text(initialsFor(member), 42, 111, { align: 'center' })
  }

  doc.setTextColor(15, 23, 42)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(21)
  doc.text(`${member.prenom || ''} ${member.nom || ''}`.toUpperCase(), 70, 96, { maxWidth: 112 })
  doc.setFontSize(10)
  doc.setTextColor(100, 116, 139)
  doc.text('Inscription gratuite validée', 70, 106)
  doc.setFillColor(236, 253, 245)
  doc.roundedRect(70, 112, 62, 10, 3, 3, 'F')
  doc.setTextColor(4, 120, 87)
  doc.setFont('helvetica', 'bold')
  doc.text(member.matricule || '-', 101, 119, { align: 'center' })

  const rows = [
    ['Commission', member.commission || '-'],
    ['Contact', member.contact || '-'],
    ['Départ Abidjan', Number(member.depart_abidjan) === 1 ? 'Oui, à prendre en compte pour le car' : 'Non'],
    ['Montant payé', '0 FCFA'],
    ['Date inscription', date],
  ]
  let y = 142
  rows.forEach(([label, value]) => {
    doc.setFillColor(248, 250, 252)
    doc.roundedRect(24, y - 7, pageWidth - 48, 13, 2, 2, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setTextColor(71, 85, 105)
    doc.setFontSize(9)
    doc.text(label, 30, y + 1)
    doc.setFont('helvetica', 'normal')
    doc.setTextColor(15, 23, 42)
    doc.text(String(value), 82, y + 1, { maxWidth: 92 })
    y += 17
  })

  doc.addImage(qrData, 'PNG', pageWidth - 50, 178, 24, 24)
  doc.setFontSize(7)
  doc.setTextColor(100, 116, 139)
  doc.text('Vérification', pageWidth - 38, 207, { align: 'center' })

  doc.setFillColor(255, 251, 235)
  doc.roundedRect(22, 236, pageWidth - 44, 26, 4, 4, 'F')
  doc.setTextColor(146, 64, 14)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(10)
  doc.text('Note', 30, 247)
  doc.setFont('helvetica', 'normal')
  doc.text('Ce reçu confirme une inscription gratuite comme membre de commission SENAFOI 2026.', 46, 247, { maxWidth: 130 })

  doc.setDrawColor(20, 83, 45)
  doc.line(pageWidth - 70, 274, pageWidth - 22, 274)
  doc.setFont('helvetica', 'bold')
  doc.setTextColor(20, 83, 45)
  doc.text('Secrétariat SENAFOI', pageWidth - 46, 281, { align: 'center' })
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(8)
  doc.setTextColor(100, 116, 139)
  doc.text('AEEMCI, pour une identité islamique !', pageWidth / 2, 288, { align: 'center' })

  doc.save(`recu_commission_${safeName(member.matricule)}.pdf`)
}

onMounted(() => {
  loadStats()
  commissionTimer = window.setInterval(() => {
    nowTs.value = Date.now()
    if (!commissionFormVisible.value && currentView.value === 'form') {
      currentView.value = 'home'
    }
  }, 30000)
})

onUnmounted(() => {
  if (commissionTimer) window.clearInterval(commissionTimer)
})
</script>

<style scoped>
.app-container { min-height: 100vh; background: linear-gradient(135deg, var(--surface) 0%, var(--green-mist) 45%, var(--gold-mist) 100%); color: var(--ink); font-family: var(--display); }
.app-header { background: rgba(255,255,255,.94); border-bottom: 1px solid var(--line); box-shadow: var(--shadow); backdrop-filter: blur(12px); }
.header-content { max-width: 1200px; margin: 0 auto; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
.logo { display: flex; align-items: center; gap: 14px; }
.logo-icon { width: 48px; height: 48px; border-radius: 0; background: linear-gradient(135deg, var(--green), var(--green)); color: #fff; display: grid; place-items: center; box-shadow: var(--shadow); }
.logo-text h1 { margin: 0; font-size: 26px; color: var(--green); letter-spacing: .02em; }
.logo-text p { margin: 2px 0 0; color: var(--muted); font-size: 13px; }
.year-badge { padding: 9px 13px; border-radius: 999px; background: var(--green); color: #fff; font-weight: 900; box-shadow: var(--shadow); }
.main-content { max-width: 1120px; margin: 0 auto; padding: 34px 20px; }
.form-wrapper { }
.step-content { animation: fadeIn .25s ease; }
.welcome-card, .step-card, .results-card { background: #fff; border-radius: 0; box-shadow: var(--shadow); overflow: hidden; border: 1px solid rgba(226,232,240,.9); }
.senafoi-banner-hero { background: linear-gradient(135deg, var(--ink), var(--green)); display: grid; place-items: center; padding: 10px; }
.senafoi-banner-img { width: 100%; height: clamp(170px, 30vw, 300px); object-fit: contain; display: block; border-radius: 0; }
.quota-panel { margin: 18px; border-radius: 0; padding: 18px; display: flex; align-items: center; justify-content: space-between; gap: 18px; background: linear-gradient(135deg, var(--green), var(--green)); color: #fff; box-shadow: var(--shadow); }
.quota-label { display: inline-flex; font-size: 11px; text-transform: uppercase; letter-spacing: .08em; font-weight: 900; color: var(--green); }
.quota-panel .quota-label { color: var(--green-mist); }
.quota-copy strong { display: block; margin: 5px 0; font-size: 22px; }
.quota-copy small { opacity: .86; font-weight: 700; }
.quota-available { min-width: 92px; min-height: 82px; border-radius: 0; display: grid; place-items: center; text-align: center; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.24); }
.quota-available span { display: block; font-size: 34px; line-height: 1; font-weight: 950; }
.quota-available small { display: block; font-size: 11px; opacity: .88; }
.choice-section { padding: 18px 28px 30px; text-align: center; }
.choice-section h3 { margin: 0 0 8px; font-size: 28px; color: var(--ink); }
.choice-subtitle { margin: 0 0 24px; color: var(--muted); }
.choice-buttons { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
.closed-card { min-height: 132px; display: grid; align-content: center; gap: 8px; border: 2px solid #fed7aa; border-radius: 0; padding: 22px; background: linear-gradient(135deg, var(--gold-mist), #fff); }
.closed-card span { width: max-content; padding: 6px 10px; border-radius: 999px; background: var(--gold-mist); color: #c2410c; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em; }
.closed-card strong { color: var(--ink); font-size: 18px; line-height: 1.25; }
.closed-card p { margin: 0; color: var(--muted); font-size: 13px; line-height: 1.5; }
.choice-btn { border: 2px solid var(--line); border-radius: 0; padding: 22px; min-height: 132px; background: #fff; display: grid; grid-template-columns: 54px 1fr 20px; gap: 16px; align-items: center; text-align: left; cursor: pointer; transition: .22s ease; }
.choice-btn:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.choice-btn-primary { border-color: var(--green); background: linear-gradient(135deg, var(--green-mist), #fff); }
.choice-btn-secondary { background: linear-gradient(135deg, var(--gold-mist), #fff); border-color: #fde68a; }
.choice-icon { width: 54px; height: 54px; border-radius: 0; display: grid; place-items: center; }
.choice-icon-primary { background: var(--green-mist); color: var(--green); }
.choice-icon-secondary { background: var(--gold-mist); color: #b45309; }
.choice-title, .choice-desc { display: block; }
.choice-title { font-size: 17px; font-weight: 950; color: var(--ink); }
.choice-desc { margin-top: 5px; font-size: 13px; color: var(--muted); }
.choice-arrow { color: var(--muted); }
.welcome-footer { border-top: 1px solid #eef2f7; padding: 16px 24px; text-align: center; color: var(--muted); font-size: 13px; }
.welcome-footer p { margin: 3px 0; }
.footer-slogan { color: var(--green); font-weight: 900; }
.step-card, .results-card { padding: 26px; }
.step-hero { display: flex; gap: 16px; align-items: center; margin-bottom: 22px; padding-bottom: 18px; border-bottom: 1px solid #eef2f7; }
.step-icon { width: 60px; height: 60px; border-radius: 0; display: grid; place-items: center; color: #fff; background: linear-gradient(135deg, var(--green), var(--green)); box-shadow: var(--shadow); }
.step-hero-text h2, .results-header h2 { margin: 0; color: var(--ink); font-size: 30px; }
.step-hero-text p, .results-header p { margin: 5px 0 0; color: var(--muted); }
.registration-form { display: grid; gap: 18px; }
.form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
.form-group { display: grid; gap: 7px; }
.form-group label { font-weight: 900; font-size: 13px; color: var(--ink-soft); }
input, select { width: 100%; box-sizing: border-box; border: 2px solid var(--line); border-radius: 0; padding: 13px 14px; font: inherit; background: #fff; color: var(--ink); }
input:focus, select:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
.transport-card { border: 1px solid var(--green-mist); background: var(--green-mist); border-radius: 0; padding: 15px; }
.toggle-line { display: grid; grid-template-columns: auto auto 1fr auto; gap: 12px; align-items: center; cursor: pointer; }
.toggle-line input { width: auto; position: absolute; opacity: 0; pointer-events: none; }
.toggle-control { width: 48px; height: 28px; border-radius: 999px; background: #d1d5db; position: relative; transition: .2s; }
.toggle-control::after { content: ""; width: 22px; height: 22px; border-radius: 50%; background: #fff; position: absolute; top: 3px; left: 3px; transition: .2s; box-shadow: var(--shadow); }
.toggle-line input:checked + .toggle-control { background: var(--green); }
.toggle-line input:checked + .toggle-control::after { transform: translateX(20px); }
.toggle-line strong, .toggle-line small { display: block; }
.toggle-line small { color: var(--muted); margin-top: 3px; }
.toggle-line b { color: var(--green); }
.photo-row { display: grid; grid-template-columns: 86px 1fr; gap: 14px; align-items: center; }
.photo-preview, .avatar { display: grid; place-items: center; background: var(--green-mist); color: var(--green); font-weight: 950; }
.photo-preview { width: 86px; height: 86px; border-radius: 0; overflow: hidden; border: 3px solid var(--green-mist); }
.photo-preview img { width: 100%; height: 100%; object-fit: cover; }
.upload-button { border: 2px dashed var(--green-mist); border-radius: 0; padding: 16px; cursor: pointer; background: linear-gradient(135deg, #f7fee7, #fff); }
.upload-button input { display: none; }
.upload-button strong, .upload-button small { display: block; }
.upload-button small { margin-top: 4px; color: var(--muted); }
.step-nav { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 4px; }
.btn { border: 0; border-radius: 0; min-height: 46px; padding: 0 17px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-weight: 950; cursor: pointer; text-decoration: none; }
.btn-primary { background: linear-gradient(135deg, var(--green), var(--green)); color: #fff; box-shadow: var(--shadow); }
.btn-outline { background: #fff; color: var(--ink-soft); border: 2px solid var(--line); }
.btn:disabled { opacity: .65; cursor: wait; }
.alert { padding: 13px 15px; border-radius: 0; margin-bottom: 16px; font-weight: 850; }
.alert-error { background: #fef2f2; color: #b91c1c; }
.alert-success { background: var(--green-mist); color: var(--green); }
.results-header { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; margin-bottom: 18px; }
.summary-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; }
.summary-row article { border: 1px solid var(--line); border-radius: 0; padding: 16px; background: linear-gradient(135deg, var(--surface), #fff); }
.summary-row span { font-size: 12px; color: var(--muted); font-weight: 900; }
.summary-row strong { display: block; font-size: 30px; color: var(--ink); }
.filters { display: grid; grid-template-columns: 260px 1fr auto; gap: 12px; margin-bottom: 14px; }
.commission-stats { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
.commission-stats button { border: 1px solid var(--line); border-radius: 0; background: #fff; padding: 9px 11px; cursor: pointer; }
.commission-stats button.active { border-color: var(--green); background: var(--green-mist); }
.commission-stats span { display: block; font-size: 11px; font-weight: 950; color: var(--muted); }
.commission-stats strong { color: var(--green); }
.state { min-height: 220px; display: grid; place-items: center; color: var(--muted); font-weight: 950; }
.members-list { display: grid; gap: 10px; max-height: 580px; overflow: auto; padding-right: 4px; }
.member-card { display: grid; grid-template-columns: 56px 1fr; gap: 12px; align-items: center; border: 1px solid #eef2f7; border-radius: 0; padding: 12px; background: #fff; }
.member-card img, .avatar { width: 56px; height: 56px; border-radius: 0; object-fit: cover; }
.member-main strong, .member-main span, .member-main small { display: block; }
.member-main span { color: var(--muted); font-size: 13px; }
.member-main small { color: var(--green); font-weight: 950; margin-top: 3px; }
.receipt-btn { border: 0; min-height: 38px; border-radius: 0; padding: 0 13px; background: var(--green-mist); color: var(--green); font-weight: 950; cursor: pointer; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
@media (max-width: 780px) {
  .header-content { padding: 14px 16px; align-items: flex-start; }
  .logo-text h1 { font-size: 22px; }
  .main-content { padding: 16px; }
  .quota-panel, .results-header { display: grid; }
  .choice-buttons, .form-grid, .summary-row, .filters { grid-template-columns: 1fr; }
  .choice-btn { grid-template-columns: 48px 1fr; }
  .choice-arrow { display: none; }
  .step-card, .results-card { padding: 20px; }
  .step-hero { align-items: flex-start; }
  .photo-row { grid-template-columns: 1fr; }
  .member-card { grid-template-columns: 52px 1fr; }
}
</style>
