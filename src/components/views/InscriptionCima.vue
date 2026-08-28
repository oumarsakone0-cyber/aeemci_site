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
              <p>Inscription CIMA / AG 2025</p>
            </div>
          </div>
        </div>
        <div class="header-right" v-if="currentStep > 1">
          <p class="step-info">Étape {{ currentStep - 1 }} sur {{ totalSteps }}</p>
          <div class="progress-bar">
            <div 
              class="progress-fill"
              :style="{ width: `${((currentStep - 1) / totalSteps) * 100}%` }"
            ></div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content" ref="mainContent">
      <div class="form-container">
        <!-- Étape 0: Choix initial -->
        <div v-if="currentStep === 0" class="step-content">
          <h2 class="step-heading">
            <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            Inscription CIMA / AG 2025
          </h2>
          
          <div class="choice-section">
            <div class="choice-container">
              <h3>Bienvenue sur la plateforme d'inscription</h3>
              <p class="choice-subtitle">Sélectionnez l'action que vous souhaitez effectuer</p>
              
              <div class="choice-buttons">
                <button
                  @click="startNewInscription"
                  type="button"
                  class="choice-btn choice-btn-primary"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                  </svg>
                  <div class="btn-content">
                    <span class="btn-title">M'inscrire au CIMA / AG 2025</span>
                    <span class="btn-subtitle">Nouvelle inscription pour la CIMA ou l'Assemblée Générale</span>
                  </div>
                </button>
                
                <button
                  @click="startVerification"
                  type="button"
                  class="choice-btn choice-btn-secondary"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  <div class="btn-content">
                    <span class="btn-title">Vérifier mon inscription</span>
                    <span class="btn-subtitle">Consulter le statut de mon inscription existante</span>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Progress Steps -->
        <div v-if="showProgressSteps" class="steps-header">
          <div class="steps-nav">
            <div
              v-for="(step, index) in steps"
              :key="index"
              class="step-item"
              :class="{
                'step-active': index + 2 === currentStep,
                'step-completed': index + 2 < currentStep,
                'step-disabled': index + 2 > currentStep
              }"
            >
              <div class="step-number">
                <span v-if="index + 2 < currentStep">✓</span>
                <span v-else>{{ index + 1 }}</span>
              </div>
              <span class="step-title">{{ step.title }}</span>
              <div
                v-if="index < steps.length - 1"
                class="step-connector"
                :class="{ 'connector-active': index + 2 < currentStep }"
              ></div>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <div v-if="currentStep > 0" class="form-content">
          <!-- Étape 1: Vérification du matricule -->
          <div v-if="currentStep === 1" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Vérification du matricule
            </h2>

            <div class="form-grid">
              <div class="form-group form-group-full">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                  </svg>
                  Matricule <span class="required">*</span>
                </label>
                <div class="input-container">
                  <input
                    v-model="matriculeInput"
                    type="text"
                    required
                    placeholder="XXX-XXX-XX-XXX"
                    maxlength="15"
                    @input="formatMatricule"
                    class="form-input"
                    :disabled="isVerifying"
                  />
                </div>
                <p class="form-help">Format: XXX-XXX-XX-XXX (lettres et chiffres)</p>
                
                <div v-if="verificationError" class="error-message">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <p>{{ verificationError }}</p>
                </div>
              </div>
            </div>

            <div class="form-navigation">
              <button
                @click="backToHome"
                type="button"
                class="btn btn-secondary"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Retour
              </button>
              <button
                @click="verifyMatricule"
                type="button"
                class="btn btn-primary"
                :disabled="isVerifying || !isMatriculeValid"
              >
                <div v-if="isVerifying" class="loading-spinner"></div>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ isVerifying ? 'Vérification...' : 'Vérifier le matricule' }}
              </button>
            </div>
          </div>

          <!-- Étape 2: Affichage des informations -->
          <div v-if="currentStep === 2" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Vos informations
            </h2>

            <!-- Message d'information sur le type d'inscription -->
            <div v-if="inscriptionType === 'AG'" class="info-blue-message">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <h4>Inscription à l'Assemblée Générale (AG)</h4>
                <p>Votre profil vous permet de vous inscrire à l'Assemblée Générale. Cette inscription est gratuite.</p>
              </div>
            </div>

            <div v-if="inscriptionType === 'CIMA'" class="success-message">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <h4>Inscription CIMA</h4>
                <p>Votre profil vous permet de vous inscrire à la CIMA.</p>
              </div>
            </div>

            <!-- Informations de l'utilisateur (lecture seule) -->
            <div v-if="foundUser" class="form-grid">
              <div class="form-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                  </svg>
                  Matricule
                </label>
                <div class="input-container">
                  <input
                    v-model="foundUser.matricule"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                  </svg>
                  Sexe
                </label>
                <div class="input-container">
                  <input
                    :value="foundUser.sexe === 'M' ? 'Masculin' : foundUser.sexe === 'F' ? 'Féminin' : foundUser.sexe"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                  </svg>
                  Prénom
                </label>
                <div class="input-container">
                  <input
                    v-model="foundUser.prenom"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                  </svg>
                  Nom
                </label>
                <div class="input-container">
                  <input
                    v-model="foundUser.nom"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 3z"/>
                  </svg>
                  Qualité/Rôle
                </label>
                <div class="input-container">
                  <input
                    v-model="foundUser.qualite_membre"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                  Téléphone
                </label>
                <div class="input-container">
                  <input
                    v-model="foundUser.telephone"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Secrétariat
                </label>
                <div class="input-container">
                  <input
                    v-model="foundUser.secretariat"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <div class="form-group" v-if="foundUser.sc && foundUser.sc.trim().length > 0">
                <label class="form-label">
                  <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Sous-comité
                </label>
                <div class="input-container">
                  <input
                    v-model="foundUser.sc"
                    type="text"
                    class="form-input"
                    disabled
                  />
                </div>
              </div>

              <!-- Information sur le montant (seulement pour CIMA) -->
              <div v-if="inscriptionType === 'CIMA'" class="form-group form-group-full">
                <div class="info-message">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <div>
                    <h4>Montant de l'inscription CIMA</h4>
                    <p class="amount">2 000 F CFA</p>
                    <p class="small-text">Le paiement sera effectué à l'étape suivante</p>
                  </div>
                </div>
              </div>

              <!-- Message pour AG (gratuit) -->
              <div v-if="inscriptionType === 'AG'" class="form-group form-group-full">
                <div class="info-message">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <div>
                    <h4>Inscription gratuite</h4>
                    <p class="small-text">L'inscription à l'Assemblée Générale est gratuite. Vous pouvez continuer directement.</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-navigation">
              <button
                @click="backToVerification"
                type="button"
                class="btn btn-secondary"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Retour
              </button>
              <button
                @click="proceedToPayment"
                type="button"
                class="btn btn-primary"
              >
                Continuer
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Étape 3: Paiement (seulement pour CIMA) -->
          <div v-if="currentStep === 3 && inscriptionType === 'CIMA'" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
              </svg>
              Informations de paiement
            </h2>

            <form @submit.prevent="submitInscription">
              <div class="form-grid">
                <!-- Récapitulatif -->
                <div class="form-group form-group-full">
                  <div class="recap-message">
                    <h4>Récapitulatif de l'inscription CIMA</h4>
                    <div class="recap-details">
                      <p><strong>Nom complet :</strong> {{ foundUser.prenom }} {{ foundUser.nom }}</p>
                      <p><strong>Matricule :</strong> {{ foundUser.matricule }}</p>
                      <p><strong>Type d'inscription :</strong> CIMA</p>
                      <p class="amount-line"><strong>Montant :</strong> <span class="amount">2 000 F CFA</span></p>
                    </div>
                  </div>
                </div>

                <!-- Contact de paiement -->
                <div class="form-group form-group-full">
                  <label class="form-label">
                    <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    Contact de paiement
                  </label>
                  <div class="input-container">
                    <input
                      v-model="contactPaiement"
                      type="tel"
                      class="form-input"
                      placeholder="Numéro de téléphone pour le paiement"
                    />
                  </div>
                  <p class="form-help">Numéro de téléphone mobile pour la transaction</p>
                </div>
              </div>

              <div class="form-navigation">
                <button
                  @click="backToInformations"
                  type="button"
                  class="btn btn-secondary"
                >
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                  </svg>
                  Retour
                </button>
                <button
                  type="submit"
                  class="btn btn-primary"
                  :disabled="isSubmitting"
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

          <!-- Étape 3: Confirmation (pour AG - inscription gratuite) -->
          <div v-if="currentStep === 3 && inscriptionType === 'AG'" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Confirmation de l'inscription
            </h2>

            <form @submit.prevent="submitInscription">
              <div class="form-grid">
                <!-- Récapitulatif -->
                <div class="form-group form-group-full">
                  <div class="recap-message">
                    <h4>Récapitulatif de l'inscription AG</h4>
                    <div class="recap-details">
                      <p><strong>Nom complet :</strong> {{ foundUser.prenom }} {{ foundUser.nom }}</p>
                      <p><strong>Matricule :</strong> {{ foundUser.matricule }}</p>
                      <p><strong>Type d'inscription :</strong> Assemblée Générale (AG)</p>
                      <p class="amount-line"><strong>Montant :</strong> <span class="free-tag">Gratuit</span></p>
                    </div>
                  </div>
                </div>

                <div class="form-group form-group-full">
                  <div class="success-message">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                      <h4>Inscription gratuite</h4>
                      <p>Cliquez sur "Confirmer l'inscription" pour finaliser votre inscription à l'Assemblée Générale.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-navigation">
                <button
                  @click="backToInformations"
                  type="button"
                  class="btn btn-secondary"
                >
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                  </svg>
                  Retour
                </button>
                <button
                  type="submit"
                  class="btn btn-primary"
                  :disabled="isSubmitting"
                >
                  <div v-if="isSubmitting" class="loading-spinner"></div>
                  <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  {{ isSubmitting ? 'Traitement en cours...' : 'Confirmer l\'inscription' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal de succès -->
    <div v-if="showSuccessModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <div class="success-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </div>
          <h3>Inscription réussie !</h3>
          <p>Votre inscription à {{ inscriptionType === 'CIMA' ? 'la CIMA' : 'l\'Assemblée Générale' }} a été enregistrée avec succès.</p>
        </div>

        <div class="modal-actions">
          <button @click="closeSuccessModal" class="btn btn-primary">
            Terminer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const API_BASE_URL = 'https://api.aeemci-ce.ci/cima_api.php'

// Références
const mainContent = ref(null)

// État
const currentStep = ref(0)
const matriculeInput = ref('')
const isVerifying = ref(false)
const verificationError = ref('')
const foundUser = ref(null)
const contactPaiement = ref('')
const isSubmitting = ref(false)
const showSuccessModal = ref(false)
const inscriptionType = ref('') // 'CIMA' ou 'AG'
const isVerificationMode = ref(false) // Mode vérification ou inscription

// Computed
const totalSteps = computed(() => 3)
const steps = computed(() => [
  { title: 'Vérification' },
  { title: 'Informations' },
  { title: inscriptionType.value === 'CIMA' ? 'Paiement' : 'Confirmation' }
])

const showProgressSteps = computed(() => currentStep.value > 0)

const isMatriculeValid = computed(() => {
  const regex = /^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{2}-[0-9]{3}$/
  return regex.test(matriculeInput.value)
})

// Fonction de scroll automatique
const scrollToTop = () => {
  if (mainContent.value) {
    mainContent.value.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Formatage du matricule
const formatMatricule = () => {
  const value = matriculeInput.value.toUpperCase()
  let formatted = value.replace(/[^A-Z0-9]/g, '')
  
  if (formatted.length > 3) {
    formatted = formatted.slice(0, 3) + '-' + formatted.slice(3)
  }
  if (formatted.length > 7) {
    formatted = formatted.slice(0, 7) + '-' + formatted.slice(7)
  }
  if (formatted.length > 10) {
    formatted = formatted.slice(0, 10) + '-' + formatted.slice(10)
  }
  if (formatted.length > 15) {
    formatted = formatted.slice(0, 15)
  }
  
  matriculeInput.value = formatted
  verificationError.value = ''
}

// Démarrer une nouvelle inscription
const startNewInscription = () => {
  isVerificationMode.value = false
  currentStep.value = 1
  scrollToTop()
}

// Démarrer la vérification
const startVerification = () => {
  isVerificationMode.value = true
  currentStep.value = 1
  scrollToTop()
}

// Retour à l'accueil
const backToHome = () => {
  currentStep.value = 0
  matriculeInput.value = ''
  foundUser.value = null
  inscriptionType.value = ''
  verificationError.value = ''
  isVerificationMode.value = false
  scrollToTop()
}

// Vérifier le matricule
const verifyMatricule = async () => {
  if (!isMatriculeValid.value) {
    verificationError.value = 'Format de matricule invalide. Utilisez le format XXX-XXX-XX-XXX'
    return
  }

  // 🔹 MODE VÉRIFICATION: Redirection directe vers page de vérification
  if (isVerificationMode.value) {
    console.log('🔍 Mode vérification - Redirection vers:', `/verification_cima/${matriculeInput.value}`)
    router.push(`/verification_cima/${matriculeInput.value}`)
    return
  }

  // 🔹 MODE INSCRIPTION: Continuer le processus normal
  isVerifying.value = true
  verificationError.value = ''
  foundUser.value = null
  inscriptionType.value = ''

  try {
    const response = await fetch(
      `${API_BASE_URL}?action=verify_matricule&matricule=${encodeURIComponent(matriculeInput.value)}`,
      {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
      }
    )

    const text = await response.text()
    console.log('📄 Réponse brute:', text)
    const data = text ? JSON.parse(text) : {}
    console.log('📦 Données parsées:', data)

    // Cas 1: Erreur avec user_info (inscription AG)
    if (!response.ok || data.error) {
      console.warn('⚠️ Inscription AG détectée')
      
      if (data.error?.user_info) {
        foundUser.value = data.error.user_info
        inscriptionType.value = 'AG'
        console.log('📌 user_info récupéré pour AG:', foundUser.value)
        currentStep.value = 2
        scrollToTop()
      } else {
        verificationError.value = data.error?.message || 'Matricule introuvable'
      }
      
      return
    }

    // Cas 2: Success avec user (inscription CIMA)
    if (data.success && data.user) {
      foundUser.value = data.user
      inscriptionType.value = 'CIMA'
      console.log('✅ user récupéré pour CIMA:', foundUser.value)
      currentStep.value = 2
      scrollToTop()
      return
    }

  } catch (error) {
    console.error('❌ Erreur de connexion:', error)
    verificationError.value = 'Erreur de connexion au serveur. Veuillez réessayer.'
  } finally {
    isVerifying.value = false
  }
}

// Navigation
const backToVerification = () => {
  currentStep.value = 1
  foundUser.value = null
  inscriptionType.value = ''
  verificationError.value = ''
  scrollToTop()
}

const proceedToPayment = () => {
  currentStep.value = 3
  scrollToTop()
}

const backToInformations = () => {
  currentStep.value = 2
  scrollToTop()
}

// Soumettre l'inscription
const submitInscription = async () => {
  isSubmitting.value = true

  try {
    const inscriptionData = {
      matricule_membre: foundUser.value.matricule,
      prenom: foundUser.value.prenom,
      nom: foundUser.value.nom,
      qualite_membre: foundUser.value.qualite_membre,
      telephone: foundUser.value.telephone,
      ville_ou_commune: foundUser.value.secretariat,
      sexe: foundUser.value.sexe,
      sc: foundUser.value.sc || '',
      cima: inscriptionType.value === 'CIMA' ? 'oui' : 'non',
      contact_paiement: inscriptionType.value === 'CIMA' ? contactPaiement.value : '',
      somme_payee: 0,
      statut_paiement: 'non_paye',
      reference_paiement: ''
    }

    console.log('📤 Envoi inscription:', inscriptionData)

    const response = await fetch(`${API_BASE_URL}?action=create_registration`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(inscriptionData)
    })

    const data = await response.json()
    console.log('📥 Réponse inscription:', data)

    if (!response.ok || !data.success) {
      alert(data.error || "Erreur lors de l'enregistrement de l'inscription")
      return
    }

    // 🔹 INSCRIPTION CIMA: Redirection vers Wave ou page de vérification
    if (inscriptionType.value === 'CIMA') {
      console.log('💳 Inscription CIMA - Traitement paiement')
      if (data.wave_launch_url) {
        console.log('🌊 Redirection vers Wave:', data.wave_launch_url)
        window.location.href = data.wave_launch_url
      } else {
        console.log('📄 Redirection vers page de vérification CIMA')
        router.push(`/verification_cima/${foundUser.value.matricule}`)
      }
      return
    }

    // 🔹 INSCRIPTION AG: Redirection directe vers page de vérification
    if (inscriptionType.value === 'AG') {
      console.log('✅ Inscription AG gratuite - Redirection vers page de vérification')
      router.push(`/verification_cima/${foundUser.value.matricule}`)
      return
    }

  } catch (error) {
    console.error('❌ Erreur inscription:', error)
    alert('Erreur de connexion. Veuillez réessayer.')
  } finally {
    isSubmitting.value = false
  }
}

// Fermer le modal et réinitialiser
const closeSuccessModal = () => {
  showSuccessModal.value = false
  currentStep.value = 0
  matriculeInput.value = ''
  foundUser.value = null
  contactPaiement.value = ''
  verificationError.value = ''
  inscriptionType.value = ''
  isVerificationMode.value = false
  scrollToTop()
}
</script>

<style scoped>
/* ... (garder tous les styles existants identiques) ... */
/* Reset et base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app-container {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--green-mist) 0%, var(--surface) 100%);
  font-family: var(--display);
}

/* Header */
.app-header {
  max-width: 1200px;
  margin: 0 auto;
  background: #ffffff;
  border-bottom: 1px solid var(--line);
  box-shadow: var(--shadow);
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
  background: var(--green);
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-text h1 {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--green);
  margin: 0;
}

.logo-text p {
  font-size: 0.875rem;
  color: var(--ink-soft);
  margin: 0;
}

.header-right {
  text-align: right;
}

.step-info {
  font-size: 0.875rem;
  color: var(--ink-soft);
  margin-bottom: 0.25rem;
}

.progress-bar {
  width: 8rem;
  height: 0.5rem;
  background: var(--line);
  border-radius: 0;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: var(--green);
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
  border-radius: 0;
  box-shadow: var(--shadow);
  overflow: hidden;
}

/* Styles pour les sections de choix */
.choice-section {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 500px;
  padding: 2rem;
}

.choice-container {
  background: var(--green-mist);
  border: 2px solid var(--green);
  border-radius: 0;
  padding: 3rem;
  text-align: center;
  max-width: 800px;
  width: 100%;
}

.choice-container h3 {
  font-size: 2rem;
  font-weight: bold;
  color: var(--green);
  margin-bottom: 1rem;
}

.choice-subtitle {
  font-size: 1.125rem;
  color: var(--ink-soft);
  margin-bottom: 3rem;
}

.choice-buttons {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  max-width: 600px;
  margin: 0 auto;
}

.choice-btn {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  padding: 2rem;
  border: 2px solid;
  border-radius: 0;
  background: #ffffff;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: left;
  font-size: 1rem;
  font-weight: 500;
}

.choice-btn-primary {
  border-color: var(--green);
  color: var(--green);
}

.choice-btn-primary:hover {
  background: var(--green-mist);
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

.choice-btn-secondary {
  border-color: #3b82f6;
  color: #1d4ed8;
}

.choice-btn-secondary:hover {
  background: #eff6ff;
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

.choice-btn svg {
  flex-shrink: 0;
  width: 3rem;
  height: 3rem;
}

.btn-content {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.btn-title {
  font-size: 1.25rem;
  font-weight: 700;
}

.btn-subtitle {
  font-size: 0.875rem;
  opacity: 0.8;
  font-weight: 400;
}

/* Steps Header */
.steps-header {
  background: var(--green-mist);
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
  color: var(--ink-soft);
}

.step-item.step-active .step-number {
  background: var(--green);
  color: #ffffff;
  border-color: var(--green);
}

.step-item.step-completed .step-number {
  background: var(--green);
  color: #ffffff;
  border-color: var(--green);
}

.step-title {
  font-size: 0.75rem;
  font-weight: 500;
  color: var(--ink-soft);
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
  background: var(--green);
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
  color: var(--ink);
  margin-bottom: 2rem;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
}

.heading-icon {
  color: var(--green);
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
  color: var(--ink-soft);
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.label-icon {
  color: var(--green);
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
  border: 2px solid #d1d5db;
  border-radius: 0;
  font-size: 1rem;
  transition: all 0.2s ease;
  background: #ffffff;
}

.form-input:focus {
  outline: none;
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
}

.form-input:disabled {
  background: #f3f4f6;
  color: var(--muted);
  cursor: not-allowed;
}

.form-help {
  font-size: 0.75rem;
  color: var(--muted);
  margin-top: 0.25rem;
}

/* Free tag styling */
.free-tag {
  color: #059669;
  font-size: 1.25rem;
  font-weight: bold;
  background: var(--green-mist);
  padding: 0.25rem 0.75rem;
  border-radius: 0;
}

/* Messages */
.error-message {
  margin-top: 1rem;
  padding: 1rem;
  background: #fee2e2;
  border: 2px solid #fca5a5;
  border-radius: 0;
  display: flex;
  align-items: start;
  gap: 0.75rem;
}

.error-message svg {
  color: #dc2626;
  flex-shrink: 0;
  margin-top: 0.125rem;
}

.error-message p {
  color: #7f1d1d;
  font-size: 0.875rem;
  line-height: 1.5;
}

.info-blue-message {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: #dbeafe;
  border: 2px solid #93c5fd;
  border-radius: 0;
  display: flex;
  align-items: start;
  gap: 0.75rem;
}

.info-blue-message svg {
  color: #2563eb;
  flex-shrink: 0;
  margin-top: 0.125rem;
}

.info-blue-message h4 {
  color: #1e3a8a;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.info-blue-message p {
  color: #1e40af;
  font-size: 0.875rem;
  line-height: 1.5;
}

.success-message {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: var(--green-mist);
  border: 2px solid #6ee7b7;
  border-radius: 0;
  display: flex;
  align-items: start;
  gap: 0.75rem;
}

.success-message svg {
  color: #059669;
  flex-shrink: 0;
  margin-top: 0.125rem;
}

.success-message h4 {
  color: var(--green);
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.success-message p {
  color: var(--green);
  font-size: 0.875rem;
  line-height: 1.5;
}

.info-message {
  padding: 1rem;
  background: var(--green-mist);
  border: 2px solid var(--green-mist);
  border-radius: 0;
  display: flex;
  align-items: start;
  gap: 0.75rem;
}

.info-message svg {
  color: var(--green);
  flex-shrink: 0;
  margin-top: 0.125rem;
}

.info-message h4 {
  color: var(--green);
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.info-message .amount {
  color: var(--green);
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 0.25rem;
}

.info-message .small-text {
  color: var(--green);
  font-size: 0.75rem;
}

.recap-message {
  padding: 1rem;
  background: var(--green-mist);
  border: 2px solid var(--green-mist);
  border-radius: 0;
}

.recap-message h4 {
  color: var(--green);
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.recap-details {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.recap-details p {
  color: var(--green);
  font-size: 0.875rem;
}

.recap-details .amount-line {
  margin-top: 0.5rem;
  padding-top: 0.5rem;
  border-top: 1px solid var(--green-mist);
}

.recap-details .amount {
  color: var(--green);
  font-size: 1.25rem;
  font-weight: bold;
}

/* Navigation Buttons */
.form-navigation {
  display: flex;
  justify-content: space-between;
  padding: 1.5rem 0;
  margin-top: 2rem;
  border-top: 1px solid var(--line);
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn-primary {
  background: var(--green);
  color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
  background: var(--green);
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-secondary {
  background: #f3f4f6;
  color: var(--ink-soft);
}

.btn-secondary:hover {
  background: var(--line);
}

.loading-spinner {
  width: 1rem;
  height: 1rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid #ffffff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
  z-index: 1000;
  padding: 1rem;
}

.modal-content {
  background: #ffffff;
  border-radius: 0;
  padding: 2rem;
  max-width: 500px;
  width: 100%;
  box-shadow: var(--shadow);
}

.modal-header {
  text-align: center;
  margin-bottom: 2rem;
}

.success-icon {
  width: 4rem;
  height: 4rem;
  background: var(--green-mist);
  color: var(--green);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
}

.modal-header h3 {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--ink);
  margin-bottom: 1rem;
}

.modal-header p {
  font-size: 1rem;
  color: var(--ink-soft);
}

.modal-actions {
  display: flex;
  justify-content: center;
}

.modal-actions .btn {
  flex: 1;
  max-width: 200px;
}

/* Responsive */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }

  .header-right {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .choice-buttons {
    flex-direction: column;
  }

  .choice-btn {
    padding: 1.5rem;
  }

  .steps-nav {
    overflow-x: auto;
    padding-bottom: 1rem;
  }

  .step-item {
    min-width: 100px;
  }
}

@media (max-width: 480px) {
  .main-content {
    padding: 1rem;
  }

  .form-container {
    border-radius: 0;
  }

  .form-content {
    padding: 1rem;
  }

  .choice-container {
    padding: 1.5rem;
  }

  .modal-content {
    padding: 1.5rem;
  }
}
</style>