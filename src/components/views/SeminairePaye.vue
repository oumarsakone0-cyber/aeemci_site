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
              <p>Confirmation d'Inscription au Séminaire</p>
            </div>
          </div>
        </div>
        <div class="header-right">
          <div class="year-badge">SENAFOI 26</div>
          <button @click="nouvelleInscription" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            Nouvelle inscription
          </button>
        </div>
      </div>
    </header>

    <!-- Etat 0 - Saisie du matricule -->
    <div v-if="showMatriculeInput" class="matricule-input-container">
      <div class="matricule-card">

        <!-- Icône décorative -->
        <div class="mi-icon-wrap">
          <div class="mi-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93C9.33 17.79 7 14.5 7 11V7.18L12 5z"/>
            </svg>
          </div>
          <div class="mi-rings">
            <div class="mi-ring mi-ring-1"></div>
            <div class="mi-ring mi-ring-2"></div>
          </div>
        </div>

        <h2 class="mi-title">Vérifier votre paiement</h2>
        <p class="mi-subtitle">
          Entrez votre matricule pour accéder à votre fiche d'inscription et confirmer votre paiement.
        </p>

        <div class="mi-form">
          <div class="mi-field" :class="{ 'mi-field--error': matriculeError, 'mi-field--focus': matriculeFocused }">
            <label class="mi-label">Matricule SENAFOI 2026</label>
            <div class="mi-input-wrap">
              <span class="mi-input-prefix">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/>
                </svg>
              </span>
              <input
                ref="matriculeInput"
                v-model="matriculeSaisi"
                type="text"
                class="mi-input"
                placeholder=" ex: SEM202600123"
                autocomplete="off"
                autocapitalize="characters"
                @focus="matriculeFocused = true; matriculeError = ''"
                @blur="matriculeFocused = false"
                @keyup.enter="verifierMatricule"
                @input="matriculeSaisi = $event.target.value.toUpperCase()"
              />
              <button
                v-if="matriculeSaisi"
                @click="matriculeSaisi = ''"
                class="mi-clear"
                type="button"
                tabindex="-1"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
              </button>
            </div>
            <p v-if="matriculeError" class="mi-error-msg">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
              </svg>
              {{ matriculeError }}
            </p>
          </div>

          <button
            @click="verifierMatricule"
            :disabled="!matriculeSaisi.trim() || isLoading"
            class="mi-btn-verify"
          >
            <div v-if="isLoading" class="spinner-small spinner-white"></div>
            <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            {{ isLoading ? 'Vérification en cours...' : 'Vérifier mon paiement' }}
          </button>
        </div>

        <div class="mi-help">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
          </svg>
          Votre matricule vous a été communiqué lors de votre inscription.
          <br>Vous ne le trouvez pas ?
          <a href="mailto:support@aeemci.org" class="mi-help-link">Contactez le support</a>
        </div>

      </div>
    </div>

    <!-- Loading State -->
    <div v-else-if="isLoading" class="loading-container">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <h2>Validation du paiement...</h2>
        <p>Veuillez patienter pendant que nous confirmons votre paiement</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="errorMessage" class="error-container">
      <div class="error-content">
        <div class="error-icon">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
          </svg>
        </div>
        <h2>Erreur de validation</h2>
        <p>{{ errorMessage }}</p>
        <div class="error-actions">
          <button @click="retryValidation" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
            </svg>
            Réessayer
          </button>
          <button @click="retourSaisie" class="btn btn-secondary-dark">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Modifier le matricule
          </button>
        </div>
      </div>
    </div>

    <!-- Success State -->
    <div v-else-if="seminariste" class="success-container">
      <div class="success-card">

        <!-- Hero section -->
        <div class="success-hero">
          <div class="hero-top">
            <div class="receipt-photo">
              <img
                v-if="seminariste.photo && !photoLoadFailed"
                :src="seminariste.photo"
                :alt="`Photo de ${seminariste.prenom} ${seminariste.nom}`"
                @error="photoLoadFailed = true"
              />
              <span v-else>{{ getInitials(seminariste) }}</span>
            </div>
            <div class="hero-text">
              <h1>Félicitations !</h1>
              <p>Votre inscription au séminaire <strong>SENAFOI 2026</strong> a été validée avec succès</p>
            </div>
          </div>

          <div class="hero-bottom">
            <div class="matricule-block">
              <div class="badge-paid">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Paiement confirmé
              </div>
              <div class="matricule-label">Votre matricule</div>
              <div class="matricule-value">{{ seminariste.matricule_seminaire }}</div>
            </div>

            <div class="pdf-action-hero">
              <button
                @click="generatePDF"
                :disabled="isGeneratingPDF"
                class="btn-pdf-main"
              >
                <div v-if="isGeneratingPDF" class="spinner-small"></div>
                <svg v-else width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11zm-4-7H8v-2h6v2zm2 4H8v-2h8v2z"/>
                </svg>
                {{ isGeneratingPDF ? 'Génération en cours...' : 'Télécharger ma fiche d\'inscription PDF' }}
              </button>
              <p class="pdf-hint">Conservez ce document — il vous sera demandé au séminaire</p>
            </div>
          </div>
        </div>

        <!-- Bande montant -->
        <div class="montant-bar">
          <div class="montant-left">
            <span class="montant-label">Montant payé</span>
            <span class="montant-value">{{ seminariste.montant_paye_formate }}</span>
          </div>
          <div class="montant-right">
            <span class="statut-badge">{{ seminariste.statut_paiement }}</span>
            <span class="operateur">via Wave CI</span>
          </div>
        </div>

        <!-- Recapitulatif complet -->
        <div class="section">
          <div class="section-header">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span>Récapitulatif de l'inscription</span>
          </div>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Nom complet</span>
                <span class="info-value">{{ seminariste.prenom }} {{ seminariste.nom }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Contact</span>
                <span class="info-value">{{ seminariste.contact }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Niveau séminaire</span>
                <span class="info-value">{{ getNiveauLabel(seminariste.niveau_seminaire) }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Sexe</span>
                <span class="info-value">{{ seminariste.sexe === 'M' ? 'Masculin' : 'Féminin' }}</span>
              </div>
            </div>

            <div v-if="seminariste.secretariat_regional" class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Secrétariat régional</span>
                <span class="info-value">{{ seminariste.secretariat_regional }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Date d'inscription</span>
                <span class="info-value">{{ formatDate(seminariste.created_at) }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-content">
                <span class="info-label">Car</span>
                <span class="info-value">{{ getCarLabel(seminariste.car_transport) }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-content">
                <span class="info-label">Dortoir</span>
                <span class="info-value">{{ getReceiptDortoir(seminariste.dortoir) }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm-8 9l-8-5V6l8 5 8-5v2l-8 5z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Référence paiement</span>
                <span class="info-value mono">{{ seminariste.transaction_id || seminariste.ref_paiement || 'N/A' }}</span>
              </div>
            </div>

            <div v-if="seminariste.a_participe_senafoi" class="info-item">
              <div class="info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="info-content">
                <span class="info-label">Niveau précédent</span>
                <span class="info-value">{{ seminariste.niveau_annee_passee }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Consignes -->
        <div class="section">
          <div class="section-header section-header--warning">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <span>Consignes importantes — SENAFOI 2026</span>
          </div>
          <div class="consignes-body">
            <div class="consignes-col">
              <p class="consignes-title">Le séminariste doit prévoir :</p>
              <ul>
                <li>Des vêtements décents (corps couvert pour les sœurs, genoux pour les frères)</li>
                <li>Sa literie personnelle (drap, couverture, moustiquaire)</li>
                <li>Son équipement sportif (jogging obligatoire pour les sœurs)</li>
              </ul>
            </div>
            <div class="consignes-col">
              <p class="consignes-title">À noter :</p>
              <ul>
                <li>Pas d'objets de luxe ou de grande valeur (tablette, console, bijoux)</li>
                <li>Coiffures islamiques obligatoires (pas de crête ni de mèche)</li>
                <li>Chaque séminariste recevra un kit AEEMCI (polo, stylo, bloc-note)</li>
              </ul>
            </div>
            <div class="consignes-contacts">
              <span class="contact-label">Contacts :</span>
              <span>+225 07 78 20 91 14</span>
              <span>+225 05 45 44 99 61</span>
              <span class="contact-label">Pépinière :</span>
              <span>+225 56 16 55 53</span>
              <span>+225 57 69 95 89</span>
            </div>
          </div>
        </div>

        <!-- Actions secondaires -->
        <div class="secondary-actions">
          <button @click="shareInscription" class="btn btn-outline">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
            </svg>
            Partager
          </button>
          <button @click="contactSupport" class="btn btn-outline">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            Support
          </button>
          <button @click="viewSeminaireInfo" class="btn btn-outline">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
            </svg>
            Infos séminaire
          </button>
        </div>

        <!-- Footer -->
        <div class="page-footer">
          <p>Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08</p>
          <p>27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 / aeemci@yahoo.fr / www.aeemci.ci</p>
          <p class="footer-slogan">AEEMCI, pour une identité islamique !</p>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import QRCode from 'qrcode'
import axios from 'axios'

const route = useRoute()
const seminariste = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')
const isGeneratingPDF = ref(false)
const photoLoadFailed = ref(false)

// -- Gestion du matricule ------------------------------------------------------
const matriculeActif = ref(route.params.matricule || '')
const showMatriculeInput = ref(!matriculeActif.value)
const matriculeSaisi = ref('')
const matriculeError = ref('')
const matriculeFocused = ref(false)
const matriculeInput = ref(null)

/** Appelé depuis le bouton "Vérifier mon paiement" */
const verifierMatricule = () => {
  const val = matriculeSaisi.value.trim()
  if (!val) {
    matriculeError.value = 'Veuillez saisir votre matricule.'
    return
  }
  matriculeActif.value = val
  showMatriculeInput.value = false
  validateAndLoadSeminaristData()
}

/** Revenir à la saisie du matricule depuis l'état d'erreur */
const retourSaisie = () => {
  errorMessage.value = ''
  seminariste.value = null
  showMatriculeInput.value = true
  nextTick(() => matriculeInput.value?.focus())
}

// -- Chargement & validation ---------------------------------------------------
const validateAndLoadSeminaristData = async () => {
  if (!matriculeActif.value) {
    showMatriculeInput.value = true
    return
  }
  try {
    isLoading.value = true
    errorMessage.value = ''
    const response = await axios.post('https://api.aeemci-ce.ci/senafoi/update_payment.php', {
      matricule: matriculeActif.value
    })
    if (response.data.success) {
      seminariste.value = response.data.seminariste
    } else {
      errorMessage.value = response.data.message || 'Erreur lors de la validation du paiement'
    }
  } catch (error) {
    if (error.response?.status === 404) {
      errorMessage.value = 'Aucun séminariste trouvé avec ce matricule'
    } else if (error.response?.status === 400) {
      errorMessage.value = error.response.data.message || 'Matricule invalide'
    } else {
      errorMessage.value = 'Erreur lors de la validation du paiement. Veuillez réessayer.'
    }
  } finally {
    isLoading.value = false
  }
}

// -- Helpers -------------------------------------------------------------------
const formatDate = (dateString) => {
  if (!dateString) return 'Non renseigné'
  return new Date(dateString).toLocaleDateString('fr-FR')
}

const getNiveauLabel = (niveau) => {
  const labels = {
    '1AS': '1ère Année Secondaire',   '2AS': '2ème Année Secondaire',
    '3AS': '3ème Année Secondaire',   '1AF': '1ère Année Formation',
    '2AF': '2ème Année Formation',    '3AF': '3ème Année Formation',
    '1BS': '1ère Année Supérieure',   '2BS': '2ème Année Supérieure',
    '3BS': '3ème Année Supérieure',   '1BF': '1ère Année Formation Avancée',
    '2BF': '2ème Année Formation Avancée', '3BF': '3ème Année Formation Avancée',
    '4': 'Niveau 4',                  'TEST_ENTREE': "Test d'entrée requis"
  }
  return labels[niveau] || niveau
}

const getDortoirLabel = (dortoir) => {
  const labels = {
    'DORTOIR_A': 'Dortoir Alpha - Hommes',   'DORTOIR_B': 'Dortoir Beta - Hommes',
    'DORTOIR_C': 'Dortoir Gamma - Hommes',   'DORTOIR_D': 'Dortoir Delta - Hommes',
    'DORTOIR_E': 'Dortoir Epsilon - Hommes', 'DORTOIR_F': 'Dortoir Zeta - Hommes',
    'DORTOIR_G': 'Dortoir Eta - Hommes',     'DORTOIR_H': 'Dortoir Theta - Hommes',
    'DORTOIR_I': 'Dortoir Iota - Femmes',    'DORTOIR_J': 'Dortoir Kappa - Femmes',
    'DORTOIR_K': 'Dortoir Lambda - Femmes',  'DORTOIR_L': 'Dortoir Mu - Femmes',
    'DORTOIR_M': 'Dortoir Nu - Femmes',      'DORTOIR_N': 'Dortoir Xi - Femmes',
    'DORTOIR_O': 'Dortoir Omicron - Femmes', 'DORTOIR_P': 'Dortoir Pi - Femmes',
    'EXTERNE': 'Hébergement Externe',
    'PEPINIERE': 'Pépinière'
  }
  return labels[dortoir] || (dortoir || 'Non assigné')
}

const getReceiptDortoir = (dortoir) => {
  return dortoir ? getDortoirLabel(dortoir) : 'Vous serez notifié par SMS'
}

const getCarLabel = (car) => {
  if (!car) return 'Non assigné'
  if (car === 'TRANSPORT_PERSONNEL') return 'Transport personnel'
  if (/^CAR_\d+$/i.test(car)) return car.toUpperCase()
  return car
}

const getInitials = (s) => {
  const parts = [s?.prenom, s?.nom].filter(Boolean).join(' ').trim().split(/\s+/)
  return parts.map(part => part[0]?.toUpperCase() || '').join('').slice(0, 2) || 'SF'
}

const imageUrlToDataUrl = async (url) => {
  if (!url) return null
  try {
    const response = await fetch(url, { mode: 'cors' })
    if (!response.ok) return null
    const blob = await response.blob()
    return await new Promise((resolve) => {
      const reader = new FileReader()
      reader.onloadend = () => resolve(reader.result)
      reader.onerror = () => resolve(null)
      reader.readAsDataURL(blob)
    })
  } catch (e) {
    return null
  }
}

const pdfImageFormat = (dataUrl) => {
  if (typeof dataUrl !== 'string') return 'JPEG'
  if (dataUrl.startsWith('data:image/png')) return 'PNG'
  if (dataUrl.startsWith('data:image/webp')) return 'WEBP'
  return 'JPEG'
}

// -- PDF -----------------------------------------------------------------------
const generatePDF = async () => {
  isGeneratingPDF.value = true
  try {
    const { default: jsPDF } = await import('jspdf')
    const { PDFDocument } = await import('pdf-lib')
    const doc = new jsPDF('p', 'mm', 'a4')
    const today = new Date()
    const qrText = `https://aeemci-ce.ci/seminaire_paye/${seminariste.value.matricule_seminaire}`
    const qrDataURL = await QRCode.toDataURL(qrText, { width: 120, margin: 1 })

    // Filigrane
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(8)
    doc.setTextColor(245, 245, 245)
    for (let y = 20; y <= 280; y += 20) {
      for (let x = 10; x <= 200; x += 40) {
        doc.text('AEEMCI SENAFOI 2026', x, y)
      }
    }

    // En-tête vert
    doc.setFillColor(21, 128, 61)
    doc.rect(0, 0, 210, 38, 'F')

    try {
      doc.addImage('https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg', 'JPEG', 10, 6, 26, 26)
    } catch (e) { /* logo optionnel */ }

    doc.setFont('helvetica', 'bold')
    doc.setFontSize(22)
    doc.setTextColor(255, 255, 255)
    doc.text('AEEMCI', 105, 14, { align: 'center' })
    doc.setFontSize(9)
    doc.setFont('helvetica', 'normal')
    doc.text("Association des Élèves et Étudiants Musulmans de Côte d'Ivoire", 105, 20, { align: 'center' })
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(13)
    doc.text("FICHE D'INSCRIPTION - REÇU SENAFOI 2026", 105, 29, { align: 'center' })

    doc.setFillColor(180, 117, 23)
    doc.rect(0, 38, 210, 2, 'F')

    try {
      const bannerImg = new Image()
      bannerImg.crossOrigin = 'anonymous'
      await new Promise((resolve, reject) => {
        bannerImg.onload = resolve
        bannerImg.onerror = reject
        bannerImg.src = 'https://res.cloudinary.com/dqk65objc/image/upload/v1751398353/aeemci_seminaire_1751398349649_ban.jpeg.jpg'
      })
      doc.addImage(bannerImg, 'JPEG', 15, 42, 180, 25)
    } catch (e) { /* bannière optionnelle */ }

    let y = 48

    doc.setTextColor(80, 80, 80)
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(8.5)
    doc.text(`Reçu N° : ${seminariste.value.id}`, 15, y)
    doc.text(`Date : ${today.toLocaleDateString('fr-FR')}`, 195, y, { align: 'right' })

    y += 6
    doc.setFillColor(240, 253, 244)
    doc.setDrawColor(21, 128, 61)
    doc.setLineWidth(0.5)
    doc.roundedRect(52, y - 5, 106, 12, 2.5, 2.5, 'FD')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.setTextColor(21, 128, 61)
    doc.text(`MATRICULE : ${seminariste.value.matricule_seminaire}`, 105, y + 3, { align: 'center' })

    y += 10
    doc.setDrawColor(21, 128, 61)
    doc.setLineWidth(0.7)
    doc.line(15, y, 195, y)

    y += 6
    doc.setFillColor(240, 253, 244)
    doc.rect(15, y, 180, 7, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.setTextColor(21, 128, 61)
    doc.text('IDENTIFICATION DU SÉMINARISTE', 17, y + 5)

    y += 11
    const col1x = 17
    const col2x = 90
    const mediaX = 161
    const mediaCenterX = 175
    const photoY = y - 2
    const photoW = 28
    const photoH = 30

    const photoDataUrl = await imageUrlToDataUrl(seminariste.value.photo)
    if (photoDataUrl) {
      doc.setDrawColor(21, 128, 61)
      doc.setFillColor(255, 255, 255)
      doc.roundedRect(mediaX, photoY, photoW, photoH, 2, 2, 'FD')
      doc.addImage(photoDataUrl, pdfImageFormat(photoDataUrl), mediaX + 1.4, photoY + 1.4, photoW - 2.8, photoH - 2.8)
    } else {
      doc.setFillColor(240, 253, 244)
      doc.setDrawColor(21, 128, 61)
      doc.roundedRect(mediaX, photoY, photoW, photoH, 2, 2, 'FD')
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(12)
      doc.setTextColor(21, 128, 61)
      doc.text(getInitials(seminariste.value), mediaCenterX, photoY + 17, { align: 'center' })
    }

    const qrSize = 22
    const qrY = photoY + photoH + 4
    doc.addImage(qrDataURL, 'PNG', mediaCenterX - (qrSize / 2), qrY, qrSize, qrSize)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(6)
    doc.setTextColor(21, 128, 61)
    doc.text('SCAN REÇU', mediaCenterX, qrY + qrSize + 3, { align: 'center' })

    const printField = (label, value, x, posY, options = {}) => {
      const {
        fontSize = 9.5,
        labelOffset = 29,
        maxWidth = null,
      } = options
      doc.setFont('helvetica', 'normal')
      doc.setFontSize(fontSize)
      doc.setTextColor(100, 100, 100)
      doc.text(label, x, posY)
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(fontSize)
      doc.setTextColor(30, 30, 30)
      const text = String(value || 'N/A')
      doc.text(maxWidth ? doc.splitTextToSize(text, maxWidth)[0] : text, x + labelOffset, posY)
    }

    printField('Nom :', seminariste.value.nom || '', col1x, y)
    if (seminariste.value.secretariat_regional) {
      printField('Secretariat :', seminariste.value.secretariat_regional, col2x, y)
    }
    y += 6
    printField('Prenom(s) :', seminariste.value.prenom || '', col1x, y)
    printField('Sexe :', seminariste.value.sexe === 'M' ? 'Masculin' : 'Féminin', col2x, y)
    y += 6
    printField('Contact :', seminariste.value.contact || '', col1x, y)
    printField('Niveau :', getNiveauLabel(seminariste.value.niveau_seminaire), col2x, y)
    y += 6
    printField('Car :', getCarLabel(seminariste.value.car_transport), col1x, y)
    y += 6
    printField('Dortoir :', getReceiptDortoir(seminariste.value.dortoir), col1x, y)
    y = Math.max(y + 6, qrY + qrSize + 8)

    doc.setFillColor(240, 253, 244)
    doc.rect(15, y, 180, 7, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.setTextColor(21, 128, 61)
    doc.text('RÉFÉRENCES DU PAIEMENT', 17, y + 5)

    y += 11
    printField(
      'Transaction :',
      seminariste.value.transaction_id || seminariste.value.ref_paiement || 'N/A',
      col1x,
      y,
      { fontSize: 9, labelOffset: 30, maxWidth: 63 }
    )
    printField('Opérateur :', 'Wave CI', 130, y, { fontSize: 9, labelOffset: 27 })
    y += 6
    printField('N° Wave :', seminariste.value.numero_wave || 'N/A', col1x, y, { fontSize: 9 })
    printField('Date :', formatDate(seminariste.value.created_at), 130, y, { fontSize: 9, labelOffset: 18 })
    y += 6

    doc.setFillColor(21, 128, 61)
    doc.rect(15, y, 180, 10, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(11)
    doc.setTextColor(255, 255, 255)
    doc.text(`Montant payé : ${seminariste.value.montant_paye_formate || 'N/A'}`, 17, y + 7)
    doc.text('PAYÉ OK', 193, y + 7, { align: 'right' })

    y += 16
    doc.setDrawColor(217, 119, 6)
    doc.setFillColor(255, 251, 235)
    doc.rect(15, y, 180, 7, 'FD')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.setTextColor(146, 64, 14)
    doc.text('CONSIGNES IMPORTANTES - SENAFOI 2026', 17, y + 5)

    y += 10
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(8)
    doc.setTextColor(30, 30, 30)
    doc.text('Le séminariste doit prévoir :', 17, y)
    y += 4
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7.5)
    ;[
      '- Des vêtements décents (corps couvert pour les sœurs, genoux couverts pour les frères)',
      '- Sa literie personnelle (drap, couverture, moustiquaire)',
      '- Son équipement sportif (jogging obligatoire pour les sœurs)',
    ].forEach(c => { doc.text(c, 19, y); y += 4 })

    y += 1
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(8)
    doc.text('À noter :', 17, y)
    y += 4
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7.5)
    ;[
      "- Pas d'objets de valeur (tablette, console de jeux, bijoux, grosse somme d'argent)",
      '- Coiffures islamiques obligatoires (pas de crête pour les garçons, ni de mèche pour les filles)',
      '- Chaque séminariste recevra un kit AEEMCI (polo, stylo, bloc-note)',
    ].forEach(n => { doc.text(n, 19, y); y += 4 })

    y += 1
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(8)
    doc.text('Contacts :', 17, y)
    y += 4
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7.5)
    ;[
      '- +225 07 78 20 91 14  -  +225 05 45 44 99 61',
      '- Pépinière : +225 56 16 55 53  -  +225 57 69 95 89',
    ].forEach(c => { doc.text(c, 19, y); y += 4 })

    doc.setFillColor(21, 128, 61)
    doc.rect(0, 273, 210, 24, 'F')
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7)
    doc.setTextColor(209, 250, 229)
    doc.text("Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 280, { align: 'center' })
    doc.text("27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 / aeemci@yahoo.fr / www.aeemci.ci", 105, 285, { align: 'center' })
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.setTextColor(255, 255, 255)
    doc.text("AEEMCI, pour une identité islamique !", 105, 292, { align: 'center' })

    const generatedPdfBytes = doc.output('arraybuffer')
    const filename = `fiche_senafoi2026_${seminariste.value.matricule_seminaire}_${today.toISOString().split('T')[0]}.pdf`

    try {
      const existingPdfResponse = await fetch('/reglement_interieur.pdf')
      if (!existingPdfResponse.ok) {
        doc.save(filename)
        return
      }

      const existingPdfBytes = await existingPdfResponse.arrayBuffer()
      const mergedPdf = await PDFDocument.create()
      const generatedPdf = await PDFDocument.load(generatedPdfBytes)
      const generatedPages = await mergedPdf.copyPages(generatedPdf, generatedPdf.getPageIndices())
      generatedPages.forEach(page => mergedPdf.addPage(page))

      const existingPdf = await PDFDocument.load(existingPdfBytes, { ignoreEncryption: true })
      const existingPages = await mergedPdf.copyPages(existingPdf, existingPdf.getPageIndices())
      existingPages.forEach(page => mergedPdf.addPage(page))

      const mergedPdfBytes = await mergedPdf.save()
      const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' })
      const url = URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      link.download = filename
      link.click()
      URL.revokeObjectURL(url)
    } catch (mergeError) {
      console.warn('Annexe PDF ignorée, téléchargement du reçu seul :', mergeError)
      doc.save(filename)
    }
  } catch (error) {
    console.error('Erreur génération PDF :', error)
    alert("Erreur lors de la génération du PDF. Veuillez réessayer.")
  } finally {
    isGeneratingPDF.value = false
  }
}

// -- Actions -------------------------------------------------------------------
const shareInscription = () => {
  const text = `Je me suis inscrit au séminaire SENAFOI 2026 ! Matricule : ${seminariste.value.matricule_seminaire}`
  if (navigator.share) {
    navigator.share({ title: 'Mon inscription AEEMCI à SENAFOI 2026', text, url: window.location.href })
  } else {
    navigator.clipboard.writeText(text).then(() => alert('Texte copié dans le presse-papiers !'))
  }
}
const contactSupport = () => {
  window.open(`mailto:support@aeemci.org?subject=Support - Inscription SENAFOI 2026 ${seminariste.value.matricule_seminaire}`, '_blank')
}
const viewSeminaireInfo = () => {
  window.open('https://www.aeemci-ce.ci/seminaire-info', '_blank')
}
const nouvelleInscription = () => {
  window.location.href = 'https://www.aeemci-ce.ci/seminaristes'
}
const retryValidation = () => {
  validateAndLoadSeminaristData()
}

// -- Init ----------------------------------------------------------------------
onMounted(() => {
  if (matriculeActif.value) {
    validateAndLoadSeminaristData()
  } else {
    nextTick(() => matriculeInput.value?.focus())
  }
})
</script>

<style scoped>
* { margin: 0; padding: 0; box-sizing: border-box; }

.app-container {
  min-height: 100vh;
  background: linear-gradient(160deg, var(--green-mist) 0%, var(--surface) 100%);
  font-family: var(--display);
}

/* -- Header -- */
.app-header { background: var(--green); box-shadow: var(--shadow); }
.header-content {
  max-width: 960px; margin: 0 auto;
  padding: .85rem 1.5rem;
  display: flex; justify-content: space-between; align-items: center;
}
.logo { display: flex; align-items: center; gap: .75rem; }
.logo-icon {
  width: 2.4rem; height: 2.4rem;
  background: rgba(255,255,255,0.2); color: #fff;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
.logo-text h1 { font-size: 1.35rem; font-weight: 700; color: #fff; }
.logo-text p  { font-size: .75rem; color: rgba(255,255,255,.8); }
.header-right { display: flex; align-items: center; gap: .75rem; }
.year-badge {
  background: #fff; color: var(--green);
  font-weight: 800; font-size: .85rem;
  padding: .3rem .9rem; border-radius: 0; letter-spacing: 2px;
}

/* Ecran de saisie du matricule */
.matricule-input-container {
  display: flex; align-items: center; justify-content: center;
  min-height: calc(100vh - 68px);
  padding: 2rem 1rem;
}

.matricule-card {
  background: #fff;
  border-radius: 0;
  box-shadow: var(--shadow);
  padding: 2.5rem 2rem;
  width: 100%; max-width: 480px;
  text-align: center;
  animation: fadeUp .4s ease both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Icône animée */
.mi-icon-wrap {
  position: relative;
  width: 80px; height: 80px;
  margin: 0 auto 1.5rem;
}
.mi-icon {
  position: relative; z-index: 2;
  width: 80px; height: 80px;
  background: linear-gradient(145deg, var(--green), var(--green-2));
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff;
  box-shadow: var(--shadow);
}
.mi-ring {
  position: absolute; border-radius: 50%;
  border: 2px solid rgba(21,128,61,.2);
  top: 50%; left: 50%; transform: translate(-50%, -50%);
  animation: pulse-ring 2.4s ease-out infinite;
}
.mi-ring-1 { width: 100%; height: 100%; animation-delay: 0s; }
.mi-ring-2 { width: 100%; height: 100%; animation-delay: .8s; }

@keyframes pulse-ring {
  0%   { transform: translate(-50%, -50%) scale(1); opacity: .6; }
  100% { transform: translate(-50%, -50%) scale(1.9); opacity: 0; }
}

.mi-title {
  font-size: 1.4rem; font-weight: 700; color: var(--ink);
  margin-bottom: .5rem;
}
.mi-subtitle {
  font-size: .88rem; color: var(--muted); line-height: 1.6;
  margin-bottom: 1.75rem;
}

/* Champ */
.mi-form { text-align: left; }

.mi-field {
  margin-bottom: 1rem;
}
.mi-label {
  display: block;
  font-size: .72rem; font-weight: 700;
  color: var(--ink-soft); text-transform: uppercase; letter-spacing: .6px;
  margin-bottom: .45rem;
}
.mi-input-wrap {
  display: flex; align-items: center;
  border: 2px solid var(--green-mist);
  border-radius: 0;
  background: var(--surface);
  transition: border-color .2s, box-shadow .2s;
  overflow: hidden;
}
.mi-field--focus .mi-input-wrap {
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(21,128,61,.12);
  background: #fff;
}
.mi-field--error .mi-input-wrap {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239,68,68,.1);
}
.mi-input-prefix {
  padding: 0 .65rem;
  color: var(--muted);
  display: flex; align-items: center;
  flex-shrink: 0;
}
.mi-field--focus .mi-input-prefix { color: var(--green); }
.mi-input {
  flex: 1; border: none; outline: none; background: transparent;
  font-size: .95rem; font-weight: 600; color: var(--ink);
  padding: .75rem 0;
  font-family: 'Courier New', monospace; letter-spacing: 1px;
}
.mi-input::placeholder { font-weight: 400; letter-spacing: 0; color: var(--muted); font-family: var(--display); }
.mi-clear {
  border: none; background: transparent; cursor: pointer;
  color: var(--muted); padding: 0 .65rem;
  display: flex; align-items: center;
  transition: color .15s;
}
.mi-clear:hover { color: #ef4444; }
.mi-error-msg {
  display: flex; align-items: center; gap: 5px;
  font-size: .75rem; color: #dc2626; margin-top: .35rem;
}
.mi-error-msg svg { fill: #dc2626; flex-shrink: 0; }

/* Bouton vérification */
.mi-btn-verify {
  width: 100%;
  background: linear-gradient(135deg, var(--green) 0%, var(--green) 100%);
  color: #fff; border: none;
  padding: .9rem 1.5rem;
  border-radius: 0;
  font-size: .95rem; font-weight: 700;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  transition: all .2s;
  box-shadow: var(--shadow);
  margin-bottom: 1.25rem;
}
.mi-btn-verify:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: var(--shadow);
}
.mi-btn-verify:disabled { opacity: .55; cursor: not-allowed; transform: none; }

.mi-help {
  font-size: .76rem; color: var(--muted); line-height: 1.7;
  display: flex; flex-direction: column; align-items: center; gap: 2px;
  border-top: 1px solid #f3f4f6; padding-top: 1rem;
  text-align: center;
}
.mi-help svg { fill: #d1d5db; margin-bottom: 2px; }
.mi-help-link { color: var(--green); font-weight: 600; text-decoration: none; }
.mi-help-link:hover { text-decoration: underline; }

/* -- Loading -- */
.loading-container {
  display: flex; align-items: center; justify-content: center;
  min-height: 60vh; padding: 2rem;
}
.loading-content { text-align: center; max-width: 400px; }
.loading-spinner {
  width: 3rem; height: 3rem;
  border: 4px solid var(--green-mist); border-top: 4px solid var(--green);
  border-radius: 50%; animation: spin 1s linear infinite;
  margin: 0 auto 1.5rem;
}
.loading-content h2 { font-size: 1.4rem; color: var(--ink); margin-bottom: .4rem; }
.loading-content p  { color: var(--muted); font-size: .9rem; }

/* -- Error -- */
.error-container {
  display: flex; align-items: center; justify-content: center;
  min-height: 60vh; padding: 2rem;
}
.error-content {
  text-align: center; max-width: 480px;
  background: #fff; padding: 2.5rem; border-radius: 0;
  box-shadow: var(--shadow);
}
.error-icon {
  width: 4rem; height: 4rem;
  background: #fef2f2; color: #dc2626; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1.25rem;
}
.error-content h2 { font-size: 1.35rem; color: var(--ink); margin-bottom: .4rem; }
.error-content p  { color: var(--muted); margin-bottom: 1.75rem; }
.error-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

/* -- Success -- */
.success-container {
  max-width: 860px; margin: 2rem auto; padding: 0 1rem 2rem;
}
.success-card {
  background: #fff; border-radius: 0;
  box-shadow: var(--shadow); overflow: hidden;
}
.success-hero {
  background: linear-gradient(145deg, var(--green) 0%, var(--green) 60%, var(--green) 100%);
  padding: 2rem 2rem 0;
}
.hero-top { display: flex; align-items: flex-start; gap: 1.25rem; margin-bottom: 1.5rem; }
.receipt-photo {
  width: 68px;
  height: 78px;
  flex-shrink: 0;
  overflow: hidden;
  border-radius: 0;
  border: 3px solid rgba(255,255,255,.9);
  background: rgba(255,255,255,.18);
  box-shadow: var(--shadow);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.1rem;
  font-weight: 900;
  letter-spacing: .04em;
}
.receipt-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.hero-text h1 { font-size: 1.9rem; font-weight: 700; color: #fff; margin-bottom: .3rem; }
.hero-text p  { font-size: .95rem; color: rgba(255,255,255,.85); line-height: 1.5; }
.hero-text p strong { color: #fff; }
.hero-bottom {
  display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;
  background: rgba(0,0,0,.12); margin: 0 -2rem; padding: 1.25rem 2rem;
}
.matricule-block { flex-shrink: 0; }
.badge-paid {
  display: inline-flex; align-items: center; gap: 5px;
  background: var(--green-2); color: #fff;
  padding: .3rem .85rem; border-radius: 0;
  font-weight: 700; font-size: .75rem; margin-bottom: .5rem;
}
.matricule-label { font-size: .7rem; color: rgba(255,255,255,.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
.matricule-value { font-size: 1.5rem; font-weight: 800; color: #fff; font-family: monospace; letter-spacing: 4px; }
.pdf-action-hero {
  flex: 1; display: flex; flex-direction: column; align-items: center;
  text-align: center; gap: .5rem;
}
.btn-pdf-main {
  background: #fff; color: var(--green); border: none;
  padding: .9rem 1.75rem; border-radius: 0;
  font-size: .95rem; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; gap: .5rem;
  transition: all .2s; box-shadow: var(--shadow);
}
.btn-pdf-main:hover:not(:disabled) { background: var(--green-mist); transform: translateY(-1px); box-shadow: var(--shadow); }
.btn-pdf-main:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.pdf-hint { font-size: .72rem; color: rgba(255,255,255,.65); }
.montant-bar {
  background: var(--green); padding: .85rem 1.75rem;
  display: flex; align-items: center; justify-content: space-between;
}
.montant-left { display: flex; flex-direction: column; }
.montant-label { font-size: .72rem; color: var(--green-mist); text-transform: uppercase; letter-spacing: .5px; }
.montant-value { font-size: 1.3rem; font-weight: 800; color: #fff; font-family: monospace; }
.montant-right { display: flex; flex-direction: column; align-items: flex-end; gap: 3px; }
.statut-badge { background: var(--green-2); color: #fff; padding: .25rem .75rem; border-radius: 0; font-size: .75rem; font-weight: 700; }
.operateur { font-size: .72rem; color: rgba(255,255,255,.65); }
.affectations-strip {
  display: flex; align-items: center;
  padding: 1rem 1.75rem; background: var(--gold-mist);
  border-bottom: 1px solid #fde68a; gap: 1rem;
}

.affectations-strip--pending .affectation-item { flex: 1 1 100%; }
.affectations-strip--pending .aff-value { line-height: 1.35; }
.affectation-item { display: flex; align-items: center; gap: .75rem; flex: 1; }
.aff-icon {
  width: 38px; height: 38px; border-radius: 0;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.aff-dortoir { background: var(--gold-mist); color: var(--gold); }
.aff-car     { background: var(--gold-mist); color: var(--gold); }
.aff-label { font-size: .7rem; color: #92400e; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
.aff-value { font-size: .9rem; font-weight: 700; color: #78350f; }
.aff-divider { width: 1px; background: #fde68a; height: 40px; }
.section { border-top: 1px solid var(--green-mist); }
.section-header {
  background: var(--green-mist); padding: .65rem 1.75rem;
  display: flex; align-items: center; gap: 8px;
  border-bottom: 1px solid var(--green-mist); color: var(--green);
}
.section-header svg  { fill: var(--green); }
.section-header span { font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.section-header--warning { background: var(--gold-mist); border-bottom-color: #fde68a; color: #92400e; }
.section-header--warning svg  { fill: var(--gold); }
.section-header--warning span { color: #92400e; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; }
.info-item {
  display: flex; align-items: center; gap: .85rem;
  padding: .9rem 1.75rem; border-bottom: 1px solid var(--surface);
}
.info-item:nth-child(odd) { border-right: 1px solid var(--surface); }
.info-icon {
  width: 34px; height: 34px; border-radius: 0;
  background: var(--green-mist); color: var(--green);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.info-icon svg { fill: var(--green); }
.info-content { display: flex; flex-direction: column; gap: 2px; }
.info-label { font-size: .68rem; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
.info-value { font-size: .88rem; color: var(--ink); font-weight: 600; }
.info-value.mono { font-family: monospace; letter-spacing: .5px; }
.consignes-body {
  padding: 1rem 1.75rem;
  display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
}
.consignes-title { font-size: .8rem; font-weight: 700; color: #78350f; margin-bottom: .4rem; }
.consignes-col ul { padding-left: 1rem; }
.consignes-col ul li { font-size: .78rem; color: #92400e; line-height: 1.7; }
.consignes-contacts {
  grid-column: 1 / -1;
  display: flex; flex-wrap: wrap; gap: .4rem .85rem;
  font-size: .78rem; color: #92400e;
  border-top: 1px solid #fde68a; padding-top: .75rem;
}
.contact-label { font-weight: 700; }
.btn {
  padding: .65rem 1.25rem; border-radius: 0;
  font-size: .85rem; font-weight: 600;
  cursor: pointer; transition: all .2s;
  border: 1.5px solid transparent;
  display: inline-flex; align-items: center; gap: .4rem; text-decoration: none;
}
.btn-primary  { background: var(--green); color: #fff; border-color: var(--green); }
.btn-primary:hover { background: var(--green); border-color: var(--green); }
.btn-secondary {
  background: rgba(255,255,255,.15); color: #fff; border-color: rgba(255,255,255,.4);
}
.btn-secondary:hover { background: rgba(255,255,255,.25); }
.btn-secondary-dark {
  background: #f3f4f6; color: var(--ink-soft); border-color: var(--line);
  padding: .65rem 1.25rem; border-radius: 0;
  font-size: .85rem; font-weight: 600; cursor: pointer; transition: all .2s;
  display: inline-flex; align-items: center; gap: .4rem;
}
.btn-secondary-dark:hover { background: var(--line); }
.btn-outline { background: transparent; color: var(--green); border-color: var(--green-mist); }
.btn-outline:hover { background: var(--green-mist); }
.secondary-actions {
  padding: 1.25rem 1.75rem;
  display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap;
  border-top: 1px solid var(--green-mist);
}
.page-footer { background: var(--green); padding: .85rem 1.5rem; text-align: center; }
.page-footer p { font-size: .72rem; color: rgba(255,255,255,.75); line-height: 1.8; }
.footer-slogan { color: #fff !important; font-weight: 700; font-size: .8rem !important; }
.spinner-small {
  width: 18px; height: 18px;
  border: 2px solid rgba(21,128,61,.3);
  border-top: 2px solid var(--green);
  border-radius: 50%; animation: spin 1s linear infinite;
}
.spinner-white {
  border-color: rgba(255,255,255,.3);
  border-top-color: #fff;
}

@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

/* -- Responsive -- */
@media (max-width: 700px) {
  .hero-top { flex-direction: column; }
  .hero-bottom { flex-direction: column; align-items: flex-start; }
  .pdf-action-hero { align-items: flex-start; width: 100%; }
  .btn-pdf-main { width: 100%; justify-content: center; }
  .info-grid { grid-template-columns: 1fr; }
  .info-item:nth-child(odd) { border-right: none; }
  .consignes-body { grid-template-columns: 1fr; }
  .affectations-strip { flex-direction: column; align-items: flex-start; }
  .aff-divider { width: 100%; height: 1px; }
  .header-content { flex-wrap: wrap; gap: .5rem; }
  .year-badge { display: none; }
  .matricule-card { padding: 1.75rem 1.25rem; }
}
@media (max-width: 480px) {
  .success-container { margin: 1rem auto; }
  .hero-text h1 { font-size: 1.5rem; }
  .matricule-value { font-size: 1.1rem; letter-spacing: 2px; }
  .secondary-actions { flex-direction: column; }
  .btn { width: 100%; justify-content: center; }
  .error-actions { flex-direction: column; }
  .error-actions .btn,
  .error-actions .btn-secondary-dark { width: 100%; justify-content: center; }
}
</style>
