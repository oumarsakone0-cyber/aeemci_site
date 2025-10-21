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
                <p>Inscription des Bacheliers</p>
              </div>
            </div>
          </div>
          <div class="header-right">
            <p class="step-info">Étape {{ currentStep }} sur {{ totalSteps }}</p>
            <div class="progress-bar">
              <div
                class="progress-fill"
                :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
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
                  'step-active': index + 1 === currentStep,
                  'step-completed': index + 1 < currentStep,
                  'step-disabled': index + 1 > currentStep
                }"
              >
                <div class="step-number">
                  <span v-if="index + 1 < currentStep">✓</span>
                  <span v-else>{{ index + 1 }}</span>
                </div>
                <span class="step-title">{{ step.title }}</span>
                <div
                  v-if="index < steps.length - 1"
                  class="step-connector"
                  :class="{ 'connector-active': index + 1 < currentStep }"
                ></div>
              </div>
            </div>
          </div>
  
          <!-- Form Content -->
          <div class="form-content">
            <form @submit.prevent="soumettreInscription">
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
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                      </svg>
                      Prénom(s) <span class="required">*</span>
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
                        <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                      </svg>
                      Date de naissance <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.dateNaissance"
                        type="date"
                        required
                        class="form-input"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2 2A22 22 0 0 1 2 2c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.23a2 2 0 0 1 .5 1.66v.82a2 2 0 0 1-.9 1.66l-1.23.82a2 2 0 0 1-1.66.5H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.23a2 2 0 0 1 .5 1.66v.82a2 2 0 0 1-.9 1.66l-1.23.82a2 2 0 0 1-1.66.5H4z"/>
                      </svg>
                      Contact <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.contact"
                        @input="formatContact"
                        type="tel"
                        required
                        class="form-input"
                        placeholder="Ex: 00 00 00 00 00"
                        maxlength="14"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.93 1.93 0 0 1-2.06 0L2 7"/>
                      </svg>
                      Email
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.email"
                        type="email"
                        class="form-input"
                        placeholder="Votre adresse email (facultatif)"
                      />
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Étape 2: Informations de l'Examen -->
              <div v-if="currentStep === 2" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Informations de l'Examen
                </h2>
  
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Série de Bac <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <select
                        v-model="formData.serieBac"
                        required
                        class="form-input"
                      >
                        <option value="">Sélectionnez votre série</option>
                        <option value="A">Série A</option>
                        <option value="D">Série D</option>
                        <option value="C">Série C</option>
                        <option value="Technique">Séries Technique</option>
                        <option value="Comptable">Séries Comptable</option>
                        <option value="Assistanat de gestion">Assistanat de gestion</option>
                      </select>
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Nombre de points au Bac <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model.number="formData.nombreDePointBac"
                        type="number"
                        step="0.01"
                        required
                        class="form-input"
                        placeholder="Ex: 250.50"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Matricule du BAC <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.matriculeBac"
                        type="text"
                        required
                        class="form-input"
                        placeholder="Votre matricule du BAC"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Ville <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.ville"
                        type="text"
                        required
                        class="form-input"
                        placeholder="Votre ville de résidence"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Filière souhaitée
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.filiereSouhaitee"
                        type="text"
                        class="form-input"
                        placeholder="Ex: Informatique (facultatif)"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Moyenne de classe
                    </label>
                    <div class="input-container">
                      <input
                        v-model.number="formData.moyenneClasse"
                        type="number"
                        step="0.01"
                        class="form-input"
                        placeholder="Ex: 12.75 (facultatif)"
                      />
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Centre d'examen
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.centreExamen"
                        type="text"
                        class="form-input"
                        placeholder="Ex: Lycée Classique (facultatif)"
                      />
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Étape 3: Téléchargement de la Collante du Bac -->
              <div v-if="currentStep === 3" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm0-8H9V5h6v2z"/>
                  </svg>
                  Collante du Bac
                </h2>
  
                <div class="file-upload-section">
                  <label class="form-label">Téléchargez votre collante du Bac <span class="required">*</span></label>
                  <div class="file-upload-area">
                    <input
                      ref="collanteBacInput"
                      type="file"
                      accept="image/*,application/pdf"
                      @change="uploadCollanteBac"
                      class="file-input"
                    />
                    <div v-if="!formData.collanteBac && !isUploading" @click="$refs.collanteBacInput.click()" class="file-placeholder">
                      <svg class="upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/>
                      </svg>
                      <p>Cliquez pour ajouter votre fichier</p>
                      <small>Formats acceptés : PDF, JPG, PNG, GIF (max 5MB)</small>
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
                    <div v-if="formData.collanteBac && !isUploading" class="file-preview">
                      <template v-if="isImageFile(formData.collanteBac)">
                        <img :src="formData.collanteBac" alt="Collante du Bac" class="preview-image" />
                      </template>
                      <template v-else>
                        <div class="pdf-preview">
                          <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                          </svg>
                          <p>Fichier PDF téléchargé</p>
                          <a :href="formData.collanteBac" target="_blank" class="view-file-link">Voir le fichier</a>
                        </div>
                      </template>
                      <button
                        @click="removeCollanteBac"
                        type="button"
                        class="remove-file"
                      >
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Étape 4: Confirmation et Soumission -->
              <div v-if="currentStep === 4" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  Récapitulatif et Soumission
                </h2>
  
                <div class="summary-section">
                  <h3>Vos informations :</h3>
                  <div class="summary-grid">
                    <div class="summary-item">
                      <strong>Nom :</strong> {{ formData.nom }}
                    </div>
                    <div class="summary-item">
                      <strong>Prénom :</strong> {{ formData.prenom }}
                    </div>
                    <div class="summary-item">
                      <strong>Date de Naissance :</strong> {{ formData.dateNaissance }}
                    </div>
                    <div class="summary-item">
                      <strong>Contact :</strong> {{ formData.contact }}
                    </div>
                    <div class="summary-item">
                      <strong>Email :</strong> {{ formData.email || 'Non renseigné' }}
                    </div>
                    <div class="summary-item">
                      <strong>Série de Bac :</strong> {{ formData.serieBac }}
                    </div>
                    <div class="summary-item">
                      <strong>Nombre de points au Bac :</strong> {{ formData.nombreDePointBac }}
                    </div>
                    <div class="summary-item">
                      <strong>Matricule du BAC :</strong> {{ formData.matriculeBac }}
                    </div>
                    <div class="summary-item">
                      <strong>Ville :</strong> {{ formData.ville }}
                    </div>
                    <div class="summary-item">
                      <strong>Filière souhaitée :</strong> {{ formData.filiereSouhaitee || 'Non renseignée' }}
                    </div>
                    <div class="summary-item">
                      <strong>Moyenne de classe :</strong> {{ formData.moyenneClasse || 'Non renseignée' }}
                    </div>
                    <div class="summary-item">
                      <strong>Centre d'examen :</strong> {{ formData.centreExamen || 'Non renseigné' }}
                    </div>
                    <div class="summary-item summary-item-full">
                      <strong>Collante du Bac :</strong>
                      <a v-if="formData.collanteBac" :href="formData.collanteBac" target="_blank" class="file-link">
                        {{ getFileName(formData.collanteBac) }}
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>
                        </svg>
                      </a>
                      <span v-else>Non téléchargée</span>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Navigation Buttons -->
              <div class="form-navigation">
                <button
                  v-if="currentStep > 1"
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
                  {{ isSubmitting ? 'Soumission en cours...' : 'Finaliser l\'inscription' }}
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
            <p>Votre inscription en tant que bachelier a été enregistrée avec succès.</p>
          </div>
  
          <div class="modal-actions">
            <button @click="resetForm" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
              </svg>
              Nouvelle inscription
            </button>
            <button @click="downloadFiche" class="btn btn-secondary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
              </svg>
              Télécharger votre fiche
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import axios from 'axios'
  
  // Configuration API
  const API_BASE_URL = 'https://sogetrag.com/api/' // Remplacez par votre URL d'API si différente
  
  // Configuration Cloudinary
  const cloudinaryConfig = {
    cloudName: 'dqk65objc',
    uploadPreset: 'sadeal',
    apiKey: '784574272958761',
    uploadUrl: 'https://api.cloudinary.com/v1_1/dqk65objc/image/upload'
  }
  
  // Références
  const collanteBacInput = ref(null)
  
  // État du formulaire
  const currentStep = ref(1)
  const totalSteps = 4 // Informations personnelles, Informations de l'Examen, Collante du Bac, Récapitulatif
  const showConfirmation = ref(false)
  const isUploading = ref(false)
  const isSubmitting = ref(false)
  const uploadProgress = ref(0)
  const uploadError = ref('')
  
  // Données du formulaire
  const formData = ref({
    nom: '',
    prenom: '',
    dateNaissance: '',
    contact: '',
    email: '',
    serieBac: '',
    nombreDePointBac: null, // Nouveau champ
    filiereSouhaitee: '',   // Nouveau champ (non obligatoire)
    moyenneClasse: null,    // Nouveau champ (non obligatoire)
    centreExamen: '',       // Nouveau champ (non obligatoire)
    matriculeBac: '',       // Nouveau champ
    ville: '',              // Nouveau champ
    collanteBac: null,      // URL du fichier téléchargé
    collanteBacId: null     // Public ID de Cloudinary
  })
  
  // Étapes du formulaire
  const steps = [
    { title: 'Infos Personnelles' },
    { title: 'Infos Examen' },
    { title: 'Collante du Bac' },
    { title: 'Confirmation' }
  ]
  
  // Computed properties
  const canProceedToNextStep = computed(() => {
    switch (currentStep.value) {
      case 1:
        // Nom, Prénom, Date de naissance, Contact sont obligatoires
        return formData.value.nom && formData.value.prenom &&
               formData.value.dateNaissance && formData.value.contact &&
               /^\d{2}\s\d{2}\s\d{2}\s\d{2}\s\d{2}$/.test(formData.value.contact) &&
               (formData.value.email === '' || (formData.value.email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)))
      case 2:
        // Série de Bac, Nombre de points, Matricule du BAC, Ville sont obligatoires
        return formData.value.serieBac && formData.value.nombreDePointBac !== null &&
               formData.value.matriculeBac && formData.value.ville
      case 3:
        return formData.value.collanteBac && !isUploading.value
      case 4:
        return true // Dernière étape, prête à soumettre
      default:
        return false
    }
  })
  
  const canSubmit = computed(() => canProceedToNextStep.value)
  
  // Fonctions de formatage
  const formatUpperCase = (field) => {
    formData.value[field] = formData.value[field].toUpperCase()
  }
  
  const formatFirstUpperCase = (field) => {
    const value = formData.value[field]
    if (value && typeof value === 'string') {
      formData.value[field] = value.charAt(0).toUpperCase() + value.slice(1)
    }
  }
  
  const formatContact = () => {
    let value = formData.value.contact.replace(/\s/g, ''); // Supprimer tous les espaces
    let formattedValue = '';
    for (let i = 0; i < value.length; i++) {
      if (i > 0 && i % 2 === 0) {
        formattedValue += ' ';
      }
      formattedValue += value[i];
    }
    formData.value.contact = formattedValue;
  }
  
  // Upload de fichier vers Cloudinary
  const uploadFileToCloudinary = async (file) => {
    try {
      uploadError.value = ''
  
      const fileName = `aeemci_bac_${Date.now()}_${file.name.replace(/\s+/g, '_')}`
  
      const uploadFormData = new FormData()
      uploadFormData.append('file', file)
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
        formData.value.collanteBac = response.data.secure_url
        formData.value.collanteBacId = response.data.public_id
  
        console.log('Fichier assigné:', formData.value.collanteBac)
        return true
      } else {
        throw new Error('Réponse Cloudinary invalide')
      }
  
    } catch (error) {
      console.error('Erreur lors du téléchargement vers Cloudinary:', error)
      uploadError.value = 'Erreur lors du téléchargement du fichier. Veuillez réessayer.'
      return false
    }
  }
  
  const uploadCollanteBac = async (e) => {
    isUploading.value = true
    uploadProgress.value = 0
    uploadError.value = ''
  
    try {
      const files = e.target.files || e.dataTransfer.files
      if (!files.length) {
        isUploading.value = false
        return
      }
  
      const file = files[0]
  
      // Vérifier le type de fichier
      const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf']
      if (!allowedTypes.includes(file.type)) {
        uploadError.value = 'Veuillez sélectionner un fichier JPG, PNG, GIF ou PDF valide.'
        isUploading.value = false
        if (e.target) e.target.value = ''
        return
      }
  
      // Vérifier la taille du fichier (max 5MB)
      if (file.size > 5 * 1024 * 1024) {
        uploadError.value = 'La taille du fichier ne doit pas dépasser 5MB.'
        isUploading.value = false
        if (e.target) e.target.value = ''
        return
      }
  
      formData.value.collanteBac = null
      formData.value.collanteBacId = null
  
      const success = await uploadFileToCloudinary(file)
  
      if (!success) {
        if (e.target) e.target.value = ''
      }
  
    } catch (error) {
      console.error('Erreur upload:', error)
      uploadError.value = 'Erreur lors de l\'upload du fichier: ' + error.message
      if (e.target) e.target.value = ''
    } finally {
      isUploading.value = false
      uploadProgress.value = 0
    }
  }
  
  // Supprimer la collante du Bac
  const removeCollanteBac = () => {
    formData.value.collanteBac = null
    formData.value.collanteBacId = null
    if (collanteBacInput.value) {
      collanteBacInput.value.value = ''
    }
  }
  
  // Vérifier si le fichier est une image pour l'aperçu
  const isImageFile = (url) => {
    if (!url) return false;
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif'];
    const lowerCaseUrl = url.toLowerCase();
    return imageExtensions.some(ext => lowerCaseUrl.includes(ext));
  }
  
  // Obtenir le nom du fichier à partir de l'URL
  const getFileName = (url) => {
    if (!url) return '';
    const parts = url.split('/');
    return parts[parts.length - 1];
  }
  
  // Fonctions de navigation
  const nextStep = () => {
    if (canProceedToNextStep.value && currentStep.value < totalSteps) {
      currentStep.value++
    }
  }
  
  const previousStep = () => {
    if (currentStep.value > 1) {
      currentStep.value--
    }
  }
  
  // Fonction de soumission
  const soumettreInscription = async () => {
    if (!canSubmit.value) return
    isSubmitting.value = true
  
    try {
      // Simuler un délai de soumission
      await new Promise(resolve => setTimeout(resolve, 1500))
  
      const response = await axios.post(`${API_BASE_URL}/bachelier-api.php`, formData.value);
  
      if (response.data.success) {
        showConfirmation.value = true;
      } else {
        throw new Error(response.data.message || 'Erreur inattendue lors de l\'inscription.');
      }
    } catch (error) {
      console.error('Erreur lors de l\'inscription:', error);
      alert('Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
    } finally {
      isSubmitting.value = false;
    }
  }
  
  // Fonction pour télécharger la fiche
  const downloadFiche = () => {
    const ficheContent = `
      <!DOCTYPE html>
      <html>
      <head>
          <title>Fiche d'Inscription Bachelier - AEEMCI</title>
          <style>
              body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20mm; font-size: 12pt; color: #333; }
              .container { max-width: 800px; margin: 0 auto; border: 1px solid #eee; padding: 30px; box-shadow: 0 0 15px rgba(0,0,0,0.05); border-radius: 8px; }
              .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
              .header svg { height: 60px; width: 60px; margin-bottom: 10px; color: #16a34a; }
              .header h1 { font-size: 28pt; color: #16a34a; margin: 0; font-weight: bold; }
              .header p { font-size: 14pt; color: #4b5563; margin: 5px 0 0; }
              .section-title { font-size: 18pt; font-weight: bold; color: #1f2937; border-bottom: 2px solid #16a34a; padding-bottom: 8px; margin-top: 30px; margin-bottom: 20px; }
              .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; margin-bottom: 20px; }
              .info-item { padding: 12px 15px; border: 1px solid #e5e7eb; border-radius: 6px; background-color: #f9fafb; }
              .info-item strong { color: #1f2937; display: block; margin-bottom: 4px; font-size: 10pt; text-transform: uppercase; }
              .info-item span { color: #374151; font-size: 11pt; }
              .info-item-full { grid-column: 1 / -1; }
              .file-link { color: #16a34a; text-decoration: underline; display: inline-flex; align-items: center; gap: 5px; }
              .file-link svg { width: 14px; height: 14px; }
              .collante-preview img { max-width: 100%; height: auto; display: block; margin-top: 15px; border: 1px solid #ddd; border-radius: 4px; }
              .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; font-size: 10pt; color: #6b7280; }
              @media print {
                  body { margin: 0; padding: 0; }
                  .container { box-shadow: none; border: none; padding: 15mm; }
                  .header, .section-title, .footer { page-break-after: avoid; }
                  .info-grid { page-break-inside: avoid; }
              }
          </style>
      </head>
      <body>
          <div class="container">
              <div class="header">
                  <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                  </svg>
                  <h1>AEEMCI</h1>
                  <p>Fiche d'Inscription Bachelier</p>
              </div>
  
              <div class="section-title">Informations Personnelles</div>
              <div class="info-grid">
                  <div class="info-item"><strong>Nom:</strong> <span>${formData.value.nom}</span></div>
                  <div class="info-item"><strong>Prénom(s):</strong> <span>${formData.value.prenom}</span></div>
                  <div class="info-item"><strong>Date de Naissance:</strong> <span>${formData.value.dateNaissance}</span></div>
                  <div class="info-item"><strong>Contact:</strong> <span>${formData.value.contact}</span></div>
                  <div class="info-item"><strong>Email:</strong> <span>${formData.value.email || 'Non renseigné'}</span></div>
              </div>
  
              <div class="section-title">Informations de l'Examen</div>
              <div class="info-grid">
                  <div class="info-item"><strong>Série de Bac:</strong> <span>${formData.value.serieBac}</span></div>
                  <div class="info-item"><strong>Nombre de points au Bac:</strong> <span>${formData.value.nombreDePointBac}</span></div>
                  <div class="info-item"><strong>Matricule du BAC:</strong> <span>${formData.value.matriculeBac}</span></div>
                  <div class="info-item"><strong>Ville:</strong> <span>${formData.value.ville}</span></div>
                  <div class="info-item"><strong>Filière souhaitée:</strong> <span>${formData.value.filiereSouhaitee || 'Non renseignée'}</span></div>
                  <div class="info-item"><strong>Moyenne de classe:</strong> <span>${formData.value.moyenneClasse || 'Non renseignée'}</span></div>
                  <div class="info-item"><strong>Centre d'examen:</strong> <span>${formData.value.centreExamen || 'Non renseigné'}</span></div>
              </div>
  
              <div class="section-title">Collante du Bac</div>
              <div class="info-grid">
                  <div class="info-item info-item-full">
                      <strong>Fichier:</strong>
                      ${formData.value.collanteBac ? `<a href="${formData.value.collanteBac}" target="_blank" class="file-link">${getFileName(formData.value.collanteBac)} <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg></a>` : '<span>Non téléchargée</span>'}
                  </div>
                  ${formData.value.collanteBac && isImageFile(formData.value.collanteBac) ? `<div class="info-item info-item-full collante-preview"><img src="${formData.value.collanteBac}" alt="Collante du Bac"></div>` : ''}
              </div>
  
              <div class="footer">
                  <p>Fiche générée le ${new Date().toLocaleDateString('fr-FR')} à ${new Date().toLocaleTimeString('fr-FR')}</p>
                  <p>Association des Élèves et Étudiants Musulmans de Côte d'Ivoire (AEEMCI)</p>
              </div>
          </div>
      </body>
      </html>
    `;
  
    const newWindow = window.open('', '_blank');
    newWindow.document.write(ficheContent);
    newWindow.document.close();
    newWindow.focus();
    newWindow.print();
  }
  
  // Réinitialisation du formulaire
  const resetForm = () => {
    formData.value = {
      nom: '',
      prenom: '',
      dateNaissance: '',
      contact: '',
      email: '',
      serieBac: '',
      nombreDePointBac: null,
      filiereSouhaitee: '',
      moyenneClasse: null,
      centreExamen: '',
      matriculeBac: '',
      ville: '',
      collanteBac: null,
      collanteBacId: null
    }
    currentStep.value = 1
    showConfirmation.value = false
    isUploading.value = false
    uploadProgress.value = 0
    uploadError.value = ''
    if (collanteBacInput.value) {
      collanteBacInput.value.value = ''
    }
  }
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
  
  /* File Upload Section */
  .file-upload-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2rem;
  }
  
  .file-upload-area {
    position: relative;
    width: 100%;
    max-width: 400px; /* Adjust as needed */
    height: 250px; /* Adjust as needed */
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    transition: all 0.2s ease;
    background: #f9fafb;
  }
  
  .file-upload-area:hover {
    border-color: #16a34a;
    background: #f0fdf4;
  }
  
  .file-input {
    display: none;
  }
  
  .file-placeholder {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
  }
  
  .upload-icon {
    color: #9ca3af;
    margin-bottom: 1rem;
  }
  
  .file-placeholder p {
    font-size: 0.875rem;
    font-weight: 500;
    color: #4b5563;
    margin-bottom: 0.5rem;
    text-align: center;
  }
  
  .file-placeholder small {
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
  }
  
  .upload-loading {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: #f9fafb;
  }
  
  .upload-progress-container {
    margin-bottom: 1rem;
  }
  
  .progress-circle {
    position: relative;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .progress-ring-circle {
    transition: stroke-dashoffset 0.3s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
  }
  
  .progress-text {
    position: absolute;
    font-size: 0.875rem;
    font-weight: 600;
    color: #16a34a;
  }
  
  .upload-loading p {
    font-size: 0.875rem;
    color: #4b5563;
    margin-bottom: 0.5rem;
  }
  
  .upload-error {
    color: #dc2626;
    font-size: 0.75rem;
    text-align: center;
    margin-top: 0.5rem;
  }
  
  .file-preview {
    width: 100%;
    height: 100%;
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 2px solid #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .preview-image {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Use contain for images to fit without cropping */
  }
  
  .pdf-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    text-align: center;
    color: #374151;
  }
  
  .pdf-preview svg {
    color: #dc2626; /* Red for PDF icon */
    margin-bottom: 0.5rem;
  }
  
  .pdf-preview p {
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
  }
  
  .view-file-link {
    font-size: 0.875rem;
    color: #16a34a;
    text-decoration: underline;
  }
  
  .remove-file {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.8);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #dc2626;
    transition: all 0.2s ease;
  }
  
  .remove-file:hover {
    background: #ffffff;
    transform: scale(1.1);
  }
  
  /* Summary Section */
  .summary-section {
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 2px solid #e5e7eb;
  }
  
  .summary-section h3 {
    font-size: 1.25rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1.5rem;
    text-align: center;
  }
  
  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
  }
  
  .summary-item {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    font-size: 0.9rem;
    color: #374151;
  }
  
  .summary-item strong {
    color: #1f2937;
  }
  
  .summary-item-full {
    grid-column: 1 / -1;
  }
  
  .file-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #16a34a;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }
  
  .file-link:hover {
    color: #15803d;
  }
  
  /* Navigation Buttons */
  .form-navigation {
    display: flex;
    justify-content: space-between;
    padding: 1.5rem 2rem;
    border-top: 1px solid #e5e7eb;
    background: #f8fafc;
  }
  
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
  }
  
  .btn-primary {
    background: #16a34a;
    color: #ffffff;
  }
  
  .btn-primary:hover:not(:disabled) {
    background: #15803d;
  }
  
  .btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .btn-secondary {
    background: #f3f4f6;
    color: #374151;
  }
  
  .btn-secondary:hover {
    background: #e5e7eb;
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
  
  /* Modal de confirmation */
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
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
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
    margin: 0 auto 1.5rem;
  }
  
  .modal-header h3 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1rem;
  }
  
  .modal-header p {
    font-size: 1rem;
    color: #4b5563;
    margin-bottom: 0.5rem;
  }
  
  .modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
  }
  
  .modal-actions .btn {
    flex: 1;
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
  
    .file-upload-area {
      max-width: 100%;
    }
  
    .summary-grid {
      grid-template-columns: 1fr;
    }
  
    .form-navigation {
      flex-direction: column;
      gap: 1rem;
    }
  
    .modal-actions {
      flex-direction: column;
    }
  
    .steps-nav {
      overflow-x: auto;
      padding-bottom: 1rem;
    }
  
    .step-item {
      min-width: 100px;
    }
  
    .step-connector {
      width: 100%;
    }
  }
  
  @media (max-width: 480px) {
    .main-content {
      padding: 1rem;
    }
  
    .form-container {
      border-radius: 0.5rem;
    }
  
    .btn {
      padding: 0.75rem 1rem;
    }
  
    .form-navigation {
      padding: 1rem;
    }
  
    .modal-content {
      padding: 1.5rem;
    }
  }
  </style>
  