<template>
  <div class="verification-page">
    <!-- Hero Header -->
    <header class="page-hero">
      <div class="hero-background">
        <div class="pattern pattern-1"></div>
        <div class="pattern pattern-2"></div>
        <div class="pattern pattern-3"></div>
      </div>
      <div class="hero-container">
        <div class="hero-content">
          <div class="brand-badge">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
            </svg>
          </div>
          <h1 class="hero-title">Vérification de <span class="gradient-text">Carte</span></h1>
          <p class="hero-description">Vérifiez instantanément le statut de votre carte de membre AEEMCI</p>
        </div>
        <button @click="nouvelleInscription" class="btn-inscription">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          Nouvelle inscription
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="page-main">
      <div class="content-wrapper">
        <!-- Search Form -->
        <div v-if="!isLoading && !cardStatus && !errorMessage" class="search-container">
          <div class="search-card">
            <div class="card-header">
              <div class="header-icon">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                </svg>
              </div>
              <h2>Entrez votre matricule</h2>
              <p>Vérifiez le statut de votre carte en quelques secondes</p>
            </div>

            <form @submit.prevent="checkCardStatus" class="search-form">
              <div class="form-group">
                <label class="form-label">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2z"/>
                  </svg>
                  Matricule
                </label>
                <div class="input-container">
                  <input
                    v-model="matriculeInput"
                    @input="handleMatriculeInput"
                    type="text"
                    placeholder="SUD-MAF-13-063"
                    maxlength="20"
                    class="form-input"
                    :class="{ 'has-error': inputError }"
                    required
                  />
                  <div v-if="isMatriculeValid" class="input-success">✓</div>
                </div>
                <div v-if="inputError" class="error-text">{{ inputError }}</div>
                <div class="input-hint">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                  </svg>
                  Format: XXX-XXX-XX-XXX (ex: SUD-MAF-13-063)
                </div>
              </div>

              <button type="submit" class="btn btn-primary" :disabled="!isMatriculeValid || isLoading">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                Vérifier le statut
              </button>
            </form>

            <div class="help-card">
              <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                </svg>
                Besoin d'aide ?
              </h3>
              <p>Contactez-nous pour toute assistance</p>
              <div class="contact-grid">
                <a href="tel:+2252733754212" class="contact-item">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                  27 33 75 42 12
                </a>
                <a href="mailto:aeemci@yahoo.fr" class="contact-item">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                  aeemci@yahoo.fr
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="state-container">
          <div class="state-card loading-card">
            <div class="loading-animation">
              <div class="spinner"></div>
              <div class="pulse pulse-1"></div>
              <div class="pulse pulse-2"></div>
              <div class="pulse pulse-3"></div>
            </div>
            <h2>Vérification en cours</h2>
            <p>Connexion à la base de données...</p>
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="errorMessage" class="state-container">
          <div class="state-card error-card">
            <div class="state-icon error-icon">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
              </svg>
            </div>
            <h2>Matricule introuvable</h2>
            <p class="state-message">{{ errorMessage }}</p>
            <div class="state-actions">
              <button @click="retryCheck" class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                </svg>
                Réessayer
              </button>
              <button @click="resetForm" class="btn btn-outline">Nouvelle recherche</button>
            </div>
          </div>
        </div>

        <!-- Success - Établie -->
        <div v-else-if="cardStatus === 'etablie'" class="state-container">
          <div class="state-card success-card">
            <div class="state-icon success-icon">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <h2>Carte Disponible !</h2>
            <p class="state-message">Votre carte est prête pour le retrait</p>
            
            <div class="member-card">
              <div class="card-badge success-badge">{{ matriculeInput }}</div>
              <div v-if="memberInfo" class="member-details">
                <div class="detail-row">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                  <span class="member-name">{{ memberInfo.prenom }} {{ memberInfo.nom }}</span>
                </div>
                <div v-if="memberInfo.contact" class="detail-row secondary">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                  <span>{{ memberInfo.contact }}</span>
                </div>
              </div>
            </div>

            <div class="info-boxes">
              <div class="info-box">
                <div class="box-icon success">✓</div>
                <div class="box-content">
                  <h4>Statut</h4>
                  <p>Carte établie</p>
                </div>
              </div>
              <div class="info-box">
                <div class="box-icon">📍</div>
                <div class="box-content">
                  <h4>Prochaine étape</h4>
                  <p>Retrait au siège</p>
                </div>
              </div>
            </div>

            <button @click="contactSecretariat" class="btn btn-primary btn-large">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
              </svg>
              Contacter le siège
            </button>
          </div>
        </div>

        <!-- Warning - Retirée -->
        <div v-else-if="cardStatus === 'retiree'" class="state-container">
          <div class="state-card warning-card">
            <div class="state-icon warning-icon">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
              </svg>
            </div>
            <h2>Carte Déjà Retirée</h2>
            <p class="state-message">Cette carte a déjà été récupérée</p>
            
            <div class="member-card">
              <div class="card-badge warning-badge">{{ matriculeInput }}</div>
              <div v-if="memberInfo" class="member-details">
                <div class="detail-row">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                  <span class="member-name">{{ memberInfo.prenom }} {{ memberInfo.nom }}</span>
                </div>
                <div v-if="memberInfo.contact" class="detail-row secondary">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                  <span>{{ memberInfo.contact }}</span>
                </div>
              </div>
            </div>

            <div class="alert-banner">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
              </svg>
              <div>
                <strong>Attention</strong>
                <p>Si vous n'avez pas récupéré votre carte, contactez le support</p>
              </div>
            </div>

            <button @click="contactSupport" class="btn btn-primary btn-large">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
              </svg>
              Contacter le support
            </button>
          </div>
        </div>

        <!-- Info - En cours -->
        <div v-else-if="cardStatus === 'en_cours'" class="state-container">
          <div class="state-card info-card">
            <div class="state-icon info-icon">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
              </svg>
            </div>
            <h2>En Cours d'Établissement</h2>
            <p class="state-message">Votre carte est en préparation</p>
            
            <div class="member-card">
              <div class="card-badge info-badge">{{ matriculeInput }}</div>
              <div v-if="memberInfo" class="member-details">
                <div class="detail-row">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                  <span class="member-name">{{ memberInfo.prenom }} {{ memberInfo.nom }}</span>
                </div>
                <div v-if="memberInfo.contact" class="detail-row secondary">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                  <span>{{ memberInfo.contact }}</span>
                </div>
              </div>
            </div>

            <div class="info-boxes">
              <div class="info-box">
                <div class="box-icon">⏳</div>
                <div class="box-content">
                  <h4>Statut</h4>
                  <p>En préparation</p>
                </div>
              </div>
              <div class="info-box">
                <div class="box-icon">📅</div>
                <div class="box-content">
                  <h4>Délai estimé</h4>
                  <p>1-3 jours ouvrables</p>
                </div>
              </div>
            </div>

            <button @click="checkAgain" class="btn btn-primary btn-large">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
              </svg>
              Vérifier à nouveau
            </button>
          </div>
        </div>

        <!-- New Search -->
        <div v-if="cardStatus" class="new-search-container">
          <button @click="resetForm" class="btn btn-outline-large">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            Vérifier un autre matricule
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const matriculeInput = ref('')
const cardStatus = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')
const inputError = ref('')
const memberInfo = ref(null)
const statusMessage = ref('')

const formatMatricule = (value) => {
  const cleaned = value.toUpperCase().replace(/[^A-Z0-9]/g, '')
  const part1 = cleaned.slice(0, 3)
  const part2 = cleaned.slice(3, 6)
  const part3 = cleaned.slice(6, 8)
  const part4 = cleaned.slice(8, 11)
  const part5 = cleaned.slice(11, 17)

  let formatted = part1
  if (part2) formatted += '-' + part2
  if (part3) formatted += '-' + part3
  if (part4) formatted += '-' + part4
  if (part5) formatted += part5

  return formatted
}

const isMatriculeValid = computed(() => {
  const pattern = /^[A-Z]{3}-[A-Z]{3}-\d{2}-\d{3}[A-Z0-9]{0,6}$/
  return pattern.test(matriculeInput.value)
})

const validateMatricule = () => {
  inputError.value = ''
  if (matriculeInput.value && !isMatriculeValid.value) {
    inputError.value = 'Format invalide'
  }
}

const handleMatriculeInput = (event) => {
  const formatted = formatMatricule(event.target.value)
  matriculeInput.value = formatted
  validateMatricule()
}

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
    const response = await axios.post('https://api.aeemci-ce.ci/check_card_status.php', {
      matricule: matriculeInput.value.trim().toUpperCase()
    })

    if (response.data.success) {
      cardStatus.value = response.data.status
      memberInfo.value = response.data.member_info
      statusMessage.value = response.data.status_message
    } else {
      errorMessage.value = response.data.message || 'Matricule non trouvé'
    }
  } catch (error) {
    console.error('Erreur:', error)
    if (error.response?.status === 404) {
      errorMessage.value = 'Aucune carte trouvée avec ce matricule'
    } else if (error.response?.status === 402) {
      errorMessage.value = 'Paiement non effectué. Veuillez finaliser votre paiement.'
    } else if (error.response?.status === 400) {
      errorMessage.value = error.response.data.message || 'Matricule invalide'
    } else {
      errorMessage.value = 'Erreur lors de la vérification'
    }
  } finally {
    isLoading.value = false
  }
}

const retryCheck = () => {
  errorMessage.value = ''
  checkCardStatus()
}

const resetForm = () => {
  matriculeInput.value = ''
  cardStatus.value = null
  errorMessage.value = ''
  inputError.value = ''
  memberInfo.value = null
}

const checkAgain = () => checkCardStatus()
const contactSecretariat = () => window.open('tel:+2252733754212', '_blank')
const contactSupport = () => window.open('mailto:aeemci@yahoo.fr?subject=Problème carte - ' + matriculeInput.value, '_blank')
const nouvelleInscription = () => window.location.href = 'https://www.aeemci-ce.ci/carte_membre'
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@300;400;600;700;900&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

.verification-page {
  min-height: 100vh;
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  font-family: var(--display);
}

/* Hero */
.page-hero {
  position: relative;
  background: linear-gradient(135deg, var(--green), var(--green));
  padding: 4rem 2rem 3rem;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  overflow: hidden;
  pointer-events: none;
}

.pattern {
  position: absolute;
  border-radius: 50%;
  opacity: 0.1;
  animation: float 20s ease-in-out infinite;
}

.pattern-1 {
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, var(--gold), transparent);
  top: -150px;
  left: -100px;
}

.pattern-2 {
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, var(--gold), transparent);
  top: -100px;
  right: -150px;
  animation-delay: -5s;
}

.pattern-3 {
  width: 250px;
  height: 250px;
  background: radial-gradient(circle, var(--gold), transparent);
  bottom: -100px;
  left: 50%;
  animation-delay: -10s;
}

@keyframes float {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33% { transform: translate(30px, -30px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
}

.hero-container {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 1;
}

.hero-content {
  text-align: center;
  color: white;
  margin-bottom: 2rem;
}

.brand-badge {
  width: 90px;
  height: 90px;
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  border: 3px solid rgba(255,255,255,0.3);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  animation: pulse-glow 3s ease-in-out infinite;
}

@keyframes pulse-glow {
  0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.4); }
  50% { box-shadow: 0 0 0 20px rgba(255,255,255,0); }
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 900;
  margin-bottom: 1rem;
  line-height: 1.1;
}

.gradient-text {
  background: linear-gradient(135deg, var(--gold), var(--gold));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-description {
  font-size: 1.3rem;
  opacity: 0.95;
}

.btn-inscription {
  padding: 1rem 2rem;
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255,255,255,0.3);
  border-radius: 0;
  color: white;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  font-family: var(--display);
}

.btn-inscription:hover {
  background: rgba(255,255,255,0.3);
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

/* Main */
.page-main {
  padding: 3rem 1.5rem;
}

.content-wrapper {
  max-width: 800px;
  margin: 0 auto;
}

/* Search Container */
.search-container {
  margin-top: -3rem;
}

.search-card {
  background: white;
  border-radius: 0;
  box-shadow: var(--shadow);
  overflow: hidden;
}

.card-header {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 3rem 2rem;
  text-align: center;
}

.header-icon {
  width: 100px;
  height: 100px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  color: var(--green);
  box-shadow: var(--shadow);
}

.card-header h2 {
  font-size: 2.2rem;
  font-weight: 900;
  color: var(--ink);
  margin-bottom: 0.5rem;
}

.card-header p {
  font-size: 1.1rem;
  color: var(--muted);
}

/* Form */
.search-form {
  padding: 3rem 2rem;
}

.form-group {
  margin-bottom: 2rem;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 700;
  color: var(--ink-soft);
  margin-bottom: 0.75rem;
  font-size: 1rem;
}

.input-container {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 1.25rem 3rem 1.25rem 1.25rem;
  border: 2px solid var(--line);
  border-radius: 0;
  font-size: 1.2rem;
  font-family: 'Courier New', monospace;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: var(--green);
  box-shadow: 0 0 0 4px rgba(1,132,4,0.1);
}

.form-input.has-error {
  border-color: #dc2626;
}

.input-success {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  width: 32px;
  height: 32px;
  background: var(--green);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}

.error-text {
  color: #dc2626;
  font-size: 0.9rem;
  margin-top: 0.5rem;
}

.input-hint {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  color: var(--muted);
  margin-top: 0.75rem;
}

.input-hint svg {
  color: var(--green);
}

/* Buttons */
.btn {
  padding: 1.25rem 2rem;
  border-radius: 0;
  font-size: 1.1rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  font-family: var(--display);
  width: 100%;
}

.btn-primary {
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  box-shadow: var(--shadow);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-3px);
  box-shadow: var(--shadow);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.btn-outline {
  background: transparent;
  color: var(--green);
  border: 2px solid var(--green);
}

.btn-outline:hover {
  background: rgba(1,132,4,0.1);
}

.btn-large {
  padding: 1.5rem 3rem;
  font-size: 1.2rem;
}

.btn-outline-large {
  padding: 1rem 2rem;
  background: transparent;
  color: var(--green);
  border: 2px solid var(--green);
  border-radius: 0;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  font-family: var(--display);
}

.btn-outline-large:hover {
  background: rgba(1,132,4,0.1);
}

/* Help Card */
.help-card {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border-radius: 0;
  padding: 2rem;
  margin-top: 2rem;
}

.help-card h3 {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 0.5rem;
}

.help-card p {
  color: var(--muted);
  margin-bottom: 1.5rem;
}

.contact-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.contact-item {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  padding: 1rem;
  background: white;
  border: 2px solid transparent;
  border-radius: 0;
  color: var(--green);
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}

.contact-item:hover {
  border-color: var(--green);
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

/* State Container */
.state-container {
  margin-top: -3rem;
}

.state-card {
  background: white;
  border-radius: 0;
  box-shadow: var(--shadow);
  padding: 4rem 3rem;
  text-align: center;
}

.state-icon {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  box-shadow: var(--shadow);
}

.success-icon {
  background: var(--green);
  color: white;
}

.warning-icon {
  background: var(--gold);
  color: white;
}

.info-icon {
  background: #3b82f6;
  color: white;
}

.error-icon {
  background: #dc2626;
  color: white;
}

.state-card h2 {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--ink);
  margin-bottom: 1rem;
}

.state-message {
  font-size: 1.2rem;
  color: var(--muted);
  margin-bottom: 2.5rem;
}

/* Loading Animation */
.loading-card {
  padding: 5rem 3rem;
}

.loading-animation {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto 2rem;
}

.spinner {
  width: 100%;
  height: 100%;
  border: 6px solid var(--line);
  border-top-color: var(--green);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.pulse {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 3px solid var(--green);
  border-radius: 50%;
  opacity: 0;
  animation: pulse 2s ease-out infinite;
}

.pulse-2 { animation-delay: 0.7s; }
.pulse-3 { animation-delay: 1.4s; }

@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  100% { transform: scale(1.5); opacity: 0; }
}

/* Member Card */
.member-card {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border-radius: 0;
  padding: 2rem;
  margin-bottom: 2rem;
}

.card-badge {
  padding: 0.875rem 2rem;
  border-radius: 0;
  font-family: 'Courier New', monospace;
  font-size: 1.3rem;
  font-weight: 700;
  display: inline-block;
  margin-bottom: 1.5rem;
}

.success-badge {
  background: var(--green);
  color: white;
}

.warning-badge {
  background: var(--gold);
  color: white;
}

.info-badge {
  background: #3b82f6;
  color: white;
}

.member-details {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.detail-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--ink);
}

.detail-row.secondary {
  font-size: 1rem;
  font-weight: 600;
  color: var(--muted);
}

.detail-row svg {
  color: var(--green);
  flex-shrink: 0;
}

/* Info Boxes */
.info-boxes {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
  margin-bottom: 2.5rem;
}

.info-box {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border-radius: 0;
  padding: 2rem 1.5rem;
  text-align: center;
}

.box-icon {
  width: 60px;
  height: 60px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  font-size: 2rem;
  box-shadow: var(--shadow);
}

.box-icon.success {
  background: var(--green);
  color: white;
}

.box-content h4 {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 0.5rem;
}

.box-content p {
  font-size: 0.95rem;
  color: var(--muted);
}

/* Alert Banner */
.alert-banner {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  background: rgba(245,158,11,0.1);
  border: 2px solid rgba(245,158,11,0.3);
  border-radius: 0;
  padding: 1.5rem;
  margin-bottom: 2rem;
  text-align: left;
}

.alert-banner svg {
  color: var(--gold);
  flex-shrink: 0;
}

.alert-banner strong {
  display: block;
  color: #92400e;
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}

.alert-banner p {
  color: #b45309;
  font-size: 0.95rem;
  margin: 0;
}

/* State Actions */
.state-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.state-actions .btn {
  width: auto;
  min-width: 200px;
}

/* New Search */
.new-search-container {
  text-align: center;
  margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .card-header {
    padding: 2rem 1.5rem;
  }
  
  .search-form {
    padding: 2rem 1.5rem;
  }
  
  .state-card {
    padding: 3rem 2rem;
  }
  
  .state-card h2 {
    font-size: 2rem;
  }
  
  .contact-grid,
  .info-boxes {
    grid-template-columns: 1fr;
  }
  
  .state-actions {
    flex-direction: column;
  }
  
  .state-actions .btn {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .card-header h2 {
    font-size: 1.75rem;
  }
  
  .state-card h2 {
    font-size: 1.75rem;
  }
  
  .btn {
    padding: 1rem 1.5rem;
    font-size: 1rem;
  }
  
  .btn-large {
    padding: 1.25rem 2rem;
    font-size: 1.1rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
</style>