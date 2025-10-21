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
              <p>Espace Organes - Gestion des Comptes Organisationnels</p>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <div class="form-container">
        <!-- Page d'accueil avec les deux boutons -->
        <div v-if="currentView === 'home'" class="welcome-section">
          <div class="welcome-header">
            <h2>Espace Organes</h2>
            <p>Accédez à votre espace de gestion organisationnelle</p>
          </div>
          
          <div class="action-buttons">
            <button @click="goToLogin" class="btn btn-primary btn-large">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Se connecter
            </button>
            
            <button @click="startAccountCreation" class="btn btn-secondary btn-large">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
              </svg>
              Créer un compte organe
            </button>
          </div>
        </div>

        <!-- Résumé après création de compte -->
        <div v-if="currentView === 'summary'" class="summary-section">
          <div class="summary-header">
            <div class="success-icon">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" class="text-green-500">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <h2>Compte créé avec succès !</h2>
            <p>Voici le résumé de votre compte organisationnel</p>
          </div>

          <div class="summary-content">
            <div class="summary-card">
              <h3>Informations de connexion</h3>
              <div class="login-credentials">
                <div class="credential-item">
                  <label>Identifiant (Matricule) :</label>
                  <span class="credential-value">{{ accountSummary.matricule_aeemci }}</span>
                </div>
                <div class="credential-item">
                  <label>Mot de passe :</label>
                  <span class="credential-value password">{{ accountSummary.password }}</span>
                </div>
              </div>
            </div>

            <div class="summary-card">
              <h3>Informations personnelles</h3>
              <div class="info-grid">
                <div class="info-item">
                  <label>Nom complet :</label>
                  <span>{{ accountSummary.nom_utilisateur }}</span>
                </div>
                <div class="info-item">
                  <label>Contact :</label>
                  <span>{{ accountSummary.contact }}</span>
                </div>
                <div class="info-item">
                  <label>Poste actuel :</label>
                  <span>{{ accountSummary.poste_actuel }}</span>
                </div>
              </div>
            </div>

            <div class="summary-card">
              <h3>Informations organisationnelles</h3>
              <div class="info-grid">
                <div class="info-item">
                  <label>Type d'organe :</label>
                  <span>{{ accountSummary.type_organe_display }}</span>
                </div>
                <div class="info-item">
                  <label>Organe assigné :</label>
                  <span>{{ accountSummary.nom_organe }}</span>
                </div>
                <div class="info-item">
                  <label>Poste :</label>
                  <span>{{ accountSummary.poste }}</span>
                </div>
                <div class="info-item">
                  <label>Date de nomination :</label>
                  <span>{{ formatDate(accountSummary.date_nomination) }}</span>
                </div>
                <div class="info-item">
                  <label>Statut :</label>
                  <span class="status-active">{{ accountSummary.statut }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="summary-actions">
            <button @click="downloadPDF" class="btn btn-primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
              </svg>
              Télécharger en PDF
            </button>
            <button @click="goHome" class="btn btn-secondary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z"/>
              </svg>
              Retour à l'accueil
            </button>
          </div>
        </div>

        <!-- Formulaire de création de compte -->
        <div v-if="currentView === 'create'" class="form-content">
          <div class="form-header">
            <button @click="goHome" class="back-btn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
              </svg>
              Retour
            </button>
            <h2>Création de compte organe</h2>
          </div>

          <form @submit.prevent="handleSubmit">
            <!-- Étape 1: Type d'organe -->
            <div v-if="step === 1" class="step-content">
              <h3 class="step-title">Type d'organe</h3>
              <div class="form-group">
                <label class="form-label">Sélectionnez le type d'organe <span class="required">*</span></label>
                <!-- Remplacé select par autocomplete -->
                <div class="autocomplete-container">
                  <input 
                    v-model="typeOrganeSearch" 
                    @input="filterTypeOrgane"
                    @focus="handleTypeOrganeFocus"
                    @click="handleTypeOrganeClick"
                    @blur="hideTypeOrganeDropdown"
                    type="text" 
                    class="form-input autocomplete-input"
                    :class="{ 'mobile-autocomplete': isMobile }"
                    placeholder="Cliquez pour voir les options ou tapez pour rechercher..."
                    :readonly="isMobile"
                    required
                  />
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 10l5 5 5-5z"/>
                  </svg>
                  <div
                    v-if="!isMobile && showTypeOrganeDropdown && filteredTypeOrganes.length"
                    class="autocomplete-dropdown"
                  >
                    <div 
                      v-for="type in filteredTypeOrganes" 
                      :key="type.value"
                      @mousedown="selectTypeOrgane(type)"
                      class="autocomplete-item"
                    >
                      {{ type.label }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Debug info -->
              <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; font-size: 12px;">
                <strong>Debug:</strong> formData.typeOrgane = "{{ formData.typeOrgane }}"
              </div>

              <!-- Ajout du choix de secrétariat pour sous-comité et section -->
              <div v-if="formData.typeOrgane && (formData.typeOrgane === 'sous-comite' || formData.typeOrgane === 'section')" class="form-group">
                <label class="form-label">Sélectionnez d'abord votre secrétariat <span class="required">*</span></label>
                <!-- Remplacé select par autocomplete -->
                <div class="autocomplete-container">
                  <input 
                    v-model="secretariatSearch" 
                    @input="filterSecretariat"
                    @focus="handleSecretariatFocus"
                    @click="handleSecretariatClick"
                    @blur="hideSecretariatDropdown"
                    type="text" 
                    class="form-input autocomplete-input"
                    :class="{ 'mobile-autocomplete': isMobile }"
                    placeholder="Cliquez pour voir les options ou tapez pour rechercher..."
                    :readonly="isMobile"
                    required
                  />
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 10l5 5 5-5z"/>
                  </svg>
                  <div
                    v-if="!isMobile && showSecretariatDropdown && filteredSecretariats.length"
                    class="autocomplete-dropdown"
                  >
                    <div 
                      v-for="secretariat in filteredSecretariats" 
                      :key="secretariat.id"
                      @mousedown="selectSecretariat(secretariat)"
                      class="autocomplete-item"
                    >
                      {{ secretariat.nom }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Liste des organes selon le type -->
              <div v-if="shouldShowOrganeSpecifique()" class="form-group">
                <label class="form-label">Sélectionnez votre {{ getOrganeLabel() }} <span class="required">*</span></label>
                <!-- Remplacé select par autocomplete -->
                <div class="autocomplete-container">
                  <input 
                    v-model="organeSearch" 
                    @input="filterOrganes"
                    @focus="handleOrganeFocus"
                    @click="handleOrganeClick"
                    @blur="hideOrganeDropdown"
                    type="text" 
                    class="form-input autocomplete-input"
                    :class="{ 'mobile-autocomplete': isMobile }"
                    :placeholder="`Cliquez pour voir les options ou tapez pour rechercher ${getOrganeLabel().toLowerCase()}...`"
                    :readonly="isMobile"
                    required
                  />
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 10l5 5 5-5z"/>
                  </svg>
                  <div
                    v-if="!isMobile && showOrganeDropdown && filteredOrganes.length"
                    class="autocomplete-dropdown"
                  >
                    <div 
                      v-for="organe in filteredOrganes" 
                      :key="organe.id"
                      @mousedown="selectOrgane(organe)"
                      class="autocomplete-item"
                    >
                      {{ organe.nom }}
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="formData.organeSpecifique" class="navigation-buttons">
                <button type="button" @click="nextStep" class="btn btn-primary">
                  Suivant
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Étape 2: Autorisation -->
            <div v-if="step === 2" class="step-content">
              <h3 class="step-title">Autorisation</h3>
              <div class="info-box">
                <div class="info-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                  </svg>
                </div>
                <div>
                  <h4>Autorisation requise</h4>
                  <p>Créez-vous ce compte avec l'autorisation du premier responsable de <strong>{{ formData.organeSpecifique?.nom }}</strong> ?</p>
                </div>
              </div>

              <div class="form-group">
                <div class="radio-group">
                  <label class="radio-item">
                    <input v-model="formData.autorise" type="radio" value="oui" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Oui, j'ai l'autorisation</span>
                  </label>
                  <label class="radio-item">
                    <input v-model="formData.autorise" type="radio" value="non" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Non, je n'ai pas l'autorisation</span>
                  </label>
                </div>
              </div>

              <div v-if="formData.autorise === 'non'" class="error-box">
                <p>Vous devez obtenir l'autorisation du premier responsable avant de créer ce compte.</p>
              </div>

              <div class="navigation-buttons">
                <button type="button" @click="previousStep" class="btn btn-secondary">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6-1.41-1.41L10.83 12z"/>
                  </svg>
                  Précédent
                </button>
                <button 
                  v-if="formData.autorise === 'oui'" 
                  type="button" 
                  @click="nextStep" 
                  class="btn btn-primary"
                >
                  Suivant
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Étape 3: Matricule -->
            <div v-if="step === 3" class="step-content">
              <h3 class="step-title">Matricule AEEMCI</h3>
              <div class="form-group">
                <label class="form-label">Votre matricule AEEMCI <span class="required">*</span></label>
                <div class="matricule-input-group">
                  <input 
                    v-model="formData.matricule" 
                    @input="formatMatricule"
                    type="text" 
                    class="form-input"
                    :class="{ 'input-error': matriculeError, 'input-success': userInfo }"
                    placeholder="IND-ABE-20-001"
                    maxlength="15"
                    required
                  />
                  <!-- Added verification button -->
                  <button 
                    type="button" 
                    @click="verifyMatricule"
                    class="btn btn-verify"
                    :disabled="!isMatriculeValid || isVerifying"
                  >
                    <div v-if="isVerifying" class="loading-spinner"></div>
                    <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    {{ isVerifying ? 'Vérification...' : 'Vérifier' }}
                  </button>
                </div>
                <!-- Mis à jour le format attendu -->
                <small class="form-help">Format attendu: IND-ABE-20-001 (3 lettres - 3 lettres - 2 chiffres - 2+ chiffres)</small>
                <div v-if="matriculeError" class="error-message">{{ matriculeError }}</div>
              </div>

              <!-- Informations utilisateur trouvées -->
              <div v-if="userInfo" class="user-info-card">
                <div class="user-photo">
                  <img v-if="userInfo.photo" :src="userInfo.photo" :alt="`Photo de ${userInfo.prenom} ${userInfo.nom}`" />
                  <div v-else class="photo-placeholder">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                  </div>
                </div>
                <div class="user-details">
                  <h4>{{ userInfo.prenom }} {{ userInfo.nom }}</h4>
                  <p><strong>Contact:</strong> {{ userInfo.contact }}</p>
                  <p><strong>Poste:</strong> {{ userInfo.poste }}</p>
                  <p><strong>Matricule:</strong> {{ formData.matricule }}</p>
                </div>
              </div>

              <div class="navigation-buttons">
                <button type="button" @click="previousStep" class="btn btn-secondary">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6-1.41-1.41L10.83 12z"/>
                  </svg>
                  Précédent
                </button>
                <button 
                  v-if="userInfo" 
                  type="submit" 
                  class="btn btn-primary"
                  :disabled="isCreating"
                >
                  <div v-if="isCreating" class="loading-spinner"></div>
                  <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  {{ isCreating ? 'Création...' : 'Créer le compte' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- Added mobile modals for select fields -->
    <!-- Modal Type d'Organe -->
    <div v-if="showTypeOrganeModal" class="mobile-overlay" @click="closeTypeOrganeModal">
      <div class="mobile-modal" @click.stop>
        <div class="mobile-modal-header">
          <h3>Choisir le Type d'Organe</h3>
          <button @click="closeTypeOrganeModal" class="modal-close-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </button>
        </div>
        <div class="mobile-modal-search">
          <input
            v-model="typeOrganeModalSearch"
            @input="filterTypeOrganeModal"
            type="text"
            class="modal-search-input"
            placeholder="Rechercher un type d'organe..."
            autofocus
          />
        </div>
        <div class="mobile-modal-list">
          <div
            v-for="type in filteredTypeOrganesModal"
            :key="type.value"
            @click="selectTypeOrganeFromModal(type)"
            class="modal-list-item"
          >
            {{ type.label }}
          </div>
          <div v-if="filteredTypeOrganesModal.length === 0" class="modal-no-results">
            Aucun résultat trouvé
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Secrétariat -->
    <div v-if="showSecretariatModal" class="mobile-overlay" @click="closeSecretariatModal">
      <div class="mobile-modal" @click.stop>
        <div class="mobile-modal-header">
          <h3>Choisir un Secrétariat</h3>
          <button @click="closeSecretariatModal" class="modal-close-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </button>
        </div>
        <div class="mobile-modal-search">
          <input
            v-model="secretariatModalSearch"
            @input="filterSecretariatModal"
            type="text"
            class="modal-search-input"
            placeholder="Rechercher un secrétariat..."
            autofocus
          />
        </div>
        <div class="mobile-modal-list">
          <div
            v-for="secretariat in filteredSecretariatsModal"
            :key="secretariat.id"
            @click="selectSecretariatFromModal(secretariat)"
            class="modal-list-item"
          >
            {{ secretariat.nom }}
          </div>
          <div v-if="filteredSecretariatsModal.length === 0" class="modal-no-results">
            Aucun résultat trouvé
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Organe -->
    <div v-if="showOrganeModal" class="mobile-overlay" @click="closeOrganeModal">
      <div class="mobile-modal" @click.stop>
        <div class="mobile-modal-header">
          <h3>Choisir {{ getOrganeLabel() }}</h3>
          <button @click="closeOrganeModal" class="modal-close-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </button>
        </div>
        <div class="mobile-modal-search">
          <input
            v-model="organeModalSearch"
            @input="filterOrganeModal"
            type="text"
            class="modal-search-input"
            :placeholder="`Rechercher ${getOrganeLabel().toLowerCase()}...`"
            autofocus
          />
        </div>
        <div class="mobile-modal-list">
          <div
            v-for="organe in filteredOrganesModal"
            :key="organe.id"
            @click="selectOrganeFromModal(organe)"
            class="modal-list-item"
          >
            {{ organe.nom }}
          </div>
          <div v-if="filteredOrganesModal.length === 0" class="modal-no-results">
            Aucun résultat trouvé
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'

// État de l'application
const currentView = ref('home') // 'home', 'create', 'login'
const step = ref(1)
const isVerifying = ref(false)
const isCreating = ref(false)
const showSuccess = ref(false)

const typeOrganeSearch = ref('')
const secretariatSearch = ref('')
const organeSearch = ref('')
const showTypeOrganeDropdown = ref(false)
const showSecretariatDropdown = ref(false)
const showOrganeDropdown = ref(false)
const filteredTypeOrganes = ref([])
const filteredSecretariats = ref([])
const filteredOrganes = ref([])

// Données du formulaire
const formData = ref({
  typeOrgane: '',
  secretariatParent: null, // Ajout du secrétariat parent
  organeSpecifique: null,
  autorise: '',
  matricule: ''
})

// Informations utilisateur et erreurs
const userInfo = ref(null)
const matriculeError = ref('')

const typeOrganes = [
  { value: 'secretariat', label: 'Secrétariat Régional' },
  { value: 'sous-comite', label: 'Sous-comité' },
  { value: 'section', label: 'Section' }
]

// Listes des organes
const secretariats = ref([])
const sousComites = ref([])
const sections = ref([])

const API_BASE_URL = 'https://sogetrag.com/api/organes-api.php'

const isMobile = ref(false)
const showTypeOrganeModal = ref(false)
const showSecretariatModal = ref(false)
const showOrganeModal = ref(false)

// Modal search states
const typeOrganeModalSearch = ref('')
const secretariatModalSearch = ref('')
const organeModalSearch = ref('')

// Modal filtered lists
const filteredTypeOrganesModal = ref([])
const filteredSecretariatsModal = ref([])
const filteredOrganesModal = ref([])

// Fonctions pour charger les données depuis l'API
const loadSecretariats = async () => {
  try {
    console.log('[v0] Loading secretariats from:', `${API_BASE_URL}?action=secretariats`)
    const response = await fetch(`${API_BASE_URL}?action=secretariats`)
    const data = await response.json()
    console.log('[v0] Secretariats response:', data)
    if (data.success) {
      secretariats.value = data.data.map(item => ({
        id: item.id_secretariat,
        nom: item.nom_secretariat,
        code: item.code_secretariat,
        region: item.region
      }))
      console.log('[v0] Secretariats loaded:', secretariats.value)
    } else {
      console.error('[v0] Error in secretariats response:', data.error)
    }
  } catch (error) {
    console.error('[v0] Erreur lors du chargement des secrétariats:', error)
  }
}

const loadSousComites = async (secretariatId) => {
  try {
    console.log('[v0] Loading sous-comites for secretariat:', secretariatId)
    const response = await fetch(`${API_BASE_URL}?action=sous_comites&secretariat_id=${secretariatId}`)
    const data = await response.json()
    console.log('[v0] Sous-comites response:', data)
    if (data.success) {
      sousComites.value = data.data.map(item => ({
        id: item.id_sous_comite,
        nom: item.nom_sous_comite,
        code: item.code_sous_comite,
        ville_commune: item.ville_commune
      }))
      console.log('[v0] Sous-comites loaded:', sousComites.value)
    } else {
      console.error('[v0] Error in sous-comites response:', data.error)
    }
  } catch (error) {
    console.error('[v0] Erreur lors du chargement des sous-comités:', error)
  }
}

const loadSections = async (secretariatId) => {
  try {
    console.log('[v0] Loading sections for secretariat:', secretariatId)
    const response = await fetch(`${API_BASE_URL}?action=sections&secretariat_id=${secretariatId}`)
    const data = await response.json()
    console.log('[v0] Sections response:', data)
    if (data.success) {
      sections.value = data.data.map(item => ({
        id: item.id_section,
        nom: item.nom_section,
        etablissement: item.etablissement,
        type_e: item.type_e,
        ville: item.ville,
        commune: item.commune
      }))
      console.log('[v0] Sections loaded:', sections.value)
    } else {
      console.error('[v0] Error in sections response:', data.error)
    }
  } catch (error) {
    console.error('[v0] Erreur lors du chargement des sections:', error)
  }
}

const filterTypeOrgane = () => {
  const search = typeOrganeSearch.value.toLowerCase()
  if (search === '') {
    filteredTypeOrganes.value = typeOrganes
  } else {
    filteredTypeOrganes.value = typeOrganes.filter(type => 
      type.label.toLowerCase().includes(search)
    )
  }
}

const filterSecretariat = () => {
  const search = secretariatSearch.value.toLowerCase()
  if (search === '') {
    filteredSecretariats.value = secretariats.value
  } else {
    filteredSecretariats.value = secretariats.value.filter(secretariat => 
      secretariat.nom.toLowerCase().includes(search)
    )
  }
}

const filterOrganes = () => {
  const search = organeSearch.value.toLowerCase()
  const organes = getOrganesList()
  if (search === '') {
    filteredOrganes.value = organes
  } else {
    filteredOrganes.value = organes.filter(organe => 
      organe.nom.toLowerCase().includes(search)
    )
  }
}

const selectTypeOrgane = (type) => {
  console.log('[v0] Selecting type organe:', type)
  formData.value.typeOrgane = type.value
  typeOrganeSearch.value = type.label
  showTypeOrganeDropdown.value = false
  
  formData.value.secretariatParent = null
  formData.value.organeSpecifique = null
  secretariatSearch.value = ''
  organeSearch.value = ''
  
  console.log('[v0] formData.typeOrgane set to:', formData.value.typeOrgane)
}

const selectSecretariat = (secretariat) => {
  formData.value.secretariatParent = secretariat
  secretariatSearch.value = secretariat.nom
  showSecretariatDropdown.value = false
  // Reset de l'organe spécifique
  formData.value.organeSpecifique = null
  organeSearch.value = ''
}

const selectOrgane = (organe) => {
  formData.value.organeSpecifique = organe
  organeSearch.value = organe.nom
  showOrganeDropdown.value = false
}

const hideTypeOrganeDropdown = () => {
  setTimeout(() => showTypeOrganeDropdown.value = false, 200)
}

const hideSecretariatDropdown = () => {
  setTimeout(() => showSecretariatDropdown.value = false, 200)
}

const hideOrganeDropdown = () => {
  setTimeout(() => showOrganeDropdown.value = false, 200)
}

// Méthodes
const goHome = () => {
  currentView.value = 'home'
  accountSummary.value = null
  resetForm()
}

const goToLogin = () => {
  // Redirection vers la page de connexion
  window.location.href = '/login_organe'
}

const startAccountCreation = () => {
  currentView.value = 'create'
  step.value = 1
}

const resetForm = () => {
  formData.value = {
    typeOrgane: '',
    secretariatParent: null,
    organeSpecifique: null,
    autorise: '',
    matricule: ''
  }
  typeOrganeSearch.value = ''
  secretariatSearch.value = ''
  organeSearch.value = ''
  userInfo.value = null
  matriculeError.value = ''
  step.value = 1
}

const nextStep = () => {
  if (step.value < 3) {
    step.value++
  }
}

const previousStep = () => {
  if (step.value > 1) {
    step.value--
    if (step.value === 2) {
      userInfo.value = null
      matriculeError.value = ''
    }
  }
}

const getOrganeLabel = () => {
  switch (formData.value.typeOrgane) {
    case 'secretariat': return 'Secrétariat Régional'
    case 'sous-comite': return 'Sous-comité'
    case 'section': return 'Section'
    default: return 'Organe'
  }
}

const shouldShowOrganeSpecifique = () => {
  if (formData.value.typeOrgane === 'secretariat') {
    return true
  }
  if ((formData.value.typeOrgane === 'sous-comite' || formData.value.typeOrgane === 'section') && formData.value.secretariatParent) {
    return true
  }
  return false
}

const getOrganesList = () => {
  let organes = []
  switch (formData.value.typeOrgane) {
    case 'secretariat': 
      organes = secretariats.value
      break
    case 'sous-comite': 
      organes = sousComites.value
      break
    case 'section': 
      organes = sections.value
      break
    default: 
      organes = []
  }
  console.log('[v0] getOrganesList for type:', formData.value.typeOrgane, 'returned:', organes)
  return organes
}

const formatMatricule = (event) => {
  let value = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '')
  
  // Format: XXX-XXX-XX-XXX+
  if (value.length > 3) {
    value = value.substring(0, 3) + '-' + value.substring(3)
  }
  if (value.length > 7) {
    value = value.substring(0, 7) + '-' + value.substring(7)
  }
  if (value.length > 10) {
    value = value.substring(0, 10) + '-' + value.substring(10)
  }
  
  formData.value.matricule = value
  matriculeError.value = ''
  userInfo.value = null
}

const verifyMatricule = async () => {
  const matricule = formData.value.matricule
  
  const matriculeRegex = /^[A-Z]{3}-[A-Z]{3}-\d{2}-\d{2,}$/
  if (!matriculeRegex.test(matricule)) {
    matriculeError.value = 'Format invalide. Utilisez le format: IND-ABE-20-001'
    return
  }
  
  isVerifying.value = true
  matriculeError.value = ''
  
  try {
    const response = await fetch(`${API_BASE_URL}?action=verify_matricule&matricule=${encodeURIComponent(matricule)}`)
    const data = await response.json()
    
    if (data.success && data.data) {
      userInfo.value = {
        prenom: data.data.prenom,
        nom: data.data.nom,
        contact: data.data.contact,
        poste: data.data.poste || 'Non défini',
        photo: data.data.photo
      }
    } else {
      matriculeError.value = data.message || 'Matricule non trouvé dans la base de données'
    }
  } catch (error) {
    console.error('Erreur lors de la vérification:', error)
    matriculeError.value = 'Erreur lors de la vérification. Veuillez réessayer.'
  } finally {
    isVerifying.value = false
  }
}

const handleSubmit = async () => {
  if (!userInfo.value) return
  
  isCreating.value = true
  
  try {
    const accountData = {
      // Informations utilisateur
      matricule_aeemci: formData.value.matricule,
      nom_utilisateur: `${userInfo.value.prenom} ${userInfo.value.nom}`,
      prenom: userInfo.value.prenom,
      nom: userInfo.value.nom,
      contact: userInfo.value.contact,
      photo: userInfo.value.photo,
      poste_actuel: userInfo.value.poste,
      
      // Informations organe
      type_organe: formData.value.typeOrgane.toLowerCase().replace(' ', '_'),
      nom_organe: getOrganeName(), // Fonction pour récupérer le nom de l'organe
      choix_organe: formData.value.organeSpecifique?.nom || formData.value.organeSpecifique?.nom_secretariat || formData.value.organeSpecifique?.nom_sous_comite || formData.value.organeSpecifique?.nom_section,
      organe_id: formData.value.organeSpecifique?.id || formData.value.organeSpecifique?.id_secretariat || formData.value.organeSpecifique?.id_sous_comite || formData.value.organeSpecifique?.id_section,
      
      // Informations nomination
      poste: 'Responsable',
      date_nomination: new Date().toISOString().split('T')[0],
      autorisation_confirmee: formData.value.autorise === 'oui',
      
      // Métadonnées
      date_creation: new Date().toISOString(),
      statut: 'actif'
    }
    
    console.log('[v0] Sending enhanced account creation data:', accountData)
    
    const response = await fetch(`${API_BASE_URL}?action=assign_responsable`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(accountData)
    })
    
    const data = await response.json()
    
    if (data.success) {
      accountSummary.value = {
        ...accountData,
        password: data.password, // Password generated by backend
        type_organe_display: getTypeOrganeDisplay(accountData.type_organe)
      }
      currentView.value = 'summary'
      resetForm()
    } else {
      console.error('Erreur lors de la création:', data.message)
    }
  } catch (error) {
    console.error('Erreur lors de la création:', error)
  } finally {
    isCreating.value = false
  }
}

const getOrganeName = () => {
  if (!formData.value.organeSpecifique) return ''
  
  switch (formData.value.typeOrgane) {
    case 'secretariat':
      return formData.value.organeSpecifique.nom_secretariat || formData.value.organeSpecifique.nom
    case 'sous-comite':
      return formData.value.organeSpecifique.nom_sous_comite || formData.value.organeSpecifique.nom
    case 'section':
      return formData.value.organeSpecifique.nom_section || formData.value.organeSpecifique.nom
    default:
      return formData.value.organeSpecifique.nom || ''
  }
}

watch(() => formData.value.secretariatParent, async (newSecretariat) => {
  console.log('[v0] Secretariat parent changed:', newSecretariat)
  if (newSecretariat && formData.value.typeOrgane === 'sous-comite') {
    await loadSousComites(newSecretariat.id)
  } else if (newSecretariat && formData.value.typeOrgane === 'section') {
    await loadSections(newSecretariat.id)
  }
  
  // Reset specific organ selection when secretariat changes
  formData.value.organeSpecifique = null
})

watch(() => formData.value.typeOrgane, async (newType) => {
  console.log('[v0] Type organe changed:', newType)
  if (newType === 'secretariat') {
    // For secretariat, we already have the data loaded
    console.log('[v0] Secretariats available:', secretariats.value)
  }
  // Reset dependent fields
  formData.value.secretariatParent = null
  formData.value.organeSpecifique = null
})

onMounted(async () => {
  console.log('[v0] Component mounted, loading secretariats...')
  await loadSecretariats()
})

const showAllTypeOrganes = () => {
  showTypeOrganeDropdown.value = true
  filteredTypeOrganes.value = typeOrganes
}

const showAllSecretariats = () => {
  showSecretariatDropdown.value = true
  filteredSecretariats.value = secretariats.value
}

const showAllOrganes = () => {
  showOrganeDropdown.value = true
  const organes = getOrganesList()
  console.log('[v0] showAllOrganes called, organes:', organes)
  filteredOrganes.value = organes
}

const isMatriculeValid = computed(() => {
  const matricule = formData.value.matricule
  const matriculeRegex = /^[A-Z]{3}-[A-Z]{3}-\d{2}-\d{2,}$/
  return matricule && matriculeRegex.test(matricule) && !matriculeError.value
})

const accountSummary = ref(null)

const getTypeOrganeDisplay = (type) => {
  const types = {
    'secretariat': 'Secrétariat Régional',
    'sous-comite': 'Sous-comité',
    'section': 'Section'
  }
  return types[type] || type
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const downloadPDF = async () => {
  try {
    // Import jsPDF dynamiquement
    const { jsPDF } = await import('jspdf');
    
    const doc = new jsPDF('p', 'mm', 'a4');
    
    // Watermark AEEMCI (discret)
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(8);
    doc.setTextColor(245, 245, 245);
    for (let y = 20; y <= 280; y += 20) {
      for (let x = 10; x <= 200; x += 40) {
        doc.text('AEEMCI', x, y);
      }
    }

    // En-tête avec design amélioré
    doc.setFillColor(0, 128, 0); // Couleur de fond verte
    doc.rect(0, 0, 210, 35, 'F'); // Rectangle de fond
    
    // Logo
    doc.addImage('https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg', 'JPEG', 10, 8, 28, 25);
    
    // Titre principal en blanc
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(20);
    doc.setTextColor(255, 255, 255); // Texte blanc
    doc.text('AEEMCI', 105, 15, { align: 'center' });
    doc.setFontSize(10);
    doc.text("Association des Elèves et Etudiants Musulmans de Côte d'Ivoire", 105, 20, { align: 'center' });
    
    // Sous-titre
    doc.setFontSize(14);
    doc.text("Compte Organisationnel", 105, 28, { align: 'center' });
    
    // Informations de connexion
    let startY = 45; // Position initiale après l'en-tête
    doc.setTextColor(0, 0, 0); // Texte noir
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Informations de connexion', 20, startY);
    
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(12);
    doc.text(`Identifiant (Matricule): ${accountSummary.value.matricule_aeemci}`, 20, startY + 10);
    doc.text(`Mot de passe: ${accountSummary.value.password}`, 20, startY + 20);

    // Informations personnelles
    startY += 30; // Position après la section précédente
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Informations personnelles', 20, startY);
    
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(12);
    doc.text(`Nom complet: ${accountSummary.value.nom_utilisateur}`, 20, startY + 10);
    doc.text(`Contact: ${accountSummary.value.contact}`, 20, startY + 20);
    doc.text(`Poste actuel: ${accountSummary.value.poste_actuel}`, 20, startY + 30);
    
    // Informations organisationnelles
    startY += 40; // Position après la section précédente
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Informations organisationnelles', 20, startY);
    
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(12);
    doc.text(`Type d'organe: ${accountSummary.value.type_organe_display}`, 20, startY + 10);
    doc.text(`Organe assigné: ${accountSummary.value.nom_organe}`, 20, startY + 20);
    doc.text(`Poste: ${accountSummary.value.poste}`, 20, startY + 30);
    doc.text(`Date de nomination: ${formatDate(accountSummary.value.date_nomination)}`, 20, startY + 40);
    doc.text(`Statut: ${accountSummary.value.statut}`, 20, startY + 50);
    
    // Pied de page amélioré
    doc.setFillColor(0, 128, 0); // Couleur de fond verte
    doc.rect(0, 275, 210, 22, 'F');
    
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7);
    doc.setTextColor(255, 255, 255);
    doc.text("Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 282, { align: 'center' });
    doc.text("27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 / aeemci@yahoo.fr / www.aeemci.ci", 105, 286, { align: 'center' });
    
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(9);
    doc.text("AEEMCI, pour une identité islamique !", 105, 292, { align: 'center' });

    // Enregistrement du PDF
    doc.save(`AEEMCI_Compte_${accountSummary.value.matricule_aeemci}.pdf`);
    
  } catch (error) {
    console.error('Erreur lors de la génération du PDF:', error);
    alert('Erreur lors de la génération du PDF');
  }
};


const checkIfMobile = () => {
  isMobile.value = window.innerWidth <= 768 || /Android|iPhone|iPad|Ipod|BlackBerry|IEMobile/i.test(navigator.userAgent)
}

const handleTypeOrganeFocus = () => {
  if (isMobile.value) {
    openTypeOrganeModal()
  } else {
    showAllTypeOrganes()
  }
}

const handleTypeOrganeClick = () => {
  if (isMobile.value) {
    openTypeOrganeModal()
  }
}

const handleSecretariatFocus = () => {
  if (isMobile.value) {
    openSecretariatModal()
  } else {
    showAllSecretariats()
  }
}

const handleSecretariatClick = () => {
  if (isMobile.value) {
    openSecretariatModal()
  }
}

const handleOrganeFocus = () => {
  if (isMobile.value) {
    openOrganeModal()
  } else {
    showAllOrganes()
  }
}

const handleOrganeClick = () => {
  if (isMobile.value) {
    openOrganeModal()
  }
}

const openTypeOrganeModal = () => {
  typeOrganeModalSearch.value = typeOrganeSearch.value
  filteredTypeOrganesModal.value = [...typeOrganes]
  showTypeOrganeModal.value = true
  document.body.style.overflow = 'hidden'
}

const openSecretariatModal = () => {
  secretariatModalSearch.value = secretariatSearch.value
  filteredSecretariatsModal.value = [...secretariats.value]
  showSecretariatModal.value = true
  document.body.style.overflow = 'hidden'
}

const openOrganeModal = () => {
  organeModalSearch.value = organeSearch.value
  filteredOrganesModal.value = getOrganesList()
  showOrganeModal.value = true
  document.body.style.overflow = 'hidden'
}

const closeTypeOrganeModal = () => {
  showTypeOrganeModal.value = false
  document.body.style.overflow = 'auto'
}

const closeSecretariatModal = () => {
  showSecretariatModal.value = false
  document.body.style.overflow = 'auto'
}

const closeOrganeModal = () => {
  showOrganeModal.value = false
  document.body.style.overflow = 'auto'
}

const filterTypeOrganeModal = () => {
  const search = typeOrganeModalSearch.value.toLowerCase()
  filteredTypeOrganesModal.value = typeOrganes.filter(type =>
    type.label.toLowerCase().includes(search)
  )
}

const filterSecretariatModal = () => {
  const search = secretariatModalSearch.value.toLowerCase()
  filteredSecretariatsModal.value = secretariats.value.filter(secretariat =>
    secretariat.nom.toLowerCase().includes(search)
  )
}

const filterOrganeModal = () => {
  const search = organeModalSearch.value.toLowerCase()
  filteredOrganesModal.value = getOrganesList().filter(organe =>
    organe.nom.toLowerCase().includes(search)
  )
}

const selectTypeOrganeFromModal = (type) => {
  typeOrganeSearch.value = type.label
  formData.value.typeOrgane = type.value
  
  formData.value.secretariatParent = null
  formData.value.organeSpecifique = null
  secretariatSearch.value = ''
  organeSearch.value = ''
  
  closeTypeOrganeModal()
}

const selectSecretariatFromModal = (secretariat) => {
  secretariatSearch.value = secretariat.nom
  formData.value.secretariatParent = secretariat
  formData.value.organeSpecifique = null
  organeSearch.value = ''
  closeSecretariatModal()
}

const selectOrganeFromModal = (organe) => {
  organeSearch.value = organe.nom
  formData.value.organeSpecifique = organe
  closeOrganeModal()
}

onMounted(() => {
  checkIfMobile()
  window.addEventListener('resize', checkIfMobile)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkIfMobile)
})
</script>

<style scoped>
/* Styles de base */
.app-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #018404, #d8d4dd);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.app-header {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
  padding: 1rem 0;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
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
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #018404, #d8d4dd);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.logo-text h1 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #2d3748;
}

.logo-text p {
  margin: 0;
  font-size: 0.875rem;
  color: #718096;
}

.main-content {
  padding: 2rem;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  min-height: calc(100vh - 100px);
}

.form-container {
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  width: 100%;
  max-width: 600px;
}

/* Page d'accueil */
.welcome-section {
  text-align: center;
}

.welcome-header h2 {
  font-size: 2rem;
  font-weight: 700;
  color: #2d3748;
  margin-bottom: 0.5rem;
}

.welcome-header p {
  color: #718096;
  font-size: 1.125rem;
  margin-bottom: 3rem;
}

.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.btn {
  padding: 1rem 2rem;
  border: none;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  text-decoration: none;
}

.btn-large {
  padding: 1.5rem 2rem;
  font-size: 1.125rem;
}

.btn-primary {
  background: linear-gradient(135deg, #018404, #d8d4dd);
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
  background: #f7fafc;
  color: #4a5568;
  border: 2px solid #e2e8f0;
}

.btn-secondary:hover {
  background: #edf2f7;
  transform: translateY(-2px);
}

.btn-verify {
  background: #48bb78;
  color: white;
  border: 2px solid #48bb78;
  margin-left: 1rem;
}

.btn-verify:hover {
  background: #38a169;
  border-color: #38a169;
}

/* Formulaire */
.form-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2rem;
}

.back-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 8px;
  color: #718096;
  transition: all 0.2s;
}

.back-btn:hover {
  background: #f7fafc;
  color: #4a5568;
}

.form-header h2 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
  color: #2d3748;
}

.step-content {
  margin-bottom: 2rem;
}

.step-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-weight: 600;
  color: #4a5568;
  margin-bottom: 0.5rem;
}

.required {
  color: #e53e3e;
}

.form-select,
.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.form-select:focus,
.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-error {
  border-color: #e53e3e !important;
}

.input-success {
  border-color: #38a169 !important;
}

.form-help {
  color: #718096;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.error-message {
  color: #e53e3e;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.loading-message {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #667eea;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

/* Cases à cocher et radio */
.radio-group {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.radio-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  padding: 0.75rem;
  border-radius: 8px;
  transition: background-color 0.2s;
}

.radio-item:hover {
  background: #f7fafc;
}

.radio-input {
  display: none;
}

.radio-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #e2e8f0;
  border-radius: 50%;
  position: relative;
  transition: all 0.2s;
}

.radio-input:checked + .radio-custom {
  border-color: #667eea;
  background: #667eea;
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
  color: #4a5568;
}

/* Boîtes d'information */
.info-box {
  background: #ebf8ff;
  border: 1px solid #bee3f8;
  border-radius: 8px;
  padding: 1rem;
  display: flex;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.info-icon {
  color: #3182ce;
  flex-shrink: 0;
}

.info-box h4 {
  margin: 0 0 0.5rem 0;
  font-size: 1rem;
  font-weight: 600;
  color: #2c5282;
}

.info-box p {
  margin: 0;
  color: #2c5282;
}

.error-box {
  background: #fed7d7;
  border: 1px solid #feb2b2;
  border-radius: 8px;
  padding: 1rem;
  margin-top: 1rem;
}

.error-box p {
  margin: 0;
  color: #c53030;
  font-weight: 500;
}

/* Carte utilisateur */
.user-info-card {
  background: #f0fff4;
  border: 2px solid #9ae6b4;
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.user-photo {
  flex-shrink: 0;
}

.user-photo img {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
}

.photo-placeholder {
  width: 80px;
  height: 80px;
  background: #e2e8f0;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #a0aec0;
}

.user-details h4 {
  margin: 0 0 0.5rem 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #22543d;
}

.user-details p {
  margin: 0.25rem 0;
  color: #2f855a;
}

/* Navigation */
.navigation-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 2rem;
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
}

.modal-content {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  max-width: 400px;
  width: 90%;
  text-align: center;
}

.modal-header {
  margin-bottom: 2rem;
}

.success-icon {
  margin-bottom: 1rem;
}

.modal-header h3 {
  margin: 0 0 1rem 0;
  font-size: 1.5rem;
  font-weight: 600;
  color: #2d3748;
}

.modal-header p {
  margin: 0.5rem 0;
  color: #718096;
}

/* Spinner de chargement */
.loading-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid transparent;
  border-top: 2px solid currentColor;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Styles pour l'autocomplete */
.autocomplete-container {
  position: relative;
}

.autocomplete-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.autocomplete-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.autocomplete-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  border: 2px solid #e2e8f0;
  border-top: none;
  border-radius: 0 0 8px 8px;
  max-height: 200px;
  overflow-y: auto;
  z-index: 1000;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.autocomplete-item {
  padding: 0.75rem 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
  border-bottom: 1px solid #f7fafc;
}

.autocomplete-item:hover {
  background-color: #f7fafc;
}

.autocomplete-item:last-child {
  border-bottom: none;
}

/* Résumé */
.summary-section {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.summary-header {
  text-align: center;
  margin-bottom: 2rem;
}

.summary-header h2 {
  color: #16a34a;
  margin-bottom: 0.5rem;
}

.summary-content {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.summary-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border: 1px solid #e5e7eb;
}

.summary-card h3 {
  color: #1f2937;
  margin-bottom: 1rem;
  font-size: 1.1rem;
  font-weight: 600;
}

.login-credentials {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.credential-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.credential-item label {
  font-weight: 500;
  color: #64748b;
}

.credential-value {
  font-weight: 600;
  color: #1e293b;
  font-family: monospace;
}

.credential-value.password {
  background: #fee2e2;
  color: #dc2626;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 1.1rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.info-item label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #64748b;
}

.info-item span {
  color: #1e293b;
  font-weight: 500;
}

.status-active {
  color: #16a34a !important;
  text-transform: capitalize;
}

.summary-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.summary-actions .btn {
  min-width: 180px;
}

@media (max-width: 768px) {
  .main-content {
    padding: 1rem;
  }
  
  .form-container {
    padding: 1.5rem;
  }
  
  .user-info-card {
    flex-direction: column;
    text-align: center;
  }
  
  .navigation-buttons {
    flex-direction: column;
  }
  
  .summary-section {
    padding: 1rem;
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .credential-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .summary-actions {
    flex-direction: column;
  }
}

/* Added input icon positioning */
.autocomplete-container {
  position: relative;
}

.input-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  pointer-events: none;
}

.mobile-autocomplete {
  cursor: pointer;
}

/* Added mobile modal styles */
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

.modal-no-results {
  padding: 2rem 1.5rem;
  text-align: center;
  color: #9ca3af;
  font-style: italic;
}
</style>
