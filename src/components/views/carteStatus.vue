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
                <p>Vérification de Carte</p>
              </div>
            </div>
          </div>
          <div class="header-right">
            <button @click="nouvelleInscription" class="btn btn-secondary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
              </svg>
              Nouvelle inscription
            </button>
          </div>
        </div>
      </header>
  
      <!-- Formulaire de saisie du matricule -->
      <div v-if="!isLoading && !cardStatus && !errorMessage" class="form-container">
        <div class="form-content">
          <div class="form-header">
            <div class="form-icon">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
              </svg>
            </div>
            <h1>Vérification du statut de votre carte</h1>
            <p>Entrez votre matricule pour vérifier le statut de votre carte de membre AEEMCI</p>
          </div>
  
          <form @submit.prevent="checkCardStatus" class="verification-form">
            <div class="input-group">
              <label for="matricule" class="input-label">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Matricule
              </label>
              <input
                id="matricule"
                v-model="matriculeInput"
                @input="handleMatriculeInput"
                type="text"
                placeholder="SUD-MAF-13-06300"
                maxlength="20"
                class="input-field"
                :class="{ 'input-error': inputError }"
                required
              />
              <div v-if="inputError" class="error-message">
                {{ inputError }}
              </div>
              <div class="input-help">
                Format attendu: SUD-MAF-13-063 (3 lettres - 3 lettres - 2 chiffres - chiffres)
              </div>
            </div>
  
            <button 
              type="submit" 
              class="btn btn-primary btn-large"
              :disabled="!isMatriculeValid || isLoading"
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
              Vérifier le statut
            </button>
          </form>
  
          <div class="help-section">
            <h3>Besoin d'aide ?</h3>
            <p>Si vous ne trouvez pas votre matricule ou si vous rencontrez des difficultés, contactez-nous :</p>
            <div class="contact-info">
              <div class="contact-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <span>27 33 75 42 12</span>
              </div>
              <div class="contact-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                <span>aeemci@yahoo.fr</span>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Loading State -->
      <div v-if="isLoading" class="loading-container">
        <div class="loading-content">
          <div class="loading-spinner"></div>
          <h2>Vérification en cours...</h2>
          <p>Veuillez patienter pendant que nous vérifions le statut de votre carte</p>
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
          <h2>Erreur</h2>
          <p>{{ errorMessage }}</p>
          <div class="error-actions">
            <button @click="retryCheck" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
              </svg>
              Réessayer
            </button>
            <button @click="resetForm" class="btn btn-secondary">
              Nouvelle vérification
            </button>
          </div>
        </div>
      </div>
  
      <!-- Success State - Carte Disponible -->
      <div v-else-if="cardStatus === 'etablie'" class="success-container">
        <div class="status-card status-available">
          <div class="status-header">
            <div class="status-icon status-icon-success">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <h1>Carte Disponible !</h1>
            <p class="status-message">Votre carte de membre AEEMCI est prête</p>
            <div class="matricule-display">
              <span class="matricule-label">Matricule:</span>
              <span class="matricule-value">{{ matriculeInput }}</span>
            </div>
            <div v-if="memberInfo" class="member-info-display">
              <div class="member-name">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>{{ memberInfo.prenom }} {{ memberInfo.nom }}</span>
              </div>
              <div v-if="memberInfo.contact" class="member-contact">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <span>{{ memberInfo.contact }}</span>
              </div>
            </div>
          </div>
  
          <div class="status-body">
            <div class="status-info">
              <div class="info-item">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Statut: Établie</h4>
                  <p>Votre carte a été créée et est disponible pour retrait</p>
                </div>
              </div>
  
              <div class="info-item">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Prochaine étape</h4>
                  <p>Rendez-vous au siège de l'AEEMCI pour récupérer votre carte physique</p>
                </div>
              </div>
            </div>
  
            <div class="action-buttons">
              <button @click="contactSecretariat" class="btn btn-primary btn-large">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                Contacter le siège
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Warning State - Carte Déjà Retirée -->
      <div v-else-if="cardStatus === 'retiree'" class="warning-container">
        <div class="status-card status-withdrawn">
          <div class="status-header">
            <div class="status-icon status-icon-warning">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
              </svg>
            </div>
            <h1>Carte Déjà Retirée</h1>
            <p class="status-message">{{ statusMessage }}</p>
            <p class="status-message" style="font-weight: bold;"></p>
            <div class="matricule-display">
              <span class="matricule-label">Matricule:</span>
              <span class="matricule-value">{{ matriculeInput }}</span>
            </div>
            <div v-if="memberInfo" class="member-info-display">
              <div class="member-name">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>{{ memberInfo.prenom }} {{ memberInfo.nom }}</span>
              </div>
              <div v-if="memberInfo.contact" class="member-contact">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <span>{{ memberInfo.contact }}</span>
              </div>
            </div>
          </div>
  
          <div class="status-body">
            <div class="status-info">
              <div class="info-item">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Statut: Retirée</h4>
                  <p>Votre carte a déjà été récupérée. Si vous ne l'avez pas en votre possession, contactez le support.</p>
                </div>
              </div>
  
              <div class="info-item">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Problème ?</h4>
                  <p>Si vous n'avez pas récupéré votre carte, contactez immédiatement le support</p>
                </div>
              </div>
            </div>
  
            <div class="action-buttons">
              <button @click="contactSupport" class="btn btn-primary btn-large">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Contacter le support
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Info State - Carte En Cours -->
      <div v-else-if="cardStatus === 'en_cours'" class="info-container">
        <div class="status-card status-pending">
          <div class="status-header">
            <div class="status-icon status-icon-info">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A1.5,1.5 0 0,1 10.5,15.5A1.5,1.5 0 0,1 12,14A1.5,1.5 0 0,1 13.5,15.5A1.5,1.5 0 0,1 12,17M12,10.5A1.5,1.5 0 0,1 10.5,9A1.5,1.5 0 0,1 12,7.5A1.5,1.5 0 0,1 13.5,9A1.5,1.5 0 0,1 12,10.5Z"/>
              </svg>
            </div>
            <h1>Carte En Cours d'Établissement</h1>
            <p class="status-message">Votre carte est en cours de préparation</p>
            <div class="matricule-display">
              <span class="matricule-label">Matricule:</span>
              <span class="matricule-value">{{ matriculeInput }}</span>
            </div>
            <div v-if="memberInfo" class="member-info-display">
              <div class="member-name">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>{{ memberInfo.prenom }} {{ memberInfo.nom }}</span>
              </div>
              <div v-if="memberInfo.contact" class="member-contact">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <span>{{ memberInfo.contact }}</span>
              </div>
            </div>
          </div>
  
          <div class="status-body">
            <div class="status-info">
              <div class="info-item">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A1.5,1.5 0 0,1 10.5,15.5A1.5,1.5 0 0,1 12,14A1.5,1.5 0 0,1 13.5,15.5A1.5,1.5 0 0,1 12,17M12,10.5A1.5,1.5 0 0,1 10.5,9A1.5,1.5 0 0,1 12,7.5A1.5,1.5 0 0,1 13.5,9A1.5,1.5 0 0,1 12,10.5Z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Statut: En cours</h4>
                  <p>Votre carte est actuellement en cours d'établissement. Veuillez patienter.</p>
                </div>
              </div>
  
              <div class="info-item">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Délai estimé</h4>
                  <p>Les cartes sont généralement prêtes sous 1-3 jours ouvrables</p>
                </div>
              </div>
            </div>
  
            <div class="action-buttons">
              <button @click="checkAgain" class="btn btn-primary btn-large">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                </svg>
                Vérifier à nouveau
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Actions communes -->
      <div v-if="cardStatus" class="common-actions">
        <button @click="resetForm" class="btn btn-outline">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          Vérifier un autre matricule
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import axios from 'axios'
  
  // État de l'application
  const matriculeInput = ref('')
  const cardStatus = ref(null) // 'etablie', 'retiree', 'en_cours'
  const isLoading = ref(false)
  const errorMessage = ref('')
  const inputError = ref('')
  const memberInfo = ref(null)
  const statusMessage = ref('')
  const nextAction = ref('')
  const cardDates = ref(null)
  const paymentInfo = ref(null)
  
  // Ajouter cette fonction de formatage automatique du matricule
  const formatMatricule = (value) => {
  const cleaned = value.toUpperCase().replace(/[^A-Z0-9]/g, '')

  const part1 = cleaned.slice(0, 3)
  const part2 = cleaned.slice(3, 6)
  const part3 = cleaned.slice(6, 8)
  const part4 = cleaned.slice(8, 11)
  const part5 = cleaned.slice(11, 17) // jusqu'à 6 caractères en plus

  let formatted = part1
  if (part2) formatted += '-' + part2
  if (part3) formatted += '-' + part3
  if (part4) formatted += '-' + part4
  if (part5) formatted += part5 // sans tiret ici

  return formatted
}
  
  // Validation du matricule
  const isMatriculeValid = computed(() => {
    const pattern = /^[A-Z]{3}-[A-Z]{3}-\d{2}-\d{3}[A-Z0-9]{0,6}$/
    return pattern.test(matriculeInput.value)
  })
  
  // Modifier la fonction de validation
  const validateMatricule = () => {
    inputError.value = ''
    
    if (matriculeInput.value && !isMatriculeValid.value) {
      inputError.value = 'Format invalide. Utilisez le format: SUD-MAF-13-063'
    }
  }
  
  // Ajouter une fonction pour gérer la saisie avec formatage automatique
  const handleMatriculeInput = (event) => {
    const formatted = formatMatricule(event.target.value)
    matriculeInput.value = formatted
    validateMatricule()
  }
  
  // Vérification du statut de la carte
  const checkCardStatus = async () => {
    if (!isMatriculeValid.value) {
      inputError.value = 'Veuillez entrer un matricule valide'
      return
    }
  
    isLoading.value = true
    errorMessage.value = ''
    cardStatus.value = null
    memberInfo.value = null
  
    try {
      // Appel à l'API pour vérifier le statut
      const response = await axios.post('https://sogetrag.com/api/check_card_status.php', {
        matricule: matriculeInput.value.trim().toUpperCase()
      })
  
      if (response.data.success) {
        cardStatus.value = response.data.status
        memberInfo.value = response.data.member_info
        statusMessage.value = response.data.status_message
        nextAction.value = response.data.next_action
        cardDates.value = response.data.dates
        paymentInfo.value = response.data.payment_info
        
        console.log('Statut de la carte:', response.data)
      } else {
        errorMessage.value = response.data.message || 'Matricule non trouvé'
      }
    } catch (error) {
      console.error('Erreur lors de la vérification:', error)
      
      if (error.response?.status === 404) {
        errorMessage.value = 'Aucune carte trouvée avec ce matricule'
      } else if (error.response?.status === 402) {
        errorMessage.value = 'Paiement non effectué pour ce matricule. Veuillez finaliser votre paiement.'
      } else if (error.response?.status === 400) {
        errorMessage.value = error.response.data.message || 'Matricule invalide'
      } else {
        errorMessage.value = 'Erreur lors de la vérification. Veuillez réessayer.'
      }
    } finally {
      isLoading.value = false
    }
  }
  
  // Actions
  const retryCheck = () => {
    errorMessage.value = ''
    checkCardStatus()
  }
  
  const resetForm = () => {
    matriculeInput.value = ''
    cardStatus.value = null
    errorMessage.value = ''
    inputError.value = ''
  }
  
  const checkAgain = () => {
    checkCardStatus()
  }
  
  const contactSecretariat = () => {
    window.open('tel:+2252733754212', '_blank')
  }
  
  const contactSupport = () => {
    window.open('mailto:aeemci@yahoo.fr?subject=Problème avec la carte - ' + matriculeInput.value, '_blank')
  }
  
  const nouvelleInscription = () => {
    window.location.href = 'https://aeemci.sastock.com/carte_membre'
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
  
  /* Form Container */
  .form-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 80vh;
    padding: 2rem;
  }
  
  .form-content {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 10px 10px -5px rgb(0 0 0 / 0.04);
    padding: 3rem;
    max-width: 600px;
    width: 100%;
  }
  
  .form-header {
    text-align: center;
    margin-bottom: 2rem;
  }
  
  .form-icon {
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
  
  .form-header h1 {
    font-size: 1.875rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .form-header p {
    color: #6b7280;
    font-size: 1rem;
  }
  
  /* Form */
  .verification-form {
    margin-bottom: 2rem;
  }
  
  .input-group {
    margin-bottom: 1.5rem;
  }
  
  .input-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }
  
  /* Améliorer le style de l'input pour le formatage automatique */
  .input-field {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-family: 'Courier New', monospace;
    text-transform: uppercase;
    transition: all 0.2s ease;
    letter-spacing: 0.05em;
  }
  
  .input-field::placeholder {
    color: #9ca3af;
    font-family: 'Courier New', monospace;
  }
  
  .input-field.input-error {
    border-color: #dc2626;
  }
  
  .error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }
  
  .input-help {
    color: #6b7280;
    font-size: 0.75rem;
    margin-top: 0.5rem;
  }
  
  /* Help Section */
  .help-section {
    background: #f9fafb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
  }
  
  .help-section h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .help-section p {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
  }
  
  .contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .contact-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #16a34a;
    font-size: 0.875rem;
    font-weight: 500;
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
  
  /* Error State */
  .error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 2rem;
  }
  
  .error-content {
    text-align: center;
    max-width: 500px;
    background: #ffffff;
    padding: 3rem;
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.04);
  }
  
  .error-icon {
    width: 4rem;
    height: 4rem;
    background: #fef2f2;
    color: #dc2626;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
  }
  
  .error-content h2 {
    font-size: 1.5rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .error-content p {
    color: #6b7280;
    margin-bottom: 2rem;
  }
  
  .error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
  }
  
  /* Status Containers */
  .success-container,
  .warning-container,
  .info-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 70vh;
    padding: 2rem;
  }
  
  .status-card {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 10px 10px -5px rgb(0 0 0 / 0.04);
    overflow: hidden;
    max-width: 600px;
    width: 100%;
  }
  
  /* Status Headers */
  .status-header {
    padding: 3rem 2rem;
    text-align: center;
  }
  
  .status-available .status-header {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
  }
  
  .status-withdrawn .status-header {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  }
  
  .status-pending .status-header {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  }
  
  .status-icon {
    width: 5rem;
    height: 5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }
  
  .status-icon-success {
    background: #16a34a;
    color: #ffffff;
  }
  
  .status-icon-warning {
    background: #f59e0b;
    color: #ffffff;
  }
  
  .status-icon-info {
    background: #3b82f6;
    color: #ffffff;
  }
  
  .status-header h1 {
    font-size: 2rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .status-message {
    font-size: 1.125rem;
    color: #4b5563;
    margin-bottom: 1.5rem;
  }
  
  .matricule-display {
    background: #ffffff;
    border-radius: 0.5rem;
    padding: 1rem;
    border: 2px solid rgba(0, 0, 0, 0.1);
  }
  
  .matricule-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
  }
  
  .matricule-value {
    font-size: 1.25rem;
    font-weight: bold;
    color: #16a34a;
    font-family: 'Courier New', monospace;
    margin-left: 0.5rem;
  }
  
  /* Styles pour l'affichage des informations du membre */
  .member-info-display {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
    border: 1px solid rgba(0, 0, 0, 0.1);
  }
  
  .member-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }
  
  .member-name svg {
    color: #16a34a;
  }
  
  .member-contact {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
  }
  
  .member-contact svg {
    color: #6b7280;
  }
  
  /* Status Body */
  .status-body {
    padding: 2rem;
  }
  
  .status-info {
    margin-bottom: 2rem;
  }
  
  .info-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
  }
  
  .info-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .status-available .info-icon {
    background: #dcfce7;
    color: #16a34a;
  }
  
  .status-withdrawn .info-icon {
    background: #fef3c7;
    color: #f59e0b;
  }
  
  .status-pending .info-icon {
    background: #dbeafe;
    color: #3b82f6;
  }
  
  .info-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
  }
  
  .info-content p {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.5;
  }
  
  /* Action Buttons */
  .action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
  }
  
  .common-actions {
    display: flex;
    justify-content: center;
    padding: 2rem;
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
  
    .form-content {
      padding: 2rem 1.5rem;
      margin: 1rem;
    }
  
    .form-header h1 {
      font-size: 1.5rem;
    }
  
    .status-header {
      padding: 2rem 1rem;
    }
  
    .status-header h1 {
      font-size: 1.5rem;
    }
  
    .status-body {
      padding: 1.5rem;
    }
  
    .action-buttons {
      flex-direction: column;
    }
  
    .error-actions {
      flex-direction: column;
    }
  
    .contact-info {
      align-items: center;
    }
  }
  
  @media (max-width: 480px) {
    .form-content {
      padding: 1.5rem 1rem;
    }
  
    .status-header h1 {
      font-size: 1.25rem;
    }
  
    .info-item {
      flex-direction: column;
      text-align: center;
    }
  
    .btn {
      width: 100%;
    }
  }
  </style>
  