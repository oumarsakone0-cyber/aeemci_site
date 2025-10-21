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
                <p>Inscription des Stands - SENAFOI 25</p>
              </div>
            </div>
          </div>
          <div class="header-right">
            <button @click="retourAccueil" class="btn btn-secondary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
              </svg>
              Retour Accueil
            </button>
          </div>
        </div>
      </header>
      <!-- Loading State -->
      <div v-if="isLoading" class="loading-container">
        <div class="loading-content">
          <div class="loading-spinner"></div>
          <h2>Traitement de votre inscription...</h2>
          <p>Veuillez patienter pendant que nous enregistrons votre stand</p>
        </div>
      </div>
      <!-- Success State -->
      <div v-else-if="standInscrit" class="success-container">
        <div class="congratulations-section">
          <div class="success-card">
            <div class="success-header">
              <div class="success-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
              </div>
              <h1>Inscription Confirmée !</h1>
              <p class="success-message">Votre stand a été inscrit avec succès pour le SENAFOI 25</p>
              <div class="payment-confirmation">
                <div class="confirmation-badge">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  <span>Stand enregistré</span>
                </div>
                <p class="matricule-info">
                  Votre stand a été enregistré avec succès<br>
                  <strong style="font-size: 25px; font-family: arial">Référence: <span style="letter-spacing: 2px">{{ standInscrit.reference_stand }}</span></strong>
                </p>
              </div>
            </div>
            <!-- Récapitulatif du stand -->
            <div class="member-summary">
              <h3>Récapitulatif de votre inscription</h3>
              <div class="summary-grid">
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Nom de l'entreprise</span>
                    <span class="summary-value">{{ standInscrit.nom_entreprise }}</span>
                  </div>
                </div>
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7Z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Produits à vendre</span>
                    <span class="summary-value">{{ standInscrit.produits_vendre }}</span>
                  </div>
                </div>
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Responsable</span>
                    <span class="summary-value">{{ standInscrit.nom_responsable }}</span>
                  </div>
                </div>
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Contact</span>
                    <span class="summary-value">{{ standInscrit.contact_responsable }}</span>
                  </div>
                </div>
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Formule choisie</span>
                    <span class="summary-value">{{ getFormuleLabel(standInscrit.formule_choisie) }}</span>
                  </div>
                </div>
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Montant total</span>
                    <span class="summary-value">{{ standInscrit.montant_total_formate }}</span>
                  </div>
                </div>
              </div>
            </div>
            <!-- Actions principales -->
            <div class="main-actions">
              <button
                @click="generatePDF"
                :disabled="isGeneratingPDF"
                class="btn btn-primary btn-large"
              >
                <div v-if="isGeneratingPDF" class="loading-spinner-small"></div>
                <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M14 2H6c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm0 14H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                </svg>
                {{ isGeneratingPDF ? 'Génération...' : 'Télécharger ma fiche d\'inscription PDF' }}
              </button>
            </div>
            <!-- Informations importantes -->
            <div class="important-info">
              <div class="info-card">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Votre stand est enregistré !</h4>
                  <p>Conservez précieusement votre référence <strong>{{ standInscrit.reference_stand }}</strong> et votre fiche d'inscription.</p>
                  <p><strong>Formule:</strong> {{ getFormuleLabel(standInscrit.formule_choisie) }} | <strong>Montant:</strong> {{ standInscrit.montant_total_formate }}</p>
                </div>
              </div>
              <div class="info-card">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Prochaines étapes</h4>
                  <p>Vous recevrez bientôt les informations de paiement et les détails pratiques pour votre stand au SENAFOI 25.</p>
                  <p><strong>Contact:</strong> {{ standInscrit.contact_responsable }}</p>
                </div>
              </div>
            </div>
            <!-- Actions secondaires -->
            <div class="secondary-actions">
              <button @click="nouvelleInscription" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                Nouveau stand
              </button>
              <button @click="contactSupport" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.89 2-2V6c0-1.1-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Support
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Formulaire d'inscription -->
      <div v-else class="form-container">
        <div class="form-card">
          <div class="form-header">
            <h2>Inscription de votre Stand</h2>
            <p>Remplissez ce formulaire pour inscrire votre stand au SENAFOI 25</p>
          </div>
          <form @submit.prevent="submitForm" class="inscription-form">
            <!-- Informations de l'entreprise -->
            <div class="form-section">
              <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Informations de l'entreprise
              </h3>
              <div class="form-group">
                <label for="nom_entreprise">Nom de l'entreprise *</label>
                <input
                  type="text"
                  id="nom_entreprise"
                  v-model="formData.nom_entreprise"
                  required
                  placeholder="Ex: Entreprise ABC"
                >
              </div>
              <div class="form-group">
                <label for="produits_vendre">Produits à vendre *</label>
                <textarea
                  id="produits_vendre"
                  v-model="formData.produits_vendre"
                  required
                  rows="3"
                  placeholder="Décrivez les produits que vous comptez vendre..."
                ></textarea>
              </div>
            </div>
            <!-- Informations du responsable -->
            <div class="form-section">
              <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Responsable du stand
              </h3>
              <div class="form-row">
                <div class="form-group">
                  <label for="nom_responsable">Nom du responsable *</label>
                  <input
                    type="text"
                    id="nom_responsable"
                    v-model="formData.nom_responsable"
                    required
                    placeholder="Nom complet"
                  >
                </div>
                <div class="form-group">
                  <label for="contact_responsable">Contact du responsable *</label>
                  <input
                    type="tel"
                    id="contact_responsable"
                    v-model="formData.contact_responsable"
                    required
                    placeholder="Ex: +225 01 02 03 04 05"
                  >
                </div>
              </div>
              <!-- Photo du responsable -->
              <div class="form-group">
                <label>Photo du responsable (optionnel)</label>
                <div class="photo-upload-container">
                  <div v-if="isUploading && currentUploadingType === 'responsable'" class="upload-loading">
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
                    <div v-if="uploadError && currentUploadingType === 'responsable'" class="upload-error">
                      {{ uploadError }}
                    </div>
                  </div>
                  <div v-else-if="!formData.photo_responsable" class="photo-placeholder" @click="triggerPhotoUpload('responsable')">
                    <svg class="upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/>
                    </svg>
                    <p>Cliquez pour ajouter une photo</p>
                    <small>Format: JPG, PNG (max 2MB)</small>
                  </div>
                  <div v-else class="photo-preview">
                    <img :src="formData.photo_responsable" alt="Photo responsable" class="preview-image" />
                    <button type="button" @click="removePhoto('responsable')" class="remove-photo">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                      </svg>
                    </button>
                  </div>
                  <input
                    ref="photoResponsableInput"
                    type="file"
                    accept="image/*"
                    @change="handlePhotoUpload($event, 'responsable')"
                    style="display: none"
                  >
                </div>
              </div>
            </div>
            <!-- Choix de la formule -->
            <div class="form-section">
              <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                </svg>
                Choisissez votre formule
              </h3>
              <div class="formules-grid">
                <div
                  v-for="formule in formules"
                  :key="formule.id"
                  class="formule-card"
                  :class="{ active: formData.formule_choisie === formule.id }"
                  @click="selectFormule(formule.id)"
                >
                  <div class="formule-header">
                    <input
                      type="radio"
                      :id="formule.id"
                      :value="formule.id"
                      v-model="formData.formule_choisie"
                      required
                    >
                    <label :for="formule.id" class="formule-title">{{ formule.nom }}</label>
                  </div>
                  <div class="formule-price">{{ formule.prix_formate }}</div>
                  <div class="formule-description">{{ formule.description }}</div>
                  <ul class="formule-includes">
                    <li v-for="include in formule.includes" :key="include">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                      </svg>
                      {{ include }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- Informations accompagnant (si formule complète) -->
            <div v-if="formData.formule_choisie === 'STAND_COMPLET_ACCOMPAGNANT'" class="form-section">
              <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 6H16c-.8 0-1.54.37-2.01.99L12 9v11h2v-6h2v6h4z"/>
                </svg>
                Informations de l'accompagnant
              </h3>
              <div class="form-row">
                <div class="form-group">
                  <label for="nom_accompagnant">Nom de l'accompagnant *</label>
                  <input
                    type="text"
                    id="nom_accompagnant"
                    v-model="formData.nom_accompagnant"
                    :required="formData.formule_choisie === 'STAND_COMPLET_ACCOMPAGNANT'"
                    placeholder="Nom complet de l'accompagnant"
                  >
                </div>
                <div class="form-group">
                  <label for="contact_accompagnant">Contact de l'accompagnant</label>
                  <input
                    type="tel"
                    id="contact_accompagnant"
                    v-model="formData.contact_accompagnant"
                    placeholder="Ex: +225 01 02 03 04 05"
                  >
                </div>
              </div>
              <!-- Photo de l'accompagnant -->
              <div class="form-group">
                <label>Photo de l'accompagnant (optionnel)</label>
                <div class="photo-upload-container">
                  <div v-if="isUploading && currentUploadingType === 'accompagnant'" class="upload-loading">
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
                    <div v-if="uploadError && currentUploadingType === 'accompagnant'" class="upload-error">
                      {{ uploadError }}
                    </div>
                  </div>
                  <div v-else-if="!formData.photo_accompagnant" class="photo-placeholder" @click="triggerPhotoUpload('accompagnant')">
                    <svg class="upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 2l3 2h4c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2h3l3-2zm3 15c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z"/>
                    </svg>
                    <p>Cliquez pour ajouter une photo</p>
                    <small>Format: JPG, PNG (max 2MB)</small>
                  </div>
                  <div v-else class="photo-preview">
                    <img :src="formData.photo_accompagnant" alt="Photo accompagnant" class="preview-image" />
                    <button type="button" @click="removePhoto('accompagnant')" class="remove-photo">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                      </svg>
                    </button>
                  </div>
                  <input
                    ref="photoAccompagnantInput"
                    type="file"
                    accept="image/*"
                    @change="handlePhotoUpload($event, 'accompagnant')"
                    style="display: none"
                  >
                </div>
              </div>
            </div>
            <!-- Récapitulatif -->
            <div v-if="formData.formule_choisie" class="form-section">
              <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                Récapitulatif
              </h3>
              <div class="recap-card">
                <div class="recap-item">
                  <span class="recap-label">Formule sélectionnée:</span>
                  <span class="recap-value">{{ getSelectedFormule()?.nom }}</span>
                </div>
                <div class="recap-item">
                  <span class="recap-label">Montant total:</span>
                  <span class="recap-value total-price">{{ getSelectedFormule()?.prix_formate }}</span>
                </div>
              </div>
            </div>
            <!-- Actions du formulaire -->
            <div class="form-actions">
              <button type="button" @click="resetForm" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                </svg>
                Réinitialiser
              </button>
              <button type="submit" :disabled="isSubmitting" class="btn btn-primary">
                <div v-if="isSubmitting" class="loading-spinner-small"></div>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                {{ isSubmitting ? 'Inscription...' : 'Inscrire mon stand' }}
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- Error Modal -->
      <div v-if="showErrorModal" class="modal-overlay" @click="closeErrorModal">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>Erreur</h3>
            <button @click="closeErrorModal" class="close-button">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <p>{{ errorMessage }}</p>
          </div>
          <div class="modal-footer">
            <button @click="closeErrorModal" class="btn btn-primary">OK</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import axios from 'axios'
  
  // Configuration Cloudinary
  const cloudinaryConfig = {
    cloudName: 'dqk65objc',
    uploadPreset: 'sadeal',
    apiKey: '784574272958761',
    uploadUrl: 'https://api.cloudinary.com/v1_1/dqk65objc/image/upload'
  }
  
  // État de l'application
  const standInscrit = ref(null)
  const isLoading = ref(false)
  const isSubmitting = ref(false)
  const isGeneratingPDF = ref(false)
  const showErrorModal = ref(false)
  const errorMessage = ref('')
  
  // Cloudinary upload states
  const isUploading = ref(false)
  const uploadProgress = ref(0)
  const uploadError = ref('')
  const currentUploadingType = ref(null) // 'responsable' or 'accompagnant'
  
  // Références pour les inputs de fichiers
  const photoResponsableInput = ref(null)
  const photoAccompagnantInput = ref(null)
  
  // Données du formulaire
  const formData = ref({
    nom_entreprise: '',
    produits_vendre: '',
    nom_responsable: '',
    contact_responsable: '',
    formule_choisie: '',
    nom_accompagnant: '',
    contact_accompagnant: '',
    photo_responsable: '', // Will store Cloudinary URL
    photo_responsable_public_id: '', // New: to store Cloudinary public ID
    photo_accompagnant: '', // Will store Cloudinary URL
    photo_accompagnant_public_id: '' // New: to store Cloudinary public ID
  })
  
  // Formules disponibles
  const formules = ref([
    {
      id: 'STAND_SIMPLE',
      nom: 'Stand Simple',
      prix: 35000,
      prix_formate: '35.000 FCFA',
      description: 'Stand de base pour exposer vos produits',
      includes: [
        'Emplacement stand',
        'Table et chaises',
        'Accès électricité'
      ]
    },
    {
      id: 'STAND_TRANSPORT',
      nom: 'Stand + Transport',
      prix: 45000,
      prix_formate: '45.000 FCFA',
      description: 'Stand + transport avec nous (+10.000 FCFA)',
      includes: [
        'Emplacement stand',
        'Table et chaises',
        'Accès électricité',
        'Transport aller-retour',
        'Transport des bagages'
      ]
    },
    {
      id: 'STAND_TRANSPORT_NOURRITURE',
      nom: 'Stand + Transport + Nourriture',
      prix: 50000,
      prix_formate: '50.000 FCFA',
      description: 'Formule complète avec repas (+5.000 FCFA)',
      includes: [
        'Emplacement stand',
        'Table et chaises',
        'Accès électricité',
        'Transport aller-retour',
        'Transport des bagages',
        'Repas pendant l\'événement'
      ]
    },
    {
      id: 'STAND_COMPLET_ACCOMPAGNANT',
      nom: 'Stand + Transport + Nourriture + Accompagnant',
      prix: 60000,
      prix_formate: '60.000 FCFA',
      description: 'Formule premium avec accompagnant (+10.000 FCFA)',
      includes: [
        'Emplacement stand',
        'Table et chaises',
        'Accès électricité',
        'Transport aller-retour',
        'Transport des bagages',
        'Repas pendant l\'événement',
        'Place pour un accompagnant'
      ]
    }
  ])
  
  // Méthodes
  const selectFormule = (formuleId) => {
    formData.value.formule_choisie = formuleId
  
    // Réinitialiser les champs accompagnant si on change de formule
    if (formuleId !== 'STAND_COMPLET_ACCOMPAGNANT') {
      formData.value.nom_accompagnant = ''
      formData.value.contact_accompagnant = ''
      formData.value.photo_accompagnant = ''
      formData.value.photo_accompagnant_public_id = ''
    }
  }
  
  const getSelectedFormule = () => {
    return formules.value.find(f => f.id === formData.value.formule_choisie)
  }
  
  const getFormuleLabel = (formuleId) => {
    const formule = formules.value.find(f => f.id === formuleId)
    return formule ? formule.nom : formuleId
  }
  
  const resetForm = () => {
    formData.value = {
      nom_entreprise: '',
      produits_vendre: '',
      nom_responsable: '',
      contact_responsable: '',
      formule_choisie: '',
      nom_accompagnant: '',
      contact_accompagnant: '',
      photo_responsable: '',
      photo_responsable_public_id: '',
      photo_accompagnant: '',
      photo_accompagnant_public_id: ''
    }
    // Ensure file inputs are cleared
    if (photoResponsableInput.value) {
      photoResponsableInput.value.value = ''
    }
    if (photoAccompagnantInput.value) {
      photoAccompagnantInput.value.value = ''
    }
  }
  
  // Méthodes de gestion des photos avec Cloudinary
  const triggerPhotoUpload = (type) => {
    if (type === 'responsable') {
      photoResponsableInput.value.click()
    } else if (type === 'accompagnant') {
      photoAccompagnantInput.value.click()
    }
  }
  
  const uploadImageToCloudinary = async (imageFile, type) => {
    uploadError.value = ''
    currentUploadingType.value = type
  
    try {
      const fileName = `aeemci_stand_${type}_${Date.now()}_${imageFile.name.replace(/\s+/g, '_')}`
  
      const uploadFormData = new FormData()
      uploadFormData.append('file', imageFile)
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
        if (type === 'responsable') {
          formData.value.photo_responsable = response.data.secure_url
          formData.value.photo_responsable_public_id = response.data.public_id
        } else if (type === 'accompagnant') {
          formData.value.photo_accompagnant = response.data.secure_url
          formData.value.photo_accompagnant_public_id = response.data.public_id
        }
        console.log(`Photo ${type} uploaded:`, response.data.secure_url)
        return true
      } else {
        throw new Error('Réponse Cloudinary invalide')
      }
    } catch (error) {
      console.error(`Erreur lors du téléchargement de la photo ${type} vers Cloudinary:`, error)
      uploadError.value = `Erreur lors du téléchargement de la photo ${type}. Veuillez réessayer.`
      return false
    }
  }
  
  const handlePhotoUpload = async (event, type) => {
    isUploading.value = true
    uploadProgress.value = 0
    uploadError.value = ''
    currentUploadingType.value = type
  
    try {
      const file = event.target.files[0]
      if (!file) {
        isUploading.value = false
        return
      }
  
      // Vérifier la taille (max 2MB)
      if (file.size > 2 * 1024 * 1024) {
        uploadError.value = 'La photo ne doit pas dépasser 2MB'
        isUploading.value = false
        if (event.target) event.target.value = ''
        return
      }
  
      // Vérifier le type
      if (!file.type.startsWith('image/')) {
        uploadError.value = 'Veuillez sélectionner une image valide'
        isUploading.value = false
        if (event.target) event.target.value = ''
        return
      }
  
      // Clear previous photo data for the specific type
      if (type === 'responsable') {
        formData.value.photo_responsable = ''
        formData.value.photo_responsable_public_id = ''
      } else if (type === 'accompagnant') {
        formData.value.photo_accompagnant = ''
        formData.value.photo_accompagnant_public_id = ''
      }
  
      const success = await uploadImageToCloudinary(file, type)
      if (!success) {
        if (event.target) event.target.value = ''
      }
    } catch (error) {
      console.error('Erreur handlePhotoUpload:', error)
      uploadError.value = 'Erreur lors de l\'upload de la photo: ' + error.message
      if (event.target) event.target.value = ''
    } finally {
      isUploading.value = false
      uploadProgress.value = 0
      currentUploadingType.value = null
    }
  }
  
  const removePhoto = (type) => {
    if (type === 'responsable') {
      formData.value.photo_responsable = ''
      formData.value.photo_responsable_public_id = ''
      if (photoResponsableInput.value) {
        photoResponsableInput.value.value = ''
      }
    } else if (type === 'accompagnant') {
      formData.value.photo_accompagnant = ''
      formData.value.photo_accompagnant_public_id = ''
      if (photoAccompagnantInput.value) {
        photoAccompagnantInput.value.value = ''
      }
    }
  }
  
  const submitForm = async () => {
    isSubmitting.value = true
    errorMessage.value = ''
  
    try {
      // Validation côté client pour l'accompagnant
      if (formData.value.formule_choisie === 'STAND_COMPLET_ACCOMPAGNANT') {
        if (!formData.value.nom_accompagnant || !formData.value.nom_accompagnant.trim()) {
          throw new Error('Le nom de l\'accompagnant est requis pour cette formule')
        }
      }
  
      // Construire payload avec la structure attendue par le backend
      const payload = {
        data: {
          nom_entreprise: formData.value.nom_entreprise.trim(),
          produits_vendre: formData.value.produits_vendre.trim(),
          nom_responsable: formData.value.nom_responsable.trim(),
          contact_responsable: formData.value.contact_responsable.trim(),
          formule_choisie: formData.value.formule_choisie,
          nom_accompagnant: formData.value.nom_accompagnant?.trim() || '',
          contact_accompagnant: formData.value.contact_accompagnant?.trim() || '',
          // Send Cloudinary URLs instead of base64
          photo_responsable: formData.value.photo_responsable || null,
          photo_accompagnant: formData.value.photo_accompagnant || null
        }
      }
  
      const response = await axios.post(
        'https://sogetrag.com/api/inscription_stands_backend.php',
        payload
      )
  
      console.log('Réponse API:', response.data)
  
      if (response.data.success) {
        // Redirection si lien Wave présent
        if (response.data.wave_launch_url) {
          console.log('Redirection vers:', response.data.wave_launch_url)
          window.location.href = response.data.wave_launch_url
          return
        }
  
        // Autre logique si pas de lien Wave
        standInscrit.value = {
          ...formData.value,
          ...response.data.stand,
          reference_stand: response.data.stand.reference_stand,
          montant_total_formate: getSelectedFormule().prix_formate,
          created_at: new Date().toISOString()
        }
      } else {
        throw new Error(response.data.message || 'Erreur lors de l\'inscription')
      }
    } catch (error) {
      console.error('Erreur lors de l\'inscription:', error)
      errorMessage.value = error?.response?.data?.message || error.message || 'Erreur lors de l\'inscription'
      showErrorModal.value = true
    } finally {
      isSubmitting.value = false
    }
  }
  
  const generateReference = () => {
    const prefix = 'STD'
    const timestamp = Date.now().toString().slice(-6)
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0')
    return `${prefix}${timestamp}${random}`
  }
  
  const generatePDF = async () => {
    isGeneratingPDF.value = true
    try {
      const { default: jsPDF } = await import('jspdf')
      const doc = new jsPDF('p', 'mm', 'a4')
      const today = new Date()
  
      // En-tête
      doc.setFillColor(0, 128, 0)
      doc.rect(0, 0, 210, 35, 'F')
  
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(20)
      doc.setTextColor(255, 255, 255)
      doc.text('AEEMCI', 105, 15, { align: 'center' })
      doc.setFontSize(10)
      doc.text("Association des Elèves et Etudiants Musulmans de Côte d'Ivoire", 105, 20, { align: 'center' })
      doc.setFontSize(14)
      doc.text("FICHE D'INSCRIPTION STAND - SENAFOI 25", 105, 28, { align: 'center' })
  
      // Informations de base
      let yPos = 50
      doc.setTextColor(0, 0, 0)
      doc.setFont('helvetica', 'normal')
      doc.setFontSize(9)
      doc.text(`Date : ${today.toLocaleDateString('fr-FR')}`, 165, yPos)
  
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(12)
      doc.text(`RÉFÉRENCE: ${standInscrit.value.reference_stand}`, 105, yPos + 7, { align: 'center' })
  
      // Section ENTREPRISE
      yPos += 20
      doc.setFillColor(240, 248, 255)
      doc.rect(15, yPos - 5, 180, 8, 'F')
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(11)
      doc.text("INFORMATIONS DE L'ENTREPRISE", 20, yPos)
  
      yPos += 10
      doc.setFontSize(9)
      doc.setFont('helvetica', 'normal')
      doc.text('Nom de l\'entreprise :', 20, yPos)
      doc.setFont('helvetica', 'bold')
      doc.text(standInscrit.value.nom_entreprise, 60, yPos)
  
      yPos += 8
      doc.setFont('helvetica', 'normal')
      doc.text('Produits à vendre :', 20, yPos)
      doc.setFont('helvetica', 'bold')
      const produits = doc.splitTextToSize(standInscrit.value.produits_vendre, 120)
      doc.text(produits, 55, yPos)
  
      // Section RESPONSABLE
      yPos += 20
      doc.setFillColor(240, 248, 255)
      doc.rect(15, yPos - 5, 180, 8, 'F')
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(11)
      doc.text("RESPONSABLE DU STAND", 20, yPos)
  
      yPos += 10
      doc.setFontSize(9)
      doc.setFont('helvetica', 'normal')
      doc.text('Nom du responsable :', 20, yPos)
      doc.setFont('helvetica', 'bold')
      doc.text(standInscrit.value.nom_responsable, 60, yPos)
  
      yPos += 8
      doc.setFont('helvetica', 'normal')
      doc.text('Contact :', 20, yPos)
      doc.setFont('helvetica', 'bold')
      doc.text(standInscrit.value.contact_responsable, 40, yPos)
  
      // Section ACCOMPAGNANT (si présent)
      if (standInscrit.value.nom_accompagnant) {
        yPos += 20
        doc.setFillColor(240, 248, 255)
        doc.rect(15, yPos - 5, 180, 8, 'F')
        doc.setFont('helvetica', 'bold')
        doc.setFontSize(11)
        doc.text("ACCOMPAGNANT", 20, yPos)
  
        yPos += 10
        doc.setFontSize(9)
        doc.setFont('helvetica', 'normal')
        doc.text('Nom de l\'accompagnant :', 20, yPos)
        doc.setFont('helvetica', 'bold')
        doc.text(standInscrit.value.nom_accompagnant, 65, yPos)
  
        if (standInscrit.value.contact_accompagnant) {
          yPos += 8
          doc.setFont('helvetica', 'normal')
          doc.text('Contact :', 20, yPos)
          doc.setFont('helvetica', 'bold')
          doc.text(standInscrit.value.contact_accompagnant, 40, yPos)
        }
      }
  
      // Section FORMULE
      yPos += 20
      doc.setFillColor(240, 248, 255)
      doc.rect(15, yPos - 5, 180, 8, 'F')
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(11)
      doc.text("FORMULE CHOISIE", 20, yPos)
  
      yPos += 10
      doc.setFontSize(9)
      doc.setFont('helvetica', 'normal')
      doc.text('Formule :', 20, yPos)
      doc.setFont('helvetica', 'bold')
      doc.text(getFormuleLabel(standInscrit.value.formule_choisie), 40, yPos)
  
      yPos += 8
      doc.setFont('helvetica', 'normal')
      doc.text('Montant total :', 20, yPos)
      doc.setFont('helvetica', 'bold')
      doc.text(standInscrit.value.montant_total_formate, 50, yPos)
  
      // Pied de page
      doc.setFillColor(0, 128, 0)
      doc.rect(0, 275, 210, 22, 'F')
  
      doc.setFont('helvetica', 'normal')
      doc.setFontSize(7)
      doc.setTextColor(255, 255, 255)
      doc.text("Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 282, { align: 'center' })
      doc.text("27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 / aeemci@yahoo.fr / www.aeemci.ci", 105, 286, { align: 'center' })
  
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(9)
      doc.text("AEEMCI, pour une identité islamique !", 105, 292, { align: 'center' })
  
      const filename = `fiche_stand_aeemci_${standInscrit.value.reference_stand}_${today.toISOString().split('T')[0]}.pdf`
      doc.save(filename)
    } catch (error) {
      console.error('Erreur lors de la génération du PDF:', error)
      errorMessage.value = "Erreur lors de la génération du PDF. Veuillez réessayer."
      showErrorModal.value = true
    } finally {
      isGeneratingPDF.value = false
    }
  }
  
  const nouvelleInscription = () => {
    standInscrit.value = null
    resetForm()
  }
  
  const retourAccueil = () => {
    window.location.href = 'https://aeemci.sastock.com'
  }
  
  const contactSupport = () => {
    window.open('mailto:support@aeemci.org?subject=Support - Inscription stand ' + standInscrit.value.reference_stand, '_blank')
  }
  
  const closeErrorModal = () => {
    showErrorModal.value = false
    errorMessage.value = ''
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
  
  /* Loading State */
  .loading-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 2rem;
  }
  
  .loading-content {
    text-align: center;
    max-width: 400px;
  }
  
  .loading-spinner {
    width: 3rem;
    height: 3rem;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #16a34a;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1.5rem;
  }
  
  .loading-content h2 {
    font-size: 1.5rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .loading-content p {
    color: #6b7280;
  }
  
  /* Success Container */
  .success-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
  }
  
  .congratulations-section {
    margin-bottom: 2rem;
  }
  
  .success-card {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 10px 10px -5px rgb(0 0 0 / 0.04);
    overflow: hidden;
  }
  
  /* Success Header */
  .success-header {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    padding: 3rem 2rem;
    text-align: center;
  }
  
  .success-icon {
    width: 5rem;
    height: 5rem;
    background: #16a34a;
    color: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 15px -3px rgba(22, 163, 74, 0.3);
  }
  
  .success-header h1 {
    font-size: 2.5rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1rem;
  }
  
  .success-message {
    font-size: 1.25rem;
    color: #4b5563;
    margin-bottom: 2rem;
  }
  
  .payment-confirmation {
    background: #ffffff;
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 2px solid #bbf7d0;
  }
  
  .confirmation-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #dcfce7;
    color: #15803d;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
  }
  
  .matricule-info {
    color: #374151;
    line-height: 1.6;
  }
  
  .matricule-info strong {
    color: #16a34a;
    font-family: 'Courier New', monospace;
    font-size: 1.1em;
  }
  
  /* Member Summary */
  .member-summary {
    padding: 2rem;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .member-summary h3 {
    font-size: 1.25rem;
    font-weight: 600;
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
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
  }
  
  .summary-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: #dcfce7;
    color: #16a34a;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .summary-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  
  .summary-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .summary-value {
    font-size: 0.875rem;
    color: #1f2937;
    font-weight: 600;
  }
  
  /* Form Container */
  .form-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
  }
  
  .form-card {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.04);
    overflow: hidden;
  }
  
  .form-header {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    padding: 2rem;
    text-align: center;
  }
  
  .form-header h2 {
    font-size: 2rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .form-header p {
    color: #4b5563;
  }
  
  .inscription-form {
    padding: 2rem;
  }
  
  .form-section {
    margin-bottom: 2rem;
  }
  
  .section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: #16a34a;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #dcfce7;
  }
  
  .form-group {
    margin-bottom: 1rem;
  }
  
  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }
  
  .form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }
  
  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
  }
  
  .form-group input:focus,
  .form-group textarea:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  
  .form-group textarea {
    resize: vertical;
    min-height: 80px;
  }
  
  /* Photo Upload */
  .photo-upload-container {
    position: relative;
    height: 200px; /* Fixed height for consistency */
    width: 100%;
  }
  
  .photo-placeholder { /* Renamed from .photo-upload-area */
    width: 100%;
    height: 100%;
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #f9fafb;
  }
  
  .photo-placeholder:hover {
    border-color: #16a34a;
    background: #f0fdf4;
  }
  
  .upload-icon { /* Used for photo-placeholder svg */
    color: #9ca3af;
    margin-bottom: 1rem;
  }
  
  .photo-placeholder p {
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    text-align: center;
  }
  
  .photo-placeholder small {
    color: #6b7280;
    text-align: center;
  }
  
  .upload-loading { /* NEW */
    width: 100%;
    height: 100%;
    border: 2px solid #d1d5db;
    border-radius: 0.75rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: #f9fafb;
  }
  
  .upload-progress-container { /* NEW */
    margin-bottom: 1rem;
  }
  
  .progress-circle { /* NEW */
    position: relative;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .progress-ring-circle { /* NEW */
    transition: stroke-dashoffset 0.3s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
  }
  
  .progress-text { /* NEW */
    position: absolute;
    font-size: 0.875rem;
    font-weight: 600;
    color: #16a34a;
  }
  
  .upload-loading p { /* NEW */
    font-size: 0.875rem;
    color: #4b5563;
    margin-bottom: 0.5rem;
  }
  
  .upload-error { /* NEW */
    color: #dc2626;
    font-size: 0.75rem;
    text-align: center;
    margin-top: 0.5rem;
  }
  
  .photo-preview {
    width: 100%;
    height: 100%; /* Make it fill the container */
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 2px solid #16a34a; /* Added border from seminariste */
  }
  
  .preview-image { /* Renamed from .photo-preview img */
    width: 100%;
    height: 100%; /* Make it fill the container */
    object-fit: cover;
    display: block;
  }
  
  .remove-photo { /* Renamed from .remove-photo-btn */
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.8); /* Changed from rgba(239, 68, 68, 0.9) */
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #dc2626; /* Changed from white */
    transition: all 0.2s ease;
  }
  
  .remove-photo:hover { /* Changed from rgba(220, 38, 38, 0.9) */
    background: #ffffff;
    transform: scale(1.1);
  }
  
  /* Formules Grid */
  .formules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
  }
  
  .formule-card {
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #ffffff;
  }
  
  .formule-card:hover {
    border-color: #16a34a;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -1px rgb(0 0 0 / 0.06);
  }
  
  .formule-card.active {
    border-color: #16a34a;
    background: #f0fdf4;
    box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.1), 0 2px 4px -1px rgba(22, 163, 74, 0.06);
  }
  
  .formule-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
  }
  
  .formule-header input[type="radio"] {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: #16a34a;
  }
  
  .formule-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    cursor: pointer;
  }
  
  .formule-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #16a34a;
    margin-bottom: 0.5rem;
  }
  
  .formule-description {
    color: #6b7280;
    margin-bottom: 1rem;
    font-size: 0.875rem;
  }
  
  .formule-includes {
    list-style: none;
    padding: 0;
  }
  
  .formule-includes li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
  }
  
  .formule-includes svg {
    color: #16a34a;
    flex-shrink: 0;
  }
  
  /* Récapitulatif */
  .recap-card {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 0.5rem;
    padding: 1rem;
  }
  
  .recap-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
  }
  
  .recap-item:last-child {
    margin-bottom: 0;
    padding-top: 0.5rem;
    border-top: 1px solid #bbf7d0;
  }
  
  .recap-label {
    font-weight: 500;
    color: #374151;
  }
  
  .recap-value {
    font-weight: 600;
    color: #1f2937;
  }
  
  .total-price {
    font-size: 1.125rem;
    color: #16a34a;
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
    text-decoration: none;
  }
  
  .btn-large {
    padding: 1rem 2rem;
    font-size: 1rem;
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
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -1px rgb(0 0 0 / 0.06);
  }
  
  .btn-secondary {
    background: #ffffff;
    color: #374151;
    border-color: #d1d5db;
  }
  
  .btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    transform: translateY(-1px);
  }
  
  .btn-outline {
    background: transparent;
    color: #16a34a;
    border-color: #16a34a;
  }
  
  .btn-outline:hover {
    background: #dcfce7;
  }
  
  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
  }
  
  /* Form Actions */
  .form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
  }
  
  /* Main Actions */
  .main-actions {
    padding: 2rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
    border-bottom: 1px solid #e5e7eb;
  }
  
  /* Important Info */
  .important-info {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .info-card {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 0.75rem;
  }
  
  .info-icon {
    width: 3rem;
    height: 3rem;
    background: #16a34a;
    color: #ffffff;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .info-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #15803d;
    margin-bottom: 0.5rem;
  }
  
  .info-content p {
    font-size: 0.875rem;
    color: #166534;
    line-height: 1.5;
    margin-bottom: 0.25rem;
  }
  
  /* Secondary Actions */
  .secondary-actions {
    padding: 1.5rem 2rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
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
    border-radius: 0.75rem;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 10px 10px -5px rgb(0 0 0 / 0.04);
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
  }
  
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .modal-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
  }
  
  .close-button {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: color 0.2s ease;
  }
  
  .close-button:hover {
    color: #374151;
  }
  
  .modal-body {
    padding: 1.5rem;
  }
  
  .modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
  }
  
  /* Loading Spinner Small */
  .loading-spinner-small {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .header-content {
      padding: 1rem;
      flex-direction: column;
      gap: 1rem;
    }
  
    .form-container,
    .success-container {
      padding: 1rem;
    }
  
    .form-row {
      grid-template-columns: 1fr;
    }
  
    .formules-grid {
      grid-template-columns: 1fr;
    }
  
    .form-actions {
      flex-direction: column;
    }
  
    .btn {
      width: 100%;
    }
  
    .secondary-actions {
      flex-direction: column;
    }
  
    .info-card {
      flex-direction: column;
      text-align: center;
    }
  
    .summary-grid {
      grid-template-columns: 1fr;
    }
  
    .main-actions {
      padding: 1.5rem;
      flex-direction: column;
    }
  
    .photo-placeholder { /* Renamed from .photo-upload-area */
      padding: 1.5rem;
    }
  
    .photo-preview img {
      height: 150px;
    }
  }
  
  @media (max-width: 480px) {
    .success-header h1 {
      font-size: 2rem;
    }
  
    .success-message {
      font-size: 1.125rem;
    }
  
    .form-header h2 {
      font-size: 1.75rem;
    }
  
    .logo-text h1 {
      font-size: 1.25rem;
    }
  
    .logo-text p {
      font-size: 0.75rem;
    }
  
    .formule-card {
      padding: 1rem;
    }
  
    .info-card {
      padding: 1rem;
    }
  }
  </style>
  