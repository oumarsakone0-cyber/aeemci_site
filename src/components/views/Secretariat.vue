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
                <p>Inscription des Secrétaires Régionaux au SENAFOI 2025</p>
              </div>
            </div>
          </div>
          <div class="header-right" v-if="currentStep > 0 && !showSRList">
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
          <!-- Étape d'accueil -->
          <div v-if="currentStep === 0 && !showSRList" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              Inscription SR au SENAFOI 2025
            </h2>
  
            <div class="choice-section">
              <div class="choice-container">
                <h3>Inscription des Secrétaires Régionaux</h3>
                <p class="choice-subtitle">Cliquez sur le bouton ci-dessous pour accéder à la liste des SR</p>
  
                <div class="choice-buttons">
                  <button
                    @click="showSRSelection"
                    type="button"
                    class="choice-btn choice-btn-primary"
                  >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                    <div class="btn-content">
                      <span class="btn-title">Consulter la liste des SR pour vous inscrire</span>
                      <span class="btn-subtitle">Sélectionnez votre nom dans la liste</span>
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Liste des SR -->
          <div v-if="showSRList" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Liste des Secrétaires Régionaux
            </h2>
  
            <div class="sr-section">
              <div class="sr-container">
                <h3>Sélectionnez votre nom dans la liste</h3>
                <p class="sr-subtitle">Trouvez et cliquez sur votre nom pour commencer l'inscription</p>
  
                <!-- Barre de recherche -->
                <div class="search-container">
                  <input
                    v-model="srSearchQuery"
                    type="text"
                    placeholder="Rechercher par nom ou prénom..."
                    class="search-input"
                  />
                  <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                  </svg>
                </div>
  
                <!-- Filtre par secrétariat régional -->
                <div class="filter-container">
                  <select
                    v-model="selectedSecretariat"
                    class="filter-select"
                  >
                    <option value="">Tous les secrétariats régionaux</option>
                    <option value="CCR">CCR</option>
                    <option
                      v-for="secretariat in secretariatsRegionaux"
                      :key="secretariat"
                      :value="secretariat"
                    >
                      {{ secretariat }}
                    </option>
                  </select>
                </div>
  
                <!-- État de chargement -->
                <div v-if="isLoadingSR" class="loading-state">
                  <div class="loading-spinner-large"></div>
                  <p>Chargement de la liste des SR...</p>
                </div>
  
                <!-- Erreur -->
                <div v-else-if="srError" class="error-state">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                  </svg>
                  <p>{{ srError }}</p>
                  <button @click="loadSRList" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                    </svg>
                    Réessayer
                  </button>
                </div>
  
                <!-- Liste des SR -->
                <div v-else-if="filteredSRList.length > 0" class="sr-list">
                  <div
                    v-for="sr in filteredSRList"
                    :key="`${sr.nom}-${sr.prenom}`"
                    @click="selectSR(sr)"
                    class="sr-item"
                  >
                    <div class="sr-info">
                      <div class="sr-name">
                        <strong>{{ sr.nom }} {{ sr.prenom }}</strong>
                      </div>
                      <div class="sr-secretariat">{{ sr.secretariat_regional }}</div>
                    </div>
                  </div>
                </div>
  
                <!-- Aucun résultat -->
                <div v-else-if="srList.length === 0" class="no-results">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                  <p>Aucun SR trouvé</p>
                </div>
  
                <div v-else class="no-results">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                  </svg>
                  <p>Aucun résultat trouvé pour "{{ srSearchQuery }}"</p>
                </div>
              </div>
            </div>
  
            <!-- Navigation -->
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
              <div></div>
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
                  'step-active': index + 1 === currentStep,
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
          <div v-if="showFormContent" class="form-content">
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
                        type="text"
                        required
                        class="form-input input-readonly"
                        placeholder="Votre nom"
                        readonly
                      />
                    </div>
                    <small class="form-help readonly-help">
                      Information récupérée de la liste des SR
                    </small>
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
                        type="text"
                        required
                        class="form-input input-readonly"
                        placeholder="Votre prénom"
                        readonly
                      />
                    </div>
                    <small class="form-help readonly-help">
                      Information récupérée de la liste des SR
                    </small>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                      </svg>
                      Sexe <span class="required">*</span>
                    </label>
                    <div class="radio-group radio-group-horizontal">
                      <label class="radio-item radio-item-compact">
                        <input
                          v-model="formData.sexe"
                          type="radio"
                          value="M"
                          class="radio-input"
                          required
                        />
                        <div class="radio-custom"></div>
                        <span class="radio-label">Masculin (M)</span>
                      </label>
                      <label class="radio-item radio-item-compact">
                        <input
                          v-model="formData.sexe"
                          type="radio"
                          value="F"
                          class="radio-input"
                          required
                        />
                        <div class="radio-custom"></div>
                        <span class="radio-label">Féminin (F)</span>
                      </label>
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
                        @change="calculateAge"
                        type="date"
                        required
                        class="form-input"
                      />
                    </div>
                    <small v-if="formData.age" class="form-help">
                      Âge calculé : {{ formData.age }} ans
                    </small>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 9H1v13h18v-2H3V9zm15-5h-2V2H8V4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H6V6h12v12zm-6-3h2v2h-2v-2zm0-4h2v2h-2v-2z"/>
                      </svg>
                      Niveau d'étude <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <select
                        v-model="formData.niveauEtude"
                        required
                        class="form-input"
                      >
                        <option value="">Sélectionnez votre niveau</option>
                        <option value="PRIMAIRE">PRIMAIRE</option>
                        <option value="6ème">6ème</option>
                        <option value="5ème">5ème</option>
                        <option value="4ème">4ème</option>
                        <option value="3ème">3ème</option>
                        <option value="2nd">2nd</option>
                        <option value="1ère">1ère</option>
                        <option value="Terminal">Terminal</option>
                        <option value="Université">Université</option>
                      </select>
                    </div>
                  </div>
  
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                      </svg>
                      Contact <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.contact"
                        @input="formatPhoneNumber"
                        type="tel"
                        required
                        class="form-input"
                        placeholder="07 XX XX XX XX"
                        maxlength="14"
                      />
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Étape 2: Informations Organisationnelles -->
              <div v-if="currentStep === 2" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Informations Organisationnelles
                </h2>
  
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                      </svg>
                      Ville <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.ville"
                        @input="formatUpperCase('ville')"
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
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
                      </svg>
                      Contact Parent/Tuteur
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.contactParent"
                        @input="formatPhoneNumberParent"
                        type="tel"
                        class="form-input"
                        placeholder="07 XX XX XX XX"
                        maxlength="14"
                      />
                    </div>
                  </div>
  
                  <div class="form-group form-group-full">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                      </svg>
                      Avez-vous une maladie ? <span class="required">*</span>
                    </label>
                    <div class="radio-group radio-group-horizontal">
                      <label class="radio-item radio-item-compact">
                        <input
                          v-model="formData.aUneMaladie"
                          type="radio"
                          value="oui"
                          class="radio-input"
                          required
                        />
                        <div class="radio-custom"></div>
                        <span class="radio-label">Oui</span>
                      </label>
                      <label class="radio-item radio-item-compact">
                        <input
                          v-model="formData.aUneMaladie"
                          type="radio"
                          value="non"
                          class="radio-input"
                          required
                        />
                        <div class="radio-custom"></div>
                        <span class="radio-label">Non</span>
                      </label>
                    </div>
                  </div>
  
                  <div v-if="formData.aUneMaladie === 'oui'" class="form-group form-group-full">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm0-8H9V5h6v2z"/>
                      </svg>
                      Détaillez-nous <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <textarea
                        v-model="formData.maladieDetail"
                        required
                        class="form-input"
                        rows="3"
                        placeholder="Décrivez votre maladie ou condition médicale"
                      ></textarea>
                    </div>
                  </div>
  
                  <div class="form-group form-group-full">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                      </svg>
                      Taille T-shirt <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <select
                        v-model="formData.tailleTshirt"
                        required
                        class="form-input"
                      >
                        <option value="">Sélectionnez votre taille</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                        <option value="XXXL">XXXL</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Étape Photo -->
              <div v-if="currentStep === 3" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 2l3 3h4c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V7c0-1.1.9-2 2-2h3l3-3zm3 15c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0-1.8c-1.77 0-3.2-1.43-3.2-3.2s1.43-3.2 3.2-3.2 3.2 1.43 3.2 3.2-1.43 3.2-3.2 3.2z"/>
                  </svg>
                  Photo d'Identité
                </h2>
  
                <div class="photo-section">
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
              </div>
  
              <!-- Étape Transport/Paiement -->
              <div v-if="currentStep === 4" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/>
                  </svg>
                  Transport et Paiement
                </h2>
  
                <!-- Section Transport -->
                <div class="transport-section">
                  <h3 class="transport-heading">
                    <svg class="heading-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                    </svg>
                    D'où partez-vous ? <span style="color: red">*</span>
                  </h3>
  
                  <div class="transport-info">
                    <p class="transport-description">
                      Sélectionnez votre lieu de départ pour déterminer les frais de transport :
                    </p>
  
                    <div class="radio-group">
                      <label class="radio-item transport-option">
                        <input
                          v-model="formData.transport"
                          type="radio"
                          value="convoi"
                          class="radio-input"
                          required
                        />
                        <div class="radio-custom"></div>
                        <div class="transport-details">
                          <span class="transport-title">Je pars d'Abidjan</span>
                          <span class="transport-subtitle">Paiement de 9 000 FCFA requis</span>
                        </div>
                      </label>
  
                      <label class="radio-item transport-option">
                        <input
                          v-model="formData.transport"
                          type="radio"
                          value="personnel"
                          class="radio-input"
                          required
                        />
                        <div class="radio-custom"></div>
                        <div class="transport-details">
                          <span class="transport-title">Je pars de l'intérieur</span>
                          <span class="transport-subtitle">Aucun paiement requis</span>
                        </div>
                      </label>
                    </div>
                  </div>
                </div>
  
                <!-- Section Paiement (seulement si Abidjan) -->
                <div v-if="formData.transport === 'convoi'" class="payment-section">
                  <div class="payment-info">
                    <div class="price-box">
                      <svg class="price-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                      </svg>
                      <span class="price-label">Frais de transport :</span>
                      <span class="price-amount">9 000 FCFA</span>
                    </div>
                  </div>
  
                  <div class="payment-method">
                    <div class="wave-header">
                      <div class="wave-icon">
                        <img src="https://play-lh.googleusercontent.com/-Mp3XW7uhwn3KGQxUKGPoc4MbA5ti-3-q23TgoVi9ujBgHWW5n4IySvlG5Exwrxsjw=w256" width="48" alt="Wave">
                      </div>
                      <div class="wave-info">
                        <h3>Paiement Wave</h3>
                        <p>Paiement sécurisé via Wave Money</p>
                      </div>
                    </div>
  
                    <div class="form-group">
                      <label class="form-label">
                        <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        Numéro de téléphone Wave <span class="required">*</span>
                      </label>
                      <div class="input-container">
                        <input
                          v-model="formData.numeroWave"
                          @input="formatWaveNumber"
                          type="tel"
                          required
                          class="form-input"
                          placeholder="07 XX XX XX XX"
                          maxlength="14"
                        />
                      </div>
                      <small class="form-help">Le numéro doit être associé à un compte Wave actif</small>
                    </div>
                  </div>
                </div>
  
                <!-- Message pour transport personnel -->
                <div v-if="formData.transport === 'personnel'" class="no-payment-section">
                  <div class="no-payment-info">
                    <svg class="info-icon" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                    </svg>
                    <h3>Inscription gratuite</h3>
                    <p>Aucun paiement requis pour les SR venant de l'intérieur du pays.</p>
                    <p>Votre transport sera à votre charge.</p>
                  </div>
                </div>
              </div>
  
              <!-- Navigation Buttons -->
              <div v-if="showFormContent" class="form-navigation">
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
                <button
                  v-if="currentStep == 1"
                  @click="backToHome"
                  type="button"
                  class="btn btn-secondary"
                >
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                  </svg>
                  Retour
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
            <h3>Inscription SR réussie !</h3>
            <p>Votre inscription en tant que Secrétaire Régional a été enregistrée avec succès.</p>
            <p v-if="inscriptionData.matricule" class="matricule-info">
              <strong>Matricule :</strong> {{ inscriptionData.matricule }}
            </p>
            <p v-if="inscriptionData.dortoir" class="dortoir-info">
              <strong>Dortoir :</strong> {{ inscriptionData.dortoir }}
            </p>
            <p v-if="inscriptionData.transport" class="transport-info">
              <strong>Transport :</strong> {{ inscriptionData.transport }}
            </p>
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
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch } from 'vue'
  import axios from 'axios'
  import { jsPDF } from 'jspdf';
  import qrcode from 'qrcode';
  
  // Configuration API
  const API_BASE_URL = 'https://sogetrag.com/api/'
  
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
  const showConfirmation = ref(false)
  const isSubmitting = ref(false)
  const isUploading = ref(false)
  const uploadProgress = ref(0)
  const uploadError = ref('')
  
  // États pour la liste des SR
  const showSRList = ref(false)
  const srList = ref([])
  const isLoadingSR = ref(false)
  const srError = ref('')
  const srSearchQuery = ref('')
  const selectedSR = ref(null)
  const selectedSecretariat = ref('') // Nouveau filtre par secrétariat
  const inscriptionsExistantes = ref([]) // Liste des inscriptions existantes
  
  // Données d'inscription retournées
  const inscriptionData = ref({})
  
  // Données du formulaire
  const formData = ref({
    nom: '',
    prenom: '',
    sexe: '',
    dateNaissance: '',
    age: null,
    niveauEtude: '',
    ville: '',
    contact: '',
    contactParent: '',
    tailleTshirt: '',
    aUneMaladie: '',
    maladieDetail: '',
    photo: null,
    photoId: null,
    numeroWave: '',
    transport: 'personnel' // Par défaut intérieur (gratuit)
  })
  
  // Computed properties
  const totalSteps = computed(() => 4)
  const steps = computed(() => [
    { title: 'Informations personnelles' },
    { title: 'Informations organisationnelles' },
    { title: 'Photo' },
    { title: 'Transport/Paiement' }
  ])
  
  const showProgressSteps = computed(() =>
    currentStep.value > 0 && !showSRList.value
  )
  
  const showFormContent = computed(() =>
    currentStep.value > 0 && !showSRList.value
  )
  
  // Computed pour filtrer la liste des SR
  const filteredSRList = computed(() => {
    let filtered = srList.value
  
    // Filtrer par secrétariat régional
    if (selectedSecretariat.value) {
      filtered = filtered.filter(sr => sr.secretariat_regional === selectedSecretariat.value)
    }
  
    // Filtrer par recherche textuelle
    if (srSearchQuery.value) {
      const query = srSearchQuery.value.toLowerCase().trim()
      filtered = filtered.filter(sr => {
        const nom = sr.nom.toLowerCase()
        const prenom = sr.prenom.toLowerCase()
        const nomComplet = `${nom} ${prenom}`
        const prenomNom = `${prenom} ${nom}`
  
        return nom.includes(query) ||
               prenom.includes(query) ||
               nomComplet.includes(query) ||
               prenomNom.includes(query)
      })
    }
  
    // Ajouter le statut d'inscription à chaque SR
    return filtered.map(sr => ({
      ...sr,
      isInscrit: inscriptionsExistantes.value.some(inscription =>
        inscription.nom === sr.nom && inscription.prenom === sr.prenom
      )
    }))
  })
  
  // Computed pour obtenir la liste des secrétariats régionaux
  const secretariatsRegionaux = computed(() => {
    const secretariats = [...new Set(srList.value.map(sr => sr.secretariat_regional))]
    return secretariats.filter(s => s).sort()
  })
  
  // Fonction pour vérifier si un SR est déjà inscrit
  const isSRInscrit = (sr) => {
    return inscriptionsExistantes.value.some(inscription =>
      inscription.nom === sr.nom && inscription.prenom === sr.prenom
    )
  }
  
  // Fonction pour récupérer la liste des SR
  const loadSRList = async () => {
    isLoadingSR.value = true
    srError.value = ''
  
    try {
      // Charger la liste des SR et les inscriptions existantes en parallèle
      const [srResponse, inscriptionsResponse] = await Promise.all([
        axios.get(`${API_BASE_URL}/sr_api.php?action=liste_sr`),
        axios.get(`${API_BASE_URL}/sr_api.php?action=inscriptions_sr`)
      ])
  
      if (srResponse.data.success) {
        srList.value = srResponse.data.data || []
      } else {
        throw new Error(srResponse.data.error || 'Erreur lors de la récupération des SR')
      }
  
      if (inscriptionsResponse.data.success) {
        inscriptionsExistantes.value = inscriptionsResponse.data.data || []
      }
    } catch (error) {
      console.error('Erreur lors de la récupération des SR:', error)
      srError.value = 'Erreur lors du chargement de la liste des SR. Veuillez réessayer.'
      srList.value = []
    } finally {
      isLoadingSR.value = false
    }
  }
  
  // Validation des étapes
  const canProceedToNextStep = computed(() => {
    switch (currentStep.value) {
      case 1:
        return formData.value.nom && formData.value.prenom &&
               formData.value.sexe && formData.value.dateNaissance &&
               formData.value.age && formData.value.niveauEtude &&
               formData.value.contact
      case 2:
        return formData.value.ville &&
               formData.value.tailleTshirt &&
               formData.value.aUneMaladie &&
               (formData.value.aUneMaladie === 'non' || formData.value.maladieDetail)
      case 3:
        return formData.value.photo
      case 4:
        return formData.value.transport &&
               (formData.value.transport === 'personnel' || formData.value.numeroWave)
      default:
        return false
    }
  })
  
  const canSubmit = computed(() => canProceedToNextStep.value)
  
  // Fonction pour calculer le prix total
  const getTotalPrice = () => {
    return formData.value.transport === 'convoi' ? 9000 : 0
  }
  
  // Fonctions de navigation
  const showSRSelection = async () => {
    showSRList.value = true
    await loadSRList()
  }
  
  const selectSR = (sr) => {
    if (sr.isInscrit) {
      alert('Ce Secrétaire Régional est déjà inscrit au SENAFOI 2025.')
      return
    }
  
    selectedSR.value = sr
    // Pré-remplir les données du formulaire
    formData.value.nom = sr.nom
    formData.value.prenom = sr.prenom
  
    // Aller au formulaire
    showSRList.value = false
    currentStep.value = 1
  }
  
  const backToHome = () => {
    showSRList.value = false
    currentStep.value = 0
    resetForm()
  }
  
  // Upload d'image vers Cloudinary
  const uploadImageToCloudinary = async (image) => {
    try {
      uploadError.value = ''
  
      const fileName = `aeemci_sr_${Date.now()}_${image.name.replace(/\s+/g, '_')}`
  
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
  
  // Supprimer la photo
  const removePhoto = () => {
    formData.value.photo = null
    formData.value.photoId = null
    if (photoInput.value) {
      photoInput.value.value = ''
    }
  }
  
  // Fonctions de formatage
  const formatUpperCase = (field) => {
    formData.value[field] = formData.value[field].toUpperCase()
  }
  
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
  
  const formatPhoneNumberParent = (event) => {
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
    formData.value.contactParent = value
  }
  
  const formatWaveNumber = (event) => {
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
    formData.value.numeroWave = value
  }
  
  // Fonctions de navigation
  const nextStep = () => {
    if (currentStep.value < totalSteps.value) {
      currentStep.value++
    }
  }
  
  const previousStep = () => {
    if (currentStep.value > 1) {
      currentStep.value--
    }
  }
  
  // Fonction pour soumettre
  const soumettreInscription = async () => {
    if (!canSubmit.value) return
    isSubmitting.value = true
  
    try {
      // Préparer les données à envoyer
      const dataToSend = {
        amount: getTotalPrice(),
        currency: 'XOF',
        data: {
          ...formData.value,
          // Utiliser ville comme secretariat pour la compatibilité backend
          secretariatRegional: formData.value.ville
        }
      }
  
      const response = await axios.post(`${API_BASE_URL}/seminaireSR.php`, dataToSend)
  
      if (response.data.success) {
        if (response.data.wave_launch_url) {
          // Redirection automatique vers l'URL de paiement Wave
          window.location.href = response.data.wave_launch_url
        } else {
          // Inscription gratuite réussie
          inscriptionData.value = response.data.data || {}
          inscriptionData.value.matricule = response.data.matricule
          showConfirmation.value = true
        }
      } else {
        throw new Error(response.data.error || 'Erreur inattendue')
      }
    } catch (error) {
      console.error('Erreur lors de l\'inscription:', error)
      alert('Une erreur est survenue lors de l\'inscription. Veuillez réessayer.')
    } finally {
      isSubmitting.value = false
    }
  }
  
  // Fonction pour réinitialiser le formulaire
  const resetForm = () => {
    currentStep.value = 0
    showConfirmation.value = false
    isSubmitting.value = false
    uploadProgress.value = 0
    uploadError.value = ''
    selectedSR.value = null
  
    formData.value = {
      nom: '',
      prenom: '',
      sexe: '',
      dateNaissance: '',
      age: null,
      niveauEtude: '',
      ville: '',
      contact: '',
      contactParent: '',
      tailleTshirt: '',
      aUneMaladie: '',
      maladieDetail: '',
      photo: null,
      photoId: null,
      numeroWave: '',
      transport: 'personnel'
    }
  
    // Réinitialiser le champ de fichier photo
    if (photoInput.value) {
      photoInput.value.value = null
    }
  }
  
  // Calcul de l'âge
  const calculateAge = () => {
    if (formData.value.dateNaissance) {
      const birthDate = new Date(formData.value.dateNaissance)
      const today = new Date()
      let age = today.getFullYear() - birthDate.getFullYear()
      const month = today.getMonth() - birthDate.getMonth()
  
      if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
        age--
      }
  
      formData.value.age = age
    } else {
      formData.value.age = null
    }
  }
  
  // Fonctions utilitaires
  const downloadFiche = async () => {
    try {
      // Import dynamique de jsPDF
      const { default: jsPDF } = await import('jspdf');
      const QRCode = (await import('qrcode')).default;
      
      const doc = new jsPDF('p', 'mm', 'a4');
      const today = new Date();
      const receiptNumber = Math.floor(Math.random() * 99999).toString().padStart(5, '0');
      const qrText = `https://aeemci.sastock.com/seminaire_sr_paye/${inscriptionData.value.matricule || 'N/A'}`;
      const qrDataURL = await QRCode.toDataURL(qrText, {
        width: 120,
        margin: 1,
      });
  
      // Filigrane AEEMCI (plus discret)
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(8);
      doc.setTextColor(245, 245, 245);
      for (let y = 20; y <= 280; y += 20) {
        for (let x = 10; x <= 200; x += 40) {
          doc.text('AEEMCI', x, y);
        }
      }
  
      // En-tête avec design amélioré
      doc.setFillColor(0, 128, 0);
      doc.rect(0, 0, 210, 35, 'F');
      
      // Logo
      try {
        doc.addImage('https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg', 'JPEG', 10, 8, 28, 25);
      } catch (error) {
        console.warn('Logo non chargé:', error);
      }
      
      // Titre principal en blanc
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(20);
      doc.setTextColor(255, 255, 255);
      doc.text('AEEMCI', 105, 15, { align: 'center' });
      doc.setFontSize(10);
      doc.text("Association des Elèves et Etudiants Musulmans de Côte d'Ivoire", 105, 20, { align: 'center' });
      
      // Sous-titre
      doc.setFontSize(14);
      doc.text("FICHE D'INSCRIPTION SR - RECU SENAFOI 25", 105, 28, { align: 'center' });
  
      // Bannière
      try {
        const bannerImg = new Image();
        bannerImg.crossOrigin = 'anonymous';
        
        await new Promise((resolve, reject) => {
          bannerImg.onload = resolve;
          bannerImg.onerror = reject;
          bannerImg.src = 'https://res.cloudinary.com/dqk65objc/image/upload/v1751398353/aeemci_seminaire_1751398349649_ban.jpeg.jpg';
        });
        
        doc.addImage(bannerImg, 'JPEG', 15, 37, 180, 27);
      } catch (error) {
        console.warn('Impossible de charger la bannière:', error);
      }
  
      let startY = 67;
  
      // Informations de base
      doc.setTextColor(0, 0, 0);
      doc.setFont('helvetica', 'normal');
      doc.setFontSize(9);
      doc.text(`RECU N° : ${receiptNumber}`, 18, startY);
      doc.text(`Date : ${today.toLocaleDateString('fr-FR')}`, 165, startY);
      
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(12);
      doc.text(`MATRICULE SR: ${inscriptionData.value.matricule || 'N/A'}`, 105, startY + 7, { align: 'center' });
  
      // Ligne de séparation
      doc.setLineWidth(0.5);
      doc.setDrawColor(0, 128, 0);
      doc.line(15, startY + 12, 195, startY + 12);
  
      // Section IDENTIFICATION DU SECRÉTAIRE RÉGIONAL
      let yPos = startY + 20;
      doc.setFillColor(240, 248, 255);
      doc.rect(15, yPos - 5, 180, 8, 'F');
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(11);
      doc.text("IDENTIFICATION DU SECRÉTAIRE RÉGIONAL", 20, yPos);
  
      // Photo (si disponible)
      if (formData.value.photo) {
        try {
          doc.addImage(formData.value.photo, 'JPEG', 155, yPos + 2, 30, 30);
        } catch (error) {
          console.warn('Photo non chargée:', error);
        }
      }
  
      // Informations personnelles (2 colonnes)
      yPos += 10;
      doc.setFontSize(9);
      
      // Colonne 1
      doc.setFont('helvetica', 'normal');
      doc.text('Nom :', 20, yPos);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.nom || '', 35, yPos);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Prénom(s) :', 20, yPos + 6);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.prenom || '', 40, yPos + 6);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Sexe :', 20, yPos + 12);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.sexe === 'M' ? 'Masculin' : 'Féminin', 35, yPos + 12);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Contact :', 20, yPos + 18);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.contact || '', 35, yPos + 18);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Âge :', 20, yPos + 24);
      doc.setFont('helvetica', 'bold');
      doc.text(`${formData.value.age || 'N/A'} ans`, 35, yPos + 24);
  
      // Colonne 2
      doc.setFont('helvetica', 'normal');
      doc.text('Ville :', 90, yPos);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.ville || '', 105, yPos);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Niveau étude :', 90, yPos + 6);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.niveauEtude || '', 115, yPos + 6);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Taille T-shirt :', 90, yPos + 12);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.tailleTshirt || '', 115, yPos + 12);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Dortoir :', 90, yPos + 18);
      doc.setFont('helvetica', 'bold');
      doc.text(inscriptionData.value.dortoir || 'Dortoir SR', 105, yPos + 18);
      
      if (formData.value.contactParent) {
        doc.setFont('helvetica', 'normal');
        doc.text('Contact Parent :', 90, yPos + 24);
        doc.setFont('helvetica', 'bold');
        doc.text(formData.value.contactParent, 120, yPos + 24);
      }
  
      // Section TRANSPORT ET PAIEMENT
      yPos += 35;
      doc.setFillColor(240, 248, 255);
      doc.rect(15, yPos - 5, 180, 8, 'F');
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(11);
      doc.text("TRANSPORT ET PAIEMENT", 20, yPos);
      
      yPos += 8;
      doc.setFontSize(9);
      doc.setFont('helvetica', 'normal');
      doc.text('Mode transport :', 20, yPos);
      doc.setFont('helvetica', 'bold');
      doc.text(formData.value.transport === 'convoi' ? 'Convoi depuis Abidjan' : 'Transport personnel depuis l\'intérieur', 50, yPos);
      
      doc.setFont('helvetica', 'normal');
      doc.text('Montant payé :', 20, yPos + 6);
      doc.setFont('helvetica', 'bold');
      doc.text(`${getTotalPrice()} FCFA`, 45, yPos + 6);
      
      if (formData.value.transport === 'convoi' && formData.value.numeroWave) {
        doc.setFont('helvetica', 'normal');
        doc.text('N° Wave :', 90, yPos);
        doc.setFont('helvetica', 'bold');
        doc.text(formData.value.numeroWave, 110, yPos);
        
        doc.setFont('helvetica', 'normal');
        doc.text('Opérateur :', 90, yPos + 6);
        doc.setFont('helvetica', 'bold');
        doc.text('WAVE', 110, yPos + 6);
      }
      
      doc.setFont('helvetica', 'normal');
      doc.text('Date inscription :', 20, yPos + 12);
      doc.setFont('helvetica', 'bold');
      doc.text(today.toLocaleDateString('fr-FR'), 50, yPos + 12);
  
      // QR Code
      doc.addImage(qrDataURL, 'PNG', 160, yPos - 15, 25, 25);
  
      // Section INFORMATIONS MÉDICALES (si applicable)
      if (formData.value.aUneMaladie === 'oui') {
        yPos += 20;
        doc.setFillColor(255, 248, 220);
        doc.rect(15, yPos - 5, 180, 8, 'F');
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(11);
        doc.setTextColor(204, 102, 0);
        doc.text("INFORMATIONS MÉDICALES", 20, yPos);
        
        yPos += 8;
        doc.setTextColor(0, 0, 0);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.text('Maladie déclarée :', 20, yPos);
        doc.setFont('helvetica', 'bold');
        doc.text('OUI', 50, yPos);
        
        yPos += 4;
        doc.setFont('helvetica', 'normal');
        doc.text('Détails :', 20, yPos);
        
        // Découper le texte de la maladie en plusieurs lignes si nécessaire
        const maladieLines = doc.splitTextToSize(formData.value.maladieDetail || '', 150);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(7);
        maladieLines.forEach((line, index) => {
          doc.text(line, 20, yPos + 4 + (index * 3));
        });
        
        yPos += 4 + (maladieLines.length * 3);
      }
  
      // Section CONSIGNES POUR LES SECRÉTAIRES RÉGIONAUX
      yPos += 15;
      doc.setFillColor(255, 248, 220);
      doc.rect(15, yPos - 5, 180, 8, 'F');
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(11);
      doc.setTextColor(204, 102, 0);
      doc.text("CONSIGNES POUR LES SECRÉTAIRES RÉGIONAUX", 20, yPos);
  
      yPos += 8;
      doc.setTextColor(0, 0, 0);
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(8);
      doc.text("En tant que Secrétaire Régional, vous devez prévoir :", 20, yPos);
      
      yPos += 4;
      doc.setFont('helvetica', 'normal');
      doc.setFontSize(7);
      
      const consignesSR = [
        "• Des vêtements décents et professionnels pour vos responsabilités",
        "• Votre matériel de travail (bloc-notes, stylos, etc.)",
        "• Votre literie personnelle (drap, couverture, moustiquaire)",
        "• Vos documents d'identité et cette fiche d'inscription"
      ];
      
      consignesSR.forEach(consigne => {
        doc.text(consigne, 22, yPos);
        yPos += 3.5;
      });
  
      yPos += 2;
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(8);
      doc.text("Responsabilités spéciales :", 20, yPos);
      
      yPos += 4;
      doc.setFont('helvetica', 'normal');
      doc.setFontSize(7);
      
      const responsabilites = [
        "• Vous serez responsable de l'encadrement des séminaristes de votre région",
        "• Participation aux réunions de coordination des SR",
        "• Assistance dans l'organisation et la logistique du séminaire",
        "• Veiller au respect du règlement intérieur par les participants de votre région"
      ];
      
      responsabilites.forEach(resp => {
        doc.text(resp, 22, yPos);
        yPos += 3.5;
      });
  
      yPos += 2;
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(8);
      doc.text("Contacts d'urgence :", 20, yPos);
      
      yPos += 4;
      doc.setFont('helvetica', 'normal');
      doc.setFontSize(7);
      
      const contacts = [
        "• Coordination SR : +225 40 65 56 51",
        "• Support technique : +225 44 39 75 79",
        "• Urgences médicales : +225 56 16 55 53"
      ];
      
      contacts.forEach(contact => {
        doc.text(contact, 22, yPos);
        yPos += 3.5;
      });
  
      // Pied de page amélioré
      doc.setFillColor(0, 128, 0);
      doc.rect(0, 275, 210, 22, 'F');
      
      doc.setFont('helvetica', 'normal');
      doc.setFontSize(7);
      doc.setTextColor(255, 255, 255);
      doc.text("Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 282, { align: 'center' });
      doc.text("27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 / aeemci@yahoo.fr / www.aeemci.ci", 105, 286, { align: 'center' });
      
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(9);
      doc.text("AEEMCI, pour une identité islamique !", 105, 292, { align: 'center' });
  
      // Téléchargement du PDF
      const filename = `fiche_sr_aeemci_${formData.value.nom}_${formData.value.prenom}_${today.toISOString().split('T')[0]}.pdf`;
      doc.save(filename);
  
    } catch (error) {
      console.error('Erreur lors de la génération du PDF:', error);
      alert('Erreur lors de la génération du PDF. Veuillez réessayer.');
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
  
  /* Styles pour les sections de choix */
  .choice-section, .sr-section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 500px;
    padding: 2rem;
  }
  
  .choice-container, .sr-container {
    background: #f0fdf4;
    border: 2px solid #16a34a;
    border-radius: 1rem;
    padding: 3rem;
    text-align: center;
    max-width: 800px;
    width: 100%;
  }
  
  .choice-container h3, .sr-container h3 {
    font-size: 2rem;
    font-weight: bold;
    color: #15803d;
    margin-bottom: 1rem;
  }
  
  .choice-subtitle, .sr-subtitle {
    font-size: 1.125rem;
    color: #4b5563;
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
    border-radius: 1rem;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
  }
  
  .choice-btn-primary {
    border-color: #16a34a;
    color: #15803d;
  }
  
  .choice-btn-primary:hover {
    background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(22, 163, 74, 0.2);
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
  
  /* Styles pour la liste des SR */
  .sr-list {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    margin-bottom: 2rem;
  }
  
  .sr-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .sr-item:hover {
    background: #f9fafb;
  }
  
  .sr-item:last-child {
    border-bottom: none;
  }
  
  .sr-info {
    flex: 1;
  }
  
  .sr-name {
    font-size: 1rem;
    margin-bottom: 0.5rem;
  }
  
  .sr-arrow {
    color: #16a34a;
  }
  
  /* États de chargement et d'erreur */
  .loading-state, .error-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    text-align: center;
    color: #6b7280;
  }
  
  .loading-spinner-large {
    width: 48px;
    height: 48px;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #16a34a;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
  }
  
  .error-state svg {
    color: #dc2626;
    margin-bottom: 1rem;
  }
  
  .error-state p {
    font-size: 1.125rem;
    font-weight: 500;
    margin-bottom: 1rem;
  }
  
  .no-results {
    padding: 3rem;
    text-align: center;
    color: #6b7280;
  }
  
  .no-results svg {
    margin-bottom: 1rem;
    opacity: 0.5;
  }
  
  .no-results p {
    font-size: 1.125rem;
    font-weight: 500;
  }
  
  /* Barre de recherche */
  .search-container {
    position: relative;
    margin-bottom: 1.5rem;
  }
  
  .search-input {
    width: 100%;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
  }
  
  .search-input:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  
  .search-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
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
  
  .input-readonly {
    background: #f9fafb;
    color: #6b7280;
    cursor: not-allowed;
  }
  
  .readonly-help {
    color: #16a34a;
    font-style: italic;
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
  
  .radio-group-horizontal {
    flex-direction: row;
    gap: 1.5rem;
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
  
  .radio-item-compact {
    padding: 0.5rem 1rem;
    flex: none;
    width: auto;
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
    flex-shrink: 0;
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
  
  /* Transport Section */
  .transport-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 2px solid #e5e7eb;
  }
  
  .transport-heading {
    font-size: 1.25rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .transport-info {
    margin-bottom: 1rem;
  }
  
  .transport-description {
    font-size: 1rem;
    color: #4b5563;
    margin-bottom: 1.5rem;
  }
  
  .transport-option {
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    margin-bottom: 1rem;
    background: #ffffff;
    transition: all 0.2s ease;
  }
  
  .transport-option:hover {
    border-color: #16a34a;
    background: #f0fdf4;
  }
  
  .transport-option:has(.radio-input:checked) {
    border-color: #16a34a;
    background: #f0fdf4;
  }
  
  .transport-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-left: 0.5rem;
  }
  
  .transport-title {
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
  }
  
  .transport-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
  }
  
  /* Photo Section */
  .photo-section {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
  }
  
  .photo-upload {
    position: relative;
    width: 200px;
    height: 250px;
  }
  
  .photo-input {
    display: none;
  }
  
  .photo-placeholder {
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
  
  .upload-icon {
    color: #9ca3af;
    margin-bottom: 1rem;
  }
  
  .photo-placeholder p {
    font-size: 0.875rem;
    font-weight: 500;
    color: #4b5563;
    margin-bottom: 0.5rem;
    text-align: center;
  }
  
  .photo-placeholder small {
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
  }
  
  .upload-loading {
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
  
  .photo-preview {
    width: 100%;
    height: 100%;
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 2px solid #16a34a;
  }
  
  .preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .remove-photo {
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
  
  .remove-photo:hover {
    background: #ffffff;
    transform: scale(1.1);
  }
  
  /* Payment Section */
  .payment-section {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #ffffff;
    border-radius: 0.75rem;
    border: 2px solid #e5e7eb;
  }
  
  .payment-info {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 2px solid #e5e7eb;
  }
  
  .price-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #ffffff;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1rem;
  }
  
  .price-icon {
    color: #16a34a;
    flex-shrink: 0;
  }
  
  .price-label {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    flex: 1;
  }
  
  .price-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: #16a34a;
  }
  
  .payment-method {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #ffffff;
    border-radius: 0.75rem;
    border: 2px solid #e5e7eb;
  }
  
  .wave-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  
  .wave-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.5rem;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .wave-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }
  
  .wave-info h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.25rem 0;
  }
  
  .wave-info p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
  }
  
  /* No Payment Section */
  .no-payment-section {
    margin-top: 2rem;
    padding: 2rem;
    background: #f0fdf4;
    border-radius: 0.75rem;
    border: 2px solid #16a34a;
    text-align: center;
  }
  
  .no-payment-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }
  
  .info-icon {
    color: #16a34a;
    margin-bottom: 1rem;
  }
  
  .no-payment-info h3 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #15803d;
    margin: 0;
  }
  
  .no-payment-info p {
    font-size: 1rem;
    color: #4b5563;
    margin: 0;
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
  
  .matricule-info, .dortoir-info, .transport-info {
    padding: 0.75rem;
    background: #f0fdf4;
    border-radius: 0.5rem;
    margin-top: 1rem;
    font-size: 1rem;
    color: #15803d;
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
  
    .choice-buttons {
      flex-direction: column;
    }
  
    .choice-btn {
      padding: 1.5rem;
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
  
    .choice-container, .sr-container {
      padding: 1.5rem;
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
  
  /* Filtre par secrétariat */
  .filter-container {
    margin-bottom: 1.5rem;
  }
  
  .filter-select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    background: white;
    transition: all 0.2s ease;
  }
  
  .filter-select:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  
  /* Styles pour les SR inscrits */
  .sr-item-inscrit {
    background: #f3f4f6;
    cursor: not-allowed;
    opacity: 0.7;
  }
  
  .sr-item-inscrit:hover {
    background: #f3f4f6;
  }
  
  .sr-status-badge {
    display: inline-block;
    background: #10b981;
    color: white;
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    margin-left: 0.5rem;
  }
  
  .sr-secretariat {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
  }
  
  .sr-check {
    color: #10b981;
  }
  </style>
  