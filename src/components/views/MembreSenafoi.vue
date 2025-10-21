<template>
    <div class="app-container">
      <!-- Header -->
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
                <p>Identification pour l'obtention de la Carte de Membre</p>
              </div>
            </div>
          </div>
          <div class="header-right">
            <p class="step-info">Étape {{ currentStep + 1 }} sur {{ totalSteps + 1 }}</p>
            <div class="progress-bar">
              <div
                class="progress-fill"
                :style="{ width: `${((currentStep + 1) / (totalSteps + 1)) * 100}%` }"
              ></div>
            </div>
          </div>
        </div>
      </header>
      <!-- Main Content -->
      <main class="main-content">
        <div class="form-container">
          <!-- Progress Steps -->
          <div class="steps-header">
            <div class="steps-nav">
              <div
                v-for="(step, index) in steps"
                :key="index"
                class="step-item"
                :class="{
                  'step-active': index === currentStep,
                  'step-completed': index < currentStep,
                  'step-disabled': index > currentStep
                }"
              >
                <div class="step-number">
                  <span v-if="index < currentStep">✓</span>
                  <span v-else>{{ index + 1 }}</span>
                </div>
                <span class="step-title">{{ step.title }}</span>
                <div
                  v-if="index < steps.length - 1"
                  class="step-connector"
                  :class="{ 'connector-active': index < currentStep }"
                ></div>
              </div>
            </div>
          </div>
          <!-- Form Content -->
          <div class="form-content">
            <form @submit.prevent="submitForm">
              <!-- Étape 0: Type de Membre -->
              <div v-if="currentStep === 0" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
                  </svg>
                  Type de Membre
                </h2>
                <div class="form-group">
                  <label class="form-label">Vous êtes : <span class="required">*</span></label>
                  <div class="radio-group radio-group-horizontal">
                    <label class="radio-item radio-item-compact">
                      <input
                        v-model="formData.type_membre"
                        type="radio"
                        value="FORMATEUR"
                        class="radio-input"
                        required
                      />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Formateur</span>
                    </label>
                    <label class="radio-item radio-item-compact">
                      <input
                        v-model="formData.type_membre"
                        type="radio"
                        value="ANCIEN_CE"
                        class="radio-input"
                        required
                      />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Ancien CE</span>
                    </label>
                    <label class="radio-item radio-item-compact">
                      <input
                        v-model="formData.type_membre"
                        type="radio"
                        value="ANCIEN_SR"
                        class="radio-input"
                        required
                      />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Ancien SR</span>
                    </label>
                    <label class="radio-item radio-item-compact">
                      <input
                        v-model="formData.type_membre"
                        type="radio"
                        value="CONFERENCIER"
                        class="radio-input"
                        required
                      />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Conférencier</span>
                    </label>
                  </div>
                </div>
              </div>
              <!-- Étape 1: Informations Personnelles -->
              <div v-if="currentStep === 1" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                  Informations Personnelles
                </h2>
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                      </svg>
                      Prénom <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.prenom"
                        @input="formatFirstUpperCase('prenom')"
                        type="text"
                        required
                        class="form-input"
                        placeholder="Votre prénom"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                      </svg>
                      Nom <span class="required">*</span>
                    </label>
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
              </div>
              <!-- Étape 2: Lieu de Provenance & Poste -->
              <div v-if="currentStep === 2" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Lieu de Provenance & Poste
                </h2>
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                      </svg>
                      Lieu de provenance <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.lieuProvenance"
                        @input="formatUpperCase('lieuProvenance')"
                        type="text"
                        required
                        class="form-input"
                        placeholder="Ville, Région ou Pays"
                      />
                    </div>
                  </div>
  
                  <!-- Conditional Poste Field -->
                  <div class="form-group">
                    <label class="form-label">Poste <span class="required">*</span></label>
                    <div class="input-container">
                      <input
                        v-if="formData.type_membre === 'FORMATEUR' || formData.type_membre === 'CONFERENCIER'"
                        type="text"
                        class="form-input"
                        :value="getDisplayPoste()"
                        readonly
                        style="background-color: #f3f4f6; color: #6b7280;"
                      />
                      <div v-else-if="formData.type_membre === 'ANCIEN_CE'" class="autocomplete-container">
                        <input
                          v-model="ancienPosteCESearch"
                          @input="filterAncienPostesCE"
                          @focus="handleAncienPosteCEFocus"
                          @click="handleAncienPosteCEClick"
                          type="text"
                          required
                          class="form-input"
                          :class="{ 'mobile-autocomplete': isMobile }"
                          placeholder="Rechercher un ancien poste CE..."
                          :readonly="isMobile"
                        />
                        <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M7 10l5 5 5-5z"/>
                        </svg>
                        <div
                          v-if="!isMobile && showAncienPosteCEDropdown && filteredAncienPostesCE.length > 0"
                          class="autocomplete-dropdown"
                        >
                          <div
                            v-for="poste in filteredAncienPostesCE"
                            :key="poste"
                            @click="selectAncienPosteCE(poste)"
                            class="dropdown-item"
                          >
                            {{ poste }}
                          </div>
                        </div>
                      </div>
                      <div v-else-if="formData.type_membre === 'ANCIEN_SR'" class="autocomplete-container">
                        <input
                          v-model="ancienPosteSRSearch"
                          @input="filterAncienPostesSR"
                          @focus="handleAncienPosteSRFocus"
                          @click="handleAncienPosteSRClick"
                          type="text"
                          required
                          class="form-input"
                          :class="{ 'mobile-autocomplete': isMobile }"
                          placeholder="Rechercher un ancien poste SR..."
                          :readonly="isMobile"
                        />
                        <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M7 10l5 5 5-5z"/>
                        </svg>
                        <div
                          v-if="!isMobile && showAncienPosteSRDropdown && filteredAncienPostesSR.length > 0"
                          class="autocomplete-dropdown"
                        >
                          <div
                            v-for="poste in filteredAncienPostesSR"
                            :key="poste"
                            @click="selectAncienPosteSR(poste)"
                            class="dropdown-item"
                          >
                            {{ poste }}
                          </div>
                        </div>
                      </div>
                      <input
                        v-else
                        type="text"
                        class="form-input"
                        value="Sélectionnez un type de membre"
                        readonly
                        disabled
                        style="background-color: #f3f4f6; color: #6b7280;"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Étape 3: Photo et Matricule -->
              <div v-if="currentStep === 3" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 2l3 3h4c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V7c0-1.1.9-2 2-2h3l3-3zm3 15c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0-1.8c-1.77 0-3.2-1.43-3.2-3.2s1.43-3.2 3.2-3.2 3.2 1.43 3.2 3.2-1.43 3.2-3.2 3.2z"/>
                  </svg>
                  Photo et Matricule
                </h2>
                <div class="photo-matricule-grid">
                  <div class="photo-section">
                    <label class="form-label">Photo d'identité <span class="required">*</span></label>
                    <div class="photo-upload">
                      <input
                        ref="photoInput"
                        type="file"
                        accept="image/*"
                        @change="uploadImage"
                        class="photo-input"
                      />
                      <div v-if="!formData.photo && !isUploading" @click="$refs.photoInput.click()" class="photo-placeholder">
                        <svg class="upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/>
                        </svg>
                        <p>Cliquez pour ajouter une photo</p>
                        <small>Fond unique recommandé</small>
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
                      <div v-if="formData.photo && !isUploading" class="photo-preview">
                        <img :src="formData.photo" alt="Photo" class="preview-image" />
                        <button
                          @click="removePhoto"
                          type="button"
                          class="remove-photo"
                        >
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="matricule-section">
                    <label class="form-label">Photo d'Identité</label>
                    <div class="matricule-display">
                      <div class="matricule-info">
                        <p><strong>détails :</strong></p>
                        <ul>
                          <li>Fond unique</li>
                          <li>Format : JPG, PNG, GIF</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Navigation Buttons -->
              <div class="form-navigation">
                <button
                  v-if="currentStep > 0"
                  @click="previousStep"
                  type="button"
                  class="btn btn-secondary"
                >
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                  </svg>
                  Précédent
                </button>
                <div v-else></div>
                <button
                  v-if="currentStep < totalSteps"
                  @click="nextStep"
                  type="button"
                  class="btn btn-primary"
                  :disabled="!canProceedToNextStep"
                >
                  Suivant
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                </button>
                <button
                  v-if="currentStep === totalSteps"
                  type="submit"
                  class="btn btn-primary"
                  :disabled="!canSubmit || isSubmitting"
                >
                  <div v-if="isSubmitting" class="loading-spinner"></div>
                  <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  {{ isSubmitting ? 'Traitement en cours...' : 'Finaliser l\'inscription' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
      <!-- Modal de confirmation -->
      <div v-if="showConfirmation" class="modal-overlay">
        <div class="modal-content">
          <div class="modal-header">
            <div class="success-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <h3>Inscription réussie !</h3>
            <p>Votre carte de membre a été créée avec succès.</p>
            <p class="matricule-final">Matricule: <strong>{{ formData.matriculeGenere }}</strong></p>
          </div>
          <div class="modal-actions">
            <button @click="downloadFiche" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
              </svg>
              Télécharger la fiche
            </button>
            <button @click="resetForm" class="btn btn-secondary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
              </svg>
              Nouvelle inscription
            </button>
          </div>
        </div>
      </div>
      <!-- Modals pour autocomplete mobile -->
      <!-- Modal Ancien Poste CE -->
      <div v-if="showAncienPosteCEModal" class="mobile-overlay" @click="closeAncienPosteCEModal">
        <div class="mobile-modal" @click.stop>
          <div class="mobile-modal-header">
            <h3>Choisir un Ancien Poste CE</h3>
            <button @click="closeAncienPosteCEModal" class="modal-close-btn">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
              </svg>
            </button>
          </div>
          <div class="mobile-modal-search">
            <input
              v-model="ancienPosteCEModalSearch"
              @input="filterAncienPostesCEModal"
              type="text"
              class="modal-search-input"
              placeholder="Rechercher un poste..."
              autofocus
            />
          </div>
          <div class="mobile-modal-list">
            <div
              v-for="poste in filteredAncienPostesCEModal"
              :key="poste"
              @click="selectAncienPosteCEFromModal(poste)"
              class="modal-list-item"
            >
              {{ poste }}
            </div>
            <div v-if="filteredAncienPostesCEModal.length === 0" class="modal-no-results">
              Aucun résultat trouvé
            </div>
          </div>
        </div>
      </div>
      <!-- Modal Ancien Poste SR -->
      <div v-if="showAncienPosteSRModal" class="mobile-overlay" @click="closeAncienPosteSRModal">
        <div class="mobile-modal" @click.stop>
          <div class="mobile-modal-header">
            <h3>Choisir un Ancien Poste SR</h3>
            <button @click="closeAncienPosteSRModal" class="modal-close-btn">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
              </svg>
            </button>
          </div>
          <div class="mobile-modal-search">
            <input
              v-model="ancienPosteSRModalSearch"
              @input="filterAncienPostesSRModal"
              type="text"
              class="modal-search-input"
              placeholder="Rechercher un poste..."
              autofocus
            />
          </div>
          <div class="mobile-modal-list">
            <div
              v-for="poste in filteredAncienPostesSRModal"
              :key="poste"
              @click="selectAncienPosteSRFromModal(poste)"
              class="modal-list-item"
            >
              {{ poste }}
            </div>
            <div v-if="filteredAncienPostesSRModal.length === 0" class="modal-no-results">
              Aucun résultat trouvé
            </div>
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
  
  // Références
  const photoInput = ref(null)
  
  // État du formulaire
  const currentStep = ref(0)
  const totalSteps = 3 // Steps are 0, 1, 2, 3 (4 steps total)
  const showConfirmation = ref(false)
  const isUploading = ref(false)
  const isSubmitting = ref(false)
  const uploadProgress = ref(0)
  const uploadError = ref('')
  
  // Détection mobile
  const isMobile = ref(false)
  
  // États des modals mobile
  const showAncienPosteCEModal = ref(false)
  const showAncienPosteSRModal = ref(false)
  
  // Recherches pour les modals
  const ancienPosteCEModalSearch = ref('')
  const ancienPosteSRModalSearch = ref('')
  
  // Listes filtrées pour les modals
  const filteredAncienPostesCEModal = ref([])
  const filteredAncienPostesSRModal = ref([])
  
  // Données du formulaire
  const formData = ref({
    type_membre: '', // FORMATEUR, ANCIEN_CE, ANCIEN_SR, CONFERENCIER
    prenom: '',
    nom: '',
    lieuProvenance: '', // Nouveau champ
    ancienPosteCE: '', // Specific for ANCIEN_CE
    ancienPosteSR: '', // Specific for ANCIEN_SR
    photo: null,
    photoId: null,
    matriculeGenere: ''
  })
  
  // Étapes du formulaire
  const steps = [
    { title: 'Type de membre' },
    { title: 'Informations personnelles' },
    { title: 'Lieu & Poste' },
    { title: 'Photo & Matricule' }
  ]
  
  // Listes des anciens postes
  const ancienPostesCE = [
    'AMIR', 'Vice-Amir', 'Vice Amirate', 'SENAFI', 'SENAFOCI', 'SENAFOCUS', 'SENACEF',
    'SENAFAD', 'SENAFSS', 'SENAFS', 'SENAMO', 'SENACREX'
  ]
  
  const ancienPostesSR = [
    'SR', 'Vice-SR', 'SERAFAD', 'SERAFI', 'SERAFOCI', 'SERAFOCUS', 'SERACEF',
    'SERAFS', 'SERAFSS', 'SERAMO', 'SERACREX', 'Commissaire aux Comptes Régional',
    'Imam Régional', 'SERASIP', 'SERAES'
  ]
  
  // État des dropdowns autocomplete
  const ancienPosteCESearch = ref('')
  const ancienPosteSRSearch = ref('')
  
  const showAncienPosteCEDropdown = ref(false)
  const showAncienPosteSRDropdown = ref(false)
  
  // Listes filtrées
  const filteredAncienPostesCE = ref([])
  const filteredAncienPostesSR = ref([])
  
  // Compteur pour le matricule (placeholder, should be from backend in real app)
  const numeroOrdre = ref(1)
  
  // Fonction pour obtenir le poste avec préfixe "Past" si ancien membre
  const getDisplayPoste = () => {
    if (formData.value.type_membre === 'FORMATEUR') {
      return 'Formateur';
    }
    if (formData.value.type_membre === 'CONFERENCIER') {
      return 'Conférencier';
    }
    if (formData.value.type_membre === 'ANCIEN_CE' && formData.value.ancienPosteCE) {
      return `Past ${formData.value.ancienPosteCE}`;
    }
    if (formData.value.type_membre === 'ANCIEN_SR' && formData.value.ancienPosteSR) {
      return `Past ${formData.value.ancienPosteSR}`;
    }
    return ''; // Should not happen if validation is correct
  }
  
  // Fonction de détection mobile
  const checkIfMobile = () => {
    isMobile.value = window.innerWidth <= 768 || /Android|iPhone|iPad|Ipod|BlackBerry|IEMobile/i.test(navigator.userAgent)
  }
  
  // Gestionnaires de focus/click pour mobile
  const handleAncienPosteCEFocus = () => {
    if (isMobile.value) {
      openAncienPosteCEModal()
    } else {
      filterAncienPostesCE()
      showAncienPosteCEDropdown.value = true
    }
  }
  const handleAncienPosteCEClick = () => {
    if (isMobile.value) {
      openAncienPosteCEModal()
    }
  }
  
  const handleAncienPosteSRFocus = () => {
    if (isMobile.value) {
      openAncienPosteSRModal()
    } else {
      filterAncienPostesSR()
      showAncienPosteSRDropdown.value = true
    }
  }
  const handleAncienPosteSRClick = () => {
    if (isMobile.value) {
      openAncienPosteSRModal()
    }
  }
  
  // Fonctions pour ouvrir les modals
  const openAncienPosteCEModal = () => {
    ancienPosteCEModalSearch.value = ancienPosteCESearch.value
    filteredAncienPostesCEModal.value = [...ancienPostesCE]
    showAncienPosteCEModal.value = true
    document.body.style.overflow = 'hidden'
  }
  
  const openAncienPosteSRModal = () => {
    ancienPosteSRModalSearch.value = ancienPosteSRSearch.value
    filteredAncienPostesSRModal.value = [...ancienPostesSR]
    showAncienPosteSRModal.value = true
    document.body.style.overflow = 'hidden'
  }
  
  // Fonctions pour fermer les modals
  const closeAncienPosteCEModal = () => {
    showAncienPosteCEModal.value = false
    document.body.style.overflow = 'auto'
  }
  
  const closeAncienPosteSRModal = () => {
    showAncienPosteSRModal.value = false
    document.body.style.overflow = 'auto'
  }
  
  // Fonctions de filtrage pour les modals
  const filterAncienPostesCEModal = () => {
    const search = ancienPosteCEModalSearch.value.toLowerCase()
    filteredAncienPostesCEModal.value = ancienPostesCE.filter(p =>
      p.toLowerCase().includes(search)
    )
  }
  
  const filterAncienPostesSRModal = () => {
    const search = ancienPosteSRModalSearch.value.toLowerCase()
    filteredAncienPostesSRModal.value = ancienPostesSR.filter(p =>
      p.toLowerCase().includes(search)
    )
  }
  
  // Fonctions de sélection depuis les modals
  const selectAncienPosteCEFromModal = (poste) => {
    ancienPosteCESearch.value = poste
    formData.value.ancienPosteCE = poste
    closeAncienPosteCEModal()
  }
  
  const selectAncienPosteSRFromModal = (poste) => {
    ancienPosteSRSearch.value = poste
    formData.value.ancienPosteSR = poste
    closeAncienPosteSRModal()
  }
  
  // Fonction pour formater en majuscules
  const formatUpperCase = (field) => {
    formData.value[field] = formData.value[field].toUpperCase()
  }
  const formatFirstUpperCase = (field) => {
    const value = formData.value[field];
    if (value && typeof value === 'string') {
      formData.value[field] = value.charAt(0).toUpperCase() + value.slice(1);
    }
  }
  
  // Fonction pour formater le numéro de téléphone (kept for contact field, though not explicitly requested to be removed)
  const formatPhoneNumber = (event) => {
    let value = event.target.value.replace(/\D/g, '')
    if (value.length >= 2) {
      value = value.substring(0, 2) + ' ' + value.substring(2)
    }
    if (value.length >= 5) {
      value = value.substring(0, 5) + ' ' + value.substring(5)
    }
    if (value.length >= 8) {
      value = value.substring(0, 8) + ' ' + value.substring(8)
    }
    if (value.length >= 11) {
      value = value.substring(0, 11) + ' ' + value.substring(11)
    }
    formData.value.contact = value
  }
  
  // Upload d'image vers Cloudinary
  const uploadImageToCloudinary = async (image) => {
    try {
      uploadError.value = ''
      const fileName = `aeemci_photo_${Date.now()}_${image.name.replace(/\s+/g, '_')}`
      const uploadFormData = new FormData()
      uploadFormData.append('file', image)
      uploadFormData.append('upload_preset', cloudinaryConfig.uploadPreset)
      uploadFormData.append('api_key', cloudinaryConfig.apiKey)
      uploadFormData.append('public_id', fileName)
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
        formData.value.photo = response.data.secure_url
        formData.value.photoId = response.data.public_id
        console.log('Photo assignée:', formData.value.photo)
        return true
      } else {
        throw new Error('Réponse Cloudinary invalide')
      }
    } catch (error) {
      console.error('Erreur lors du téléchargement vers Cloudinary:', error)
      uploadError.value = 'Erreur lors du téléchargement de la photo. Veuillez réessayer.'
      return false
    }
  }
  const uploadImage = async (e) => {
    isUploading.value = true
    uploadProgress.value = 0
    uploadError.value = ''
    try {
      const files = e.target.files || e.dataTransfer.files
      if (!files.length) {
        isUploading.value = false
        return
      }
      const image = files[0]
      if (!image.type.startsWith('image/')) {
        uploadError.value = 'Veuillez sélectionner un fichier image valide'
        isUploading.value = false
        if (e.target) e.target.value = ''
        return
      }
      // Vérifier la taille du fichier (max 5MB)
      if (image.size > 5 * 1024 * 1024) {
        uploadError.value = 'La taille de l\'image ne doit pas dépasser 5MB'
        isUploading.value = false
        if (e.target) e.target.value = ''
        return
      }
      formData.value.photo = null
      formData.value.photoId = null
      const success = await uploadImageToCloudinary(image)
      if (!success) {
        if (e.target) e.target.value = ''
      }
    } catch (error) {
      console.error('Erreur upload:', error)
      uploadError.value = 'Erreur lors de l\'upload de la photo: ' + error.message
      if (e.target) e.target.value = ''
    } finally {
      isUploading.value = false
      uploadProgress.value = 0
    }
  }
  
  // Fonctions de filtrage pour autocomplete
  const filterAncienPostesCE = () => {
    const search = ancienPosteCESearch.value.toLowerCase()
    filteredAncienPostesCE.value = ancienPostesCE.filter(p =>
      p.toLowerCase().includes(search)
    )
  }
  
  const filterAncienPostesSR = () => {
    const search = ancienPosteSRSearch.value.toLowerCase()
    filteredAncienPostesSR.value = ancienPostesSR.filter(p =>
      p.toLowerCase().includes(search)
    )
  }
  
  // Supprimer la photo
  const removePhoto = () => {
    formData.value.photo = null
    formData.value.photoId = null
    if (photoInput.value) {
      photoInput.value.value = ''
    }
  }
  
  // Génération du matricule
  const generateMatricule = () => {
    const currentYearLastTwoDigits = new Date().getFullYear().toString().slice(-2);
    const orderNumber = numeroOrdre.value.toString().padStart(3, '0'); // Placeholder for a unique order number
  
    let typeCode = '';
    let specificCode = '';
  
    switch (formData.value.type_membre) {
      case 'FORMATEUR':
        typeCode = 'FMT';
        break;
      case 'ANCIEN_CE':
        typeCode = 'ACE';
        specificCode = formData.value.ancienPosteCE ? formData.value.ancienPosteCE.substring(0, 3).toUpperCase() : 'XXX';
        break;
      case 'ANCIEN_SR':
        typeCode = 'ASR';
        specificCode = formData.value.ancienPosteSR ? formData.value.ancienPosteSR.substring(0, 3).toUpperCase() : 'XXX';
        break;
      case 'CONFERENCIER':
        typeCode = 'CFR';
        break;
      default:
        typeCode = 'MEM'; // Default for unknown types
    }
  
    if (specificCode) {
      return `${typeCode}-${specificCode}-${currentYearLastTwoDigits}-${orderNumber}`;
    } else {
      // For Formateur, Conférencier, or if specific code is not applicable/selected
      const lieuCode = formData.value.lieuProvenance ? formData.value.lieuProvenance.substring(0, 3).toUpperCase() : 'XXX';
      return `${typeCode}-${lieuCode}-${currentYearLastTwoDigits}-${orderNumber}`;
    }
  }
  
  // Validation des étapes
  const canProceedToNextStep = computed(() => {
    switch (currentStep.value) {
      case 0:
        return formData.value.type_membre
      case 1:
        return formData.value.prenom && formData.value.nom
      case 2:
        if (!formData.value.lieuProvenance) return false;
        if (formData.value.type_membre === 'ANCIEN_CE') {
          return formData.value.ancienPosteCE;
        }
        if (formData.value.type_membre === 'ANCIEN_SR') {
          return formData.value.ancienPosteSR;
        }
        return true; // For FORMATEUR and CONFERENCIER, only lieuProvenance is needed here
      case 3:
        return formData.value.photo
      default:
        return false
    }
  })
  
  const canSubmit = computed(() => {
    return canProceedToNextStep.value
  })
  
  // Navigation
  const nextStep = () => {
    if (canProceedToNextStep.value && currentStep.value < totalSteps) {
      currentStep.value++
    }
  }
  const previousStep = () => {
    if (currentStep.value > 0) {
      currentStep.value--
    }
  }
  
  // Soumission du formulaire
  const submitForm = async () => {
    isSubmitting.value = true
    try {
      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 1500))
      // In a real app, you would send formData to your backend here
      console.log('Form Data Submitted:', formData.value)
      formData.value.matriculeGenere = generateMatricule() // Generate and store matricule on submission
      showConfirmation.value = true
    } catch (error) {
      console.error('Error submitting form:', error)
      alert("Une erreur est survenue lors de l'inscription. Veuillez réessayer.");
    } finally {
      isSubmitting.value = false
    }
  }
  
  // Téléchargement de la fiche
  const downloadFiche = () => {
    let posteInfo = '';
    if (formData.value.type_membre === 'FORMATEUR' || formData.value.type_membre === 'CONFERENCIER') {
      posteInfo = `Poste: ${getDisplayPoste()}`;
    } else if (formData.value.type_membre === 'ANCIEN_CE') {
      posteInfo = `Ancien Poste CE: ${formData.value.ancienPosteCE}`;
    } else if (formData.value.type_membre === 'ANCIEN_SR') {
      posteInfo = `Ancien Poste SR: ${formData.value.ancienPosteSR}`;
    }
  
    const ficheContent = `FICHE D'INSCRIPTION AEEMCI
  ========================
  Type de membre: ${formData.value.type_membre}
  
  Informations Personnelles:
  - Nom: ${formData.value.nom}
  - Prénom: ${formData.value.prenom}
  
  Lieu de Provenance & Poste:
  - Lieu de provenance: ${formData.value.lieuProvenance || 'N/A'}
  - ${posteInfo || 'N/A'}
  
  Matricule: ${formData.value.matriculeGenere}
  Date d'inscription: ${new Date().toLocaleDateString('fr-FR')}
  `
    const blob = new Blob([ficheContent], { type: 'text/plain' })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `fiche_inscription_${formData.value.matriculeGenere}.txt`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    URL.revokeObjectURL(url)
  }
  
  // Réinitialisation du formulaire
  const resetForm = () => {
    formData.value = {
      type_membre: '',
      prenom: '',
      nom: '',
      lieuProvenance: '',
      ancienPosteCE: '',
      ancienPosteSR: '',
      photo: null,
      photoId: null,
      matriculeGenere: ''
    }
    currentStep.value = 0
    showConfirmation.value = false
    ancienPosteCESearch.value = ''
    ancienPosteSRSearch.value = ''
  }
  
  // Fermeture des dropdowns au clic extérieur
  const handleClickOutside = (event) => {
    if (!event.target.closest('.autocomplete-container')) {
      showAncienPosteCEDropdown.value = false
      showAncienPosteSRDropdown.value = false
    }
  }
  
  // Gestionnaire de redimensionnement
  const handleResize = () => {
    checkIfMobile()
  }
  
  // Initialisation
  onMounted(() => {
    checkIfMobile()
    window.addEventListener('resize', handleResize)
    filteredAncienPostesCE.value = ancienPostesCE
    filteredAncienPostesSR.value = ancienPostesSR
    document.addEventListener('click', handleClickOutside)
  })
  
  onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize)
    document.removeEventListener('click', handleClickOutside)
    document.body.style.overflow = 'auto'
  })
  
  // Watcher for type_membre to reset specific fields
  watch(() => formData.value.type_membre, (newType) => {
    // Reset ancienPosteCE and ancienPosteSR if type_membre changes
    if (newType !== 'ANCIEN_CE') {
      formData.value.ancienPosteCE = '';
      ancienPosteCESearch.value = '';
    }
    if (newType !== 'ANCIEN_SR') {
      formData.value.ancienPosteSR = '';
      ancienPosteSRSearch.value = '';
    }
  });
  </script>
  
  <style scoped>
  /* Reset et base */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  .app-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #dcfce7 0%, #f9fafb 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  /* Header */
  .app-header {
    max-width: 1200px;
    margin: 0 auto;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  }
  .header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .header-left {
    display: flex;
    align-items: center;
  }
  .logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  .logo-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: #16a34a;
    color: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.25rem;
  }
  .logo-text h1 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #16a34a;
    margin: 0;
  }
  .logo-text p {
    font-size: 0.875rem;
    color: #4b5563;
    margin: 0;
  }
  .header-right {
    text-align: right;
  }
  .step-info {
    font-size: 0.875rem;
    color: #4b5563;
    margin-bottom: 0.25rem;
  }
  .progress-bar {
    width: 8rem;
    height: 0.5rem;
    background: #e5e7eb;
    border-radius: 0.25rem;
    overflow: hidden;
  }
  .progress-fill {
    height: 100%;
    background: #16a34a;
    transition: width 0.3s ease;
  }
  /* Main Content */
  .main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
  }
  .form-container {
    background: #ffffff;
    border-radius: 0.75rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    overflow: hidden;
  }
  /* Steps Header */
  .steps-header {
    background: #dcfce7;
    padding: 1.5rem 2rem;
  }
  .steps-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
  }
  .step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
  }
  .step-number {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border: 2px solid #d1d5db;
    background: #ffffff;
    color: #4b5563;
  }
  .step-item.step-active .step-number {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
  }
  .step-item.step-completed .step-number {
    background: #15803d;
    color: #ffffff;
    border-color: #15803d;
  }
  .step-title {
    font-size: 0.75rem;
    font-weight: 500;
    color: #374151;
    text-align: center;
  }
  .step-connector {
    position: absolute;
    top: 1rem;
    left: 60%;
    right: -40%;
    height: 2px;
    background: #d1d5db;
    z-index: 1;
  }
  .step-connector.connector-active {
    background: #16a34a;
  }
  /* Form Content */
  .form-content {
    padding: 2rem;
  }
  .step-content {
    min-height: 400px;
  }
  .step-heading {
    font-size: 1.75rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 2rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
  }
  .heading-icon {
    color: #16a34a;
  }
  /* Info Box pour ancien membre */
  .info-box {
    background: #f0f9ff;
    border: 2px solid #0ea5e9;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-top: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
  }
  .info-icon {
    color: #0ea5e9;
    flex-shrink: 0;
    margin-top: 0.25rem;
  }
  .info-content h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #0c4a6e;
    margin-bottom: 0.5rem;
  }
  .info-content p {
    color: #075985;
    font-size: 0.875rem;
    line-height: 1.5;
  }
  /* Form Elements */
  .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  .form-group {
    display: flex;
    flex-direction: column;
  }
  .form-group-full {
    grid-column: 1 / -1;
  }
  .form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .label-icon {
    color: #16a34a;
  }
  .required {
    color: #dc2626;
  }
  .input-container {
    position: relative;
  }
  .form-input {
    width: 100%;
    padding: 0.75rem;
    padding-right: 2.5rem;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: all 0.2s ease;
    background: #ffffff;
  }
  .form-input:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  .input-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
  }
  .form-help {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
  }
  /* Radio Group */
  .radio-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }
  .radio-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
  }
  .radio-item:hover {
    background: #f9fafb;
    border-color: #16a34a;
  }
  .radio-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
  }
  .radio-custom {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    margin-right: 0.75rem;
    position: relative;
    transition: all 0.2s ease;
  }
  .radio-input:checked + .radio-custom {
    border-color: #16a34a;
    background: #16a34a;
  }
  .radio-input:checked + .radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 0.5rem;
    height: 0.5rem;
    background: #ffffff;
    border-radius: 50%;
  }
  .radio-label {
    font-weight: 500;
    color: #374151;
  }
  /* Conditional Fields */
  .conditional-fields {
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: #f9fafb;
    border-radius: 0.5rem;
    border: 2px solid #e5e7eb;
  }
  /* Autocomplete */
  .autocomplete-container {
    position: relative;
  }
  .autocomplete-dropdown {
    position: absolute;
    bottom: 100%;
    left: 0;
    right: 0;
    background: #ffffff;
    border: 2px solid #d1d5db;
    border-bottom: none;
    border-radius: 0.5rem 0.5rem 0 0;
    box-shadow: 0 -1px 3px 0 rgb(0 0 0 / 0.1), 0 -1px 2px -1px rgb(0 0 0 / 0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 10;
  }
  .dropdown-item {
    padding: 0.75rem;
    cursor: pointer;
    font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s ease;
  }
  .dropdown-item:hover {
    background: #dcfce7;
  }
  .dropdown-item:last-child {
    border-bottom: none;
  }
  /* Styles pour les inputs mobile autocomplete */
  .mobile-autocomplete {
    cursor: pointer;
    background: #f9fafb;
    position: relative;
  }
  .mobile-autocomplete::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent;
    cursor: pointer;
  }
  .mobile-autocomplete:focus {
    background: #ffffff;
  }
  /* Styles pour les modals mobile */
  .mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(4px);
  }
  .mobile-modal {
    background: #ffffff;
    border-radius: 1rem 1rem 0 0;
    width: 100%;
    max-width: 500px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 -10px 25px -5px rgba(0, 0, 0, 0.1), 0 -10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: slideUp 0.3s ease-out;
  }
  @keyframes slideUp {
    from {
      transform: translateY(100%);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  .mobile-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 1rem 1.5rem;
    border-bottom: 2px solid #f3f4f6;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-radius: 1rem 1rem 0 0;
  }
  .mobile-modal-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #16a34a;
    margin: 0;
  }
  .modal-close-btn {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .modal-close-btn:hover {
    background: #f3f4f6;
    color: #374151;
  }
  .mobile-modal-search {
    padding: 1rem 1.5rem;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
  }
  .modal-search-input {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    font-size: 1rem;
    background: #f9fafb;
    transition: all 0.2s ease;
  }
  .modal-search-input:focus {
    outline: none;
    border-color: #16a34a;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  .mobile-modal-list {
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem 0;
    background: #ffffff;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
  }
  .modal-list-item {
    padding: 1rem 1.5rem;
    cursor: pointer;
    font-size: 1rem;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    min-height: 60px;
    touch-action: manipulation;
    -webkit-tap-highlight-color: rgba(22, 163, 74, 0.1);
  }
  .modal-list-item:hover {
    background: #f0fdf4;
    color: #16a34a;
  }
  .modal-list-item:active {
    background: #dcfce7;
    transform: scale(0.98);
  }
  .modal-list-item:last-child {
    border-bottom: none;
  }
  .modal-list-item-detailed {
    align-items: flex-start;
    padding: 1.25rem 1.5rem;
  }
  .modal-poste-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    width: 100%;
  }
  .modal-poste-name {
    font-weight: 600;
    color: #374151;
    font-size: 1rem;
  }
  .modal-poste-definition {
    font-size: 0.875rem;
    color: #6b7280;
    font-style: italic;
    line-height: 1.4;
  }
  .modal-no-results {
    padding: 2rem 1.5rem;
    text-align: center;
    color: #6b7280;
    font-style: italic;
  }
  /* Photo and Matricule */
  .photo-matricule-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: start;
  }
  .photo-upload {
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    padding: 2rem;
    text-align: center;
    transition: all 0.2s ease;
  }
  .photo-upload:hover {
    border-color: #16a34a;
    background: #dcfce7;
  }
  .photo-input {
    display: none;
  }
  .photo-placeholder {
    cursor: pointer;
  }
  .upload-icon {
    color: #9ca3af;
    margin-bottom: 1rem;
  }
  .photo-placeholder p {
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
  }
  .photo-placeholder small {
    color: #6b7280;
  }
  .upload-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }
  .spinner {
    width: 2rem;
    height: 2rem;
    border: 3px solid #e5e7eb;
    border-top: 3px solid #16a34a;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  .photo-preview {
    position: relative;
    display: inline-block;
  }
  .preview-image {
    width: 8rem;
    height: 8rem;
    object-fit: cover;
    border-radius: 0.5rem;
    border: 2px solid #16a34a;
  }
  .remove-photo {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    width: 1.5rem;
    height: 1.5rem;
    background: #dc2626;
    color: #ffffff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .matricule-display {
    background: #dcfce7;
    border: 2px solid #16a34a;
    border-radius: 0.75rem;
    padding: 1.5rem;
  }
  .matricule-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #15803d;
    margin-bottom: 1rem;
    text-align: center;
    font-family: 'Courier New', monospace;
  }
  .matricule-info p {
    font-weight: 600;
    color: #15803d;
    margin-bottom: 0.5rem;
  }
  .matricule-info ul {
    list-style: none;
    padding-left: 0;
  }
  .matricule-info li {
    font-size: 0.875rem;
    color: #4b5563;
    margin-bottom: 0.25rem;
    padding-left: 1rem;
    position: relative;
  }
  .matricule-info li::before {
    content: '•';
    color: #16a34a;
    position: absolute;
    left: 0;
  }
  /* Buttons */
  .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }
  .btn-primary {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
  }
  .btn-primary:hover:not(:disabled) {
    background: #15803d;
    border-color: #15803d;
    transform: translateY(-1px);
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
  }
  .btn-secondary {
    background: #ffffff;
    color: #374151;
    border-color: #d1d5db;
  }
  .btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
  }
  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
  }
  /* Form Navigation */
  .form-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e5e7eb;
  }
  /* Modal */
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
    z-index: 50;
  }
  .modal-content {
    background: #ffffff;
    border-radius: 0.75rem;
    padding: 2rem;
    max-width: 400px;
    width: 90%;
    margin: 1rem;
    border: 2px solid #e5e7eb;
  }
  .modal-header {
    text-align: center;
    margin-bottom: 2rem;
  }
  .success-icon {
    width: 4rem;
    height: 4rem;
    background: #dcfce7;
    color: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    border: 2px solid #16a34a;
  }
  .modal-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  .modal-header p {
    color: #4b5563;
    margin-bottom: 0.5rem;
  }
  .matricule-final {
    font-size: 0.875rem;
    color: #6b7280;
  }
  .matricule-final strong {
    color: #16a34a;
    font-family: 'Courier New', monospace;
  }
  .modal-actions {
    display: flex;
    gap: 0.75rem;
  }
  .modal-actions .btn {
    flex: 1;
  }
  /* Radio group horizontal pour le sexe */
  .radio-group-horizontal {
    flex-direction: row;
    gap: 1.5rem;
  }
  .radio-item-compact {
    padding: 0.5rem 1rem;
    flex: none;
    width: auto;
  }
  /* Styles pour les définitions de postes */
  .poste-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  .poste-name {
    font-weight: 600;
    color: #374151;
  }
  .poste-definition {
    font-size: 0.75rem;
    color: #6b7280;
    font-style: italic;
    line-height: 1.3;
  }
  .poste-selected-definition {
    margin-top: 0.5rem;
    padding: 0.75rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 0.5rem;
  }
  .definition-text {
    color: #15803d;
    font-size: 0.875rem;
    line-height: 1.4;
  }
  /* Animation de chargement */
  .loading-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 0.5rem;
  }
  .btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
  }
  .btn:disabled:hover {
    transform: none;
    box-shadow: none;
  }
  /* Styles pour l'upload avec progress */
  .upload-progress-container {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
  }
  .progress-circle {
    position: relative;
    display: inline-block;
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
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.375rem;
    color: #dc2626;
    font-size: 0.875rem;
    text-align: center;
  }
  /* Animation pour le cercle de progression */
  @keyframes progressPulse {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0.7;
    }
  }
  .progress-ring-fill {
    animation: progressPulse 2s ease-in-out infinite;
  }
  /* Responsive */
  @media (max-width: 768px) {
    .header-content {
      padding: 1rem;
      flex-direction: column;
      gap: 1rem;
    }
    .header-right {
      width: 100%;
      text-align: center;
    }
    .progress-bar {
      width: 100%;
      max-width: 300px;
      margin: 0 auto;
    }
    .main-content {
      padding: 1rem;
    }
    .form-content {
      padding: 1rem;
    }
    .form-grid {
      grid-template-columns: 1fr;
    }
    .photo-matricule-grid {
      grid-template-columns: 1fr;
    }
    .steps-header {
      padding: 1rem;
      background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    }
    .steps-nav {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.5rem;
      flex-wrap: nowrap;
      overflow-x: auto;
      padding: 0.5rem 0;
    }
    .step-item {
      flex-direction: column;
      align-items: center;
      min-width: 60px;
      flex-shrink: 0;
      position: relative;
    }
    .step-number {
      width: 2.5rem;
      height: 2.5rem;
      font-size: 0.875rem;
      margin-bottom: 0.25rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .step-title {
      font-size: 0.6rem;
      font-weight: 600;
      text-align: center;
      line-height: 1.2;
      max-width: 60px;
      word-wrap: break-word;
    }
    .step-item.step-active .step-title {
      color: #16a34a;
      font-weight: 700;
    }
    .step-item.step-completed .step-title {
      color: #15803d;
    }
    .step-connector {
      position: absolute;
      top: 1.25rem;
      left: calc(100% + 0.25rem);
      width: 0.5rem;
      height: 2px;
      background: #d1d5db;
      z-index: 1;
    }
    .step-connector.connector-active {
      background: #16a34a;
    }
    .step-item:last-child .step-connector {
      display: none;
    }
    .step-item.step-active::before {
      content: '';
      position: absolute;
      top: -0.5rem;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-left: 6px solid transparent;
      border-right: 6px solid transparent;
      border-top: 8px solid #16a34a;
      z-index: 2;
    }
    .modal-actions {
      flex-direction: column;
    }
    .step-heading {
      font-size: 1.5rem;
      flex-direction: column;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
    }
    .form-input {
      padding: 1rem;
      font-size: 1rem;
      border-radius: 0.75rem;
    }
    .radio-group {
      gap: 1rem;
    }
    .radio-item {
      padding: 1rem;
      border-radius: 0.75rem;
    }
    .photo-upload {
      padding: 1.5rem;
      border-radius: 1rem;
    }
    .upload-icon {
      width: 40px;
      height: 40px;
    }
    .form-navigation {
      flex-direction: column;
      gap: 1rem;
      padding-top: 1.5rem;
    }
    .form-navigation .btn {
      width: 100%;
      padding: 1rem;
      font-size: 1rem;
      border-radius: 0.75rem;
    }
    .radio-group-horizontal {
      flex-direction: column;
      gap: 0.75rem;
    }
    .radio-item-compact {
      width: 100%;
      justify-content: flex-start;
    }
    .mobile-modal {
      max-height: 85vh;
    }
    .mobile-modal-header {
      padding: 1rem 1rem 0.75rem 1rem;
    }
    .mobile-modal-header h3 {
      font-size: 1.125rem;
    }
    .mobile-modal-search {
      padding: 0.75rem 1rem;
    }
    .modal-list-item {
      padding: 0.875rem 1rem;
      font-size: 0.9rem;
      min-height: 56px;
      touch-action: manipulation;
    }
    .modal-list-item-detailed {
      padding: 1rem;
    }
    .modal-poste-name {
      font-size: 0.9rem;
    }
    .modal-poste-definition {
      font-size: 0.8rem;
    }
    .info-box {
      flex-direction: column;
      gap: 0.75rem;
    }
    .info-icon {
      align-self: flex-start;
    }
  }
  @media (max-width: 480px) {
    .step-heading {
      font-size: 1.25rem;
    }
    .logo-text h1 {
      font-size: 1.25rem;
    }
    .logo-text p {
      font-size: 0.75rem;
    }
    .steps-nav {
      gap: 0.25rem;
    }
    .step-item {
      min-width: 50px;
    }
    .step-number {
      width: 2rem;
      height: 2rem;
      font-size: 0.75rem;
    }
    .step-title {
      font-size: 0.55rem;
      max-width: 50px;
    }
    .step-connector {
      width: 0.25rem;
      left: calc(100% + 0.125rem);
    }
    .form-input {
      padding: 0.875rem;
      font-size: 0.9rem;
    }
    .btn {
      padding: 0.875rem 1rem;
      font-size: 0.9rem;
    }
    .modal-content {
      margin: 0.5rem;
      padding: 1.5rem;
    }
    .modal-header h3 {
      font-size: 1.125rem;
    }
  }
  /* Animations pour mobile */
  @media (max-width: 768px) {
    .step-item {
      transition: all 0.3s ease;
    }
    .step-item.step-active {
      transform: scale(1.05);
    }
    .step-number {
      transition: all 0.3s ease;
    }
    .step-item.step-active .step-number {
      box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
    }
    .progress-fill {
      transition: width 0.5s ease;
    }
    .steps-nav {
      scroll-behavior: smooth;
    }
  }
  /* Mode paysage mobile */
  @media (max-width: 768px) and (orientation: landscape) {
    .mobile-modal {
      max-height: 90vh;
    }
    .mobile-modal-header {
      padding: 0.75rem 1.5rem 0.5rem 1.5rem;
    }
    .mobile-modal-search {
      padding: 0.75rem 1.5rem;
    }
    .modal-list-item {
      padding: 0.75rem 1.5rem;
      min-height: 48px;
    }
    .steps-header {
      padding: 0.75rem 1rem;
    }
    .step-number {
      width: 2rem;
      height: 2rem;
      font-size: 0.75rem;
    }
    .step-title {
      font-size: 0.55rem;
    }
    .form-content {
      padding: 1rem 2rem;
    }
  }
  /* Accessibilité mobile */
  @media (max-width: 768px) {
    .btn:focus,
    .form-input:focus,
    .radio-item:focus-within {
      outline: 3px solid #16a34a;
      outline-offset: 2px;
    }
    .step-item {
      min-height: 44px;
      min-width: 44px;
    }
    .radio-item {
      min-height: 48px;
    }
    .dropdown-item {
      min-height: 48px;
    }
  }
  /* Accessibilité */
  @media (prefers-reduced-motion: reduce) {
    .mobile-modal {
      animation: none;
    }
    .modal-list-item {
      animation: none;
    }
    .modal-list-item:active {
      transform: none;
    }
  }
  /* Focus visible pour accessibilité */
  .modal-close-btn:focus-visible,
  .modal-search-input:focus-visible,
  .modal-list-item:focus-visible {
    outline: 3px solid #16a34a;
    outline-offset: 2px;
  }
  /* Indicateur de scroll */
  .mobile-modal-list::-webkit-scrollbar {
    width: 4px;
  }
  .mobile-modal-list::-webkit-scrollbar-track {
    background: #f1f5f9;
  }
  .mobile-modal-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
  }
  .mobile-modal-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }
  /* Animation pour les éléments de liste */
  .modal-list-item {
    animation: fadeInUp 0.2s ease-out;
  }
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  /* Amélioration de la lisibilité */
  .mobile-modal {
    font-feature-settings: 'kern' 1, 'liga' 1;
    text-rendering: optimizeLegibility;
  }
  /* Indicateur visuel pour les éléments sélectionnables */
  .modal-list-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: transparent;
    transition: background-color 0.2s ease;
  }
  .modal-list-item:hover::before {
    background: #16a34a;
  }
  </style>
  