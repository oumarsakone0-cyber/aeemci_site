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
                <p>Inscription des Membres de Commissions</p>
              </div>
            </div>
          </div>
          <div class="header-right" v-if="currentStep > 0">
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
          <!-- Étape de choix initial -->
          <div v-if="currentStep === 0" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              Inscription Commission AEEMCI
            </h2>
            
            <div class="choice-section">
              <div class="choice-container">
                <h3>Rejoignez une Commission AEEMCI</h3>
                <p class="choice-subtitle">Contribuez au développement de l'organisation</p>
                
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
                      <span class="btn-title">S'inscrire à une Commission</span>
                      <span class="btn-subtitle">Nouvelle inscription pour rejoindre une commission</span>
                    </div>
                  </button>
                  
                  <button
                    @click="showResults"
                    type="button"
                    class="choice-btn choice-btn-secondary"
                  >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                    <div class="btn-content">
                      <span class="btn-title">Consulter les membres des commissions</span>
                      <span class="btn-subtitle">Voir la liste des membres inscrits</span>
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Tableau des résultats -->
          <div v-if="showResultsTable" class="step-content">
            <h2 class="step-heading">
              <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
              </svg>
              Membres des Commissions
            </h2>
            
            <div class="results-section">
              <div class="results-header">
                <p>Liste des membres inscrits dans les commissions</p>
                <div class="search-container">
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par nom ou commission..."
                    class="search-input"
                    :disabled="isLoadingResults"
                  />
                  <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                  </svg>
                </div>
              </div>
  
              <!-- Informations de pagination -->
              <div v-if="!isLoadingResults && !resultError && membresCommissions.length > 0" class="pagination-info">
                <p>
                  Affichage de {{ paginationInfo.start }} à {{ paginationInfo.end }}
                  sur {{ paginationInfo.total }} résultat{{ paginationInfo.total > 1 ? 's' : '' }}
                  <span v-if="searchQuery"> pour "{{ searchQuery }}"</span>
                </p>
              </div>
  
              <!-- État de chargement -->
              <div v-if="isLoadingResults" class="loading-state">
                <div class="loading-spinner-large"></div>
                <p>Chargement des membres...</p>
              </div>
  
              <!-- Erreur -->
              <div v-if="resultError" class="error-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                </svg>
                <p>{{ resultError }}</p>
                <button @click="fetchMembresCommissions" class="btn btn-primary">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                  </svg>
                  Réessayer
                </button>
              </div>
  
              <!-- Tableau -->
              <div v-if="!isLoadingResults && !resultError" class="table-container">
                <table class="results-table">
                  <thead>
                    <tr>
                      <th>Nom</th>
                      <th>Prénom</th>
                      <th>Commission</th>
                      <th>Contact</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="membre in filteredResults" :key="membre.id">
                      <td class="table-cell">{{ membre.nom }}</td>
                      <td class="table-cell">{{ membre.prenom }}</td>
                      <td class="table-cell">
                        <span class="commission-badge">{{ membre.commission }}</span>
                      </td>
                      <td class="table-cell">{{ membre.contact }}</td>
                    </tr>
                  </tbody>
                </table>
  
                <div v-if="!isLoadingResults && !resultError && filteredResults.length === 0 && membresCommissions.length > 0" class="no-results">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                  </svg>
                  <p>Aucun résultat trouvé pour "{{ searchQuery }}"</p>
                </div>
  
                <div v-if="!isLoadingResults && !resultError && membresCommissions.length === 0" class="no-results">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                  </svg>
                  <p>Aucun membre disponible</p>
                </div>
              </div>
  
              <!-- Contrôles de pagination -->
              <div v-if="!isLoadingResults && !resultError && totalPages > 1" class="pagination-controls">
                <div class="pagination-buttons">
                  <button
                    @click="previousPage"
                    :disabled="currentPage === 1"
                    class="pagination-btn pagination-btn-nav"
                  >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                    Pré
                  </button>
  
                  <div class="pagination-numbers">
                    <button
                      v-for="page in Math.min(5, totalPages)"
                      :key="page"
                      @click="goToPage(page)"
                      :class="['pagination-btn', { 'pagination-btn-active': page === currentPage }]"
                    >
                      {{ page }}
                    </button>
  
                    <span v-if="totalPages > 5" class="pagination-ellipsis">...</span>
  
                    <button
                      v-if="totalPages > 5 && currentPage < totalPages - 2"
                      @click="goToPage(totalPages)"
                      :class="['pagination-btn', { 'pagination-btn-active': totalPages === currentPage }]"
                    >
                      {{ totalPages }}
                    </button>
                  </div>
  
                  <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    class="pagination-btn pagination-btn-nav"
                  >
                    Suiv
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                    </svg>
                  </button>
                </div>
  
                <div class="items-per-page">
                  <label for="itemsPerPage">Éléments par page :</label>
                  <select
                    id="itemsPerPage"
                    v-model="itemsPerPage"
                    @change="currentPage = 1"
                    class="items-select"
                  >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                  </select>
                </div>
              </div>
  
              <div class="results-actions">
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
                  @click="startNewInscription"
                  type="button"
                  class="btn btn-primary"
                >
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                  </svg>
                  S'inscrire à une Commission
                </button>
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
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
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
                        <path d="M12 3l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 3z"/>
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
                        <option value="LYCEE">LYCEE</option>
                        <option value="BAC">BAC</option>
                        <option value="BAC+ 1">BAC+ 1</option>
                        <option value="BAC+ 2">BAC+ 2</option>
                        <option value="BAC+ 3">BAC+ 3</option>
                        <option value="BAC+ 4">BAC+ 4</option>
                        <option value="BAC+ 5">BAC +5</option>
                        <option value="Plus">PLUS</option>
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
  
              <!-- Étape 2: Commission -->
              <div v-if="currentStep === 2" class="step-content">
                <h2 class="step-heading">
                  <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Sélection de Commission
                </h2>
  
                <div class="form-grid">
                  <div class="form-group form-group-full">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
                      </svg>
                      Commission <span class="required">*</span>
                    </label>
                    <div class="commission-grid">
                      <label 
                        v-for="commission in commissionsDisponibles" 
                        :key="commission"
                        class="commission-item"
                        :class="{ 'commission-selected': formData.commission === commission }"
                      >
                        <input
                          v-model="formData.commission"
                          type="radio"
                          :value="commission"
                          class="commission-input"
                          required
                        />
                        <div class="commission-card">
                          <div class="commission-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                          </div>
                          <span class="commission-name">{{ commission }}</span>
                        </div>
                      </label>
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
                        placeholder="07 XX XX XX XX (optionnel)"
                        maxlength="14"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 3z"/>
                      </svg>
                      Lieu de départ <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <select
                        v-model="formData.depart"
                        required
                        class="form-input"
                      >
                        <option value="Abidjan">ABIDJAN</option>
                        <option value="Interieur">L'INTERIEUR</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group" v-if="formData.depart == 'Interieur'">
                    <label class="form-label">
                      <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                      </svg>
                      Lieu Exact <span class="required">*</span>
                    </label>
                    <div class="input-container">
                      <input
                        v-model="formData.lieu"
                        @input="formatFirstUpperCase('prenom')"
                        type="text"
                        required
                        class="form-input"
                        placeholder="Votre prénom"
                      />
                    </div>
                  </div>

                </div>
              </div>
  
              <!-- Étape 3: Photo -->
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
            <h3>Inscription réussie !</h3>
            <p>Votre inscription à la commission a été enregistrée avec succès.</p>
            <p v-if="inscriptionData.matricule" class="matricule-info">
              <strong>Matricule :</strong> {{ inscriptionData.matricule }}
            </p>
            <p v-if="inscriptionData.commission" class="commission-info">
              <strong>Commission :</strong> {{ inscriptionData.commission }}
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
  import { jsPDF } from "jspdf"
  
  // Configuration API
  const API_BASE_URL = 'https://sogetrag.com/api/'
  
  // Configuration Cloudinary
  const cloudinaryConfig = {
    cloudName: 'dqk65objc',
    uploadPreset: 'sadeal',
    apiKey: '784574272958761',
    uploadUrl: 'https://api.cloudinary.com/v1_1/dqk65objc/image/upload'
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
  
  // États pour les résultats
  const showResultsTable = ref(false)
  const searchQuery = ref('')
  
  // Données des résultats
  const membresCommissions = ref([])
  const isLoadingResults = ref(false)
  const resultError = ref('')
  
  // Variables de pagination
  const currentPage = ref(1)
  const itemsPerPage = ref(10)
  const totalItems = ref(0)
  
  // Données d'inscription retournées
  const inscriptionData = ref({})
  
  // Liste des commissions disponibles
  const commissionsDisponibles = [
    'ADMINISTRATION',
    'CADRE DE VIE',
    'COMMUNICATION',
    'FINANCE',
    'FORMATION',
    'INFORMATIQUE',
    'PEPINIERE',
    'PROTOCOLE',
    'RESTAURATION',
    'SANTE',
    'SECURITE',
    'TRANSPORT',
    'MANAGER GENERAL',
    'MANAGER GENERAL ADJOINT',
    'PCO',
    'PCO-A'
  ]
  
  // Données du formulaire
  const formData = ref({
    nom: '',
    prenom: '',
    sexe: '',
    dateNaissance: '',
    age: null,
    niveauEtude: '',
    depart: '',
    lieu: '',
    contact: '',
    contactParent: '',
    commission: '', // Nouveau champ spécifique aux commissions
    photo: null,
    photoId: null
  })
  
  // Computed properties
  const totalSteps = computed(() => 3) // Au lieu de 4
  const steps = computed(() => [
    { title: 'Informations personnelles' },
    { title: 'Commission' },
    { title: 'Photo' }
  ])
  
  const showProgressSteps = computed(() => 
    currentStep.value > 0 && !showResultsTable.value
  )
  
  const showFormContent = computed(() => 
    currentStep.value > 0 && !showResultsTable.value
  )
  
  // Computed pour les résultats avec pagination
  const filteredResults = computed(() => {
    if (!searchQuery.value) {
      const startIndex = (currentPage.value - 1) * itemsPerPage.value
      const endIndex = startIndex + itemsPerPage.value
      return membresCommissions.value.slice(startIndex, endIndex)
    }
  
    const query = searchQuery.value.toLowerCase()
    const filtered = membresCommissions.value.filter(membre =>
      membre.nom.toLowerCase().includes(query) ||
      membre.prenom.toLowerCase().includes(query) ||
      membre.commission.toLowerCase().includes(query)
    )
  
    if (currentPage.value > 1) {
      currentPage.value = 1
    }
  
    const startIndex = (currentPage.value - 1) * itemsPerPage.value
    const endIndex = startIndex + itemsPerPage.value
    return filtered.slice(startIndex, endIndex)
  })
  
  const totalPages = computed(() => {
    const total = searchQuery.value ?
      membresCommissions.value.filter(membre =>
        membre.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        membre.prenom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        membre.commission.toLowerCase().includes(searchQuery.value.toLowerCase())
      ).length :
      membresCommissions.value.length
  
    return Math.ceil(total / itemsPerPage.value)
  })
  
  const paginationInfo = computed(() => {
    const total = searchQuery.value ?
      membresCommissions.value.filter(membre =>
        membre.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        membre.prenom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        membre.commission.toLowerCase().includes(searchQuery.value.toLowerCase())
      ).length :
      membresCommissions.value.length
    const startIndex = (currentPage.value - 1) * itemsPerPage.value + 1
    const endIndex = Math.min(currentPage.value * itemsPerPage.value, total)
  
    return {
      start: total > 0 ? startIndex : 0,
      end: endIndex,
      total: total
    }
  })
  
  // Fonction pour calculer l'âge
  const calculateAge = () => {
    if (!formData.value.dateNaissance) {
      formData.value.age = null
      return
    }
  
    const today = new Date()
    const birthDate = new Date(formData.value.dateNaissance)
    let age = today.getFullYear() - birthDate.getFullYear()
    const monthDiff = today.getMonth() - birthDate.getMonth()
  
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
      age--
    }
  
    formData.value.age = age
  }
  
  // Fonction pour récupérer les membres des commissions
  const fetchMembresCommissions = async () => {
    isLoadingResults.value = true
    resultError.value = ''
  
    try {
      const response = await axios.get(`${API_BASE_URL}/commission_api.php?action=membres`)
  
      if (response.data.success) {
        membresCommissions.value = response.data.data || []
        totalItems.value = membresCommissions.value.length
      } else {
        throw new Error(response.data.message || 'Erreur lors de la récupération des données')
      }
    } catch (error) {
      console.error('Erreur lors de la récupération des membres:', error)
      resultError.value = 'Erreur lors du chargement des membres. Veuillez réessayer.'
      membresCommissions.value = []
    } finally {
      isLoadingResults.value = false
    }
  }
  
  // Fonctions de pagination
  const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page
    }
  }
  
  const previousPage = () => {
    if (currentPage.value > 1) {
      currentPage.value--
    }
  }
  
  const nextPage = () => {
    if (currentPage.value < totalPages.value) {
      currentPage.value++
    }
  }
  
  // Fonction pour afficher les résultats
  const showResults = async () => {
    showResultsTable.value = true
    await fetchMembresCommissions()
  }
  
  // Watcher pour réinitialiser la pagination lors d'une recherche
  watch(searchQuery, () => {
    currentPage.value = 1
  })
  
  // Validation des étapes
  const canProceedToNextStep = computed(() => {
    switch (currentStep.value) {
      case 1:
        return formData.value.nom && formData.value.prenom &&
               formData.value.sexe && formData.value.dateNaissance &&
               formData.value.age && formData.value.niveauEtude &&
               formData.value.contact
      case 2:
        return formData.value.commission
      case 3:
        return formData.value.photo
      default:
        return false
    }
  })
  
  const canSubmit = computed(() => canProceedToNextStep.value)
  
  // Fonctions de navigation
  const startNewInscription = () => {
    showResultsTable.value = false
    currentStep.value = 1
  }
  
  const backToHome = () => {
    showResultsTable.value = false
    currentStep.value = 0
    resetForm()
  }
  
  // Upload d'image vers Cloudinary
  const uploadImageToCloudinary = async (image) => {
    try {
      uploadError.value = ''
      
      const fileName = `aeemci_commission_${Date.now()}_${image.name.replace(/\s+/g, '_')}`
      
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
  
  const formatFirstUpperCase = (field) => {
    const value = formData.value[field]
    if (value && typeof value === 'string') {
      formData.value[field] = value.charAt(0).toUpperCase() + value.slice(1)
    }
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
  
  // Fonction de soumission
  const soumettreInscription = async () => {
    if (!canSubmit.value) return
    isSubmitting.value = true
  
    try {
      const response = await axios.post(`${API_BASE_URL}/commissionInscription.php`, {
        data: formData.value
      })
  
      if (response.data.success) {
        inscriptionData.value = response.data.data || {}
        showConfirmation.value = true
      } else {
        throw new Error(response.data.message || 'Erreur inattendue')
      }
    } catch (error) {
      console.error('Erreur lors de l\'inscription:', error)
      alert('Une erreur est survenue lors de l\'inscription. Veuillez réessayer.')
    } finally {
      isSubmitting.value = false
    }
  }
  
  // Fonctions utilitaires
  const downloadFiche = () => {

    const doc = new jsPDF('p', 'mm', 'a4');

    // Ajouter le logo
    doc.addImage('https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg', 'JPEG', 10, 10, 40, 35);  // Remplacez le lien par celui de votre logo

    // Ajouter la bannière
    try {
        const bannerImg = new Image();
        bannerImg.crossOrigin = 'anonymous';
        
        bannerImg.onload = function () {
            // Ajouter la bannière à la position choisie (par exemple, à 50 mm de haut)
            doc.addImage(bannerImg, 'JPEG', 15, 50, 180, 30);  // Largeur: 180 mm, Hauteur: 30 mm
            generatePDFContent(doc);
        };

        bannerImg.onerror = function () {
            console.warn('Impossible de charger la bannière.');
            generatePDFContent(doc);  // Si l'image échoue à charger, on génère quand même le PDF sans la bannière.
        };

        bannerImg.src = 'https://res.cloudinary.com/dqk65objc/image/upload/v1751398353/aeemci_seminaire_1751398349649_ban.jpeg.jpg';  // Chemin vers votre bannière
    } catch (error) {
        console.warn('Erreur lors de l’ajout de la bannière:', error);
        generatePDFContent(doc);  // Si une erreur survient, on génère quand même le PDF sans la bannière
    }

    // Fonction pour générer le contenu du PDF
    const generatePDFContent = (doc) => {
    const margin = 20;
    let startY = 80;

    // Titre principal centré
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text("FICHE D'INSCRIPTION COMMISSION AEEMCI", 105, startY, { align: 'center' });

    // Sous-titre
    startY += 10;
    doc.setFontSize(12);
    doc.setFont('helvetica', 'italic');
    doc.text("Année " + new Date().getFullYear(), 105, startY, { align: 'center' });

    // Ligne de séparation
    startY += 10;
    doc.setLineWidth(0.5);
    doc.line(margin, startY, 210 - margin, startY);

    // Informations personnelles
    startY += 10;
    doc.setFont('helvetica', 'bold');
    doc.text("🧍 Informations Personnelles", margin, startY);

    doc.setFont('helvetica', 'normal');
    startY += 8;
    doc.text(`Nom:`, margin, startY);
    doc.text(`${formData.value.nom || 'Non renseigné'}`, margin + 40, startY);

    startY += 7;
    doc.text(`Prénom:`, margin, startY);
    doc.text(`${formData.value.prenom || 'Non renseigné'}`, margin + 40, startY);

    startY += 7;
    doc.text(`Sexe:`, margin, startY);
    doc.text(`${formData.value.sexe || 'Non renseigné'}`, margin + 40, startY);

    startY += 7;
    doc.text(`Date de naissance:`, margin, startY);
    doc.text(`${formData.value.dateNaissance || 'Non renseignée'}`, margin + 40, startY);

    startY += 7;
    doc.text(`Âge:`, margin, startY);
    doc.text(`${formData.value.age || 'Non renseigné'} ans`, margin + 40, startY);

    startY += 7;
    doc.text(`Niveau d'étude:`, margin, startY);
    doc.text(`${formData.value.niveauEtude || 'Non renseigné'}`, margin + 40, startY);

    startY += 7;
    doc.text(`Contact personnel:`, margin, startY);
    doc.text(`${formData.value.contact || 'Non renseigné'}`, margin + 40, startY);

    startY += 7;
    doc.text(`Contact parent:`, margin, startY);
    doc.text(`${formData.value.contactParent || 'Non renseigné'}`, margin + 40, startY);

    // Ligne de séparation
    startY += 10;
    doc.line(margin, startY, 210 - margin, startY);

    // Informations commission
    startY += 10;
    doc.setFont('helvetica', 'bold');
    doc.text("🏛 Informations de la Commission", margin, startY);

    doc.setFont('helvetica', 'normal');
    startY += 8;
    doc.text(`Commission:`, margin, startY);
    doc.text(`${formData.value.commission || 'Non renseignée'}`, margin + 40, startY);

    // Ligne de séparation
    startY += 10;
    doc.line(margin, startY, 210 - margin, startY);

    // Informations d’inscription
    startY += 10;
    doc.setFont('helvetica', 'bold');
    doc.text("📝 Informations d'Inscription", margin, startY);

    doc.setFont('helvetica', 'normal');
    startY += 8;
    doc.text(`Matricule:`, margin, startY);
    doc.text(`${inscriptionData.value.matricule || 'Non renseigné'}`, margin + 40, startY);

    startY += 7;
    doc.text(`Date d'inscription:`, margin, startY);
    doc.text(`${new Date().toLocaleDateString('fr-FR')}`, margin + 40, startY);

    // Optionnel : Ajouter un encadré pour signature ou photo
    startY += 20;
    doc.setDrawColor(0);
    doc.rect(150, 100, 40, 50); // Rect pour photo
    doc.setFontSize(10);
    doc.text("Photo", 170, 130, { align: 'center' });

    doc.setFontSize(12);
    doc.setFont('helvetica', 'italic');
    doc.text("Signature du membre", 160, startY + 40, { align: 'center' });
    doc.line(140, startY + 42, 180, startY + 42);

    // Génération
    const nom = formData.value.nom || 'fiche';
    const prenom = formData.value.prenom || 'membre';
    const filename = `fiche_commission_${nom}_${prenom}.pdf`;

    doc.save(filename);
};

};

  
  const resetForm = () => {
    // Réinitialiser le formulaire
    currentStep.value = 0
    showConfirmation.value = false
    showResultsTable.value = false
    searchQuery.value = ''
    inscriptionData.value = {}
  
    // Réinitialiser les données du formulaire
    Object.keys(formData.value).forEach(key => {
      if (typeof formData.value[key] === 'string') {
        formData.value[key] = ''
      } else if (typeof formData.value[key] === 'boolean') {
        formData.value[key] = false
      } else {
        formData.value[key] = null
      }
    })
  }
  
  // Lifecycle hooks
  onMounted(() => {
    // Initialisation si nécessaire
  })
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
  .choice-section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 500px;
    padding: 2rem;
  }
  
  .choice-container {
    background: #f0fdf4;
    border: 2px solid #16a34a;
    border-radius: 1rem;
    padding: 3rem;
    text-align: center;
    max-width: 800px;
    width: 100%;
  }
  
  .choice-container h3 {
    font-size: 2rem;
    font-weight: bold;
    color: #15803d;
    margin-bottom: 1rem;
  }
  
  .choice-subtitle {
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
  
  .choice-btn-secondary {
    border-color: #3b82f6;
    color: #1d4ed8;
  }
  
  .choice-btn-secondary:hover {
    background: #eff6ff;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.2);
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
  
  /* Commission Grid */
  .commission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
  }
  
  .commission-item {
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .commission-input {
    display: none;
  }
  
  .commission-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    background: #ffffff;
    transition: all 0.2s ease;
    text-align: center;
  }
  
  .commission-item:hover .commission-card {
    border-color: #16a34a;
    background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  }
  
  .commission-selected .commission-card {
    border-color: #16a34a;
    background: #16a34a;
    color: #ffffff;
  }
  
  .commission-icon {
    width: 3rem;
    height: 3rem;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #16a34a;
    transition: all 0.2s ease;
  }
  
  .commission-selected .commission-icon {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
  }
  
  .commission-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    transition: color 0.2s ease;
  }
  
  .commission-selected .commission-name {
    color: #ffffff;
  }
  
  /* Badge pour commission */
  .commission-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #dbeafe;
    color: #1e40af;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
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
  
  /* Styles pour le tableau des résultats */
  .results-section {
    padding: 2rem;
  }
  
  .results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .results-header p {
    font-size: 1.125rem;
    color: #4b5563;
    margin: 0;
  }
  
  .search-container {
    position: relative;
    max-width: 300px;
    width: 100%;
  }
  
  .pagination-info {
    margin-bottom: 1rem;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
  }
  
  .pagination-info p {
    font-size: 0.875rem;
    color: #4b5563;
    margin: 0;
  }
  
  .table-container {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
  }
  
  .results-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .results-table thead {
    background: #f8fafc;
  }
  
  .results-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
  
  .table-row {
    transition: background-color 0.2s ease;
  }
  
  .table-row:hover {
    background: #f9fafb;
  }
  
  .table-row:nth-child(even) {
    background: #f8fafc;
  }
  
  .table-row:nth-child(even):hover {
    background: #f3f4f6;
  }
  
  .table-cell {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
    font-size: 0.875rem;
  }
  
  .pagination-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .pagination-buttons {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .pagination-numbers {
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }
  
  .pagination-btn {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    background: #ffffff;
    color: #374151;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }
  
  .pagination-btn:hover:not(:disabled) {
    background: #f9fafb;
    border-color: #9ca3af;
  }
  
  .pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f3f4f6;
  }
  
  .pagination-btn-active {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
  }
  
  .pagination-btn-active:hover {
    background: #15803d;
    border-color: #15803d;
  }
  
  .pagination-btn-nav {
    padding: 0.5rem 1rem;
  }
  
  .pagination-ellipsis {
    padding: 0.5rem;
    color: #6b7280;
    font-weight: 500;
  }
  
  .items-per-page {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .items-per-page label {
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
  }
  
  .items-select {
    padding: 0.375rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background: #ffffff;
    color: #374151;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .items-select:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  
  .results-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e5e7eb;
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
  
  .matricule-info, .commission-info {
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
  
    .commission-grid {
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
  
    .choice-buttons {
      flex-direction: column;
    }
  
    .choice-btn {
      padding: 1.5rem;
    }
  
    .results-header {
      flex-direction: column;
      align-items: flex-start;
    }
  
    .search-container {
      max-width: 100%;
    }
  
    .pagination-controls {
      flex-direction: column;
      gap: 1rem;
    }
  
    .items-per-page {
      width: 100%;
      justify-content: space-between;
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
  
    .choice-container {
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
  
    .commission-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>
  