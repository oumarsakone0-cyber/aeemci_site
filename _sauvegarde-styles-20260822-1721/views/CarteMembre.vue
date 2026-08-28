<template>
  <div class="inscription-app">
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
            <p>Carte de Membre</p>
          </div>
        </div>
        <div class="progress-info">
          <span>Étape {{ currentStep + 1 }}/6</span>
          <div class="progress-bar">
            <div class="progress-fill" :style="{ width: progressWidth }"></div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Form -->
    <main class="form-main">
      <div class="form-container">
        <!-- Steps Navigation -->
        <nav class="steps-nav">
          <div v-for="(step, idx) in steps" :key="idx" 
               class="step-item"
               :class="{ active: currentStep === idx, completed: currentStep > idx }">
            <div class="step-circle">
              <span v-if="currentStep > idx">✓</span>
              <span v-else>{{ idx + 1 }}</span>
            </div>
            <span class="step-label">{{ step }}</span>
          </div>
        </nav>

        <!-- Form Content -->
        <form @submit.prevent="handleSubmit" class="form-body">
          <!-- Step 0: Type Membre -->
          <div v-show="currentStep === 0" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
              </svg>
              Type de Membre
            </h2>
            <div class="type-cards">
              <label class="type-card">
                <input v-model="form.type_membre" type="radio" value="ACTUEL" required>
                <div class="card-inner">
                  <div class="card-icon">👤</div>
                  <h3>Membre Actuel</h3>
                  <p>Vous êtes actuellement membre actif</p>
                </div>
              </label>
              <label class="type-card">
                <input v-model="form.type_membre" type="radio" value="ANCIEN" required>
                <div class="card-inner">
                  <div class="card-icon">🎖️</div>
                  <h3>Ancien Membre</h3>
                  <p>Vous avez été membre par le passé</p>
                </div>
              </label>
            </div>
            <div v-if="form.type_membre === 'ANCIEN'" class="info-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
              </svg>
              <div>
                <strong>Information</strong>
                <p>Vos postes seront préfixés par "Past"</p>
              </div>
            </div>
          </div>

          <!-- Step 1: Infos Personnelles -->
          <div v-show="currentStep === 1" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Informations Personnelles
            </h2>
            <div class="form-grid">
              <div class="form-field">
                <label>Prénom <span class="req">*</span></label>
                <input v-model="form.prenom" @input="formatFirstUpper('prenom')" type="text" required>
              </div>
              <div class="form-field">
                <label>Nom <span class="req">*</span></label>
                <input v-model="form.nom" @input="formatUpper('nom')" type="text" required>
              </div>
              <div class="form-field">
                <label>Date de Naissance <span class="req">*</span></label>
                <input v-model="form.dateNaissance" type="date" required>
              </div>
              <div class="form-field">
                <label>Lieu de Naissance <span class="req">*</span></label>
                <input v-model="form.lieuNaissance" @input="formatUpper('lieuNaissance')" type="text" required>
              </div>
              <div class="form-field">
                <label>Contact <span class="req">*</span></label>
                <input v-model="form.contact" @input="formatPhone($event, 'contact')" type="tel" required maxlength="14">
              </div>
              <div class="form-field">
                <label>Sexe <span class="req">*</span></label>
                <div class="radio-group">
                  <label><input v-model="form.sexe" type="radio" value="M" required> Masculin</label>
                  <label><input v-model="form.sexe" type="radio" value="F" required> Féminin</label>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 2: Militantisme -->
          <div v-show="currentStep === 2" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              Militantisme
            </h2>
            <div class="form-grid">
              <div class="form-field fullwidth">
                <label>Secrétariat Régional de début <span class="req">*</span></label>
                <select v-model="form.srDebut" required>
                  <option value="">-- Sélectionner --</option>
                  <option v-for="sr in secretariats" :key="sr">{{ sr }}</option>
                </select>
              </div>
              <div class="form-field">
                <label>Sous-comité de début <span class="req">*</span></label>
                <input v-model="form.sousComite" @input="formatUpper('sousComite')" type="text" required>
              </div>
              <div class="form-field">
                <label>Année de début <span class="req">*</span></label>
                <input v-model="form.anneeDebut" type="text" required maxlength="4">
              </div>
            </div>
          </div>

          <!-- Step 3: Statut & Poste -->
          <div v-show="currentStep === 3" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
              </svg>
              Statut et Poste
            </h2>
            <div class="form-grid">
              <div class="form-field fullwidth">
                <label>Organes <span class="req">*</span></label>
                <div class="radio-grid">
                  <label><input v-model="form.organe" @change="resetStatut" type="radio" value="COMITE_EXECUTIF" required> Organes centraux, CNP</label>
                  <label><input v-model="form.organe" @change="resetStatut" type="radio" value="SECRETARIAT_REGIONAL" required> Secrétariat Régional</label>
                  <label><input v-model="form.organe" @change="resetStatut" type="radio" value="SOUS_COMITE" required> Sous-comité</label>
                  <label><input v-model="form.organe" @change="resetStatut" type="radio" value="SECTION" required> Section</label>
                </div>
              </div>

              <div v-if="form.organe" class="form-field fullwidth">
                <label>Je suis <span class="req">*</span></label>
                <select v-model="form.statut" @change="updatePosteOptions" required>
                  <option value="">-- Sélectionner --</option>
                  <option v-for="st in getStatutOptions" :key="st.value" :value="st.value">{{ st.label }}</option>
                </select>
              </div>

              <!-- Champs conditionnels selon le statut -->
              <template v-if="needsSecretariat">
                <div class="form-field">
                  <label>Secrétariat <span class="req">*</span></label>
                  <select v-model="form.secretariatPoste" required>
                    <option value="">-- Sélectionner --</option>
                    <option v-for="sr in secretariats" :key="sr">{{ sr }}</option>
                  </select>
                </div>
              </template>

              <template v-if="needsCommuneVille">
                <div class="form-field">
                  <label>Sous-comité (Commune/Ville) <span class="req">*</span></label>
                  <input v-model="form.communeVille" @input="formatUpper('communeVille')" type="text" required>
                </div>
              </template>

              <template v-if="needsSection">
                <div class="form-field">
                  <label>Section <span class="req">*</span></label>
                  <input v-model="form.section" @input="formatUpper('section')" type="text" required>
                </div>
              </template>

              <template v-if="needsPoste">
                <div class="form-field fullwidth">
                  <label>Poste <span class="req">*</span></label>
                  <select v-model="form.poste" required>
                    <option value="">-- Sélectionner --</option>
                    <option v-for="p in getPosteOptions" :key="p">{{ p }}</option>
                  </select>
                </div>
              </template>
            </div>
          </div>

          <!-- Step 4: Photo -->
          <div v-show="currentStep === 4" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 2l3 3h4c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V7c0-1.1.9-2 2-2h3l3-3zm3 15c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z"/>
              </svg>
              Photo d'Identité
            </h2>
            <div class="photo-section">
              <input ref="photoInput" type="file" @change="uploadPhoto" accept="image/*" hidden>
              <div v-if="!form.photo && !isUploading" @click="$refs.photoInput.click()" class="photo-placeholder">
                <div class="upload-icon">📸</div>
                <p>Cliquez pour ajouter une photo</p>
                <small>JPG, PNG (max 5MB)</small>
              </div>
              <div v-if="isUploading" class="uploading">
                <div class="spinner"></div>
                <p>Upload en cours... {{ uploadProgress }}%</p>
              </div>
              <div v-if="form.photo && !isUploading" class="photo-preview">
                <img :src="form.photo" alt="Photo">
                <button @click="removePhoto" type="button" class="remove-btn">×</button>
              </div>
            </div>
          </div>

          <!-- Step 5: Paiement -->
          <div v-show="currentStep === 5" class="form-step">
            <h2 class="step-title">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
              </svg>
              Paiement
            </h2>
            <div class="payment-box">
              <div class="price-display">
                <span class="price-amount">1 000</span>
                <span class="price-currency">FCFA</span>
              </div>
              <div class="form-field">
                <label>Numéro Wave <span class="req">*</span></label>
                <input v-model="form.numeroWave" @input="formatPhone($event, 'numeroWave')" type="tel" required maxlength="14" placeholder="07 XX XX XX XX">
              </div>
              <small>Le numéro doit être associé à un compte Wave actif</small>
            </div>
          </div>

          <!-- Navigation -->
          <div class="form-actions">
            <button v-if="currentStep > 0" @click="prevStep" type="button" class="btn btn-back">
              ← Précédent
            </button>
            <div v-else></div>
            <button v-if="currentStep < 5" @click="nextStep" type="button" class="btn btn-next" :disabled="!canProceed">
              Suivant →
            </button>
            <button v-else type="submit" class="btn btn-submit" :disabled="!canSubmit || isSubmitting">
              <span v-if="isSubmitting" class="spinner-small"></span>
              {{ isSubmitting ? 'Traitement...' : 'Finaliser' }}
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const currentStep = ref(0)
const isSubmitting = ref(false)
const isUploading = ref(false)
const uploadProgress = ref(0)
const photoInput = ref(null)

const steps = ['Type', 'Infos', 'Militantisme', 'Statut', 'Photo', 'Paiement']

const form = ref({
  type_membre: '',
  prenom: '',
  nom: '',
  dateNaissance: '',
  lieuNaissance: '',
  contact: '',
  sexe: '',
  srDebut: '',
  sousComite: '',
  anneeDebut: '',
  organe: '',
  statut: '',
  secretariatPoste: '',
  poste: '',
  communeVille: '',
  section: '',
  photo: null,
  numeroWave: ''
})

const secretariats = [
  'ABIDJAN NORD', 'ABIDJAN SUD', 'ABIDJAN EST', 'ABIDJAN OUEST',
  'AGNEBY-TIASSA ET ME', 'BAGOUE', 'BELIER', 'BERE', 'BAFING', 'BOUNKANI',
  'CAVALLY', 'FOLON', 'GBEKE', 'GBOKLE', 'GOH', 'GONTOUGO',
  'GRANDS-PONTS', 'N\'ZI', 'HAUT-SASSANDRA', 'IFFOU', 'INDENIE-DJUABLIN',
  'KABADOUGOU', 'LOH-DJIBOUA', 'NAWA', 'MORONOU', 'PORO', 'WORODOUGOU',
  'MARAHOUE', 'TCHOLOGO', 'TONPKI', 'SAN-PEDRO', 'SUD COMOE', 'GUEMON', 'HAMBOL'
]

const postesGeneraux = [
  'Président', 'Vice-Président', 'Vice-Présidente', 'Commissaire aux Comptes',
  'Secrétaire Général', 'Secrétaire Général adjoint', 'Trésorier Général',
  'Trésorier Général adjoint', 'Délégué Culturel', 'Délégué Culturel adjoint',
  'Délégué Social', 'Délégué Social Adjoint',
  'Délégué aux activités Sociales, Sportives et Environnementales',
  'Délégué aux activités Sociales, Sportives et Environnementales adjoint',
  'Délégué à la Mobilisation et à l\'Organisation',
  'Délégué à la Mobilisation et à l\'Organisation adjoint'
]

const postesCE = [
  'AMIR', 'Vice-Amir', 'Vice Amirate', 'SENAFI', 'SENAFOCI', 'SENAFAD',
  'SENACEF', 'SENAMO', 'SENACREX', 'SENASIP', 'SENAES', 'Membre CRP'
]

const postesSR = [
  'SR', 'Vice-SR', 'SERAFAD', 'SERAFI', 'SERAFOCI', 'SERACEF',
  'SERAMO', 'SERACREX', 'SERASIP', 'SERAES', 'Membre CRP', 'Commissaire aux Comptes Régional'
]

const progressWidth = computed(() => ((currentStep.value + 1) / 6 * 100) + '%')

const getStatutOptions = computed(() => {
  const organe = form.value.organe
  if (organe === 'COMITE_EXECUTIF') {
    return [
      { value: 'MEMBRE_CE', label: 'Membre du Comité Exécutif' },
      { value: 'MEMBRE_CCC', label: 'Membre du Commissariat Central aux Comptes' },
      { value: 'MEMBRE_CA', label: 'Membre du Conseil d\'Administration' },
      { value: 'MEMBRE_CC', label: 'Membre du Conseil Consultatif' },
      { value: 'MEMBRE_CP', label: 'Membre du Conseil National Pédagogique' }
    ]
  }
  if (organe === 'SECRETARIAT_REGIONAL') {
    return [
      { value: 'SR', label: 'Membre du Secrétariat Régional' },
      { value: 'IMAM_REGIONAL', label: 'Imam Régional' },
      { value: 'MEMBRE_CRP', label: 'Membre du Conseil Régional Pédagogique' }
    ]
  }
  if (organe === 'SOUS_COMITE') {
    return [
      { value: 'SOUS_COMITE', label: 'Membre du bureau (Sous-comité)' },
      { value: 'IMAM_SOUS_COMITE', label: 'Imam de Sous-comité' },
      { value: 'FORMATEUR_SOUS_COMITE', label: 'Formateur de Sous-comité' },
      { value: 'MEMBRE_CELLULE_SOUS_COMITE', label: 'Membre d\'une Cellule Spécialisée' }
    ]
  }
  if (organe === 'SECTION') {
    return [
      { value: 'BUREAU_SECTION', label: 'Membre du bureau (Section)' },
      { value: 'MEMBRE_SIMPLE', label: 'Membre simple' },
      { value: 'IMAM_SECTION', label: 'Imam de Section' },
      { value: 'FORMATEUR_SECTION', label: 'Formateur de section' },
      { value: 'MEMBRE_CELLULE_SECTION', label: 'Membre d\'une Cellule Spécialisée' }
    ]
  }
  return []
})

const needsSecretariat = computed(() => {
  return ['SR', 'IMAM_REGIONAL', 'MEMBRE_CRP', 'SOUS_COMITE', 'IMAM_SOUS_COMITE', 
          'FORMATEUR_SOUS_COMITE', 'MEMBRE_CELLULE_SOUS_COMITE', 'BUREAU_SECTION',
          'MEMBRE_SIMPLE', 'IMAM_SECTION', 'FORMATEUR_SECTION', 'MEMBRE_CELLULE_SECTION']
         .includes(form.value.statut)
})

const needsCommuneVille = computed(() => {
  return ['SOUS_COMITE', 'IMAM_SOUS_COMITE', 'FORMATEUR_SOUS_COMITE', 
          'MEMBRE_CELLULE_SOUS_COMITE', 'BUREAU_SECTION', 'MEMBRE_SIMPLE',
          'IMAM_SECTION', 'FORMATEUR_SECTION', 'MEMBRE_CELLULE_SECTION']
         .includes(form.value.statut)
})

const needsSection = computed(() => {
  return ['BUREAU_SECTION', 'MEMBRE_SIMPLE', 'IMAM_SECTION', 
          'FORMATEUR_SECTION', 'MEMBRE_CELLULE_SECTION']
         .includes(form.value.statut)
})

const needsPoste = computed(() => {
  return ['MEMBRE_CE', 'MEMBRE_CA', 'MEMBRE_CCC', 'MEMBRE_CC', 'MEMBRE_CP',
          'SR', 'SOUS_COMITE', 'BUREAU_SECTION'].includes(form.value.statut)
})

const getPosteOptions = computed(() => {
  const statut = form.value.statut
  if (['MEMBRE_CE', 'MEMBRE_CCC'].includes(statut)) return postesCE
  if (statut === 'MEMBRE_CA') return ['PCA', 'Administrateur', 'Administratrice']
  if (statut === 'MEMBRE_CC') return ['Président du Conseil Consultatif', 'Membre du Conseil Consultatif']
  if (statut === 'MEMBRE_CP') return ['Président du CNP', 'Membre du CNP']
  if (statut === 'SR') return postesSR
  if (['SOUS_COMITE', 'BUREAU_SECTION'].includes(statut)) return postesGeneraux
  return []
})

const canProceed = computed(() => {
  switch (currentStep.value) {
    case 0: return form.value.type_membre
    case 1: return form.value.prenom && form.value.nom && form.value.dateNaissance && 
                   form.value.lieuNaissance && form.value.contact && form.value.sexe
    case 2: return form.value.srDebut && form.value.sousComite && form.value.anneeDebut
    case 3: return form.value.organe && form.value.statut && validateStatutFields()
    case 4: return form.value.photo
    case 5: return form.value.numeroWave
    default: return false
  }
})

const canSubmit = computed(() => canProceed.value)

const validateStatutFields = () => {
  if (needsSecretariat.value && !form.value.secretariatPoste) return false
  if (needsCommuneVille.value && !form.value.communeVille) return false
  if (needsSection.value && !form.value.section) return false
  if (needsPoste.value && !form.value.poste) return false
  return true
}

const formatUpper = (field) => {
  form.value[field] = form.value[field].toUpperCase()
}

const formatFirstUpper = (field) => {
  const val = form.value[field]
  if (val) form.value[field] = val.charAt(0).toUpperCase() + val.slice(1)
}

const formatPhone = (e, field) => {
  let val = e.target.value.replace(/\D/g, '')
  if (val.length >= 2) val = val.substring(0,2) + ' ' + val.substring(2)
  if (val.length >= 5) val = val.substring(0,5) + ' ' + val.substring(5)
  if (val.length >= 8) val = val.substring(0,8) + ' ' + val.substring(8)
  if (val.length >= 11) val = val.substring(0,11) + ' ' + val.substring(11)
  form.value[field] = val
}

const resetStatut = () => {
  form.value.statut = ''
  form.value.poste = ''
  form.value.secretariatPoste = ''
  form.value.communeVille = ''
  form.value.section = ''
}

const updatePosteOptions = () => {
  form.value.poste = ''
  form.value.secretariatPoste = ''
  form.value.communeVille = ''
  form.value.section = ''
}

const uploadPhoto = async (e) => {
  const file = e.target.files[0]
  if (!file) return
  
  if (file.size > 5 * 1024 * 1024) {
    alert('Fichier trop volumineux (max 5MB)')
    return
  }
  
  isUploading.value = true
  uploadProgress.value = 0
  
  const formData = new FormData()
  formData.append('file', file)
  formData.append('upload_preset', 'dwuzzeked')
  
  try {
    const res = await axios.post(
      'https://api.cloudinary.com/v1_1/dwuzzeked/upload',
      formData,
      {
        onUploadProgress: (progressEvent) => {
          uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        }
      }
    )
    form.value.photo = res.data.secure_url
  } catch (err) {
    alert('Erreur lors de l\'upload')
  } finally {
    isUploading.value = false
  }
}

const removePhoto = () => {
  form.value.photo = null
  if (photoInput.value) photoInput.value.value = ''
}

const nextStep = () => {
  if (canProceed.value && currentStep.value < 5) {
    currentStep.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const prevStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const handleSubmit = async () => {
  if (!canSubmit.value) return
  
  isSubmitting.value = true
  try {
    const res = await axios.post('https://api.aeemci-ce.ci/payer.php', {
      amount: 1000,
      currency: 'XOF',
      data: form.value
    })
    if (res.data.wave_launch_url) {
      window.location.href = res.data.wave_launch_url
    } else if (res.data.checkout_url) {
      window.location.href = res.data.checkout_url
    }
  } catch (err) {
    console.error('Erreur:', err)
    alert('Une erreur est survenue')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@300;400;600;700;900&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

.inscription-app {
  min-height: 100vh;
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  font-family: 'Zain', sans-serif;
}

/* Header */
.app-header {
  background: white;
  border-bottom: 2px solid #e5e7eb;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-wrapper {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1.5rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo-badge {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #018404, #016a03);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.logo-text h1 {
  font-size: 2rem;
  font-weight: 900;
  color: #018404;
  margin: 0;
}

.logo-text p {
  font-size: 0.95rem;
  color: #6b7280;
  margin: 0;
}

.progress-info {
  text-align: right;
}

.progress-info span {
  font-size: 0.9rem;
  font-weight: 600;
  color: #6b7280;
  display: block;
  margin-bottom: 0.5rem;
}

.progress-bar {
  width: 200px;
  height: 8px;
  background: #e5e7eb;
  border-radius: 10px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #018404, #16a34a);
  transition: width 0.3s ease;
}

/* Main */
.form-main {
  max-width: 1000px;
  margin: 3rem auto;
  padding: 0 1.5rem;
}

.form-container {
  background: white;
  border-radius: 24px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  overflow: hidden;
}

/* Steps Nav */
.steps-nav {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 2rem 1.5rem;
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  overflow-x: auto;
}

.step-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  min-width: 80px;
  opacity: 0.5;
  transition: all 0.3s ease;
}

.step-item.active, .step-item.completed { opacity: 1; }

.step-circle {
  width: 50px;
  height: 50px;
  background: white;
  border: 3px solid #d1d5db;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: #6b7280;
  transition: all 0.3s ease;
}

.step-item.active .step-circle {
  background: #018404;
  border-color: #018404;
  color: white;
  transform: scale(1.1);
}

.step-item.completed .step-circle {
  background: #16a34a;
  border-color: #16a34a;
  color: white;
}

.step-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #6b7280;
}

.step-item.active .step-label { color: #018404; }

/* Form Body */
.form-body { padding: 3rem; }

.form-step { min-height: 400px; }

.step-title {
  display: flex;
  align-items: center;
  gap: 1rem;
  font-size: 2rem;
  font-weight: 900;
  color: #1a1a1a;
  margin-bottom: 3rem;
  text-align: center;
  justify-content: center;
}

.step-title svg { color: #018404; }

/* Type Cards */
.type-cards {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
}

.type-card { cursor: pointer; }
.type-card input { display: none; }

.card-inner {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border: 3px solid transparent;
  border-radius: 20px;
  padding: 3rem 2rem;
  text-align: center;
  transition: all 0.3s ease;
}

.type-card:hover .card-inner {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(1,132,4,0.15);
}

.type-card input:checked + .card-inner {
  background: linear-gradient(135deg, rgba(1,132,4,0.1), rgba(251,146,60,0.1));
  border-color: #018404;
}

.card-icon { font-size: 4rem; margin-bottom: 1rem; }

.card-inner h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #018404;
  margin-bottom: 0.5rem;
}

.card-inner p { color: #6b7280; }

.info-box {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  background: rgba(14,165,233,0.1);
  border: 2px solid rgba(14,165,233,0.3);
  border-radius: 12px;
  padding: 1.5rem;
  margin-top: 2rem;
}

.info-box svg { color: #0ea5e9; flex-shrink: 0; }
.info-box strong { display: block; color: #0c4a6e; margin-bottom: 0.25rem; }
.info-box p { color: #075985; margin: 0; }

/* Form Grid */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
}

.form-field { display: flex; flex-direction: column; gap: 0.75rem; }
.form-field.fullwidth { grid-column: 1 / -1; }

.form-field label {
  font-weight: 600;
  color: #374151;
  font-size: 0.95rem;
}

.req { color: #dc2626; }

.form-field input,
.form-field select {
  padding: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 1rem;
  font-family: 'Zain', sans-serif;
  transition: all 0.3s ease;
}

.form-field input:focus,
.form-field select:focus {
  outline: none;
  border-color: #018404;
  box-shadow: 0 0 0 4px rgba(1,132,4,0.1);
}

.radio-group {
  display: flex;
  gap: 2rem;
}

.radio-group label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-weight: 500;
}

.radio-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.radio-grid label {
  padding: 1rem;
  background: rgba(1,132,4,0.05);
  border: 2px solid transparent;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.radio-grid label:hover {
  background: rgba(1,132,4,0.1);
  border-color: #018404;
}

/* Photo */
.photo-section {
  max-width: 500px;
  margin: 0 auto;
}

.photo-placeholder {
  border: 3px dashed #d1d5db;
  border-radius: 20px;
  padding: 4rem 2rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.photo-placeholder:hover {
  border-color: #018404;
  background: rgba(1,132,4,0.05);
}

.upload-icon { font-size: 5rem; margin-bottom: 1rem; }

.uploading {
  text-align: center;
  padding: 3rem;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #e5e7eb;
  border-top-color: #018404;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin { to { transform: rotate(360deg); } }

.photo-preview {
  position: relative;
}

.photo-preview img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  border-radius: 20px;
  border: 3px solid #018404;
}

.remove-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 40px;
  height: 40px;
  background: #dc2626;
  color: white;
  border: none;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
}

/* Payment */
.payment-box {
  max-width: 500px;
  margin: 0 auto;
  text-align: center;
}

.price-display {
  background: linear-gradient(135deg, #018404, #16a34a);
  color: white;
  padding: 2rem;
  border-radius: 20px;
  margin-bottom: 2rem;
}

.price-amount {
  font-size: 4rem;
  font-weight: 900;
  display: block;
}

.price-currency {
  font-size: 1.5rem;
  font-weight: 600;
}

/* Actions */
.form-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 2px solid #e5e7eb;
}

.btn {
  padding: 1rem 2rem;
  border-radius: 50px;
  font-size: 1rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: 'Zain', sans-serif;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-back {
  background: white;
  color: #018404;
  border: 2px solid #018404;
}

.btn-back:hover {
  background: #018404;
  color: white;
}

.btn-next, .btn-submit {
  background: linear-gradient(135deg, #018404, #16a34a);
  color: white;
  box-shadow: 0 8px 25px rgba(1,132,4,0.3);
}

.btn-next:hover, .btn-submit:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(1,132,4,0.4);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.spinner-small {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

/* Responsive */
@media (max-width: 768px) {
  .header-wrapper {
    flex-direction: column;
    gap: 1.5rem;
    padding: 1rem;
  }
  
  .progress-bar { width: 100%; max-width: 300px; }
  
  .form-body { padding: 2rem 1.5rem; }
  
  .form-grid, .type-cards, .radio-grid {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
    gap: 1rem;
  }
  
  .form-actions .btn { width: 100%; justify-content: center; }
  
  .step-title { font-size: 1.5rem; flex-direction: column; }
}

@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
</style>