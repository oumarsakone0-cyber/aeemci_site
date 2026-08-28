<template>
  <div v-if="!SEMINARISTE_INSCRIPTION_VISIBLE" class="blank-registration-page"></div>
  <div v-else class="app-container">
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
        <div class="header-right" v-if="currentStep > 0 && !showResultsTable && !showParticipationChoice && !showLevelSelection && !showParticipantSelection && !showProfileView">
          <div class="year-badge">SENAFOI 26</div>
          <div class="step-progress">
            <p class="step-info">Étape {{ currentStep }} / {{ totalSteps }}</p>
            <div class="progress-bar">
              <div
                class="progress-fill"
                :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
              ></div>
            </div>
          </div>
        </div>
        <div class="header-right" v-else>
          <div class="year-badge">SENAFOI 26</div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <div class="form-wrapper">
        <!-- Étape de choix initial -->
        <div v-if="currentStep === 0 && !showResultsTable && !showParticipationChoice && !showProfileView" class="step-content">
          <div class="welcome-card">
            <div class="senafoi-banner-hero">
              <img
                :src="senafoiBanner"
                alt="SENAFOI Korhogo 2026 - 37e edition"
                class="senafoi-banner-img"
              />
            </div>

            <!-- Places restantes et compte à rebours de clôture, sur une seule ligne -->
            <div class="quota-panel" :class="{ 'quota-panel-full': quotaInfo.is_full || inscriptionsFermees }">
              <div class="quota-copy">
                <span class="quota-label">{{ quotaStatusText }}</span>
                
                <small v-if="inscriptionsFermees">Inscriptions terminées pour le SENAFOI 2026</small>
                <small v-else>Clôture le {{ dateClotureLisible }}</small>
              </div>

              <div class="quota-available cloture-chrono" :class="{ 'cloture-chrono--fini': inscriptionsFermees }">
                <span v-if="inscriptionsFermees">Terminé</span>
                <span v-else class="chrono">{{ compteRebours.heures }}:{{ compteRebours.minutes }}:{{ compteRebours.secondes }}</span>
                <small>{{ inscriptionsFermees ? 'inscriptions closes' : 'avant clôture' }}</small>
              </div>
            </div>

            <div class="choice-section">
              <h3>Que souhaitez-vous faire ?</h3>
              <p class="choice-subtitle">Choisissez l'option qui vous correspond</p>

              <div class="choice-buttons">
                <button
                  v-if="canOpenNewInscription"
                  @click="showParticipationSelection"
                  type="button"
                  class="choice-btn choice-btn-primary"
                >
                  <div class="choice-icon choice-icon-primary">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                  </div>
                  <div class="choice-content">
                    <span class="choice-title">M'inscrire au SENAFOI 2026</span>
                    <span class="choice-desc">Nouvelle inscription pour cette année</span>
                  </div>
                  <svg class="choice-arrow" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                </button>

                <div v-else class="quota-closed">
                  <template v-if="inscriptionsFermees">
                    <strong>Les inscriptions sont terminées.</strong>
                    <span>La clôture du SENAFOI 2026 est passée. Les personnes qui avaient déjà commencé peuvent terminer leur inscription.</span>
                  </template>
                  <template v-else>
                    <strong>Les inscriptions sont completes.</strong>
                    <span>Le quota 2026 est atteint. Les personnes qui ont deja commence peuvent terminer leur inscription.</span>
                  </template>
                </div>

                <button
                  @click="showResults"
                  type="button"
                  class="choice-btn choice-btn-secondary"
                >
                  <div class="choice-icon choice-icon-secondary">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                  </div>
                  <div class="choice-content">
                    <span class="choice-title">Consulter les résultats 2025</span>
                    <span class="choice-desc">Voir les résultats du SENAFOI 2025</span>
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
        </div>

        <!-- Étape : Choix de participation -->
        <div v-if="showParticipationChoice && !showLevelSelection && !showParticipantSelection && !showProfileView" class="step-content">
          <div class="step-card">
            <div class="step-hero">
              <div class="step-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div class="step-hero-text">
                <h2>Votre Participation</h2>
                <p>Avez-vous participé au SENAFOI 2025 ?</p>
              </div>
            </div>

            <div class="participation-section">
              <p class="section-subtitle">Cette information nous aidera à mieux vous orienter</p>

              <div class="participation-buttons">
                <button
                  @click="selectParticipationType('ancien')"
                  type="button"
                  class="participation-btn"
                >
                  <div class="part-icon part-icon-yes">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                    </svg>
                  </div>
                  <div class="part-content">
                    <span class="part-title">Oui, j'ai participé</span>
                    <span class="part-desc">Je vais sélectionner mon profil 2025</span>
                  </div>
                </button>

                <button
                  @click="selectParticipationType('nouveau')"
                  type="button"
                  class="participation-btn"
                >
                  <div class="part-icon part-icon-no">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  <div class="part-content">
                    <span class="part-title">Non, je suis nouveau</span>
                    <span class="part-desc">Première participation au SENAFOI</span>
                  </div>
                </button>
              </div>
            </div>

            <div class="step-nav">
              <button @click="retourEtapePrecedente" type="button" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Retour
              </button>
            </div>
          </div>
        </div>

        <!-- Étape : Sélection du niveau précédent -->
        <div v-if="showLevelSelection && !showProfileView" class="step-content">
          <div class="step-card">
            <div class="step-hero">
              <div class="step-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 3l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 3z"/>
                </svg>
              </div>
              <div class="step-hero-text">
                <h2>Votre Niveau 2025</h2>
                <p>Quel était votre niveau au SENAFOI 2025 ?</p>
              </div>
            </div>

            <div class="level-section">
              <p class="section-subtitle">Sélectionnez le niveau auquel vous avez participé l'année dernière</p>

              <div class="level-grid">
                <button
                  v-for="niveau in niveauxDisponibles"
                  :key="niveau"
                  @click="selectNiveauPrecedent(niveau)"
                  type="button"
                  class="level-btn"
                  :class="{ 'level-btn-selected': selectedNiveauPrecedent === niveau }"
                >
                  <span class="level-number">{{ niveau }}</span>
                  <span class="level-label">Niveau</span>
                </button>
              </div>

              <div v-if="selectedNiveauPrecedent" class="level-actions">
                <button
                  @click="loadParticipantsByLevel"
                  type="button"
                  class="btn btn-primary btn-large"
                  :disabled="isLoadingParticipants"
                >
                  <div v-if="isLoadingParticipants" class="spinner-small"></div>
                  <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                  {{ isLoadingParticipants ? 'Chargement...' : 'Continuer' }}
                </button>
              </div>
            </div>

            <div class="step-nav">
              <button @click="retourEtapePrecedente" type="button" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Retour
              </button>
            </div>
          </div>
        </div>

        <!-- Étape : Sélection du participant -->
        <div v-if="showParticipantSelection && !showProfileView" class="step-content">
          <div class="step-card">
            <div class="step-hero">
              <div class="step-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div class="step-hero-text">
                <h2>Sélectionnez votre profil</h2>
                <p>Participants du Niveau {{ selectedNiveauPrecedent }} - SENAFOI 2025</p>
              </div>
            </div>

            <div class="participant-section">
              <!-- Barre de recherche -->
              <div class="search-box">
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <input
                  v-model="participantSearchQuery"
                  type="text"
                  placeholder="Rechercher par nom ou prénom..."
                  class="search-input"
                />
              </div>

              <!-- États -->
              <div v-if="isLoadingParticipants" class="loading-state">
                <div class="loading-spinner"></div>
                <p>Chargement des participants...</p>
              </div>

              <div v-else-if="participantError" class="error-state">
                <div class="error-icon-box">
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                  </svg>
                </div>
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
                  <div class="participant-avatar">
                    {{ participant.nom.charAt(0) }}{{ participant.prenom.charAt(0) }}
                  </div>
                  <div class="participant-info">
                    <div class="participant-name">{{ participant.nom }} {{ participant.prenom }}</div>
                    <div class="participant-badges">
                      <span class="badge badge-niveau">{{ participant.niveau }}</span>
                      <span
                        class="badge"
                        :class="{
                          'badge-admis': participant.decision === 'ADMIS',
                          'badge-recale': participant.decision === 'RECALE',
                          'badge-exclus': participant.decision === 'EXCLUS'
                        }"
                      >
                        {{ participant.decision }}
                      </span>
                    </div>
                  </div>
                  <div v-if="selectedParticipant?.id === participant.id" class="participant-check">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                  </div>
                </div>
              </div>

              <div v-else class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <p v-if="participantSearchQuery">Aucun résultat pour "{{ participantSearchQuery }}"</p>
                <p v-else>Aucun participant trouvé pour ce niveau</p>
              </div>

              <!-- Action de confirmation -->
              <div v-if="selectedParticipant" class="confirm-selection">
                <button @click="confirmerSelectionParticipant" type="button" class="btn btn-primary btn-large">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  Confirmer et voir le profil
                </button>
              </div>
            </div>

            <div class="step-nav">
              <button @click="retourEtapePrecedente" type="button" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Retour
              </button>
            </div>
          </div>
        </div>

        <!-- Affichage du profil sélectionné -->
        <div v-if="showProfileView" class="step-content">
          <div class="profile-card">
            <div class="profile-hero">
              <div class="profile-avatar-large">
                <template v-if="selectedParticipant.photo">
                  <img :src="selectedParticipant.photo" alt="Photo profil" />
                </template>
                <template v-else>
                  {{ selectedParticipant.nom.charAt(0) }}{{ selectedParticipant.prenom.charAt(0) }}
                </template>
              </div>
              <div class="profile-hero-info">
                <h2>{{ selectedParticipant.nom }} {{ selectedParticipant.prenom }}</h2>
                <div class="profile-badges">
                  <span class="badge badge-niveau">Niveau {{ selectedParticipant.niveau }}</span>
                  <span
                    class="badge"
                    :class="{
                      'badge-admis': selectedParticipant.decision === 'ADMIS',
                      'badge-recale': selectedParticipant.decision === 'RECALE',
                      'badge-exclus': selectedParticipant.decision === 'EXCLUS'
                    }"
                  >
                    {{ selectedParticipant.decision }}
                  </span>
                </div>
              </div>
            </div>

            <div v-if="getNouveauNiveau(selectedParticipant)" class="new-level-banner">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              <span>Niveau 2026 : <strong>{{ getNouveauNiveau(selectedParticipant) }}</strong></span>
            </div>

            <div class="profile-details-section">
              <div class="section-header">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>Informations du profil 2025</span>
              </div>
              <div class="profile-grid">
                <div class="profile-item">
                  <span class="profile-label">Sexe</span>
                  <span class="profile-value">{{ selectedParticipant.sexe === 'M' ? 'Masculin' : 'Féminin' }}</span>
                </div>
                <div class="profile-item">
                  <span class="profile-label">Contact</span>
                  <span class="profile-value">{{ selectedParticipant.contact || '—' }}</span>
                </div>
                <div class="profile-item">
                  <span class="profile-label">Niveau d'étude</span>
                  <span class="profile-value">{{ selectedParticipant.niveau_etude || '—' }}</span>
                </div>
                <div class="profile-item">
                  <span class="profile-label">Ville</span>
                  <span class="profile-value">{{ selectedParticipant.secretariat_regional || '—' }}</span>
                </div>
                <div class="profile-item">
                  <span class="profile-label">Taille T-shirt</span>
                  <span class="profile-value">{{ selectedParticipant.taille_tshirt || '—' }}</span>
                </div>
              </div>
            </div>

            <div class="profile-note">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
              </svg>
              <p>Vos informations seront pré-remplies dans le formulaire. Vous pourrez modifier certains champs si nécessaire.</p>
            </div>

            <div class="profile-actions">
              <button @click="retourSelectionParticipant" type="button" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Changer de profil
              </button>
              <button @click="confirmerEtContinuer" type="button" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Continuer l'inscription
              </button>
            </div>
          </div>
        </div>

        <!-- Tableau des résultats de l'an passé -->
        <div v-if="showResultsTable" class="step-content">
          <div class="results-card">
            <div class="results-hero">
              <div class="results-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
              </div>
              <div class="results-hero-text">
                <h2>Résultats SENAFOI 2025</h2>
                <p>Liste des séminaristes ayant participé au SENAFOI 2025</p>
              </div>
            </div>

            <div class="results-section">
              <div class="results-toolbar">
                <div class="search-box">
                  <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                  </svg>
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par nom ou prénom..."
                    class="search-input"
                    :disabled="isLoadingResults"
                  />
                </div>
              </div>

              <!-- Pagination info -->
              <div v-if="!isLoadingResults && !resultError && resultatsAnneePassee.length > 0" class="pagination-info">
                <p>
                  Affichage de {{ paginationInfo.start }} à {{ paginationInfo.end }}
                  sur {{ paginationInfo.total }} résultat{{ paginationInfo.total > 1 ? 's' : '' }}
                  <span v-if="searchQuery"> pour "{{ searchQuery }}"</span>
                </p>
              </div>

              <!-- Loading -->
              <div v-if="isLoadingResults" class="loading-state">
                <div class="loading-spinner"></div>
                <p>Chargement des résultats...</p>
              </div>

              <!-- Error -->
              <div v-if="resultError" class="error-state">
                <div class="error-icon-box">
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                  </svg>
                </div>
                <p>{{ resultError }}</p>
                <button @click="fetchResultatsSenafoi24" class="btn btn-primary">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                  </svg>
                  Réessayer
                </button>
              </div>

              <!-- Table -->
              <div v-if="!isLoadingResults && !resultError" class="table-container">
                <!-- Desktop Table -->
                <table class="results-table desktop-table">
                  <thead>
                    <tr>
                      <th>Nom</th>
                      <th>Prénom</th>
                      <th>Niveau</th>
                      <th>Décision</th>
                      <th>Bulletin</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="participant in filteredResults" :key="participant.id" class="table-row">
                      <td class="table-cell">{{ participant.nom }}</td>
                      <td class="table-cell">{{ participant.prenom }}</td>
                      <td class="table-cell"><span class="badge badge-niveau">{{ participant.niveau }}</span></td>
                      <td class="table-cell">
                        <span class="badge" :class="{'badge-admis': participant.decision==='ADMIS','badge-recale': participant.decision==='RECALE','badge-exclus': participant.decision==='EXCLUS'}">
                          {{ participant.decision }}
                        </span>
                      </td>
                      <td class="table-cell">
                        <button @click="telechargerBulletin(participant)" class="bulletin-btn" :class="{'bulletin-btn-loading': bulletinLoading[participant.id]}" :disabled="bulletinLoading[participant.id]">
                          <div v-if="bulletinLoading[participant.id]" class="spinner-small"></div>
                          <svg v-else width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 15h8v2H8zm0-4h8v2H8zm0-4h5v2H8z"/></svg>
                          {{ bulletinLoading[participant.id] ? '...' : 'Mon Bulletin' }}
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <!-- Mobile Cards -->
                <div class="mobile-cards">
                  <div v-for="participant in filteredResults" :key="'card-' + participant.id" class="result-card-mobile">
                    <div class="result-card-header">
                      <div class="result-card-avatar">{{ participant.nom.charAt(0) }}{{ participant.prenom.charAt(0) }}</div>
                      <div class="result-card-name">
                        <span class="result-card-nom">{{ participant.nom }}</span>
                        <span class="result-card-prenom">{{ participant.prenom }}</span>
                      </div>
                    </div>
                    <div class="result-card-badges">
                      <span class="badge badge-niveau">{{ participant.niveau }}</span>
                      <span class="badge" :class="{'badge-admis': participant.decision==='ADMIS','badge-recale': participant.decision==='RECALE','badge-exclus': participant.decision==='EXCLUS'}">
                        {{ participant.decision }}
                      </span>
                    </div>
                    <button @click="telechargerBulletin(participant)" class="bulletin-btn bulletin-btn-full" :class="{'bulletin-btn-loading': bulletinLoading[participant.id]}" :disabled="bulletinLoading[participant.id]">
                      <div v-if="bulletinLoading[participant.id]" class="spinner-small"></div>
                      <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 15h8v2H8zm0-4h8v2H8zm0-4h5v2H8z"/></svg>
                      {{ bulletinLoading[participant.id] ? 'Génération...' : 'Télécharger mon Bulletin' }}
                    </button>
                  </div>
                </div>

                <div v-if="filteredResults.length === 0 && resultatsAnneePassee.length > 0" class="empty-state">
                  <p>Aucun résultat pour cette recherche.</p>
                </div>
                <div v-if="resultatsAnneePassee.length === 0" class="empty-state">
                  <p>Aucun résultat disponible.</p>
                </div>
              </div>

              <!-- Pagination -->
              <div v-if="!isLoadingResults && !resultError && totalPages > 1" class="pagination-controls">
                <div class="pagination-buttons">
                  <button @click="previousPage" :disabled="currentPage === 1" class="pagination-btn">
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
                  <button @click="nextPage" :disabled="currentPage === totalPages" class="pagination-btn">
                    Suiv
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                    </svg>
                  </button>
                </div>
                <div class="items-per-page">
                  <label for="itemsPerPage">Par page :</label>
                  <select id="itemsPerPage" v-model="itemsPerPage" @change="currentPage = 1" class="items-select">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="results-actions">
              <button @click="backToHome" type="button" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Retour
              </button>
              <button v-if="canOpenNewInscription" @click="showParticipationSelection" type="button" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                S'inscrire au SENAFOI 2026
              </button>
            </div>
          </div>
        </div>

        <!-- Progress Steps (pour le formulaire) -->
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
                <span v-if="index + 1 < currentStep">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                </span>
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

        <div v-if="showFormContent" class="quota-strip" :class="{ 'quota-strip-full': quotaInfo.is_full }">
          <div>
            <span>Places disponibles</span>
            <strong>{{ availablePlaces.toLocaleString('fr-FR') }}</strong>
          </div>
          <small v-if="inscriptionsFermees">Inscriptions closes</small>
          <small v-else>Clôture dans {{ compteRebours.heures }}h {{ compteRebours.minutes }}min</small>
        </div>

        <!-- Form Content -->
        <div v-if="showFormContent" class="form-content">
          <form @submit.prevent="soumettreInscription">

            <!-- ===================== ÉTAPE 1 : Informations Personnelles ===================== -->
            <div v-if="currentStep === 1" class="form-step">
              <div class="form-section-header">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>Informations Personnelles</span>
              </div>

              <!-- Bandeau info ancien participant -->
              <div v-if="isAncienParticipant" class="ancien-banner">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                </svg>
                <p>Vos informations de l'année passée ont été pré-remplies. Les champs grisés ne sont pas modifiables.</p>
              </div>

              <div class="form-grid">
                <!-- Nom -->
                <div class="form-group">
                  <label class="form-label">Nom <span class="required">*</span></label>
                  <input
                    v-model="formData.nom"
                    @input="!isAncienParticipant && formatUpperCase('nom')"
                    type="text"
                    required
                    class="form-input"
                    placeholder="Votre nom"
                    :readonly="isAncienParticipant"
                    :class="{ 'input-readonly': isAncienParticipant }"
                  />
                  <small v-if="isAncienParticipant" class="form-help readonly-help">Récupéré depuis votre profil 2025</small>
                </div>

                <!-- Prénom -->
                <div class="form-group">
                  <label class="form-label">Prénom(s) <span class="required">*</span></label>
                  <input
                    v-model="formData.prenom"
                    @input="!isAncienParticipant && formatFirstUpperCase('prenom')"
                    type="text"
                    required
                    class="form-input"
                    placeholder="Votre prénom"
                    :readonly="isAncienParticipant"
                    :class="{ 'input-readonly': isAncienParticipant }"
                  />
                  <small v-if="isAncienParticipant" class="form-help readonly-help">Récupéré depuis votre profil 2025</small>
                </div>

                <!-- Sexe -->
                <div class="form-group">
                  <label class="form-label">Sexe <span class="required">*</span></label>
                  <div class="radio-group radio-group-horizontal">
                    <label class="radio-item" :class="{ 'radio-item-readonly': isAncienParticipant }">
                      <input v-model="formData.sexe" type="radio" value="M" class="radio-input" required :disabled="isAncienParticipant" />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Masculin</span>
                    </label>
                    <label class="radio-item" :class="{ 'radio-item-readonly': isAncienParticipant }">
                      <input v-model="formData.sexe" type="radio" value="F" class="radio-input" required :disabled="isAncienParticipant" />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Féminin</span>
                    </label>
                  </div>
                </div>

                <!-- Date de naissance -->
                <div class="form-group">
                  <label class="form-label">Date de naissance <span class="required">*</span></label>
                  <input v-model="formData.dateNaissance" @change="calculateAge" type="date" required class="form-input birth-date-input" />
                  <small v-if="formData.age" class="form-help">Âge calculé : {{ formData.age }} ans</small>
                </div>

                <!-- Niveau d'étude -->
                <div class="form-group">
                  <label class="form-label">Niveau d'étude <span class="required">*</span></label>
                  <select v-model="formData.niveauEtude" required class="form-input">
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

                <!-- Contact -->
                <div class="form-group">
                  <label class="form-label">Contact <span class="required">*</span></label>
                  <input
                    v-model="formData.contact"
                    @input="formatPhoneNumber"
                    @blur="formatPhoneNumber"
                    type="tel"
                    required
                    class="form-input"
                    placeholder="07 XX XX XX XX"
                    maxlength="14"
                  />
                </div>
              </div>
            </div>

            <!-- ===================== ÉTAPE 2 : Informations Organisationnelles ===================== -->
            <div v-if="currentStep === 2" class="form-step">
              <div class="form-section-header">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                <span>Informations Organisationnelles</span>
              </div>

              <div class="form-grid">
                <!-- Ville -->
                <div class="form-group">
                  <label class="form-label">Ville / Commune <span class="required">*</span></label>
                  <input v-model="formData.ville" type="text" required class="form-input" placeholder="Votre ville" />
                </div>

                <!-- Contact parent -->
                <div class="form-group">
                  <label class="form-label">Contact parent/tuteur <span class="required">*</span></label>
                  <input
                    v-model="formData.contactParent"
                    @input="formatPhoneNumberParent"
                    @blur="formatPhoneNumberParent"
                    type="tel"
                    required
                    class="form-input"
                    placeholder="07 XX XX XX XX"
                    maxlength="14"
                  />
                </div>

                <!-- Maladie -->
                <div class="form-group form-group-full">
                  <label class="form-label">Avez-vous une maladie particulière ? <span class="required">*</span></label>
                  <div class="radio-group radio-group-horizontal">
                    <label class="radio-item">
                      <input v-model="formData.aUneMaladie" type="radio" value="oui" class="radio-input" required />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Oui</span>
                    </label>
                    <label class="radio-item">
                      <input v-model="formData.aUneMaladie" type="radio" value="non" class="radio-input" required />
                      <div class="radio-custom"></div>
                      <span class="radio-label">Non</span>
                    </label>
                  </div>
                </div>

                <!-- Détail maladie -->
                <div v-if="formData.aUneMaladie === 'oui'" class="form-group form-group-full">
                  <label class="form-label">Détaillez-nous <span class="required">*</span></label>
                  <div class="maladie-options">
                    <label
                      v-for="maladie in maladiesDisponibles"
                      :key="maladie"
                      class="maladie-option"
                      :class="{ 'maladie-option-selected': selectedMaladies.includes(maladie) }"
                    >
                      <input
                        v-model="selectedMaladies"
                        type="checkbox"
                        :value="maladie"
                        @change="syncMaladieDetail"
                      />
                      <span class="maladie-check">
                        <svg v-if="selectedMaladies.includes(maladie)" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                      </span>
                      <span>{{ maladie }}</span>
                    </label>
                  </div>

                  <label class="maladie-other" :class="{ 'maladie-other-active': selectedMaladies.includes(autreMaladieLabel) }">
                    <div class="maladie-other-toggle">
                      <input
                        v-model="selectedMaladies"
                        type="checkbox"
                        :value="autreMaladieLabel"
                        @change="syncMaladieDetail"
                      />
                      <span class="maladie-check">
                        <svg v-if="selectedMaladies.includes(autreMaladieLabel)" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                      </span>
                      <span>{{ autreMaladieLabel }}</span>
                    </div>
                    <input
                      v-if="selectedMaladies.includes(autreMaladieLabel)"
                      v-model="autreMaladie"
                      @input="syncMaladieDetail"
                      type="text"
                      class="form-input maladie-other-input"
                      placeholder="Précisez votre maladie ou condition"
                      required
                    />
                  </label>

                  <input v-model="formData.maladieDetail" type="hidden" required />
                  <small class="form-help">Sélection actuelle : {{ formData.maladieDetail || 'Aucune maladie sélectionnée' }}</small>
                </div>

                <!-- Taille T-shirt -->
                
              </div>
            </div>

            <!-- ===================== ÉTAPE 3 : Photo ===================== -->
            <div v-if="currentStep === 3" class="form-step">
              <div class="form-section-header">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 2l3 3h4c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V7c0-1.1.9-2 2-2h3l3-3zm3 15c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0-1.8c-1.77 0-3.2-1.43-3.2-3.2s1.43-3.2 3.2-3.2 3.2 1.43 3.2 3.2-1.43 3.2-3.2 3.2z"/>
                </svg>
                <span>Photo d'Identité</span>
              </div>

              <!-- Nouvelle photo obligatoire pour les anciens participants -->
              <div v-if="isAncienParticipant" class="ancien-banner ancien-banner-info">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                </svg>
                <p>Veuillez ajouter une nouvelle photo d'identite pour votre inscription 2026.</p>
              </div>

              <div class="photo-section">
                <div class="photo-upload">
                  <input ref="photoInput" type="file" accept="image/*" @change="uploadImage" class="photo-input" />
                  <div v-if="!formData.photo && !isUploading" @click="$refs.photoInput.click()" class="photo-placeholder">
                    <svg class="upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/>
                    </svg>
                    <p>Cliquez pour ajouter une photo obligatoire</p>
                    <small>Fond unique recommandé</small>
                  </div>
                  <div v-if="isUploading" class="upload-loading">
                    <div class="upload-progress-container">
                      <div class="progress-circle">
                        <svg class="progress-ring" width="60" height="60">
                          <circle class="progress-ring-circle" stroke="#e5e7eb" stroke-width="4" fill="transparent" r="26" cx="30" cy="30" />
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
                    <p>Upload en cours...</p>
                    <div v-if="uploadError" class="upload-error">{{ uploadError }}</div>
                  </div>
                  <div v-if="formData.photo && !isUploading" class="photo-preview">
                    <img :src="formData.photo" alt="Photo" class="preview-image" />
                    <button @click="$refs.photoInput.click()" type="button" class="change-photo" title="Changer la photo">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                      </svg>
                    </button>
                    <button @click="removePhoto" type="button" class="remove-photo" title="Supprimer la photo">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- ===================== ÉTAPE 4 : Paiement ===================== -->
            <!-- ===================== ÉTAPE 4 : Paiement ===================== -->
            <div v-if="currentStep === 4" class="form-step payment-step">

              <!-- En-tête compact -->
              <div class="form-section-header">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                </svg>
                <span>Paiement & Transport</span>
              </div>

              <!-- Layout deux colonnes -->
              <div class="payment-layout">

                <!-- Colonne gauche : Transport + Prix -->
                <div class="payment-left">

                  <!-- Transport compact -->
                  <div class="pay-block">
                    <p class="pay-block-title">Mode de transport <span class="required">*</span></p>
                    <div class="transport-cards">
                      <label
                        class="transport-card"
                        :class="{ 'transport-card-active': formData.transport === 'convoi' }"
                      >
                        <input v-model="formData.transport" type="radio" value="convoi" class="radio-input" required />
                        <div class="tc-icon">🚌</div>
                        <div class="tc-info">
                          <span class="tc-title">Convoi AEEMCI</span>
                          <span class="tc-sub">Depuis Abidjan</span>
                        </div>
                        <span class="tc-price">
                          {{ isLoadingTarifs ? '...' : (tarifs.avec_transport?.montant ?? 35000).toLocaleString('fr-FR') }} F
                        </span>
                      </label>

                      <label
                        class="transport-card"
                        :class="{ 'transport-card-active': formData.transport === 'personnel' }"
                      >
                        <input v-model="formData.transport" type="radio" value="personnel" class="radio-input" required />
                        <div class="tc-icon">🚗</div>
                        <div class="tc-info">
                          <span class="tc-title">Transport personnel</span>
                          <span class="tc-sub">Intérieur du pays</span>
                        </div>
                        <span class="tc-price">
                          {{ isLoadingTarifs ? '...' : (tarifs.sans_transport?.montant ?? 20000).toLocaleString('fr-FR') }} F
                        </span>
                      </label>
                    </div>

                    <div v-if="formData.transport === 'convoi'" class="tc-note">
                      ℹ️ Le convoi part uniquement d'Abidjan
                    </div>
                  </div>

                  <!-- Prix total -->
                  <div class="pay-block price-summary">
                    <div class="price-row">
                      <span class="price-row-label">Total à payer</span>
                      <div class="price-row-amount">
                        <span v-if="promoAppliquee" class="price-crossed">
                          {{ formData.transport === 'convoi'
                              ? (tarifs.avec_transport?.montant ?? 35000).toLocaleString('fr-FR')
                              : (tarifs.sans_transport?.montant ?? 20000).toLocaleString('fr-FR') }} F
                        </span>
                        <span class="price-final">
                          {{ getTotalPrice().toLocaleString('fr-FR') }} FCFA
                        </span>
                        <span v-if="promoAppliquee" class="promo-tag">-{{ promoAppliquee.reduction_pct }}%</span>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- Colonne droite : Paiement Wave + Promo -->
                <div class="payment-right">

                  <!-- Wave -->
                  <div class="pay-block wave-block">
                    <div class="wave-compact-header">
                      <img src="https://play-lh.googleusercontent.com/-Mp3XW7uhwn3KGQxUKGPoc4MbA5ti-3-q23TgoVi9ujBgHWW5n4IySvlG5Exwrxsjw=w256" width="32" alt="Wave" class="wave-logo" />
                      <div>
                        <p class="wave-compact-title">Paiement Wave</p>
                        <p class="wave-compact-sub">Paiement sécurisé</p>
                      </div>
                    </div>
                    <div class="form-group" style="margin-top:.75rem">
                      <label class="form-label">Numéro Wave <span class="required">*</span></label>
                      <input
                        v-model="formData.numeroWave"
                        @input="formatWaveNumber"
                        @blur="formatWaveNumber"
                        type="tel"
                        required
                        class="form-input"
                        placeholder="07 XX XX XX XX"
                        maxlength="14"
                      />
                    </div>
                  </div>

                  <!-- Code promo avec toggle 
                  <div class="pay-block promo-block">
                    <label class="promo-toggle-label">
                      <input
                        v-model="showPromoField"
                        type="checkbox"
                        class="promo-checkbox"
                        @change="() => { if (!showPromoField) { promoAppliquee = null; codePromo = ''; promoError = '' } }"
                      />
                      <span class="promo-toggle-custom"></span>
                      <span class="promo-toggle-text">J'ai un code promo</span>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color:#d97706;flex-shrink:0">
                        <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58s1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41s-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
                      </svg>
                    </label>

                    <!-- Champ promo si coché 
                    <div v-if="showPromoField" class="promo-field-wrap">
                      <div v-if="promoAppliquee" class="promo-applied">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        <span>{{ promoAppliquee.code }} — {{ promoAppliquee.reduction_pct }}% appliqué</span>
                        <button @click="promoAppliquee = null; codePromo = ''" type="button" class="promo-remove-sm">✕</button>
                      </div>
                      <div v-else class="promo-input-row">
                        <input
                          v-model="codePromo"
                          type="text"
                          placeholder="Votre code"
                          class="form-input promo-input"
                          :disabled="isCheckingPromo"
                          @keyup.enter="verifierCodePromo"
                        />
                        <button
                          @click="verifierCodePromo"
                          type="button"
                          class="btn btn-outline btn-sm"
                          :disabled="!codePromo.trim() || isCheckingPromo"
                        >
                          <div v-if="isCheckingPromo" class="spinner-small"></div>
                          <span v-else>OK</span>
                        </button>
                      </div>
                      <p v-if="promoError" class="promo-error">{{ promoError }}</p>
                    </div>
                  </div>
                  -->

                </div>
              </div>
            </div>

            <!-- Navigation Buttons -->
            <div v-if="showFormContent" class="form-navigation">
              <button v-if="currentStep > 1" @click="previousStep" type="button" class="btn btn-outline">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
  </svg>
  Précédent
              </button>
              <button v-else-if="currentStep === 1" @click="backToHome" type="button" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Retour
              </button>

              <button v-if="currentStep < totalSteps" @click="nextStep" type="button" class="btn btn-primary" :disabled="!canProceedToNextStep">
                Suivant
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                </svg>
              </button>

              <button v-if="currentStep === totalSteps" type="submit" class="btn btn-primary" :disabled="!canSubmit || isSubmitting">
                <div v-if="isSubmitting" class="spinner-small"></div>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                {{ isSubmitting ? 'Traitement en cours...' : "Finaliser l'inscription" }}
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
          <div class="success-icon-modal">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </div>
          <h3>Inscription réussie !</h3>
          <p>Votre inscription au séminaire a été enregistrée avec succès.</p>
          <p v-if="inscriptionData.matricule" class="matricule-info"><strong>Matricule :</strong> {{ inscriptionData.matricule }}</p>
          <p v-if="inscriptionData.dortoir" class="dortoir-info"><strong>Dortoir :</strong> {{ inscriptionData.dortoir }}</p>
          <p v-if="inscriptionData.car" class="car-info"><strong>Transport :</strong> {{ inscriptionData.car }}</p>
        </div>
        <div class="modal-actions">
          <button @click="downloadFiche" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
            </svg>
            Télécharger la fiche
          </button>
          <button @click="resetForm" class="btn btn-outline">
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
import { ref, computed, nextTick, onMounted, onUnmounted, watch } from 'vue'
import axios from 'axios'
import senafoiBanner from '../../assets/senafoi-2026-banner.jpeg'

const SEMINARISTE_INSCRIPTION_VISIBLE = true

const UPLOAD_URL = import.meta.env.DEV
  ? '/proxy-upload'
  : 'https://api.aeemci-ce.ci/cloud/upload.php'

// Configuration API
const API_BASE_URL = 'https://api.aeemci-ce.ci/senafoi'

// Configuration Cloudinary
const cloudinaryConfig = {
  cloudName: 'r-sidence-meubl-e',
  uploadPreset: 'dsegjq9c',
  apiKey: '162815926914253',
  uploadUrl: 'https://api.cloudinary.com/v1_1/r-sidence-meubl-e/upload'
}

// Références
const photoInput = ref(null)
const bulletinLoading = ref({})
const showPromoField = ref(false)

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

// Tarifs depuis la BD
const tarifs = ref({ avec_transport: null, sans_transport: null })
const isLoadingTarifs = ref(false)

// Code promo
const codePromo = ref('')
const promoAppliquee = ref(null)   // { reduction_pct, montant_reduit, message }
const promoError = ref('')
const isCheckingPromo = ref(false)

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

const stopInscriptions = ref(true)
const quotaInfo = ref({
  quota_total: 0,
  inscriptions_count: 0,
  places_disponibles: 0,
  is_full: false,
  refreshed_at: ''
})
const isLoadingQuota = ref(false)
const quotaError = ref('')
const quotaRefreshTimer = ref(null)
const hasStartedInscription = ref(false)

// Variables de pagination
const currentPage = ref(1)
const itemsPerPage = ref(10)
const totalItems = ref(0)

// Données d'inscription retournées
const inscriptionData = ref({})

// Niveaux disponibles
const niveauxDisponibles = ['1', '2', '3', '4', '5']
const maladiesDisponibles = [
  'Asthme',
  'Drepanocyte',
  'Diabète',
  'Ulcère gastro-duodénal',
  'Colopathie fonctionnelle',
  'Anémie +/-',
]
const autreMaladieLabel = 'Autres à préciser'
const selectedMaladies = ref([])
const autreMaladie = ref('')

// Date maximum pour la date de naissance (18 ans minimum)
const maxDate = computed(() => {
  const today = new Date()
  const maxDateVal = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate())
  return maxDateVal.toISOString().split('T')[0]
})

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
  tailleTshirt: 'L',
  aUneMaladie: '',
  maladieDetail: '',
  photo: null,
  photoId: null,
  numeroWave: '',
  niveauSeminaire: 'TEST_ENTREE',
  transport: 'convoi',
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

const availablePlaces = computed(() => Math.max(0, Number(quotaInfo.value.places_disponibles) || 0))
const quotaTotal = computed(() => Math.max(0, Number(quotaInfo.value.quota_total) || 0))

// ─── Clôture des inscriptions ─────────────────────────────────────────────
// Heure locale d'Abidjan, qui est aussi l'heure UTC.
const DATE_CLOTURE = new Date('2026-08-01T12:00:00Z')
const maintenant = ref(Date.now())
let minuteurCloture = null

const inscriptionsFermees = computed(() => maintenant.value >= DATE_CLOTURE.getTime())

const dateClotureLisible = computed(() =>
  DATE_CLOTURE.toLocaleString('fr-FR', {
    weekday: 'long', day: 'numeric', month: 'long',
    hour: '2-digit', minute: '2-digit',
  })
)

const compteRebours = computed(() => {
  const reste = Math.max(0, DATE_CLOTURE.getTime() - maintenant.value)
  const totalSecondes = Math.floor(reste / 1000)
  return {
    heures: String(Math.floor(totalSecondes / 3600)).padStart(2, '0'),
    minutes: String(Math.floor((totalSecondes % 3600) / 60)).padStart(2, '0'),
    secondes: String(totalSecondes % 60).padStart(2, '0'),
  }
})

// Une inscription déjà commencée peut être terminée après la clôture ;
// seules les nouvelles sont bloquées.
const canOpenNewInscription = computed(() =>
  stopInscriptions.value
  && (!inscriptionsFermees.value || hasStartedInscription.value)
  && (!quotaInfo.value.is_full || hasStartedInscription.value)
)

const quotaStatusText = computed(() => {
  if (inscriptionsFermees.value) return 'Inscriptions closes'
  if (isLoadingQuota.value && !quotaInfo.value.refreshed_at) return 'Chargement du quota...'
  if (quotaError.value) return 'Quota momentanement indisponible'
  if (quotaInfo.value.is_full) return 'Quota atteint'
  return 'Inscriptions ouvertes'
})

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
    return nom.includes(query) || prenom.includes(query) || nomComplet.includes(query) || prenomNom.includes(query)
  })
})

const fetchTarifs = async () => {
  isLoadingTarifs.value = true
  try {
    const res = await axios.get(`${API_BASE_URL}/tarifs_api.php?action=get_tarifs`)
    if (res.data.success) {
      tarifs.value = res.data.tarifs
    }
  } catch (e) {
    console.error('Erreur chargement tarifs', e)
  } finally {
    isLoadingTarifs.value = false
  }
}

const fetchQuota = async () => {
  isLoadingQuota.value = true
  quotaError.value = ''
  try {
    const res = await axios.get(`${API_BASE_URL}/quota_api.php?action=get_quota&annee=2026`, {
      params: { t: Date.now() }
    })
    if (res.data.success) {
      quotaInfo.value = {
        quota_total: Number(res.data.data.quota_total) || 0,
        inscriptions_count: Number(res.data.data.inscriptions_count) || 0,
        places_disponibles: Number(res.data.data.places_disponibles) || 0,
        is_full: Boolean(res.data.data.is_full),
        refreshed_at: res.data.data.refreshed_at || new Date().toISOString()
      }
    } else {
      throw new Error(res.data.message || 'Erreur quota')
    }
  } catch (e) {
    console.error('Erreur chargement quota', e)
    quotaError.value = 'Impossible de charger les places disponibles.'
  } finally {
    isLoadingQuota.value = false
  }
}

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

const telechargerBulletin = async (participant) => {
  bulletinLoading.value[participant.id] = true
  try {
    let fullData = null
    if (participant.matricule) {
      try {
        const response = await fetch('https://api.aeemci-ce.ci/get_resultat_final.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ action: 'get_seminarist', matricule: participant.matricule })
        })
        const data = await response.json()
        if (data.success) {
          fullData = data.data
        }
      } catch (e) {
        console.warn('Impossible de récupérer les données complètes')
      }
    }
    const result = fullData ? { ...participant, ...fullData } : participant
    const { jsPDF } = await import('jspdf')
    const QRCode = await import('qrcode')
    const doc = new jsPDF('p', 'mm', 'a4')
    const today = new Date()
    const bulletinNumber = Math.floor(Math.random() * 99999).toString().padStart(5, '0')
    const qrText = `SENAFOI 2025 - ${result.matricule || result.id} - ${result.nom} ${result.prenom}`
    const qrDataURL = await QRCode.default.toDataURL(qrText, { width: 120, margin: 1 })
    const emerald = [34, 94, 78]
    const gold = [212, 175, 55]

    // Filigrane
    doc.setTextColor(240, 240, 240)
    doc.setFontSize(60)
    doc.text('﷽', 105, 150, { align: 'center' })

    // Bandeau vert
    doc.setFillColor(...emerald)
    doc.rect(0, 0, 210, 35, 'F')

    // Logo AEEMCI
    try {
      const logoImg = new Image()
      logoImg.crossOrigin = 'anonymous'
      await new Promise((resolve, reject) => {
        logoImg.onload = resolve
        logoImg.onerror = reject
        logoImg.src = 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg'
      })
      doc.addImage(logoImg, 'JPEG', 10, 8, 28, 25)
    } catch {}

    // Titre
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(20)
    doc.setTextColor(255, 255, 255)
    doc.text('AEEMCI', 105, 15, { align: 'center' })
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    doc.text("Association des Élèves et Étudiants Musulmans de Côte d'Ivoire", 105, 20, { align: 'center' })
    doc.setFontSize(14)
    doc.setFont('helvetica', 'bold')
    doc.setTextColor(...gold)
    doc.text('BULLETIN - SENAFOI 2025', 105, 28, { align: 'center' })

    let startY = 45
    doc.setTextColor(0, 0, 0)
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(9)
    doc.text(`BULLETIN N° : ${bulletinNumber}`, 18, startY)
    doc.text(`Date : ${today.toLocaleDateString('fr-FR')}`, 165, startY)

    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.text(`MATRICULE: ${String(result.matricule || result.id || 'N/A')}`, 105, startY + 7, { align: 'center' })

    doc.setDrawColor(...gold)
    doc.setLineWidth(0.5)
    doc.line(15, startY + 12, 195, startY + 12)

    let yPos = startY + 20
    doc.setFillColor(240, 253, 244)
    doc.rect(15, yPos - 5, 180, 8, 'F')
    doc.setDrawColor(...emerald)
    doc.setLineWidth(0.3)
    doc.rect(15, yPos - 5, 180, 8)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(11)
    doc.setTextColor(...emerald)
    doc.text('IDENTIFICATION DU SÉMINARISTE', 20, yPos)
    doc.setTextColor(0, 0, 0)

    if (result.photo) {
      try {
        const photoImg = new Image()
        photoImg.crossOrigin = 'anonymous'
        await new Promise((resolve, reject) => {
          photoImg.onload = resolve
          photoImg.onerror = reject
          photoImg.src = result.photo
        })
        doc.addImage(photoImg, 'JPEG', 155, yPos + 2, 32, 35)
      } catch {}
    }

    yPos += 10
    const identFields = [
      ['Nom', result.nom || ''],
      ['Prénom(s)', result.prenom || ''],
      ['Niveau Séminaire', result.niveau_seminaire || result.niveau || ''],
      ['Sexe', result.sexe || ''],
      ["Niveau d'étude", result.niveau_etude || ''],
      ['Ville/Commune', result.ville || result.secretariat_regional || ''],
    ]
    identFields.forEach(([label, value]) => {
      doc.setFont('helvetica', 'normal')
      doc.setFontSize(9)
      doc.text(`${label} :`, 20, yPos)
      doc.setFont('helvetica', 'bold')
      doc.text(String(value), 60, yPos)
      yPos += 6
    })

    yPos += 4
    doc.setFillColor(240, 253, 244)
    doc.rect(15, yPos - 5, 180, 8, 'F')
    doc.setDrawColor(...emerald)
    doc.rect(15, yPos - 5, 180, 8)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(11)
    doc.setTextColor(...emerald)
    doc.text("RÉSULTATS DE L'ÉVALUATION", 20, yPos)
    doc.setTextColor(0, 0, 0)
    yPos += 10

    const noteFields = [
      ['Score obtenu', result.score_obtenu, false],
      ['Conduite', result.conduite !== undefined ? `${result.conduite}/20` : '', false],
      ['Moyenne', result.moyenne !== undefined ? `${result.moyenne}/20` : '', false],
    ]
    noteFields.forEach(([label, value]) => {
      if (value !== '' && value !== undefined && value !== null) {
        doc.setFillColor(248, 250, 252)
        doc.rect(15, yPos - 4, 180, 7, 'F')
        doc.setDrawColor(229, 231, 235)
        doc.rect(15, yPos - 4, 180, 7)
        doc.setFont('helvetica', 'normal')
        doc.setFontSize(9)
        doc.setTextColor(55, 65, 81)
        doc.text(label, 20, yPos)
        const numVal = parseFloat(String(value))
        if (!isNaN(numVal) && String(value).includes('/20')) {
          doc.setFont('helvetica', 'bold')
          if (numVal >= 10) {
            doc.setTextColor(22, 101, 52)
          } else {
            doc.setTextColor(185, 28, 28)
          }
        } else {
          doc.setFont('helvetica', 'bold')
          doc.setTextColor(30, 41, 59)
        }
        doc.text(String(value), 100, yPos)
        doc.setTextColor(0, 0, 0)
        yPos += 8
      }
    })

    yPos += 4
    doc.setFillColor(240, 253, 244)
    doc.rect(15, yPos - 5, 180, 8, 'F')
    doc.setDrawColor(...emerald)
    doc.rect(15, yPos - 5, 180, 8)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(11)
    doc.setTextColor(...emerald)
    doc.text('DÉCISION DU JURY', 20, yPos)
    doc.setTextColor(0, 0, 0)
    yPos += 12

    const decision = result.decision || (parseFloat(result.moyenne) >= 10 ? 'ADMIS' : 'RECALÉ')
    let bgColor, textColor, borderColor
    if (decision === 'ADMIS') {
      bgColor = [220, 252, 231]; textColor = [22, 101, 52]; borderColor = [74, 222, 128]
    } else if (decision === 'RECALE' || decision === 'RECALÉ') {
      bgColor = [254, 243, 199]; textColor = [146, 64, 14]; borderColor = [251, 191, 36]
    } else {
      bgColor = [254, 202, 202]; textColor = [153, 27, 27]; borderColor = [248, 113, 113]
    }
    doc.setFillColor(...bgColor)
    doc.setDrawColor(...borderColor)
    doc.setLineWidth(0.5)
    doc.roundedRect(70, yPos - 6, 70, 12, 3, 3, 'FD')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(14)
    doc.setTextColor(...textColor)
    doc.text(decision, 105, yPos + 2, { align: 'center' })
    doc.setTextColor(0, 0, 0)
    yPos += 18

    doc.addImage(qrDataURL, 'PNG', 170, yPos - 20, 25, 25)

    yPos += 10
    doc.setFillColor(255, 253, 240)
    doc.rect(15, yPos - 5, 180, 8, 'F')
    doc.setDrawColor(...gold)
    doc.rect(15, yPos - 5, 180, 8)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(11)
    doc.setTextColor(...gold)
    doc.text('INFORMATIONS SENAFOI 2025', 20, yPos)
    yPos += 8
    doc.setTextColor(0, 0, 0)
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(8)
    const infos = [
      '• Séminaire National de Formation Islamique et Managériale',
      '• Dates : du 03 au 10 Août 2025',
      '• Lieu : Lycée Mamie Adjoua de Yamoussoukro',
      "• Organisé par l'AEEMCI"
    ]
    infos.forEach(info => {
      doc.text(info, 20, yPos)
      yPos += 5
    })

    doc.setFillColor(...emerald)
    doc.rect(0, 275, 210, 22, 'F')
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7)
    doc.setTextColor(255, 255, 255)
    doc.text("Siège : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 282, { align: 'center' })
    doc.text('27 33 75 42 12 / aeemci@yahoo.fr / www.aeemci.ci', 105, 286, { align: 'center' })
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.text('AEEMCI, pour une identité islamique !', 105, 292, { align: 'center' })
    doc.save(`bulletin_senafoi_2025_${result.nom}_${result.prenom}.pdf`)
  } catch (err) {
    console.error('Erreur génération bulletin:', err)
    alert('Erreur lors de la génération du bulletin. Veuillez réessayer.')
  } finally {
    bulletinLoading.value[participant.id] = false
  }
}

// Fonction pour déterminer le nouveau niveau
const getNouveauNiveau = (participant) => {
  if (!participant) return ''
  const niveau = String(participant.niveau || participant.niveau_actuel || participant.niveau_annee_passee || '').trim()
  const decision = String(participant.decision || participant.decision_participant || '').trim().toUpperCase()
  if (decision === 'ADMIS') {
    const match = niveau.match(/(?:NIVEAU\s*)?(\d)(.*)/i)
    if (!match) return niveau
    const niveauNum = match[1]
    const suffixe = (match[2] || '').trim()
    switch (niveauNum) {
      case '1': return `2${suffixe ? ' ' + suffixe : ''}`
      case '2': return `3${suffixe ? ' ' + suffixe : ''}`
      case '3': return `4${suffixe ? ' ' + suffixe : ''}`
      case '4': return `5${suffixe ? ' ' + suffixe : ''}`
      case '5': return 'Certificat'
      default: return niveau
    }
  } else {
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

// Fonction pour récupérer les résultats SENAFOI 2025
const fetchResultatsSenafoi24 = async () => {
  isLoadingResults.value = true
  resultError.value = ''
  try {
    const response = await axios.get(`${API_BASE_URL}/seminaire_api.php?action=get_anciens_par_niveau`)
    if (response.data.success) {
      // Aplatir les données groupées par niveau
      let allParticipants = []
      if (Array.isArray(response.data.data)) {
        response.data.data.forEach(item => {
          if (item.participants && Array.isArray(item.participants)) {
            item.participants.forEach(p => {
              allParticipants.push({
                ...p,
                niveau: p.niveau_actuel || item.niveau,
                decision: p.decision_participant || p.decision
              })
            })
          }
        })
      }
      resultatsAnneePassee.value = allParticipants
      totalItems.value = allParticipants.length
    } else {
      throw new Error(response.data.message)
    }
  } catch (error) {
    console.error(error)
    resultError.value = 'Erreur chargement résultats'
    resultatsAnneePassee.value = []
  } finally {
    isLoadingResults.value = false
  }
}

// Fonction pour charger les participants par niveau
const fetchParticipantsByLevel = async (niveau) => {
  isLoadingParticipants.value = true
  participantError.value = ''
  try {
    const response = await axios.get(`${API_BASE_URL}/seminaire_api.php?action=get_anciens_par_niveau&niveau=${niveau}`)
    if (response.data.success) {
      const niveauData = response.data.data.find(item => String(item.niveau) === String(niveau))
      if (niveauData) {
        participantsByLevel.value = niveauData.participants.map(p => ({
          ...p,
          niveau: p.niveau_actuel,
          decision: p.decision_participant,
        }))
      } else {
        participantsByLevel.value = []
      }
    } else {
      throw new Error(response.data.message)
    }
  } catch (error) {
    console.error(error)
    participantError.value = 'Erreur chargement participants'
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

// Watcher pour réinitialiser la pagination
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
             formData.value.aUneMaladie &&
             (formData.value.aUneMaladie === 'non' || formData.value.maladieDetail)
    case 3:
      return Boolean(formData.value.photo)
    case 4:
      return formData.value.transport && formData.value.numeroWave
    default:
      return false
  }
})

const verifierCodePromo = async () => {
  if (!codePromo.value.trim()) return
  isCheckingPromo.value = true
  promoError.value = ''
  promoAppliquee.value = null

  try {
    const transportVal = formData.value.transport === 'convoi' ? 1 : 0
    const res = await axios.post(
      `${API_BASE_URL}/tarifs_api.php?action=verifier_code_promo`,
      { code: codePromo.value.trim(), transport: transportVal }
    )
    if (res.data.success) {
      promoAppliquee.value = res.data
    } else {
      promoError.value = res.data.message
    }
  } catch (e) {
    promoError.value = 'Erreur lors de la vérification du code.'
  } finally {
    isCheckingPromo.value = false
  }
}

// Réinitialiser la promo si le transport change
watch(() => formData.value.transport, () => {
  promoAppliquee.value = null
  promoError.value = ''
  codePromo.value = ''
  // showPromoField reste intact, l'utilisateur garde sa préférence
})

const syncMaladieDetail = () => {
  const selections = selectedMaladies.value.filter(item => item !== autreMaladieLabel)
  const autre = autreMaladie.value.trim()
  formData.value.maladieDetail = [
    ...selections,
    ...(selectedMaladies.value.includes(autreMaladieLabel) && autre ? [autre] : [])
  ].join(', ')
}

const hydrateMaladiesFromDetail = (detail = '') => {
  const valeurs = String(detail || '')
    .split(',')
    .map(item => item.trim())
    .filter(Boolean)
  selectedMaladies.value = []
  autreMaladie.value = ''

  valeurs.forEach(item => {
    if (maladiesDisponibles.includes(item)) {
      selectedMaladies.value.push(item)
    } else {
      if (!selectedMaladies.value.includes(autreMaladieLabel)) {
        selectedMaladies.value.push(autreMaladieLabel)
      }
      autreMaladie.value = autreMaladie.value ? `${autreMaladie.value}, ${item}` : item
    }
  })

  syncMaladieDetail()
}

watch(() => formData.value.aUneMaladie, (value) => {
  if (value === 'non') {
    selectedMaladies.value = []
    autreMaladie.value = ''
    formData.value.maladieDetail = ''
  }
})

const canSubmit = computed(() => canProceedToNextStep.value)

// Fonction pour calculer le prix total
const getTotalPrice = () => {
  // Si un code promo est appliqué, utiliser son montant
  if (promoAppliquee.value) {
    return promoAppliquee.value.montant_reduit
  }
  // Sinon, utiliser le tarif de base depuis la BD
  if (formData.value.transport === 'convoi') {
    return tarifs.value.avec_transport?.montant ?? 35000
  }
  return tarifs.value.sans_transport?.montant ?? 20000
}

// Fonctions de navigation
const showParticipationSelection = () => {
  if (!canOpenNewInscription.value) return
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
    isAncienParticipant.value = false
    formData.value.a_participe_senafoi = false
    formData.value.niveauSeminaire = 'TEST_ENTREE'
    startNewInscription()
  } else {
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
  await fetchParticipantsByLevel(selectedNiveauPrecedent.value)
  if (participantsByLevel.value.length > 0) {
    showLevelSelection.value = false
    showParticipantSelection.value = true
  } else {
    participantError.value = `Aucun participant trouvé pour le niveau ${selectedNiveauPrecedent.value}.`
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
  const p = selectedParticipant.value
  formData.value.nom = p.nom
  formData.value.prenom = p.prenom
  formData.value.sexe = p.sexe || ''
  formData.value.contact = p.contact || ''
  formData.value.contactParent = p.contact_parent || ''
  formData.value.ville = p.secretariat_regional || ''
  formData.value.niveauEtude = p.niveau_etude || ''
  formData.value.tailleTshirt = p.taille_tshirt || 'L'
  const maladeRaw = p.malade
  if (maladeRaw === 1 || maladeRaw === '1' || maladeRaw === true) {
    formData.value.aUneMaladie = 'oui'
  } else if (maladeRaw === 0 || maladeRaw === '0' || maladeRaw === false) {
    formData.value.aUneMaladie = 'non'
  } else {
    formData.value.aUneMaladie = ''
  }
  formData.value.maladieDetail = p.detail_malade || ''
  hydrateMaladiesFromDetail(formData.value.maladieDetail)
  formData.value.photo = null
  formData.value.photoId = null
  formData.value.numeroWave = p.numero_wave || ''
  formData.value.transport = p.transport || 'convoi'
  formData.value.niveau_annee_passee = p.niveau_actuel
  formData.value.participant_trouve = true
  formData.value.a_participe_senafoi = true
  formData.value.decision_participant = p.decision_participant
  formData.value.niveauSeminaire = getNouveauNiveau(p)
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
  hasStartedInscription.value = true
  showResultsTable.value = false
  showParticipationChoice.value = false
  showLevelSelection.value = false
  showParticipantSelection.value = false
  showProfileView.value = false
  currentStep.value = 1
  scrollToCurrentStep()
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

// Upload d'image
const uploadImage = async (event) => {
  const file = event.target.files[0]
  if (!file) return
  isUploading.value = true
  uploadProgress.value = 0
  uploadError.value = ''
  try {
    const uploadFormData = new FormData()
    uploadFormData.append('file', file)
    uploadFormData.append('folder', 'senafoi')
    uploadFormData.append('subfolder', 'photos')
    const response = await axios.post(UPLOAD_URL, uploadFormData, {
      headers: { 'X-API-Key': 'SAKONE25_04_2026_medias' },
      onUploadProgress: (e) => {
        uploadProgress.value = Math.round(e.loaded * 100 / e.total)
      }
    })
    if (response.data.success) {
      formData.value.photo = response.data.url
      formData.value.photoId = response.data.public_id
    } else {
      throw new Error(response.data.error || 'Erreur serveur')
    }
  } catch (error) {
    console.error('Erreur upload:', error)
    uploadError.value = 'Erreur lors du téléchargement. Veuillez réessayer.'
  } finally {
    isUploading.value = false
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

const formatLocalPhone = (value, shouldGroup = false) => {
  let digits = String(value || '').replace(/\D/g, '')
  if (digits.startsWith('00225')) digits = digits.slice(5)
  if (digits.startsWith('225') && digits.length > 10) digits = digits.slice(3)
  digits = digits.slice(0, 10)
  if (!shouldGroup) return digits
  return digits.replace(/(\d{2})(?=\d)/g, '$1 ').trim()
}

const syncPhoneField = (event, field) => {
  const shouldGroup = event.type === 'blur'
  formData.value[field] = formatLocalPhone(event.target.value, shouldGroup)
}

const formatPhoneNumber = (event) => {
  syncPhoneField(event, 'contact')
}

const formatPhoneNumberParent = (event) => {
  syncPhoneField(event, 'contactParent')
}

const formatWaveNumber = (event) => {
  syncPhoneField(event, 'numeroWave')
}

// Fonctions de navigation
const scrollToCurrentStep = async () => {
  await nextTick()
  const target = document.querySelector('.steps-header') || document.querySelector('.form-content')
  if (target) {
    target.scrollIntoView({ behavior: 'smooth', block: 'start' })
  } else {
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const nextStep = () => {
  if (currentStep.value < totalSteps.value) {
    currentStep.value++
    scrollToCurrentStep()
  }
}

const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
    scrollToCurrentStep()
  }
}

// Fonction de soumission
const soumettreInscription = async () => {
  if (!canSubmit.value) return
  isSubmitting.value = true
  try {
    const dataToSend = {
      amount: getTotalPrice(),
      currency: 'XOF',
      data: {
        ...formData.value,
        secretariatRegional: formData.value.ville
      }
    }
    const response = await axios.post(`${API_BASE_URL}/inscription_seminaire.php`, dataToSend)
    if (response.data.success && response.data.wave_launch_url) {
      window.location.href = response.data.wave_launch_url
    } else {
      throw new Error(response.data.message || 'Erreur inattendue')
    }
  } catch (error) {
    console.error("Erreur lors de l'inscription:", error)
    alert("Une erreur est survenue lors de l'inscription. Veuillez réessayer.")
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
- Type d'inscription: ${isAncienParticipant.value ? 'Ancien participant SENAFOI 2025' : 'Nouveau participant'}
${isAncienParticipant.value ? `- Niveau précédent: ${formData.value.niveau_annee_passee}
- Décision précédente: ${formData.value.decision_participant}` : ''}

Paiement:
- Montant total: ${getTotalPrice()} FCFA

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
  hasStartedInscription.value = false
  selectedMaladies.value = []
  autreMaladie.value = ''
  Object.keys(formData.value).forEach(key => {
    if (typeof formData.value[key] === 'string') {
      formData.value[key] = ''
    } else if (typeof formData.value[key] === 'boolean') {
      formData.value[key] = false
    } else {
      formData.value[key] = null
    }
  })
  formData.value.niveauSeminaire = 'TEST_ENTREE'
  formData.value.transport = 'convoi'
}

onMounted(async () => {
  await Promise.all([fetchTarifs(), fetchQuota()])
  quotaRefreshTimer.value = window.setInterval(fetchQuota, 20000)
  // Le décompte bat à la seconde et bascule tout seul sur le message de clôture
  minuteurCloture = window.setInterval(() => { maintenant.value = Date.now() }, 1000)
})

onUnmounted(() => {
  if (quotaRefreshTimer.value) {
    window.clearInterval(quotaRefreshTimer.value)
  }
  if (minuteurCloture) {
    window.clearInterval(minuteurCloture)
  }
})
</script>

<style scoped>
* { margin: 0; padding: 0; box-sizing: border-box; }

.blank-registration-page {
  min-height: 100vh;
  background: #fff;
}

.app-container {
  min-height: 100vh;
  background: linear-gradient(160deg, var(--green-mist) 0%, var(--surface) 100%);
  font-family: var(--display);
}

/* ---- Header ---- */
.app-header {
  background: var(--green);
  box-shadow: var(--shadow);
}
.header-content {
  max-width: 960px;
  margin: 0 auto;
  padding: .85rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.logo { display: flex; align-items: center; gap: .75rem; }
.logo-icon {
  width: 2.4rem; height: 2.4rem;
  background: rgba(255,255,255,0.2);
  color: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
}
.logo-text h1 { font-size: 1.35rem; font-weight: 700; color: #fff; }
.logo-text p { font-size: .75rem; color: rgba(255,255,255,.8); }
.header-right { display: flex; align-items: center; gap: .75rem; }
.year-badge {
  background: #fff;
  color: var(--green);
  font-weight: 800;
  font-size: .85rem;
  padding: .3rem .9rem;
  border-radius: 0;
  letter-spacing: 2px;
}
.step-progress { text-align: right; }
.step-info { font-size: .75rem; color: rgba(255,255,255,.8); margin-bottom: .25rem; }
.progress-bar { width: 8rem; height: .4rem; background: rgba(255,255,255,.3); border-radius: 0; overflow: hidden; }
.progress-fill { height: 100%; background: #fff; transition: width .3s ease; }

/* ---- Main Content ---- */
.main-content {
  max-width: 960px;
  margin: 0 auto;
  padding: 2rem 1rem;
}
.form-wrapper { }
.quota-strip {
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: .85rem 1rem;
  background: var(--green-mist);
  border: 1px solid var(--green-mist);
  border-radius: 0;
  color: var(--green);
}
.quota-strip-full {
  background: #fef2f2;
  border-color: #fecaca;
  color: #991b1b;
}
.quota-strip div {
  display: flex;
  align-items: baseline;
  gap: .5rem;
}
.quota-strip span {
  font-size: .82rem;
  font-weight: 700;
  text-transform: uppercase;
}
.quota-strip strong {
  font-size: 1.8rem;
  line-height: 1;
}
.quota-strip small {
  color: var(--ink-soft);
  font-size: .82rem;
}

/* ---- Welcome Card ---- */
.welcome-card {
  background: #fff;
  border-radius: 0;
  box-shadow: var(--shadow);
  overflow: hidden;
}
.senafoi-banner-hero {
  background: #fff;
  padding: clamp(.75rem, 2vw, 1.15rem);
  border-bottom: 1px solid var(--green-mist);
}
.senafoi-banner-img {
  display: block;
  width: 100%;
  height: auto;
  max-height: 260px;
  object-fit: contain;
  border: 3px solid var(--green);
  border-radius: 0;
  box-shadow: var(--shadow);
}
.welcome-hero {
  background: linear-gradient(145deg, var(--green) 0%, var(--green) 60%, var(--green) 100%);
  padding: 2rem;
  display: flex;
  align-items: flex-start;
  gap: 1.25rem;
}
.welcome-icon {
  width: 60px; height: 60px; flex-shrink: 0;
  background: rgba(255,255,255,.18);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff;
}
.welcome-text h1 { font-size: 1.9rem; font-weight: 700; color: #fff; margin-bottom: .3rem; }
.welcome-text p { font-size: .95rem; color: rgba(255,255,255,.85); line-height: 1.5; }
/* ── Compte à rebours de clôture, logé dans le panneau du quota ──
   Le chronomètre est plus long que le nombre de places : on réduit sa taille
   pour qu'il tienne sur la même ligne sans faire grossir le bandeau. */
.cloture-chrono {
  padding: .55rem .8rem;
  border-radius: 0;
  background: var(--gold-mist);
  border: 1px solid #fed7aa;
}
.cloture-chrono span {
  font-size: 1.75rem !important;
  color: #c2410c;
  font-variant-numeric: tabular-nums;
  letter-spacing: -.01em;
  white-space: nowrap;
}
.cloture-chrono small { color: var(--gold) !important; }
.cloture-chrono--fini {
  background: #fef2f2;
  border-color: #fecaca;
}
.cloture-chrono--fini span,
.cloture-chrono--fini small { color: #b91c1c !important; }

.quota-panel {
  margin: 1.5rem auto 0;
  max-width: 680px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.25rem;
  padding: 1rem 1.25rem;
  border: 1px solid var(--green-mist);
  border-radius: 0;
  background: var(--green-mist);
}
.quota-panel-full {
  border-color: #fecaca;
  background: #fef2f2;
}
.quota-copy {
  display: flex;
  flex-direction: column;
  gap: .25rem;
  text-align: left;
}
.quota-copy strong {
  color: var(--green);
  font-size: 1.05rem;
}
.quota-copy small {
  color: var(--ink-soft);
  font-size: .82rem;
}
.quota-label {
  color: var(--green);
  font-size: .78rem;
  font-weight: 800;
  text-transform: uppercase;
}
.quota-panel-full .quota-label,
.quota-panel-full .quota-copy strong {
  color: #991b1b;
}
.quota-available {
  min-width: 150px;
  text-align: center;
  color: var(--green);
  flex: none;
}
.quota-available span {
  display: block;
  font-size: 3.2rem;
  line-height: 1;
  font-weight: 900;
}
.quota-available small {
  display: block;
  margin-top: .2rem;
  color: var(--green);
  font-size: .8rem;
  font-weight: 700;
}
.quota-panel-full .quota-available,
.quota-panel-full .quota-available small {
  color: #991b1b;
}
.quota-closed {
  padding: 1.1rem;
  border: 1px solid #fecaca;
  border-radius: 0;
  background: #fef2f2;
  color: #991b1b;
  text-align: left;
}
.quota-closed strong,
.quota-closed span {
  display: block;
}
.quota-closed span {
  margin-top: .25rem;
  color: #7f1d1d;
  font-size: .88rem;
  line-height: 1.5;
}
.choice-section { padding: 2rem; text-align: center; }
.choice-section h3 { font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: .5rem; }
.choice-subtitle { font-size: .95rem; color: var(--muted); margin-bottom: 2rem; }
.choice-buttons { display: flex; flex-direction: column; gap: 1rem; max-width: 500px; margin: 0 auto; }
.choice-btn {
  display: flex; align-items: center; gap: 1rem;
  padding: 1.25rem;
  border: 2px solid var(--line);
  border-radius: 0;
  background: #fff;
  cursor: pointer;
  transition: all .2s;
  text-align: left;
}
.choice-btn:hover { border-color: var(--green); background: var(--green-mist); }
.choice-btn-primary:hover { border-color: var(--green); }
.choice-btn-secondary:hover { border-color: #0284c7; }
.choice-icon {
  width: 50px; height: 50px; border-radius: 0;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.choice-icon-primary { background: var(--green-mist); color: var(--green); }
.choice-icon-secondary { background: #e0f2fe; color: #0284c7; }
.choice-content { flex: 1; }
.choice-title { display: block; font-size: 1rem; font-weight: 700; color: var(--ink); margin-bottom: .25rem; }
.choice-desc { display: block; font-size: .85rem; color: var(--muted); }
.choice-arrow { color: var(--muted); flex-shrink: 0; }
.welcome-footer {
  background: var(--green);
  padding: .85rem 1.5rem;
  text-align: center;
}
.welcome-footer p { font-size: .72rem; color: rgba(255,255,255,.75); line-height: 1.8; }
.footer-slogan { color: #fff !important; font-weight: 700; font-size: .8rem !important; }

/* ---- Step Card ---- */
.step-card, .profile-card, .results-card {
  background: #fff;
  border-radius: 0;
  box-shadow: var(--shadow);
  overflow: hidden;
}
.step-hero, .profile-hero, .results-hero {
  background: linear-gradient(145deg, var(--green) 0%, var(--green) 60%, var(--green) 100%);
  padding: 1.5rem 2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}
.step-icon, .results-icon {
  width: 50px; height: 50px; flex-shrink: 0;
  background: rgba(255,255,255,.18);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff;
}
.step-hero-text h2, .results-hero-text h2 { font-size: 1.4rem; font-weight: 700; color: #fff; margin-bottom: .2rem; }
.step-hero-text p, .results-hero-text p { font-size: .85rem; color: rgba(255,255,255,.8); }

/* ---- Sections ---- */
.participation-section, .level-section, .participant-section, .results-section { padding: 2rem; }
.section-subtitle { font-size: .95rem; color: var(--muted); text-align: center; margin-bottom: 2rem; }
.participation-buttons { display: flex; flex-direction: column; gap: 1rem; max-width: 450px; margin: 0 auto; }
.participation-btn {
  display: flex; align-items: center; gap: 1rem;
  padding: 1.25rem;
  border: 2px solid var(--line);
  border-radius: 0;
  background: #fff;
  cursor: pointer;
  transition: all .2s;
  text-align: left;
  width: 100%;
}
.participation-btn:hover { border-color: var(--green); background: var(--green-mist); }
.part-icon {
  width: 44px; height: 44px; border-radius: 0;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.part-icon-yes { background: var(--green-mist); color: var(--green); }
.part-icon-no { background: var(--gold-mist); color: var(--gold); }
.part-content { flex: 1; }
.part-title { display: block; font-size: 1rem; font-weight: 700; color: var(--ink); margin-bottom: .2rem; }
.part-desc { display: block; font-size: .8rem; color: var(--muted); }

/* ---- Level Grid ---- */
.level-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; max-width: 400px; margin: 0 auto 2rem; }
.level-btn {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 1.25rem;
  border: 2px solid var(--line);
  border-radius: 0;
  background: #fff;
  cursor: pointer;
  transition: all .2s;
}
.level-btn:hover { border-color: #0284c7; background: #f0f9ff; }
.level-btn-selected { border-color: #0284c7; background: #0284c7; }
.level-btn-selected .level-number, .level-btn-selected .level-label { color: #fff; }
.level-number { font-size: 1.5rem; font-weight: 800; color: var(--ink); }
.level-label { font-size: .7rem; color: var(--muted); text-transform: uppercase; }
.level-actions { text-align: center; }

/* ---- Participants List ---- */
.search-box { position: relative; margin-bottom: 1.5rem; }
.search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
.search-input {
  width: 100%;
  padding: .85rem 1rem .85rem 3rem;
  border: 2px solid var(--line);
  border-radius: 0;
  font-size: .95rem;
  transition: all .2s;
}
.search-input:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
.participants-list { max-height: 350px; overflow-y: auto; border: 1px solid var(--line); border-radius: 0; margin-bottom: 1.5rem; }
.participant-item {
  display: flex; align-items: center; gap: 1rem;
  padding: 1rem;
  border-bottom: 1px solid #f3f4f6;
  cursor: pointer;
  transition: all .2s;
}
.participant-item:hover { background: var(--surface); }
.participant-item:last-child { border-bottom: none; }
.participant-selected { background: var(--green-mist); border-color: var(--green); }
.participant-avatar {
  width: 44px; height: 44px;
  background: linear-gradient(135deg, var(--green), #0284c7);
  color: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .9rem; font-weight: 700;
  flex-shrink: 0;
}
.participant-info { flex: 1; }
.participant-name { font-size: .95rem; font-weight: 600; color: var(--ink); margin-bottom: .25rem; }
.participant-badges { display: flex; gap: .5rem; }
.participant-check { color: var(--green); }

/* ---- Badges ---- */
.badge {
  display: inline-block;
  padding: .2rem .6rem;
  border-radius: 0;
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
}
.badge-niveau { background: #e0f2fe; color: #0369a1; }
.badge-admis { background: var(--green-mist); color: var(--green); }
.badge-recale { background: var(--gold-mist); color: #92400e; }
.badge-exclus { background: #fecaca; color: #991b1b; }

/* ---- Profile Card ---- */
.profile-hero {
  padding: 2rem;
  flex-direction: column;
  align-items: center;
  text-align: center;
}
.profile-avatar-large {
  width: 90px; height: 90px;
  background: linear-gradient(135deg, var(--green), #0284c7);
  color: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; font-weight: 700;
  margin-bottom: 1rem;
  overflow: hidden;
}
.profile-avatar-large img { width: 100%; height: 100%; object-fit: cover; }
.profile-hero-info h2 { font-size: 1.4rem; font-weight: 700; color: #fff; margin-bottom: .5rem; }
.profile-badges { display: flex; gap: .5rem; justify-content: center; }
.new-level-banner {
  background: var(--gold-mist);
  padding: .85rem 1.5rem;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  color: #92400e;
  font-size: .9rem;
  border-bottom: 1px solid #fde68a;
}
.new-level-banner svg { color: var(--gold); }
.profile-details-section { border-top: 1px solid var(--green-mist); }
.section-header {
  background: var(--green-mist);
  padding: .65rem 1.75rem;
  display: flex; align-items: center; gap: 8px;
  border-bottom: 1px solid var(--green-mist);
  color: var(--green);
}
.section-header svg { fill: var(--green); }
.section-header span { font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.profile-grid { display: grid; grid-template-columns: 1fr 1fr; }
.profile-item {
  display: flex; flex-direction: column; gap: 2px;
  padding: .9rem 1.75rem;
  border-bottom: 1px solid var(--surface);
}
.profile-item:nth-child(odd) { border-right: 1px solid var(--surface); }
.profile-label { font-size: .68rem; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
.profile-value { font-size: .88rem; color: var(--ink); font-weight: 600; }
.profile-note {
  display: flex; align-items: flex-start; gap: .75rem;
  padding: 1rem 1.75rem;
  background: var(--green-mist);
  color: var(--green);
  font-size: .85rem;
}
.profile-note svg { flex-shrink: 0; margin-top: 2px; }
.profile-actions {
  display: flex; gap: 1rem; justify-content: center;
  padding: 1.5rem;
  border-top: 1px solid var(--green-mist);
}

/* ---- Results Table ---- */
.results-toolbar { margin-bottom: 1.5rem; }
.pagination-info {
  margin-bottom: 1rem;
  padding: .75rem 1rem;
  background: var(--surface);
  border-radius: 0;
  border: 1px solid var(--line);
}
.pagination-info p { font-size: .85rem; color: var(--ink-soft); margin: 0; }
.table-container {
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 0;
  overflow: hidden;
}
.results-table { width: 100%; border-collapse: collapse; }
.results-table thead { background: var(--surface); }
.results-table th {
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: var(--ink-soft);
  border-bottom: 2px solid var(--line);
  font-size: .8rem;
  text-transform: uppercase;
  letter-spacing: .5px;
}
.table-row { transition: background-color .2s; }
.table-row:hover { background: var(--surface); }
.table-row:nth-child(even) { background: var(--surface); }
.table-row:nth-child(even):hover { background: #f3f4f6; }
.table-cell { padding: 1rem; border-bottom: 1px solid var(--line); color: var(--ink-soft); font-size: .85rem; }
.bulletin-btn {
  display: inline-flex; align-items: center; gap: .35rem;
  padding: .4rem .75rem;
  background: linear-gradient(135deg, var(--green), #0284c7);
  color: #fff;
  border: none;
  border-radius: 0;
  font-size: .75rem; font-weight: 600;
  cursor: pointer;
  transition: all .2s;
  white-space: nowrap;
}
.bulletin-btn:hover:not(:disabled) { transform: translateY(-1px); box-shadow: var(--shadow); }
.bulletin-btn:disabled, .bulletin-btn-loading { opacity: .65; cursor: not-allowed; }
.bulletin-btn-full { width: 100%; justify-content: center; padding: .65rem 1rem; border-radius: 0; }

/* Mobile Cards */
.desktop-table { display: table; }
.mobile-cards { display: none; }
@media (max-width: 640px) {
  .desktop-table { display: none; }
  .mobile-cards { display: flex; flex-direction: column; gap: .75rem; padding: .75rem; }
  .result-card-mobile { background: #fff; border: 1px solid var(--line); border-radius: 0; padding: 1rem; }
  .result-card-header { display: flex; align-items: center; gap: .75rem; margin-bottom: .75rem; }
  .result-card-avatar { width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, var(--green), #0284c7); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 700; }
  .result-card-name { display: flex; flex-direction: column; gap: .1rem; }
  .result-card-nom { font-size: .95rem; font-weight: 700; color: var(--ink); }
  .result-card-prenom { font-size: .8rem; color: var(--muted); }
  .result-card-badges { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: .875rem; }
}

/* Pagination */
.pagination-controls {
  display: flex; justify-content: space-between; align-items: center;
  margin-top: 1.5rem; padding: 1rem;
  background: var(--surface); border-radius: 0; border: 1px solid var(--line);
  flex-wrap: wrap; gap: 1rem;
}
.pagination-buttons { display: flex; align-items: center; gap: .5rem; }
.pagination-numbers { display: flex; align-items: center; gap: .25rem; }
.pagination-btn {
  padding: .5rem .75rem;
  border: 1px solid #d1d5db;
  background: #fff;
  color: var(--ink-soft);
  border-radius: 0;
  font-size: .85rem; font-weight: 500;
  cursor: pointer;
  transition: all .2s;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.pagination-btn:hover:not(:disabled) { background: var(--surface); border-color: var(--muted); }
.pagination-btn:disabled { opacity: .5; cursor: not-allowed; background: #f3f4f6; }
.pagination-btn-active { background: var(--green); color: #fff; border-color: var(--green); }
.pagination-btn-active:hover { background: var(--green); border-color: var(--green); }
.pagination-ellipsis { padding: .5rem; color: var(--muted); font-weight: 500; }
.items-per-page { display: flex; align-items: center; gap: .5rem; }
.items-per-page label { font-size: .85rem; color: var(--ink-soft); font-weight: 500; }
.items-select { padding: .375rem .75rem; border: 1px solid #d1d5db; border-radius: 0; background: #fff; font-size: .85rem; cursor: pointer; }
.items-select:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
.results-actions {
  display: flex; justify-content: space-between; align-items: center;
  margin-top: 2rem; padding: 1.5rem;
  border-top: 1px solid var(--green-mist);
}

/* ---- Step Navigation ---- */
.step-nav { padding: 1.5rem 2rem; border-top: 1px solid var(--green-mist); }

/* ---- Steps Header (Form) ---- */
.steps-header { background: var(--green-mist); padding: 1.5rem 2rem; margin-bottom: -1px; border-radius: 0; }
.steps-nav { display: flex; justify-content: space-between; align-items: center; position: relative; }
.step-item { display: flex; flex-direction: column; align-items: center; position: relative; flex: 1; }
.step-number {
  width: 2rem; height: 2rem;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .85rem; font-weight: 600;
  margin-bottom: .5rem;
  transition: all .3s;
  border: 2px solid #d1d5db;
  background: #fff;
  color: var(--ink-soft);
}
.step-item.step-active .step-number { background: var(--green); color: #fff; border-color: var(--green); }
.step-item.step-completed .step-number { background: var(--green); color: #fff; border-color: var(--green); }
.step-title { font-size: .7rem; font-weight: 500; color: var(--ink-soft); text-align: center; }
.step-connector {
  position: absolute; top: 1rem; left: 60%; right: -40%;
  height: 2px; background: #d1d5db; z-index: 1;
}
.step-connector.connector-active { background: var(--green); }

/* ---- Form Content ---- */
.form-content {
  background: #fff;
  border-radius: 0;
  box-shadow: var(--shadow);
}
.form-step { padding: 2rem; }
.form-section-header {
  background: var(--green-mist);
  padding: .65rem 1rem;
  display: flex; align-items: center; gap: 8px;
  border-radius: 0;
  margin-bottom: 1.5rem;
  color: var(--green);
}
.form-section-header svg { fill: var(--green); }
.form-section-header span { font-size: .85rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.ancien-banner {
  display: flex; align-items: flex-start; gap: .75rem;
  padding: .875rem 1rem;
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  border-radius: 0;
  margin-bottom: 1.5rem;
  color: #1e40af;
  font-size: .85rem;
}
.ancien-banner svg { flex-shrink: 0; margin-top: 1px; }
.ancien-banner p { margin: 0; line-height: 1.5; }
.ancien-banner-success { background: var(--green-mist); border-color: var(--green-mist); color: var(--green); }
.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
.form-group { display: flex; flex-direction: column; }
.form-group-full { grid-column: 1 / -1; }
.form-label { font-size: .85rem; font-weight: 500; color: var(--ink-soft); margin-bottom: .5rem; }
.required { color: #dc2626; }
.form-input {
  width: 100%;
  padding: .75rem;
  border: 2px solid var(--line);
  border-radius: 0;
  font-size: .95rem;
  transition: all .2s;
  background: #fff;
}
.form-input:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
.birth-date-input {
  border-color: #2563eb;
}
.birth-date-input:focus {
  border-color: #1d4ed8;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, .14);
}
.input-readonly { background: var(--surface); color: var(--muted); cursor: not-allowed; }
.form-help { font-size: .75rem; color: var(--muted); margin-top: .25rem; }
.readonly-help { color: var(--green); font-style: italic; }

.maladie-options {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: .65rem;
}

.maladie-option,
.maladie-other {
  border: 2px solid var(--line);
  border-radius: 0;
  background: #fff;
  transition: all .2s;
}

.maladie-option {
  min-height: 44px;
  display: flex;
  align-items: center;
  gap: .55rem;
  padding: .7rem .8rem;
  cursor: pointer;
  color: var(--ink-soft);
  font-size: .86rem;
  font-weight: 600;
}

.maladie-option:hover,
.maladie-other:hover {
  border-color: var(--green);
  background: var(--green-mist);
}

.maladie-option input,
.maladie-other input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}

.maladie-check {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  flex-shrink: 0;
  transition: all .2s;
}

.maladie-option-selected,
.maladie-other-active {
  border-color: var(--green);
  background: var(--green-mist);
}

.maladie-option-selected .maladie-check,
.maladie-other-active .maladie-check {
  border-color: var(--green);
  background: var(--green);
}

.maladie-other {
  margin-top: .75rem;
  padding: .75rem .8rem;
}

.maladie-other-toggle {
  display: flex;
  align-items: center;
  gap: .55rem;
  cursor: pointer;
  color: var(--ink-soft);
  font-size: .86rem;
  font-weight: 700;
}

.maladie-other-input {
  margin-top: .75rem;
}

/* Radio Group */
.radio-group { display: flex; flex-direction: column; gap: .75rem; }
.radio-group-horizontal { flex-direction: row; gap: 1.5rem; }
.radio-item {
  display: flex; align-items: center;
  padding: .5rem .75rem;
  border: 2px solid var(--line);
  border-radius: 0;
  cursor: pointer;
  transition: all .2s;
  position: relative;
}
.radio-item:hover { background: var(--surface); border-color: var(--green); }
.radio-input { position: absolute; opacity: 0; cursor: pointer; }
.radio-custom {
  width: 1.25rem; height: 1.25rem;
  border: 2px solid #d1d5db;
  border-radius: 50%;
  margin-right: .75rem;
  position: relative;
  transition: all .2s;
  flex-shrink: 0;
}
.radio-input:checked + .radio-custom { border-color: var(--green); background: var(--green); }
.radio-input:checked + .radio-custom::after {
  content: '';
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: .5rem; height: .5rem;
  background: #fff;
  border-radius: 50%;
}
.radio-label { font-weight: 500; color: var(--ink-soft); }
.radio-item-readonly { opacity: .7; cursor: not-allowed; }

/* Transport Section */
.transport-section {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: var(--surface);
  border-radius: 0;
  border: 2px solid var(--line);
}
.transport-heading { font-size: 1.1rem; font-weight: 700; color: var(--ink); margin-bottom: .5rem; }
.transport-description { font-size: .9rem; color: var(--muted); margin-bottom: 1.5rem; }
.transport-option { padding: 1rem; margin-bottom: .75rem; background: #fff; }
.transport-option:has(.radio-input:checked) { border-color: var(--green); background: var(--green-mist); }
.transport-details { display: flex; flex-direction: column; gap: .15rem; margin-left: .5rem; }
.transport-title { font-weight: 600; color: var(--ink); font-size: .95rem; }
.transport-subtitle { font-size: .8rem; color: var(--muted); }
.transport-note {
  display: flex; align-items: flex-start; gap: .5rem;
  margin-top: 1rem;
  padding: 1rem;
  background: var(--gold-mist);
  border: 1px solid var(--gold);
  border-radius: 0;
  color: #92400e;
  font-size: .85rem;
}
.transport-note svg { color: var(--gold); flex-shrink: 0; margin-top: 2px; }

/* Payment */
.payment-info {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: var(--surface);
  border-radius: 0;
  border: 2px solid var(--line);
}
.price-box {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1rem;
  background: #fff;
  border-radius: 0;
  border: 1px solid var(--line);
}
.price-label { font-size: 1rem; font-weight: 600; color: var(--ink); }
.price-amount { font-size: 1.25rem; font-weight: 700; color: var(--green); }
.price-breakdown { margin-top: 1rem; padding: 1rem; background: #fff; border-radius: 0; border: 1px solid var(--line); }
.breakdown-item { display: flex; justify-content: space-between; padding: .5rem 0; border-bottom: 1px solid #f3f4f6; font-size: .85rem; color: var(--ink-soft); }
.breakdown-item:last-child { border-bottom: none; }
.payment-method { padding: 1.5rem; background: #fff; border-radius: 0; border: 2px solid var(--line); }
.wave-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
.wave-icon { width: 48px; height: 48px; border-radius: 0; overflow: hidden; }
.wave-icon img { width: 100%; height: 100%; object-fit: contain; }
.wave-info h3 { font-size: 1.1rem; font-weight: 600; color: var(--ink); margin: 0 0 .2rem 0; }
.wave-info p { font-size: .85rem; color: var(--muted); margin: 0; }

/* Photo Section */
.photo-section { display: flex; justify-content: center; margin-bottom: 2rem; }
.photo-upload { position: relative; width: 200px; height: 250px; }
.photo-input { display: none; }
.photo-placeholder {
  width: 100%; height: 100%;
  border: 2px dashed #d1d5db;
  border-radius: 0;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 1.5rem;
  cursor: pointer;
  transition: all .2s;
  background: var(--surface);
}
.photo-placeholder:hover { border-color: var(--green); background: var(--green-mist); }
.upload-icon { color: var(--muted); margin-bottom: 1rem; }
.photo-placeholder p { font-size: .85rem; font-weight: 500; color: var(--ink-soft); margin-bottom: .5rem; text-align: center; }
.photo-placeholder small { font-size: .75rem; color: var(--muted); text-align: center; }
.upload-loading {
  width: 100%; height: 100%;
  border: 2px solid #d1d5db;
  border-radius: 0;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 1.5rem;
  background: var(--surface);
}
.upload-progress-container { margin-bottom: 1rem; }
.progress-circle {
  position: relative;
  width: 60px; height: 60px;
  display: flex; align-items: center; justify-content: center;
}
.progress-ring-circle { transition: stroke-dashoffset .3s; transform: rotate(-90deg); transform-origin: 50% 50%; }
.progress-text { position: absolute; font-size: .85rem; font-weight: 600; color: var(--green); }
.upload-loading p { font-size: .85rem; color: var(--ink-soft); margin-bottom: .5rem; }
.upload-error { color: #dc2626; font-size: .75rem; text-align: center; margin-top: .5rem; }
.photo-preview {
  width: 100%; height: 100%;
  position: relative;
  border-radius: 0;
  overflow: hidden;
  border: 2px solid var(--green);
}
.preview-image { width: 100%; height: 100%; object-fit: cover; }
.change-photo, .remove-photo {
  position: absolute;
  width: 2rem; height: 2rem;
  border-radius: 50%;
  background: rgba(255,255,255,.9);
  border: none;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: all .2s;
}
.change-photo { top: .5rem; left: .5rem; color: var(--green); }
.remove-photo { top: .5rem; right: .5rem; color: #dc2626; }
.change-photo:hover, .remove-photo:hover { background: #fff; transform: scale(1.1); }

/* Navigation */
.form-navigation {
  display: flex; justify-content: space-between;
  padding: 1.5rem 2rem;
  border-top: 1px solid var(--line);
  background: var(--surface);
}

/* Buttons */
.btn {
  padding: .65rem 1.25rem;
  border-radius: 0;
  font-size: .85rem; font-weight: 600;
  cursor: pointer; transition: all .2s;
  border: 1.5px solid transparent;
  display: inline-flex; align-items: center; gap: .4rem;
  text-decoration: none;
}
.btn-primary { background: var(--green); color: #fff; border-color: var(--green); }
.btn-primary:hover:not(:disabled) { background: var(--green); border-color: var(--green); }
.btn-primary:disabled { opacity: .5; cursor: not-allowed; }
.btn-outline { background: transparent; color: var(--green); border-color: var(--green-mist); }
.btn-outline:hover { background: var(--green-mist); }
.btn-large { padding: .85rem 1.75rem; font-size: .95rem; }
.confirm-selection { margin-top: 1.5rem; text-align: center; }

/* States */
.loading-state, .error-state, .empty-state {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 3rem; text-align: center; color: var(--muted);
}
.loading-spinner {
  width: 3rem; height: 3rem;
  border: 4px solid var(--green-mist);
  border-top: 4px solid var(--green);
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}
.spinner-small {
  width: 18px; height: 18px;
  border: 2px solid rgba(255,255,255,.3);
  border-top: 2px solid #fff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
.error-icon-box {
  width: 4rem; height: 4rem;
  background: #fef2f2; color: #dc2626;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 1rem;
}
.loading-state p, .error-state p, .empty-state p { font-size: 1rem; font-weight: 500; margin-bottom: 1rem; }

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,.5);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000;
  padding: 1rem;
}
.modal-content {
  background: #fff;
  border-radius: 0;
  padding: 2rem;
  max-width: 500px;
  width: 100%;
  box-shadow: var(--shadow);
}
.modal-header { text-align: center; margin-bottom: 2rem; }
.success-icon-modal {
  width: 4rem; height: 4rem;
  background: var(--green-mist); color: var(--green);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1.5rem;
}
.modal-header h3 { font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: 1rem; }
.modal-header p { font-size: 1rem; color: var(--ink-soft); margin-bottom: .5rem; }
.matricule-info, .dortoir-info, .car-info {
  padding: .75rem;
  background: var(--green-mist);
  border-radius: 0;
  margin-top: 1rem;
  font-size: 1rem;
  color: var(--green);
}
.modal-actions { display: flex; gap: 1rem; }
.modal-actions .btn { flex: 1; justify-content: center; }

@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

/* Responsive */
@media (max-width: 768px) {
  .header-content { flex-wrap: wrap; gap: .5rem; }
  .form-grid { grid-template-columns: 1fr; }
  .maladie-options { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .profile-grid { grid-template-columns: 1fr; }
  .profile-item:nth-child(odd) { border-right: none; }
  .level-grid { grid-template-columns: repeat(2, 1fr); }
  .welcome-hero { flex-direction: column; }
  .step-hero, .profile-hero { flex-direction: column; text-align: center; }
  .steps-nav { overflow-x: auto; padding-bottom: 1rem; }
  .step-item { min-width: 80px; }
  .modal-actions { flex-direction: column; }
  .quota-panel, .quota-strip { flex-direction: column; align-items: stretch; text-align: center; }
  .quota-copy { text-align: center; }
  .quota-available { min-width: 0; }
  .quota-strip div { justify-content: center; }
  .senafoi-banner-img { max-height: 190px; border-radius: 0; }
}

@media (max-width: 480px) {
  .main-content { padding: 1rem; }
  .choice-section, .participation-section, .level-section { padding: 1.5rem; }
  .form-step { padding: 1.5rem; }
  .form-navigation { padding: 1rem; }
  .welcome-text h1 { font-size: 1.5rem; }
  .radio-group-horizontal { flex-direction: column; gap: .75rem; }
  .maladie-options { grid-template-columns: 1fr; }
  .year-badge { display: none; }
}

.loading-tarif {
  display: flex; align-items: center; gap: .5rem;
  font-size: .85rem; color: var(--muted); padding: 1rem;
}
.price-original {
  display: block; font-size: .8rem; color: var(--muted);
  text-decoration: line-through;
}
.price-promo { color: var(--green) !important; }
.promo-badge {
  display: inline-block;
  background: var(--green-mist); color: var(--green);
  font-size: .7rem; font-weight: 700;
  padding: .15rem .5rem; border-radius: 0;
  margin-left: .5rem;
}
.promo-section {
  margin-top: 1.5rem; padding: 1.25rem;
  background: var(--surface); border-radius: 0;
  border: 2px solid var(--line);
}
.promo-title {
  display: flex; align-items: center; gap: .4rem;
  font-size: .9rem; font-weight: 600; color: var(--ink-soft);
  margin-bottom: 1rem;
}
.promo-input-row { display: flex; gap: .75rem; }
.promo-input { flex: 1; text-transform: uppercase; letter-spacing: 1px; }
.promo-error { font-size: .8rem; color: #dc2626; margin-top: .5rem; }
.promo-success {
  display: flex; align-items: center; gap: .5rem;
  padding: .75rem 1rem;
  background: var(--green-mist); border: 1px solid var(--green-mist);
  border-radius: 0; color: var(--green); font-size: .85rem; font-weight: 500;
}
.promo-success svg { flex-shrink: 0; }
.promo-remove {
  margin-left: auto; background: none; border: none;
  cursor: pointer; color: #dc2626; font-weight: 700;
  padding: 0 .25rem; font-size: 1rem;
}

/* ===== PAYMENT REDESIGN ===== */
.payment-step { padding: 1.5rem !important; }

.payment-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-top: .5rem;
}

.pay-block {
  background: var(--surface);
  border: 1.5px solid var(--line);
  border-radius: 0;
  padding: 1rem;
  margin-bottom: .75rem;
}
.pay-block:last-child { margin-bottom: 0; }

.pay-block-title {
  font-size: .8rem;
  font-weight: 700;
  color: var(--ink-soft);
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: .75rem;
}

/* Transport Cards */
.transport-cards { display: flex; flex-direction: column; gap: .5rem; }
.transport-card {
  display: flex; align-items: center; gap: .6rem;
  padding: .65rem .85rem;
  border: 2px solid var(--line);
  border-radius: 0;
  background: #fff;
  cursor: pointer;
  transition: all .2s;
  position: relative;
}
.transport-card:hover { border-color: var(--green); }
.transport-card-active { border-color: var(--green); background: var(--green-mist); }
.transport-card .radio-input { position: absolute; opacity: 0; pointer-events: none; }
.tc-icon { font-size: 1.2rem; flex-shrink: 0; }
.tc-info { flex: 1; display: flex; flex-direction: column; }
.tc-title { font-size: .85rem; font-weight: 600; color: var(--ink); }
.tc-sub { font-size: .7rem; color: var(--muted); }
.tc-price { font-size: .8rem; font-weight: 700; color: var(--green); white-space: nowrap; }
.tc-note {
  margin-top: .5rem;
  font-size: .72rem;
  color: #92400e;
  background: var(--gold-mist);
  padding: .35rem .6rem;
  border-radius: 0;
}

/* Price summary */
.price-summary { background: var(--green-mist); border-color: var(--green-mist); }
.price-row { display: flex; align-items: center; justify-content: space-between; }
.price-row-label { font-size: .85rem; font-weight: 600; color: var(--ink-soft); }
.price-row-amount { display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; justify-content: flex-end; }
.price-crossed { font-size: .75rem; color: var(--muted); text-decoration: line-through; }
.price-final { font-size: 1.2rem; font-weight: 800; color: var(--green); }
.promo-tag {
  background: var(--green-mist); color: var(--green);
  font-size: .65rem; font-weight: 700;
  padding: .1rem .4rem; border-radius: 0;
}

/* Wave compact */
.wave-block { background: #fff; }
.wave-compact-header { display: flex; align-items: center; gap: .6rem; }
.wave-logo { border-radius: 0; }
.wave-compact-title { font-size: .85rem; font-weight: 700; color: var(--ink); margin: 0; }
.wave-compact-sub { font-size: .7rem; color: var(--muted); margin: 0; }

/* Promo toggle */
.promo-block { background: var(--gold-mist); border-color: #fde68a; }
.promo-toggle-label {
  display: flex; align-items: center; gap: .6rem;
  cursor: pointer;
  font-size: .85rem; font-weight: 600; color: #92400e;
}
.promo-checkbox { display: none; }
.promo-toggle-custom {
  width: 18px; height: 18px; flex-shrink: 0;
  border: 2px solid var(--gold);
  border-radius: 0;
  background: #fff;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s;
}
.promo-checkbox:checked + .promo-toggle-custom {
  background: var(--gold); border-color: var(--gold);
}
.promo-checkbox:checked + .promo-toggle-custom::after {
  content: '✓';
  color: #fff;
  font-size: .75rem;
  font-weight: 700;
  line-height: 1;
}
.promo-toggle-text { flex: 1; }
.promo-field-wrap { margin-top: .75rem; }
.promo-applied {
  display: flex; align-items: center; gap: .5rem;
  padding: .5rem .75rem;
  background: var(--green-mist); border: 1px solid var(--green-mist);
  border-radius: 0; color: var(--green);
  font-size: .8rem; font-weight: 600;
}
.promo-applied svg { flex-shrink: 0; color: var(--green); }
.promo-remove-sm {
  margin-left: auto; background: none; border: none;
  cursor: pointer; color: #dc2626; font-weight: 700; font-size: .9rem;
}
.promo-input-row { display: flex; gap: .5rem; }
.promo-input { flex: 1; text-transform: uppercase; letter-spacing: 1px; padding: .6rem .75rem !important; }
.btn-sm { padding: .5rem .85rem !important; font-size: .8rem !important; }

/* Responsive payment */
@media (max-width: 640px) {
  .payment-layout { grid-template-columns: 1fr; }
}
</style>
