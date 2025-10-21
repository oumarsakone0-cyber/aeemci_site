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
              <p>Inscription au Séminaire National de Formation Islamique</p>
            </div>
          </div>
        </div>
        <div class="header-right" v-if="currentStep > 0 && !showResultsTable && !showParticipationChoice && !showProfileView">
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
        <div v-if="currentStep === 0 && !showResultsTable && !showParticipationChoice && !showProfileView" class="step-content">
          <h2 class="step-heading">
            <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            Bienvenue au SENAFOI 2025
          </h2>
  
          <div class="choice-section">
            <div class="choice-container">
              <h3>Que souhaitez-vous faire ?</h3>
              <p class="choice-subtitle">Choisissez l'option qui vous correspond</p>
  
              <div class="choice-buttons">
                <button
                  @click="showParticipationSelection"
                  type="button"
                  class="choice-btn choice-btn-primary"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                  </svg>
                  <div class="btn-content">
                    <span class="btn-title">M'inscrire au SENAFOI 2025</span>
                    <span class="btn-subtitle">Nouvelle inscription pour cette année</span>
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
                    <span class="btn-title">Consulter les résultats de l'an passé</span>
                    <span class="btn-subtitle">Voir les résultats du SENAFOI 2024</span>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Nouvelle étape : Choix de participation -->
        <div v-if="showParticipationChoice && !showLevelSelection && !showParticipantSelection && !showProfileView" class="step-content">
          <h2 class="step-heading">
            <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Votre Participation
          </h2>
  
          <div class="participation-section">
            <div class="participation-container">
              <h3>Avez-vous participé au SENAFOI 2024 ?</h3>
              <p class="participation-subtitle">Cette information nous aidera à mieux vous orienter</p>
  
              <div class="participation-buttons">
                <button
                  @click="selectParticipationType('ancien')"
                  type="button"
                  class="participation-btn participation-btn-primary"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                  </svg>
                  <div class="btn-content">
                    <span class="btn-title">Oui, j'ai participé au SENAFOI 2024</span>
                    <span class="btn-subtitle">Je vais sélectionner mon profil précédent</span>
                  </div>
                </button>
  
                <button
                  @click="selectParticipationType('nouveau')"
                  type="button"
                  class="participation-btn participation-btn-secondary"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  <div class="btn-content">
                    <span class="btn-title">Non, je suis nouveau séminariste</span>
                    <span class="btn-subtitle">Première participation au SENAFOI</span>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Étape : Sélection du niveau précédent -->
        <div v-if="showLevelSelection && !showProfileView" class="step-content">
          <h2 class="step-heading">
            <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 3l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 3z"/>
            </svg>
            Votre Niveau au SENAFOI 2024
          </h2>
  
          <div class="level-section">
            <div class="level-container">
              <h3>Quel était votre niveau au SENAFOI 2024 ?</h3>
              <p class="level-subtitle">Sélectionnez le niveau auquel vous avez participé l'année dernière</p>
  
              <div class="level-grid">
                <button
                  v-for="niveau in niveauxDisponibles"
                  :key="niveau"
                  @click="selectNiveauPrecedent(niveau)"
                  type="button"
                  class="level-btn"
                  :class="{ 'level-btn-selected': selectedNiveauPrecedent === niveau }"
                >
                  Niveau {{ niveau }}
                </button>
              </div>
  
              <div v-if="selectedNiveauPrecedent" class="level-actions">
                <button
                  @click="loadParticipantsByLevel"
                  type="button"
                  class="btn btn-primary"
                  :disabled="isLoadingParticipants"
                >
                  <div v-if="isLoadingParticipants" class="loading-spinner"></div>
                  <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                  {{ isLoadingParticipants ? 'Chargement...' : 'Continuer' }}
                </button>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Étape : Sélection du participant -->
        <div v-if="showParticipantSelection && !showProfileView" class="step-content">
          <h2 class="step-heading">
            <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Sélectionnez votre profil
          </h2>
  
          <div class="participant-section">
            <div class="participant-container">
              <h3>Participants du Niveau {{ selectedNiveauPrecedent }} - SENAFOI 2024</h3>
              <p class="participant-subtitle">Trouvez et sélectionnez votre nom dans la liste</p>
  
              <!-- Barre de recherche -->
              <div class="search-container">
                <input
                  v-model="participantSearchQuery"
                  type="text"
                  placeholder="Rechercher par nom ou prénom..."
                  class="search-input"
                />
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
              </div>
  
              <!-- Liste des participants -->
              <div v-if="isLoadingParticipants" class="loading-state">
                <div class="loading-spinner-large"></div>
                <p>Chargement des participants...</p>
              </div>
  
              <div v-else-if="participantError" class="error-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                </svg>
                <p>{{ participantError }}</p>
                <button @click="loadParticipantsByLevel" class="btn btn-primary">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                  </svg>
                  Réessayer
                </button>
              </div>
  
              <div v-else-if="filteredParticipants.length > 0" class="participants-list">
                <div
                  v-for="participant in filteredParticipants"
                  :key="participant.id"
                  @click="selectParticipant(participant)"
                  class="participant-item"
                  :class="{ 'participant-selected': selectedParticipant?.id === participant.id }"
                >
                  <div class="participant-info">
                    <div class="participant-name">
                      <strong>{{ participant.nom }} {{ participant.prenom }}</strong>
                    </div>
                    <div class="participant-details">
                      <span class="niveau-badge">{{ participant.niveau }}</span>
                      <span
                        class="decision-badge"
                        :class="{
                          'decision-admis': participant.decision === 'ADMIS',
                          'decision-ajourne': participant.decision === 'RECALE',
                          'decision-exclus': participant.decision === 'EXCLUS'
                        }"
                      >
                        {{ participant.decision }}
                      </span>
                    </div>
                  </div>
                  <div v-if="selectedParticipant?.id === participant.id" class="participant-check">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                  </div>
                </div>
              </div>
  
              <div v-else-if="participantsByLevel.length === 0" class="no-results">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <p>Aucun participant trouvé pour ce niveau</p>
              </div>
  
              <div v-else class="no-results">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <p>Aucun résultat trouvé pour "{{ participantSearchQuery }}"</p>
              </div>
  
              <!-- Actions -->
              <div v-if="selectedParticipant" class="participant-actions">
                <button
                  @click="confirmerSelectionParticipant"
                  type="button"
                  class="btn btn-primary"
                >
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  Confirmer et voir le profil
                </button>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Affichage du profil sélectionné -->
        <div v-if="showProfileView" class="step-content">
          <h2 class="step-heading">
            <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Votre Profil SENAFOI 2024
          </h2>
  
          <div class="profile-section">
            <div class="profile-container">
              <div class="profile-card">
                <div class="profile-header">
                  <h3>{{ selectedParticipant.nom }} {{ selectedParticipant.prenom }}</h3>
                </div>
  
                <div class="profile-details">
                  <div class="profile-item">
                    <span class="profile-label">Niveau 2024 :</span>
                    <span class="niveau-badge">{{ selectedParticipant.niveau }}</span>
                  </div>
  
                  <div class="profile-item">
                    <span class="profile-label">Décision :</span>
                    <span
                      class="decision-badge"
                      :class="{
                        'decision-admis': selectedParticipant.decision === 'ADMIS',
                        'decision-ajourne': selectedParticipant.decision === 'RECALE',
                        'decision-exclus': selectedParticipant.decision === 'EXCLUS'
                      }"
                    >
                      {{ selectedParticipant.decision }}
                    </span>
                  </div>
  
                  <div v-if="getNouveauNiveau(selectedParticipant)" class="profile-item nouveau-niveau-item">
                    <span class="profile-label">Niveau 2025 :</span>
                    <span class="nouveau-niveau-badge">{{ getNouveauNiveau(selectedParticipant) }}</span>
                  </div>
                </div>
  
                <div class="profile-actions">
                  <button
                    @click="retourSelectionParticipant"
                    type="button"
                    class="btn btn-secondary"
                  >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                    Changer de profil
                  </button>
  
                  <button
                    @click="confirmerEtContinuer"
                    type="button"
                    class="btn btn-primary"
                  >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Continuer l'inscription
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Navigation pour les étapes de sélection -->
        <div v-if="(showParticipationChoice || showLevelSelection || showParticipantSelection || showProfileView) && !showResultsTable" class="form-navigation">
          <button
            @click="retourEtapePrecedente"
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
  
        <!-- Tableau des résultats de l'an passé -->
        <div v-if="showResultsTable" class="step-content">
          <h2 class="step-heading">
            <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            Résultats SENAFOI 2024
          </h2>
  
          <div class="results-section">
            <div class="results-header">
              <p>Liste des séminaristes ayant participé au SENAFOI 2024</p>
              <div class="search-container">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Rechercher par nom ou prénom..."
                  class="search-input"
                  :disabled="isLoadingResults"
                />
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
              </div>
            </div>
  
            <!-- Informations de pagination -->
            <div v-if="!isLoadingResults && !resultError && resultatsAnneePassee.length > 0" class="pagination-info">
              <p>
                Affichage de {{ paginationInfo.start }} à {{ paginationInfo.end }}
                sur {{ paginationInfo.total }} résultat{{ paginationInfo.total > 1 ? 's' : '' }}
                <span v-if="searchQuery"> pour "{{ searchQuery }}"</span>
              </p>
            </div>
  
            <!-- État de chargement -->
            <div v-if="isLoadingResults" class="loading-state">
              <div class="loading-spinner-large"></div>
              <p>Chargement des résultats...</p>
            </div>
  
            <!-- Erreur -->
            <div v-if="resultError" class="error-state">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
              </svg>
              <p>{{ resultError }}</p>
              <button @click="fetchResultatsSenafoi24" class="btn btn-primary">
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
                    <th>Niveau</th>
                    <th>Décision</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="participant in filteredResults" :key="participant.id" >
                    <td class="table-cell">{{ participant.nom }}</td>
                    <td class="table-cell">{{ participant.prenom }}</td>
                    <td class="table-cell">
                      <span class="niveau-badge">{{ participant.niveau }}</span>
                    </td>
                    <td class="table-cell">
                      <span
                        class="decision-badge"
                        :class="{
                          'decision-admis': participant.decision === 'ADMIS',
                          'decision-ajourne': participant.decision === 'RECALE',
                          'decision-exclus': participant.decision === 'EXCLUS'
                        }"
                      >
                        {{ participant.decision }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
  
              <div v-if="!isLoadingResults && !resultError && filteredResults.length === 0 && resultatsAnneePassee.length > 0" class="no-results">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <p>Aucun résultat trouvé pour "{{ searchQuery }}"</p>
              </div>
  
              <div v-if="!isLoadingResults && !resultError && resultatsAnneePassee.length === 0" class="no-results">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                <p>Aucun résultat disponible</p>
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
                    v-for="page in Math.min(2, totalPages)"
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
                @click="showParticipationSelection"
                type="button"
                class="btn btn-primary"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                S'inscrire au SENAFOI 2025
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
                      :readonly="isAncienParticipant"
                      :class="{ 'input-readonly': isAncienParticipant }"
                    />
                  </div>
                  <small v-if="isAncienParticipant" class="form-help readonly-help">
                    Information récupérée de votre profil SENAFOI 2024
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
                      @input="formatFirstUpperCase('prenom')"
                      type="text"
                      required
                      class="form-input"
                      placeholder="Votre prénom"
                      :readonly="isAncienParticipant"
                      :class="{ 'input-readonly': isAncienParticipant }"
                    />
                  </div>
                  <small v-if="isAncienParticipant" class="form-help readonly-help">
                    Information récupérée de votre profil SENAFOI 2024
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
                    Contact Parent/Tuteur <span class="required">*</span>
                  </label>
                  <div class="input-container">
                    <input
                      v-model="formData.contactParent"
                      @input="formatPhoneNumberParent"
                      type="tel"
                      required
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
  
            <!-- Étape Paiement -->
            <div v-if="currentStep === 4" class="step-content">
              <h2 class="step-heading">
                <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                </svg>
                Paiement
              </h2>
  
              <!-- Section Transport -->
              <div class="transport-section">
                <h3 class="transport-heading">
                  <svg class="heading-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                  </svg>
                  Choisir un mode de Transport <span style="color: red">*</span>
                </h3>
  
                <div class="transport-info">
                  <p class="transport-description">
                    Choisissez votre mode de transport pour vous rendre au séminaire :
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
                        <span class="transport-title">Convoi AEEMCI</span>
                        <span class="transport-subtitle">Pour ceux venant d'Abidjan</span>
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
                        <span class="transport-title">Transport personnel</span>
                        <span class="transport-subtitle">Pour ceux venant de l'intérieur du pays à leur charge</span>
                      </div>
                    </label>
                  </div>
  
                  <div v-if="formData.transport === 'convoi'" class="transport-note">
                    <div class="note-box">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                      </svg>
                      <p><strong>Important :</strong> Le convoi AEEMCI part uniquement d'Abidjan.</p>
                    </div>
                  </div>
                </div>
              </div>
  
              <div class="payment-info">
                <div class="price-box">
                  <svg class="price-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                  </svg>
                  <span class="price-label">Frais d'inscription :</span>
                  <span class="price-amount">{{ getTotalPrice() }} FCFA</span>
                </div>
  
                <div v-if="formData.transport === 'personnel'" class="price-breakdown">
                  <div class="breakdown-item">
                    <span>Transport à la charge du séminariste</span>
                    <span>20 000 FCFA</span>
                  </div>
                  <div v-if="formData.transport === 'convoi'" class="breakdown-item">
                    <span>Convoi Aeemci</span>
                    <span>35 000 FCFA</span>
                  </div>
                  <div class="breakdown-total">
                    <span><strong>Total</strong></span>
                    <span><strong>{{ getTotalPrice() }} FCFA</strong></span>
                  </div>
                </div>
              </div>
  
              <div class="payment-method">
                <div class="wave-header">
                  <div class="wave-icon"><img src="https://play-lh.googleusercontent.com/-Mp3XW7uhwn3KGQxUKGPoc4MbA5ti-3-q23TgoVi9ujBgHWW5n4IySvlG5Exwrxsjw=w256" width="200"></div>
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
          <p>Votre inscription au séminaire a été enregistrée avec succès.</p>
          <p v-if="inscriptionData.matricule" class="matricule-info">
            <strong>Matricule :</strong> {{ inscriptionData.matricule }}
          </p>
          <p v-if="inscriptionData.dortoir" class="dortoir-info">
            <strong>Dortoir :</strong> {{ inscriptionData.dortoir }}
          </p>
          <p v-if="inscriptionData.car" class="car-info">
            <strong>Transport :</strong> {{ inscriptionData.car }}
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
  
  // États pour les résultats
  const showResultsTable = ref(false)
  const searchQuery = ref('')
  
  // États pour la sélection de participation
  const showParticipationChoice = ref(false)
  const showLevelSelection = ref(false)
  const showParticipantSelection = ref(false)
  const showProfileView = ref(false)
  const participationType = ref('')
  const selectedNiveauPrecedent = ref('')
  const selectedParticipant = ref(null)
  const participantsByLevel = ref([])
  const isLoadingParticipants = ref(false)
  const participantError = ref('')
  const participantSearchQuery = ref('')
  const isAncienParticipant = ref(false)
  
  // Données des résultats
  const resultatsAnneePassee = ref([])
  const isLoadingResults = ref(false)
  const resultError = ref('')
  
  // Variables de pagination
  const currentPage = ref(1)
  const itemsPerPage = ref(10)
  const totalItems = ref(0)
  
  // Données d'inscription retournées
  const inscriptionData = ref({})
  
  // Niveaux disponibles simplifiés (1, 2, 3, 4)
  const niveauxDisponibles = ['1', '2', '3', '4']
  
  // Date maximum pour la date de naissance (18 ans minimum)
  const maxDate = computed(() => {
  const today = new Date()
  const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate())
  return maxDate.toISOString().split('T')[0]
  })
  
  // Données du formulaire
  const formData = ref({
  nom: '',
  prenom: '',
  sexe: '',
  dateNaissance: '',
  age: null,
  niveauEtude: '',
  ville: '', // Remplace secretariatRegional
  contact: '',
  contactParent: '',
  tailleTshirt: '',
  aUneMaladie: '', // 'oui' or 'non'
  maladieDetail: '',
  photo: null,
  photoId: null,
  numeroWave: '',
  niveauSeminaire: 'TEST_ENTREE',
  transport: 'convoi',
  // Champs pour les anciens participants
  a_participe_senafoi: false,
  niveau_annee_passee: '',
  decision_participant: ''
  })
  
  // Computed properties
  const totalSteps = computed(() => 4)
  const steps = computed(() => [
  { title: 'Informations personnelles' },
  { title: 'Informations organisationnelles' },
  { title: 'Photo' },
  { title: 'Paiement' }
  ])
  
  const showProgressSteps = computed(() =>
  currentStep.value > 0 &&
  !showResultsTable.value &&
  !showParticipationChoice.value &&
  !showLevelSelection.value &&
  !showParticipantSelection.value &&
  !showProfileView.value
  )
  
  const showFormContent = computed(() =>
  currentStep.value > 0 &&
  !showResultsTable.value &&
  !showParticipationChoice.value &&
  !showLevelSelection.value &&
  !showParticipantSelection.value &&
  !showProfileView.value
  )
  
  // Computed pour filtrer les participants par niveau
  const filteredParticipants = computed(() => {
  if (!participantSearchQuery.value) {
    return participantsByLevel.value
  }
  
  const query = participantSearchQuery.value.toLowerCase().trim()
  return participantsByLevel.value.filter(participant => {
    const nom = participant.nom.toLowerCase()
    const prenom = participant.prenom.toLowerCase()
    const nomComplet = `${nom} ${prenom}`
    const prenomNom = `${prenom} ${nom}`
  
    return nom.includes(query) ||
           prenom.includes(query) ||
           nomComplet.includes(query) ||
           prenomNom.includes(query)
  })
  })
  
  // Computed pour les résultats avec pagination
  const filteredResults = computed(() => {
  if (!searchQuery.value) {
    const startIndex = (currentPage.value - 1) * itemsPerPage.value
    const endIndex = startIndex + itemsPerPage.value
    return resultatsAnneePassee.value.slice(startIndex, endIndex)
  }
  
  const query = searchQuery.value.toLowerCase()
  const filtered = resultatsAnneePassee.value.filter(participant =>
    participant.nom.toLowerCase().includes(query) ||
    participant.prenom.toLowerCase().includes(query)
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
    resultatsAnneePassee.value.filter(participant =>
      participant.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      participant.prenom.toLowerCase().includes(searchQuery.value.toLowerCase())
    ).length :
    resultatsAnneePassee.value.length
  
  return Math.ceil(total / itemsPerPage.value)
  })
  
  const paginationInfo = computed(() => {
  const total = searchQuery.value ?
    resultatsAnneePassee.value.filter(participant =>
      participant.nom.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      participant.prenom.toLowerCase().includes(searchQuery.value.toLowerCase())
    ).length :
    resultatsAnneePassee.value.length
  const startIndex = (currentPage.value - 1) * itemsPerPage.value + 1
  const endIndex = Math.min(currentPage.value * itemsPerPage.value, total)
  
  return {
    start: total > 0 ? startIndex : 0,
    end: endIndex,
    total: total
  }
  })
  
  // Fonction pour déterminer le nouveau niveau avec incrémentation automatique
  const getNouveauNiveau = (participant) => {
  if (!participant) return ''
  
  const niveau = participant.niveau
  const decision = participant.decision
  
  // Si ADMIS, passe au niveau suivant
  if (decision === 'ADMIS') {
    // Extraire le numéro du niveau après "NIVEAU " (1, 2, 3, 4)
    const match = niveau.match(/NIVEAU (\d)(.*)/)
    if (!match) return niveau
    const niveauNum = match[1]
    const suffixe = match[2] // AS, AF, BS, BF, etc.
  
    switch (niveauNum) {
      case '1':
        return '2' + suffixe
      case '2':
        return '3' + suffixe
      case '3':
        return '4' + suffixe
      case '4':
        return '5' + suffixe
      case '5':
        return 'DIPLOME'
      default:
        return niveau
    }
  } else {
    // Si RECALE ou EXCLUS, reste au même niveau
    return niveau
  }
  }
  
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
  
  // Fonction pour récupérer les données SENAFOI 2024
  const fetchResultatsSenafoi24 = async () => {
  isLoadingResults.value = true
  resultError.value = ''
  
  try {
    const response = await axios.get(`${API_BASE_URL}/seminaire_api.php?action=senafoi24`)
  
    if (response.data.success) {
      resultatsAnneePassee.value = response.data.data || []
      totalItems.value = resultatsAnneePassee.value.length
    } else {
      throw new Error(response.data.message || 'Erreur lors de la récupération des données')
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des résultats:', error)
    resultError.value = 'Erreur lors du chargement des résultats. Veuillez réessayer.'
    resultatsAnneePassee.value = []
  } finally {
    isLoadingResults.value = false
  }
  }
  
  // Fonction pour charger les participants par niveau (filtré avec correction)
  const fetchParticipantsByLevel = async (niveau) => {
  isLoadingParticipants.value = true
  participantError.value = ''
  
  try {
    // Récupérer tous les participants du SENAFOI 2024
    const response = await axios.get(`${API_BASE_URL}/seminaire_api.php?action=senafoi24`)
  
    if (response.data.success) {
      const allParticipants = response.data.data || []
      console.log('Tous les participants:', allParticipants)
      console.log('Niveau sélectionné:', niveau)
  
      // Filtrer par niveau (tous les niveaux qui commencent par le numéro choisi)
      participantsByLevel.value = allParticipants.filter(participant => {
        const niveauParticipant = participant.niveau
        console.log('Niveau participant:', niveauParticipant)
  
        // Extraire le chiffre du niveau après "NIVEAU "
        const match = niveauParticipant.match(/NIVEAU (\d)/)
        if (match) {
          const niveauChiffre = match[1]
          console.log(`Niveau extrait: ${niveauChiffre}, comparé avec: ${niveau}`)
          return niveauChiffre === niveau.toString()
        }
        return false
      })
  
      console.log('Participants filtrés:', participantsByLevel.value)
      console.log('Nombre de participants trouvés:', participantsByLevel.value.length)
  
      if (participantsByLevel.value.length === 0) {
        console.warn(`Aucun participant trouvé pour le niveau ${niveau}`)
        // Afficher tous les niveaux disponibles pour debug
        const niveauxDisponibles = [...new Set(allParticipants.map(p => p.niveau))]
        console.log('Niveaux disponibles dans les données:', niveauxDisponibles)
      }
    } else {
      throw new Error(response.data.message || 'Erreur lors de la récupération des participants')
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des participants:', error)
    participantError.value = 'Erreur lors du chargement des participants. Veuillez réessayer.'
    participantsByLevel.value = []
  } finally {
    isLoadingParticipants.value = false
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
  await fetchResultatsSenafoi24()
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
      return formData.value.ville &&
             formData.value.contactParent &&
             formData.value.tailleTshirt &&
             formData.value.aUneMaladie &&
             (formData.value.aUneMaladie === 'non' || formData.value.maladieDetail)
    case 3:
      return formData.value.photo
    case 4:
      return formData.value.transport && formData.value.numeroWave
    default:
      return false
  }
  })
  
  const canSubmit = computed(() => canProceedToNextStep.value)
  
  // Fonction pour calculer le prix total
  const getTotalPrice = () => {
  const basePrice = 20000
  const transportPrice = formData.value.transport === 'convoi' ? 15000 : 0
  return basePrice + transportPrice
  }
  
  // Fonctions de navigation
  const showParticipationSelection = () => {
  showResultsTable.value = false
  showParticipationChoice.value = true
  showLevelSelection.value = false
  showParticipantSelection.value = false
  showProfileView.value = false
  currentStep.value = 0
  }
  
  const selectParticipationType = (type) => {
  participationType.value = type
  
  if (type === 'nouveau') {
    // Nouveau séminariste - aller directement au formulaire
    isAncienParticipant.value = false
    formData.value.a_participe_senafoi = false
    formData.value.niveauSeminaire = 'TEST_ENTREE'
    startNewInscription()
  } else {
    // Ancien participant - aller à la sélection du niveau
    isAncienParticipant.value = true
    formData.value.a_participe_senafoi = true
    showParticipationChoice.value = false
    showLevelSelection.value = true
    currentStep.value = -1
  }
  }
  
  const selectNiveauPrecedent = (niveau) => {
  selectedNiveauPrecedent.value = niveau
  }
  
  const loadParticipantsByLevel = async () => {
  if (!selectedNiveauPrecedent.value) {
    console.error('Aucun niveau sélectionné')
    return
  }
  
  console.log('Chargement des participants pour le niveau:', selectedNiveauPrecedent.value)
  await fetchParticipantsByLevel(selectedNiveauPrecedent.value)
  
  // Vérifier si des participants ont été trouvés avant de changer de vue
  if (participantsByLevel.value.length > 0) {
    showLevelSelection.value = false
    showParticipantSelection.value = true
  } else {
    // Rester sur la sélection de niveau et afficher un message
    participantError.value = `Aucun participant trouvé pour le niveau ${selectedNiveauPrecedent.value}. Veuillez choisir un autre niveau.`
  }
  }
  
  const selectParticipant = (participant) => {
  selectedParticipant.value = participant
  }
  
  const confirmerSelectionParticipant = () => {
  if (!selectedParticipant.value) return
  
  showParticipantSelection.value = false
  showProfileView.value = true
  }
  
  const retourSelectionParticipant = () => {
  showProfileView.value = false
  showParticipantSelection.value = true
  }
  
  const confirmerEtContinuer = () => {
  if (!selectedParticipant.value) return
  
  // Pré-remplir les données du formulaire
  formData.value.nom = selectedParticipant.value.nom
  formData.value.prenom = selectedParticipant.value.prenom
  formData.value.niveau_annee_passee = selectedParticipant.value.niveau
  formData.value.participant_trouve = true
  formData.value.decision_participant = selectedParticipant.value.decision
  
  // Déterminer le nouveau niveau
  const nouveauNiveau = getNouveauNiveau(selectedParticipant.value)
  formData.value.niveauSeminaire = nouveauNiveau
  
  // Aller au formulaire
  startNewInscription()
  }
  
  const retourEtapePrecedente = () => {
  if (showProfileView.value) {
    showProfileView.value = false
    showParticipantSelection.value = true
  } else if (showParticipantSelection.value) {
    showParticipantSelection.value = false
    showLevelSelection.value = true
    selectedParticipant.value = null
    participantSearchQuery.value = ''
  } else if (showLevelSelection.value) {
    showLevelSelection.value = false
    showParticipationChoice.value = true
    selectedNiveauPrecedent.value = ''
    participantsByLevel.value = []
  } else if (showParticipationChoice.value) {
    showParticipationChoice.value = false
    participationType.value = ''
    currentStep.value = 0
  }
  }
  
  const startNewInscription = () => {
  showResultsTable.value = false
  showParticipationChoice.value = false
  showLevelSelection.value = false
  showParticipantSelection.value = false
  showProfileView.value = false
  currentStep.value = 1
  }
  
  const backToHome = () => {
  showResultsTable.value = false
  showParticipationChoice.value = false
  showLevelSelection.value = false
  showParticipantSelection.value = false
  showProfileView.value = false
  currentStep.value = 0
  resetForm()
  }
  
  // Upload d'image vers Cloudinary
  const uploadImageToCloudinary = async (image) => {
  try {
    uploadError.value = ''
  
    const fileName = `aeemci_seminaire_${Date.now()}_${image.name.replace(/\s+/g, '_')}`
  
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
  
  // Fonction de soumission
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
  
    const response = await axios.post(`${API_BASE_URL}/seminaireSeminariste.php`, dataToSend)
  
    if (response.data.success && response.data.wave_launch_url) {
      // Redirection automatique vers l'URL de paiement Wave
      window.location.href = response.data.wave_launch_url
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
  const ficheContent = `FICHE D'INSCRIPTION SÉMINAIRE AEEMCI
  ===================================
  
  Informations Personnelles:
  - Nom: ${formData.value.nom}
  - Prénom: ${formData.value.prenom}
  - Sexe: ${formData.value.sexe}
  - Date de naissance: ${formData.value.dateNaissance}
  - Âge: ${formData.value.age} ans
  - Niveau d'étude: ${formData.value.niveauEtude}
  - Contact: ${formData.value.contact}
  
  Informations Organisationnelles:
  - Ville: ${formData.value.ville}
  - Contact Parent: ${formData.value.contactParent}
  - Taille T-shirt: ${formData.value.tailleTshirt}
  - A une maladie: ${formData.value.aUneMaladie === 'oui' ? 'Oui' : 'Non'}
  ${formData.value.aUneMaladie === 'oui' ? `- Détail maladie: ${formData.value.maladieDetail}` : ''}
  
  Informations Séminaire:
  - Matricule: ${inscriptionData.value.matricule || 'N/A'}
  - Niveau Séminaire: ${formData.value.niveauSeminaire}
  - Dortoir: ${inscriptionData.value.dortoir || 'N/A'}
  - Transport: ${formData.value.transport === 'convoi' ? 'Convoi AEEMCI' : 'Transport personnel'}
  - Type d'inscription: ${isAncienParticipant.value ? 'Ancien participant SENAFOI 2024' : 'Nouveau participant'}
  ${isAncienParticipant.value ? `- Niveau précédent: ${formData.value.niveau_annee_passee}
  - Décision précédente: ${formData.value.decision_participant}` : ''}
  
  Paiement:
  - Montant total: ${getTotalPrice()} FCFA
  - Inscription de base: 20 000 FCFA
  ${formData.value.transport === 'convoi' ? '- Transport aller-retour: 15 000 FCFA' : ''}
  
  Date d'inscription: ${new Date().toLocaleDateString('fr-FR')}`
  
  const blob = new Blob([ficheContent], { type: 'text/plain' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `fiche_seminaire_${formData.value.nom}_${formData.value.prenom}.txt`
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
  }
  
  const resetForm = () => {
  // Réinitialiser le formulaire
  currentStep.value = 0
  showConfirmation.value = false
  showResultsTable.value = false
  showParticipationChoice.value = false
  showLevelSelection.value = false
  showParticipantSelection.value = false
  showProfileView.value = false
  searchQuery.value = ''
  inscriptionData.value = {}
  participationType.value = ''
  selectedNiveauPrecedent.value = ''
  selectedParticipant.value = null
  participantsByLevel.value = []
  participantSearchQuery.value = ''
  isAncienParticipant.value = false
  
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
  
  // Réassigner les valeurs par défaut
  formData.value.niveauSeminaire = 'TEST_ENTREE'
  formData.value.transport = 'convoi'
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
  .choice-section, .participation-section, .level-section {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 500px;
  padding: 2rem;
  }
  
  .choice-container, .participation-container, .level-container {
  background: #f0fdf4;
  border: 2px solid #16a34a;
  border-radius: 1rem;
  padding: 3rem;
  text-align: center;
  max-width: 800px;
  width: 100%;
  }
  
  .choice-container h3, .participation-container h3, .level-container h3 {
  font-size: 2rem;
  font-weight: bold;
  color: #15803d;
  margin-bottom: 1rem;
  }
  
  .choice-subtitle, .participation-subtitle, .level-subtitle {
  font-size: 1.125rem;
  color: #4b5563;
  margin-bottom: 3rem;
  }
  
  .choice-buttons, .participation-buttons {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  max-width: 600px;
  margin: 0 auto;
  }
  
  .choice-btn, .participation-btn {
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
  
  .choice-btn-primary, .participation-btn-primary {
  border-color: #16a34a;
  color: #15803d;
  }
  
  .choice-btn-primary:hover, .participation-btn-primary:hover {
  background: #f0fdf4;
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(22, 163, 74, 0.2);
  }
  
  .choice-btn-secondary, .participation-btn-secondary {
  border-color: #3b82f6;
  color: #1d4ed8;
  }
  
  .choice-btn-secondary:hover, .participation-btn-secondary:hover {
  background: #eff6ff;
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.2);
  }
  
  .choice-btn svg, .participation-btn svg {
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
  
  /* Styles pour la sélection de niveau */
  .level-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
  }
  
  .level-btn {
  padding: 1rem;
  border: 2px solid #d1d5db;
  border-radius: 0.75rem;
  background: #ffffff;
  color: #374151;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  }
  
  .level-btn:hover {
  border-color: #0284c7;
  background: #f0f9ff;
  color: #0c4a6e;
  }
  
  .level-btn-selected {
  border-color: #0284c7;
  background: #0284c7;
  color: #ffffff;
  }
  
  .level-actions {
  margin-top: 2rem;
  }
  
  /* Styles pour la sélection de participant */
  .participant-section {
  padding: 2rem;
  }
  
  .participant-container {
  max-width: 800px;
  margin: 0 auto;
  }
  
  .participant-container h3 {
  font-size: 1.5rem;
  font-weight: bold;
  color: #1f2937;
  margin-bottom: 0.5rem;
  text-align: center;
  }
  
  .participant-subtitle {
  font-size: 1rem;
  color: #4b5563;
  margin-bottom: 2rem;
  text-align: center;
  }
  
  .participants-list {
  max-height: 400px;
  overflow-y: auto;
  border: 1px solid #e5e7eb;
  border-radius: 0.75rem;
  margin-bottom: 2rem;
  }
  
  .participant-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #f3f4f6;
  cursor: pointer;
  transition: all 0.2s ease;
  }
  
  .participant-item:hover {
  background: #f9fafb;
  }
  
  .participant-item:last-child {
  border-bottom: none;
  }
  
  .participant-selected {
  background: #f0fdf4;
  border-color: #16a34a;
  }
  
  .participant-info {
  flex: 1;
  }
  
  .participant-name {
  font-size: 1rem;
  margin-bottom: 0.5rem;
  }
  
  .participant-details {
  display: flex;
  gap: 0.5rem;
  align-items: center;
  }
  
  .participant-check {
  color: #16a34a;
  }
  
  .participant-actions {
  margin-top: 2rem;
  padding: 1.5rem;
  background: #f8fafc;
  border-radius: 0.75rem;
  border: 1px solid #e5e7eb;
  }
  
  /* Styles pour l'affichage du profil */
  .profile-section {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
  padding: 2rem;
  }
  
  .profile-container {
  max-width: 600px;
  width: 100%;
  }
  
  .profile-card {
  background: #ffffff;
  border: 2px solid #e5e7eb;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
  }
  
  .profile-header {
  text-align: center;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #f3f4f6;
  }
  
  .profile-header h3 {
  font-size: 1.5rem;
  font-weight: bold;
  color: #1f2937;
  }
  
  .profile-details {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
  }
  
  .profile-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 0.5rem;
  }
  
  .profile-label {
  font-weight: 500;
  color: #374151;
  }
  
  .nouveau-niveau-item {
  background: #f0fdf4;
  border: 1px solid #16a34a;
  }
  
  .nouveau-niveau-badge {
  background: #16a34a;
  color: #ffffff;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 600;
  }
  
  .profile-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  }
  
  /* Badges */
  .niveau-badge {
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
  
  .decision-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  }
  
  .decision-admis {
  background: #dcfce7;
  color: #166534;
  }
  
  .decision-ajourne {
  background: #fef3c7;
  color: #92400e;
  }
  
  .decision-exclus {
  background: #fecaca;
  color: #991b1b;
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
  
  .transport-note {
  margin-top: 1rem;
  }
  
  .note-box {
  background: #fef3c7;
  border: 1px solid #f59e0b;
  border-radius: 0.5rem;
  padding: 1rem;
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
  }
  
  .note-box svg {
  color: #f59e0b;
  margin-top: 0.125rem;
  flex-shrink: 0;
  }
  
  .note-box p {
  color: #92400e;
  font-size: 0.875rem;
  margin: 0;
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
  
  .price-breakdown {
  padding: 1rem;
  background: #ffffff;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
  }
  
  .breakdown-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f3f4f6;
  font-size: 0.875rem;
  color: #4b5563;
  }
  
  .breakdown-item:last-of-type {
  border-bottom: none;
  }
  
  .breakdown-total {
  display: flex;
  justify-content: space-between;
  padding-top: 0.75rem;
  margin-top: 0.5rem;
  border-top: 2px solid #e5e7eb;
  font-size: 1rem;
  color: #1f2937;
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
  
  .matricule-info, .dortoir-info, .car-info {
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
  
  .choice-buttons, .participation-buttons {
    flex-direction: column;
  }
  
  .choice-btn, .participation-btn {
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
  
  .choice-container, .participation-container, .level-container {
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
  </style>
  