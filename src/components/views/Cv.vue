<template>
  <div class="app-container">
    <!-- Header -->
    <header class="app-header">
      <div class="header-content">
        <div class="header-left">
          <div class="logo">
            <div class="logo-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 2 2h12c1.11 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
              </svg>
            </div>
            <div class="logo-text">
              <h1>AEEMCI</h1>
              <p>Collecte des CV - Candidatures</p>
            </div>
          </div>
        </div>
        <div class="header-right">
          <p class="step-info">Formulaire de candidature</p>
          <div class="progress-bar">
            <div
              class="progress-fill"
              :style="{ width: `${formProgress}%` }"
            ></div>
          </div>
        </div>
      </div>
    </header>
    <!-- Main Content -->
    <main class="main-content">
      <div class="form-container">
        <div class="form-content">
          <form @submit.prevent="submitForm">
            <div class="step-content">
              <h2 class="step-heading">
                <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                </svg>
                Informations du Candidat
              </h2>

              <!-- Nom et Prénom -->
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Prénom <span class="required">*</span></label>
                  <div class="input-container">
                    <input
                      v-model="formData.prenom"
                      @input="formatUpperCase('prenom')"
                      type="text"
                      required
                      class="form-input"
                      placeholder="Votre prénom"
                    />
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label">Nom <span class="required">*</span></label>
                  <div class="input-container">
                    <input
                      v-model="formData.nom"
                      @input="formatUpperCase('nom')"
                      type="text"
                      required
                      class="form-input"
                      placeholder="Votre nom"
                    />
                  </div>
                </div>
              </div>

              <!-- Niveau d'études -->
              <div class="form-group form-group-full">
                <label class="form-label">Niveau d'études <span class="required">*</span></label>
                <div class="input-container">
                  <select
                    v-model="formData.niveauEtudes"
                    required
                    class="form-input"
                  >
                    <option value="">Sélectionnez votre niveau</option>
                    <option value="BEPC">BEPC</option>
                    <option value="Terminal">Terminal</option>
                    <option value="BAC">Baccalauréat</option>
                    <option value="BAC+1">Bac+1</option>
                    <option value="BAC+2">Bac+2 (BTS/DUT)</option>
                    <option value="BAC+3">Bac+3 (Licence)</option>
                    <option value="BAC+4">Bac+4 (Maîtrise)</option>
                    <option value="BAC+5">Bac+5 (Master/Ingénieur)</option>
                    <option value="BAC+8">Bac+8 (Doctorat)</option>
                    <option value="AUTRE">Autre</option>
                  </select>
                </div>
              </div>

              <!-- Ville de résidence -->
              <div class="form-group form-group-full">
                <label class="form-label">Ville de résidence <span class="required">*</span></label>
                <div class="input-container">
                  <input
                    v-model="formData.villeResidence"
                    @input="formatUpperCase('villeResidence')"
                    type="text"
                    required
                    class="form-input"
                    placeholder="Votre ville de résidence"
                  />
                </div>
              </div>

              <!-- Contact -->
              <div class="form-group form-group-full">
                <label class="form-label">Contact (Téléphone) <span class="required">*</span></label>
                <div class="input-container">
                  <input
                    v-model="formData.contact"
                    @input="formatPhoneNumber"
                    type="tel"
                    required
                    class="form-input"
                    placeholder="07 12 34 56 78"
                  />
                </div>
              </div>

              <!-- Militant AEEMCI -->
              <div class="form-group form-group-full">
                <label class="form-label">Êtes-vous militant de l'AEEMCI ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input
                      v-model="formData.estMilitant"
                      type="radio"
                      value="OUI"
                      class="radio-input"
                      required
                    />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Oui</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input
                      v-model="formData.estMilitant"
                      type="radio"
                      value="NON"
                      class="radio-input"
                      required
                    />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Non</span>
                  </label>
                </div>
              </div>

              <!-- Champs conditionnels pour militants -->
              <div v-if="formData.estMilitant === 'OUI'" class="militant-fields">
                <div class="form-row">
                  <div class="form-group">
                    <label class="form-label">Poste occupé <span class="required">*</span></label>
                    <div class="input-container">
                      <select
                        v-model="formData.poste"
                        required
                        class="form-input"
                      >
                        <option value="">Sélectionnez votre poste</option>
                        <optgroup label="Postes Section / S-C">
                          <option value="Président">Président</option>
                          <option value="Vice-Président">Vice-Président</option>
                          <option value="Vice-Présidente">Vice-Présidente</option>
                          <option value="Commissaire aux Comptes">Commissaire aux Comptes</option>
                          <option value="Secrétaire Général">Secrétaire Général</option>
                          <option value="Secrétaire Général adjoint">Secrétaire Général adjoint</option>
                          <option value="Trésorier Général">Trésorier Général</option>
                          <option value="Trésorier Général adjoint">Trésorier Général adjoint</option>
                          <option value="Délégué Culturel">Délégué Culturel</option>
                          <option value="Délégué Culturel adjoint">Délégué Culturel adjoint</option>
                          <option value="Délégué Social">Délégué Social</option>
                          <option value="Délégué Social Adjoint">Délégué Social Adjoint</option>
                          <option value="Délégué aux activités Sociales, Sportives et Environnementales">Délégué aux activités Sociales, Sportives et Environnementales</option>
                          <option value="Délégué aux activités Sociales, Sportives et Environnementales adjoint">Délégué aux activités Sociales, Sportives et Environnementales adjoint</option>
                          <option value="Délégué à la Mobilisation et à l'Organisation">Délégué à la Mobilisation et à l'Organisation</option>
                          <option value="Délégué à la Mobilisation et à l'Organisation adjoint">Délégué à la Mobilisation et à l'Organisation adjoint</option>
                        </optgroup>
                        <optgroup label="Postes Régionaux">
                          <option value="SR">Secrétaire Régional</option>
                          <option value="Vice-SR">Vice Secrétaire Régional</option>
                          <option value="SERAFAD">Secrétaire Régional chargé des Affaires Administratives</option>
                          <option value="SERAFI">Secrétaire Régional chargé des Affaires financières</option>
                          <option value="SERAFOCI">Secrétaire Régional chargé de la Formation, de la Culture et des questions Idéologiques</option>
                          <option value="SERAFOCUS">Secrétaire Régional chargé de la Formation, de la Culture et du Sport</option>
                          <option value="SERACEF">Secrétaire Régional chargé des Cellules Féminines</option>
                          <option value="SERAFS">Secrétaire Régional chargé des Affaires Sociales</option>
                          <option value="SERAFSS">Secrétaire Régional chargé des Affaires Sociales et Sportives</option>
                          <option value="SERAMO">Secrétaire Régional chargé de la Mobilisation et de l'Organisation</option>
                          <option value="SERACREX">Secrétaire Régional chargé de la Communication et des Relations Extérieures</option>
                          <option value="Commissaire aux Comptes Régional">Commissaire aux Comptes Régional</option>
                          <option value="Imam Régional">Imam Régional</option>
                          <option value="SERASIP">Secrétaire Régional chargé des Affaires Sociales et de l'Insertion Professionnelle</option>
                          <option value="SERAES">Secrétaire Régional chargé de l'Environnement et du Sport</option>
                        </optgroup>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Matricule </label>
                    <div class="input-container">
                      <input
                        v-model="formData.matricule"
                        type="text"
                        class="form-input"
                        placeholder="Votre matricule"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Upload CV -->
              <div class="form-group form-group-full">
                <label class="form-label">Télécharger votre CV (PDF uniquement) <span class="required">*</span></label>
                <div class="cv-upload">
                  <input
                    ref="cvInput"
                    type="file"
                    accept=".pdf"
                    @change="uploadCV"
                    class="cv-input"
                    required
                  />
                  <div v-if="!formData.cvUrl && !isUploading" @click="$refs.cvInput.click()" class="cv-placeholder">
                    <svg class="upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 2 2h12c1.11 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                    </svg>
                    <p>Cliquez pour ajouter votre CV</p>
                    <small>Format PDF uniquement (max 10MB)</small>
                  </div>
                  <div v-if="isUploading" class="upload-loading">
                    <div class="upload-progress-container">
                      <div class="progress-circle">
                        <svg class="progress-ring" width="60" height="60">
                          <circle
                            class="progress-ring-circle"
                            stroke="#e5e7eb"
                            stroke-width="4"
                            fill="transparent"
                            r="26"
                            cx="30"
                            cy="30"
                          />
                          <circle
                            class="progress-ring-circle progress-ring-fill"
                            stroke="#16a34a"
                            stroke-width="4"
                            fill="transparent"
                            r="26"
                            cx="30"
                            cy="30"
                            :stroke-dasharray="`${2 * Math.PI * 26}`"
                            :stroke-dashoffset="`${2 * Math.PI * 26 * (1 - uploadProgress / 100)}`"
                          />
                        </svg>
                        <div class="progress-text">{{ uploadProgress }}%</div>
                      </div>
                    </div>
                    <p>Upload vers Cloudinary...</p>
                    <div v-if="uploadError" class="upload-error">
                      {{ uploadError }}
                    </div>
                  </div>
                  <div v-if="formData.cvUrl && !isUploading" class="cv-preview">
                    <div class="cv-info">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 2 2h12c1.11 0 2-.9 2-2V8l-6-6z"/>
                      </svg>
                      <span>{{ formData.cvFileName }}</span>
                    </div>
                    <button
                      @click="removeCV"
                      type="button"
                      class="remove-cv"
                    >
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Bouton de soumission -->
              <div class="form-actions">
                <button
                  type="submit"
                  :disabled="isSubmitting || isUploading"
                  class="submit-button"
                >
                  <span v-if="!isSubmitting">Soumettre mon CV</span>
                  <span v-else>Envoi en cours...</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- Modal de confirmation -->
    <div v-if="showConfirmation" class="modal-overlay" @click="showConfirmation = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>Candidature envoyée !</h3>
          <button @click="showConfirmation = false" class="modal-close">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </button>
        </div>
        <div class="modal-body">
          <p>Votre candidature a été envoyée avec succès.</p>
          <div v-if="candidatureNumero" class="candidature-info">
            <strong>Numéro de candidature : {{ candidatureNumero }}</strong>
            <p class="candidature-note">Conservez ce numéro pour le suivi de votre candidature.</p>
          </div>
          <p>Nous vous contacterons bientôt.</p>
        </div>
        <div class="modal-footer">
          <button @click="resetForm" class="modal-button">Nouvelle candidature</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'
import axios from 'axios'

// Configuration Cloudinary
const cloudinaryConfig = {
  cloudName: 'r-sidence-meubl-e',
  uploadPreset: 'dsegjq9c',
  apiKey: '162815926914253',
  uploadUrl: 'https://api.cloudinary.com/v1_1/r-sidence-meubl-e/upload'
}

const API_BASE_URL = 'https://sogetrag.com/api' // Ajustez selon votre configuration

// Références
const cvInput = ref(null)

// État du formulaire
const showConfirmation = ref(false)
const isUploading = ref(false)
const isSubmitting = ref(false)
const uploadProgress = ref(0)
const uploadError = ref('')
const submitError = ref('')
const candidatureNumero = ref('')

const formData = ref({
  prenom: '',
  nom: '',
  niveauEtudes: '',
  villeResidence: '',
  contact: '',
  estMilitant: '',
  poste: '',
  matricule: '',
  cvUrl: '',
  cvFileName: '',
  cvId: ''
})

const formProgress = computed(() => {
  const requiredFields = ['prenom', 'nom', 'niveauEtudes', 'villeResidence', 'contact', 'estMilitant', 'cvUrl']
  const filledFields = requiredFields.filter(field => formData.value[field])
  
  // Ajouter les champs conditionnels pour les militants
  if (formData.value.estMilitant === 'OUI') {
    const militantFields = ['poste', 'matricule']
    const filledMilitantFields = militantFields.filter(field => formData.value[field])
    return Math.round(((filledFields.length + filledMilitantFields.length) / (requiredFields.length + militantFields.length)) * 100)
  }
  
  return Math.round((filledFields.length / requiredFields.length) * 100)
})

// Formatage des champs
const formatUpperCase = (field) => {
  if (formData.value[field]) {
    formData.value[field] = formData.value[field].toUpperCase()
  }
}

const formatPhoneNumber = () => {
  let value = formData.value.contact.replace(/\D/g, '')
  if (value.length >= 2) {
    value = value.substring(0, 2) + ' ' + value.substring(2)
  }
  if (value.length >= 6) {
    value = value.substring(0, 6) + ' ' + value.substring(6)
  }
  if (value.length >= 9) {
    value = value.substring(0, 9) + ' ' + value.substring(9)
  }
  formData.value.contact = value
}

const uploadCVToCloudinary = async (file) => {
  try {
    uploadError.value = ''

    const fileName = `aeemci_cv_${Date.now()}_${file.name.replace(/\s+/g, '_')}`

    const uploadFormData = new FormData()
    uploadFormData.append('file', file)
    uploadFormData.append('upload_preset', cloudinaryConfig.uploadPreset)
    uploadFormData.append('api_key', cloudinaryConfig.apiKey)
    uploadFormData.append('public_id', fileName)
    uploadFormData.append('resource_type', 'raw') // Important pour les PDF

    const response = await axios.post(cloudinaryConfig.uploadUrl, uploadFormData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: (progressEvent) => {
        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        uploadProgress.value = percentCompleted
      }
    })

    if (response.data && response.data.secure_url) {
      formData.value.cvUrl = response.data.secure_url
      formData.value.cvId = response.data.public_id
      formData.value.cvFileName = file.name

      console.log('CV assigné:', formData.value.cvUrl)
      return true
    } else {
      throw new Error('Réponse Cloudinary invalide')
    }

  } catch (error) {
    console.error('Erreur lors du téléchargement vers Cloudinary:', error)
    uploadError.value = 'Erreur lors du téléchargement du CV. Veuillez réessayer.'
    return false
  }
}

const uploadCV = async (e) => {
  isUploading.value = true
  uploadProgress.value = 0
  uploadError.value = ''

  try {
    const files = e.target.files
    if (!files.length) {
      isUploading.value = false
      return
    }

    const file = files[0]

    if (file.type !== 'application/pdf') {
      uploadError.value = 'Veuillez sélectionner un fichier PDF valide'
      isUploading.value = false
      if (e.target) e.target.value = ''
      return
    }

    // Vérifier la taille du fichier (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
      uploadError.value = 'La taille du CV ne doit pas dépasser 10MB'
      isUploading.value = false
      if (e.target) e.target.value = ''
      return
    }

    formData.value.cvUrl = null
    formData.value.cvId = null
    formData.value.cvFileName = null

    const success = await uploadCVToCloudinary(file)

    if (!success) {
      if (e.target) e.target.value = ''
    }

  } catch (error) {
    console.error('Erreur upload:', error)
    uploadError.value = 'Erreur lors de l\'upload du CV: ' + error.message
    if (e.target) e.target.value = ''
  } finally {
    isUploading.value = false
    uploadProgress.value = 0
  }
}

const removeCV = () => {
  formData.value.cvUrl = ''
  formData.value.cvId = ''
  formData.value.cvFileName = ''
  if (cvInput.value) {
    cvInput.value.value = ''
  }
}

const submitForm = async () => {
  if (isSubmitting.value || isUploading.value) return

  // Validation des champs militants
  if (formData.value.estMilitant === 'OUI') {
    if (!formData.value.poste) {
      alert('Veuillez remplir tous les champs obligatoires pour les militants.')
      return
    }
  }

  if (!formData.value.cvUrl) {
    alert('Veuillez télécharger votre CV.')
    return
  }

  isSubmitting.value = true
  submitError.value = ''

  try {
    // Préparation des données pour l'API PHP
    const apiData = {
      prenom: formData.value.prenom,
      nom: formData.value.nom,
      niveau_etudes: formData.value.niveauEtudes,
      ville_residence: formData.value.villeResidence,
      contact: formData.value.contact.replace(/\s/g, ''), // Supprimer les espaces du numéro
      est_militant: formData.value.estMilitant,
      poste: formData.value.estMilitant === 'OUI' ? formData.value.poste : null,
      matricule: formData.value.estMilitant === 'OUI' ? formData.value.matricule : null,
      cv_url: formData.value.cvUrl,
      cv_filename: formData.value.cvFileName,
      cv_cloudinary_id: formData.value.cvId
    }

    console.log('Envoi des données vers l\'API:', apiData)

    // Envoi vers l'API PHP
    const response = await axios.post(`${API_BASE_URL}/cv-collection.php`, apiData, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    })

    console.log('Réponse de l\'API:', response.data)

    if (response.data.success) {
      candidatureNumero.value = response.data.numero_candidature
      showConfirmation.value = true
    } else {
      throw new Error(response.data.message || 'Erreur lors de l\'envoi de la candidature')
    }
    
  } catch (error) {
    console.error('Erreur lors de l\'envoi:', error)
    
    if (error.response && error.response.data) {
      submitError.value = error.response.data.message || 'Erreur lors de l\'envoi de la candidature'
    } else if (error.message) {
      submitError.value = error.message
    } else {
      submitError.value = 'Erreur de connexion. Veuillez vérifier votre connexion internet et réessayer.'
    }
    
    alert(submitError.value)
  } finally {
    isSubmitting.value = false
  }
}

const resetForm = () => {
  formData.value = {
    prenom: '',
    nom: '',
    niveauEtudes: '',
    villeResidence: '',
    contact: '',
    estMilitant: '',
    poste: '',
    matricule: '',
    cvUrl: '',
    cvFileName: '',
    cvId: ''
  }
  showConfirmation.value = false
  submitError.value = ''
  candidatureNumero.value = ''
  if (cvInput.value) {
    cvInput.value.value = ''
  }
}
</script>

<style scoped>
/* Styles spécifiques au formulaire de CV */
.militant-fields {
  margin-top: 1rem;
  padding: 1rem;
  background-color: #f8fafc;
  border-radius: 8px;
  border-left: 4px solid #3b82f6;
}

.cv-upload {
  border: 2px dashed #d1d5db;
  border-radius: 8px;
  padding: 2rem;
  text-align: center;
  transition: all 0.3s ease;
}

.cv-upload:hover {
  border-color: #3b82f6;
  background-color: #f8fafc;
}

.cv-input {
  display: none;
}

.cv-placeholder {
  cursor: pointer;
  color: #6b7280;
}

.cv-placeholder:hover {
  color: #3b82f6;
}

.cv-preview {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
  background-color: #f0f9ff;
  border-radius: 6px;
  border: 1px solid #0ea5e9;
}

.cv-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #0369a1;
  font-weight: 500;
}

.remove-cv {
  background: #ef4444;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 0.5rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.remove-cv:hover {
  background: #dc2626;
}

.upload-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  color: #6b7280;
}

.upload-progress-container {
  position: relative;
}

.progress-circle {
  position: relative;
  width: 60px;
  height: 60px;
}

.progress-ring {
  transform: rotate(-90deg);
}

.progress-ring-circle {
  transition: stroke-dashoffset 0.3s ease;
}

.progress-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 0.875rem;
  font-weight: 600;
  color: #16a34a;
}

.upload-error {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

.form-actions {
  margin-top: 2rem;
  text-align: center;
}

.submit-button {
  background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
  color: white;
  border: none;
  border-radius: 8px;
  padding: 1rem 2rem;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 200px;
}

.submit-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
}

.submit-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  max-width: 400px;
  width: 90%;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.modal-header h3 {
  color: #16a34a;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  cursor: pointer;
  color: #6b7280;
}

.modal-body p {
  color: #6b7280;
  line-height: 1.6;
  margin: 0;
}

.candidature-info {
  background-color: #dcfce775;
  border: 1px solid #16a34a;
  border-radius: 8px;
  padding: 1rem;
  margin: 1rem 0;
  text-align: center;
}

.candidature-info strong {
  color: #16a34a;
  font-size: 1.1rem;
  display: block;
  margin-bottom: 0.5rem;
}

.candidature-note {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0.5rem 0 0 0;
}

.modal-footer {
  margin-top: 1.5rem;
  text-align: center;
}

.modal-button {
  background: #15803d;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 0.75rem 1.5rem;
  cursor: pointer;
  font-weight: 500;
}

.modal-button:hover {
  background: #15803d;
}

/* Styles existants du formulaire original (adaptés) */
.app-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #dcfce7 0%, #f9fafb 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.app-header {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo-icon {
  background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
  color: white;
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-text h1 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.logo-text p {
  margin: 0;
  font-size: 0.875rem;
  color: #6b7280;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.step-info {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0;
}

.progress-bar {
  width: 200px;
  height: 6px;
  background: #e5e7eb;
  border-radius: 3px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
  transition: width 0.3s ease;
}

.main-content {
  max-width: 900px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.form-container {
  background: white;
  border-radius: 16px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.form-content {
  padding: 2rem;
}

.step-heading {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 2rem;
}

.heading-icon {
  color: #3b82f6;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group-full {
  grid-column: 1 / -1;
}

.form-label {
  display: block;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.5rem;
}

.required {
  color: #ef4444;
}

.input-container {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: white;
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.radio-group {
  display: flex;
  gap: 1rem;
}

.radio-group-horizontal {
  flex-direction: row;
}

.radio-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.radio-item-compact {
  padding: 0.5rem;
  border-radius: 6px;
  transition: background-color 0.2s;
}

.radio-item-compact:hover {
  background-color: #f3f4f6;
}

.radio-input {
  display: none;
}

.radio-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #d1d5db;
  border-radius: 50%;
  position: relative;
  transition: all 0.3s ease;
}

.radio-input:checked + .radio-custom {
  border-color: #3b82f6;
  background: #3b82f6;
}

.radio-input:checked + .radio-custom::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 8px;
  height: 8px;
  background: white;
  border-radius: 50%;
}

.radio-label {
  font-weight: 500;
  color: #374151;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .header-content {
    flex-direction: column;
    gap: 1rem;
  }
  
  .header-right {
    width: 100%;
    justify-content: center;
  }
}
</style>
