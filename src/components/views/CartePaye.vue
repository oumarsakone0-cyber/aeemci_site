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
              <p>Confirmation d'Inscription</p>
            </div>
          </div>
        </div>
        <button @click="nouvelleInscription" class="btn btn-header">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          Nouvelle inscription
        </button>
      </div>
    </header>

    <!-- Loading State -->
    <div v-if="isLoading" class="state-container">
      <div class="state-content loading-state">
        <div class="loading-animation">
          <div class="loading-circle"></div>
          <div class="loading-circle"></div>
          <div class="loading-circle"></div>
        </div>
        <h2>Validation en cours...</h2>
        <p>Vérification de votre paiement et récupération de vos informations</p>
        <div class="progress-bar">
          <div class="progress-fill"></div>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="errorMessage" class="state-container">
      <div class="state-content error-state">
        <div class="error-animation">
          <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
        </div>
        <h2>Oups ! Une erreur est survenue</h2>
        <p class="error-message">{{ errorMessage }}</p>
        <div class="error-actions">
          <button @click="retryValidation" class="btn btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
            </svg>
            Réessayer
          </button>
          <button @click="nouvelleInscription" class="btn btn-secondary">
            Nouvelle inscription
          </button>
        </div>
      </div>
    </div>

    <!-- Success State -->
    <div v-else-if="membre" class="success-container">
      <!-- Success Animation Banner -->
      <div class="success-banner">
        <div class="confetti"></div>
        <div class="success-badge">
          <div class="badge-circle">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </div>
          <h1>Félicitations !</h1>
          <p>Votre inscription a été validée avec succès</p>
        </div>
      </div>

      <!-- Main Content Card -->
      <div class="content-card">
        <!-- Matricule Badge -->
        <div class="matricule-highlight">
          <div class="matricule-badge">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            <span>Paiement validé</span>
          </div>
          <div class="matricule-box">
            <span class="matricule-label">Votre matricule</span>
            <span class="matricule-value">{{ membre.matricule_gen }}</span>
          </div>
        </div>

        <!-- Member Information Grid -->
        <div class="info-section">
          <h3 class="section-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Informations personnelles
          </h3>
          
          <div class="info-grid">
            <div class="info-item">
              <div class="info-icon bg-blue">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                </svg>
              </div>
              <div class="info-details">
                <span class="info-label">Nom complet</span>
                <span class="info-value">{{ membre.prenom }} {{ membre.nom }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon bg-green">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
              </div>
              <div class="info-details">
                <span class="info-label">Contact</span>
                <span class="info-value">{{ membre.contact || 'Non renseigné' }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon bg-purple">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
              </div>
              <div class="info-details">
                <span class="info-label">Secrétariat</span>
                <span class="info-value">{{ membre.region || membre.secretariat_debut || 'Non renseigné' }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon bg-orange">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="info-details">
                <span class="info-label">Statut</span>
                <span class="info-value">{{ getStatutLabel(membre.je_suis) }}</span>
              </div>
            </div>

            <div v-if="membre.qualite_membre" class="info-item">
              <div class="info-icon bg-teal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="info-details">
                <span class="info-label">Poste</span>
                <span class="info-value">{{ membre.qualite_membre }}</span>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon bg-indigo">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                </svg>
              </div>
              <div class="info-details">
                <span class="info-label">Date d'inscription</span>
                <span class="info-value">{{ formatDate(membre.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-section">
          <div class="payment-card">
            <div class="payment-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
              </svg>
            </div>
            <div class="payment-details">
              <h4>Paiement validé</h4>
              <p class="payment-amount">1 000 F CFA</p>
              <p class="payment-status">
                <span class="status-badge">{{ membre.statut_paiement }}</span>
              </p>
              <p v-if="membre.ref_paiement || membre.transaction_id" class="payment-ref">
                Réf: {{ membre.ref_paiement || membre.transaction_id }}
              </p>
            </div>
          </div>
        </div>

        <!-- Main Action -->
        <div class="main-action">
          <button
            @click="generatePDF"
            :disabled="isGeneratingPDF"
            class="btn btn-primary btn-large"
          >
            <div v-if="isGeneratingPDF" class="btn-spinner"></div>
            <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
            </svg>
            <span>{{ isGeneratingPDF ? 'Génération en cours...' : 'Télécharger mon reçu PDF' }}</span>
          </button>
        </div>

        <!-- Important Notes -->
        <div class="notes-section">
          <div class="note-card success-note">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
            </svg>
            <div>
              <h4>Inscription confirmée !</h4>
              <p>Conservez précieusement votre matricule <strong>{{ membre.matricule_gen }}</strong>. Il vous sera demandé lors des activités AEEMCI.</p>
            </div>
          </div>

          <div class="note-card info-note">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
            </svg>
            <div>
              <h4>Prochaines étapes</h4>
              <p>Téléchargez votre reçu PDF et conservez-le. Vous recevrez également un email de confirmation.</p>
            </div>
          </div>
        </div>

        <!-- Secondary Actions -->
        <div class="secondary-actions">
          <button @click="shareCard" class="btn btn-outline">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
            </svg>
            Partager
          </button>

          <button @click="contactSupport" class="btn btn-outline">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/>
            </svg>
            Support
          </button>

          <button @click="viewMemberArea" class="btn btn-outline">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
            </svg>
            Espace membre
          </button>
        </div>
      </div>
    </div>

    <!-- Hidden component for PDF generation -->
    <div class="hidden">
      <FicheMembre v-if="membre" ref="ficheRef" :membre="membre" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import QRCode from 'qrcode'
import axios from 'axios'

// Components
import FicheMembre from './FicheMembre.vue'

// Route and state
const route = useRoute()
const ficheRef = ref(null)

// Application state
const membre = ref(null)
const isLoading = ref(true)
const errorMessage = ref('')
const isGeneratingPDF = ref(false)

// Get matricule from URL
const matricule = route.params.matricule || ''

// Validate and load member data
const validateAndLoadMemberData = async () => {
  if (!matricule) {
    errorMessage.value = 'Matricule manquant dans l\'URL'
    isLoading.value = false
    return
  }

  try {
    isLoading.value = true
    errorMessage.value = ''

    const response = await axios.post('https://api.aeemci-ce.ci/update_payment_status.php', {
      matricule: matricule
    })
    
    if (response.data.success) {
      membre.value = response.data.membre
      console.log('✅ Paiement validé:', response.data)
    } else {
      errorMessage.value = response.data.message || 'Erreur lors de la validation du paiement'
    }
  } catch (error) {
    console.error('❌ Erreur validation:', error)
    if (error.response?.status === 404) {
      errorMessage.value = 'Aucun membre trouvé avec ce matricule'
    } else if (error.response?.status === 400) {
      errorMessage.value = error.response.data.message || 'Matricule invalide'
    } else {
      errorMessage.value = 'Erreur lors de la validation du paiement. Veuillez réessayer.'
    }
  } finally {
    isLoading.value = false
  }
}

// Utility functions
const formatDate = (dateString) => {
  if (!dateString) return 'Non renseigné'
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

const getStatutLabel = (statut) => {
  const labels = {
    'SR': 'Secrétaire Régional',
    'SOUS_COMITE': 'Membre du Sous-comité',
    'BUREAU_SECTION': 'Membre du bureau de Section',
    'MEMBRE_SIMPLE': 'Membre simple'
  }
  return labels[statut] || statut || 'Non renseigné'
}

// Generate PDF
const generatePDF = async () => {
  isGeneratingPDF.value = true

  try {
    const { default: jsPDF } = await import('jspdf')
    const doc = new jsPDF('p', 'mm', 'a4')
    const today = new Date()
    const receiptNumber = Math.floor(Math.random() * 99999).toString().padStart(5, '0')

    const qrText = `https://www.aeemci-ce.ci/carte_paye/${membre.value.matricule_gen}`
    const qrDataURL = await QRCode.toDataURL(qrText, {
      width: 150,
      margin: 1,
    })

    // Watermark
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.setTextColor(248, 248, 248)
    for (let y = 20; y <= 280; y += 15) {
      for (let x = 10; x <= 200; x += 30) {
        doc.text('AEEMCI', x, y, { angle: 45 })
      }
    }

    // Logo
    try {
      doc.addImage('https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg', 'JPEG', 10, 8, 28, 25)
      doc.setDrawColor(255, 255, 255)
      doc.setLineWidth(0.5)
      doc.roundedRect(10, 8, 28, 25, 2, 2, 'S')
    } catch (e) {
      console.warn('Logo non chargé')
    }

    // Header
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(24)
    doc.setTextColor(0, 128, 0)
    doc.text('AEEMCI', 85, 20)

    doc.setFontSize(12)
    doc.setTextColor(0, 0, 0)
    doc.text("Association des Elèves et Etudiants Musulmans de Côte d'Ivoire", 105, 25, { align: 'center' })

    // Title
    doc.setFillColor(0, 200, 100)
    doc.roundedRect(50, 30, 105, 20, 5, 5, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(12)
    doc.text("REÇU D'INSCRIPTION DE LA CARTE", 100, 38, { align: 'center' })
    doc.text("DE MEMBRE", 100, 44.5, { align: 'center' })

    doc.setLineWidth(0.2)
    doc.line(20, 60, 190, 60)

    // Receipt info
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(10)
    doc.text(`Reçu N° : ${receiptNumber}`, 100, 70, { align: 'center' })

    doc.setFont('helvetica', 'bold')
    doc.setFontSize(14)
    doc.text(`MATRICULE: ${membre.value.matricule_gen}`, 100, 76, { align: 'center' })

    doc.setLineWidth(0.2)
    doc.line(20, 85, 190, 85)

    // Identification
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.text("IDENTIFICATION", 20, 105)

    // Photo
    if (membre.value.photo_membre) {
      try {
        doc.addImage(membre.value.photo_membre, 'JPEG', 145, 100, 40, 40)
        doc.setDrawColor(0, 128, 0)
        doc.setLineWidth(1)
        doc.roundedRect(145, 100, 40, 40, 3, 3, 'S')
      } catch (e) {
        console.warn('Photo non ajoutée')
      }
    }

    // Personal info
    const infosY = 115
    doc.setFontSize(10)

    const addField = (label, value, x, y) => {
      doc.setFont('helvetica', 'normal')
      doc.text(label, x, y)
      doc.setFont('helvetica', 'bold')
      doc.text(value || 'N/A', x + 25, y)
    }

    addField('Nom :', membre.value.nom, 20, infosY)
    addField('Prénom(s) :', membre.value.prenom, 20, infosY + 8)
    addField('Contact :', membre.value.contact, 20, infosY + 16)
    addField('Secrétariat :', membre.value.region || membre.value.secretariat_debut, 20, infosY + 27)
    addField('Sous-comité :', membre.value.ville_ou_commune || membre.value.souscomite_debut, 20, infosY + 38)
    addField('Section :', membre.value.section, 20, infosY + 46)

    // Payment references
    doc.setFontSize(12)
    doc.setFont('helvetica', 'bold')
    doc.text("RÉFÉRENCES DU PAIEMENT", 20, infosY + 66)

    doc.setFontSize(10)
    addField('ID transaction :', membre.value.transaction_id || 'N/A', 20, infosY + 78)
    addField('Opérateur :', 'WAVE', 20, infosY + 86)
    addField('Date paiement :', formatDate(membre.value.created_at), 20, infosY + 102)

    // Date and place
    const dateStr = today.toLocaleDateString('fr-FR', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
    doc.text(`Fait à Abidjan, le ${dateStr}`, 20, infosY + 115)

    // QR Code
    doc.addImage(qrDataURL, 'PNG', 146, 185, 40, 40)

    // Footer
    doc.setFontSize(8)
    doc.setFont('helvetica', 'normal')
    doc.text("Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 275, { align: 'center' })
    doc.text("27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 / aeemci@yahoo.fr / www.aeemci.ci", 105, 280, { align: 'center' })

    doc.setFont('helvetica', 'bold')
    doc.setTextColor(0, 128, 0)
    doc.text("AEEMCI, pour une identité islamique !", 105, 286, { align: 'center' })

    const filename = `recu_aeemci_${membre.value.matricule_gen}_${today.toISOString().split('T')[0]}.pdf`
    doc.save(filename)

  } catch (error) {
    console.error('Erreur PDF:', error)
    alert("Erreur lors de la génération du PDF. Veuillez réessayer.")
  } finally {
    isGeneratingPDF.value = false
  }
}

// Actions
const shareCard = () => {
  const text = `Je viens de recevoir ma carte de membre AEEMCI ! Matricule: ${membre.value.matricule_gen}`
  if (navigator.share) {
    navigator.share({
      title: 'Ma carte AEEMCI',
      text: text,
      url: window.location.href
    })
  } else {
    navigator.clipboard.writeText(text).then(() => {
      alert('Texte copié dans le presse-papiers !')
    })
  }
}

const contactSupport = () => {
  window.open(`mailto:support@aeemci.org?subject=Support - Carte membre ${membre.value.matricule_gen}`, '_blank')
}

const viewMemberArea = () => {
  window.open('https://www.aeemci-ce.ci/espace-membre', '_blank')
}

const nouvelleInscription = () => {
  window.location.href = 'https://www.aeemci-ce.ci/carte_membre'
}

const retryValidation = () => {
  validateAndLoadMemberData()
}

// Initialize
onMounted(() => {
  validateAndLoadMemberData()
})
</script>

<style scoped>
/* Base Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app-container {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--green-mist) 0%, var(--green-mist) 50%, var(--surface) 100%);
  font-family: var(--display);
}

/* Header */
.app-header {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--line);
  box-shadow: var(--shadow);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo-icon {
  width: 3rem;
  height: 3rem;
  background: linear-gradient(135deg, var(--green), #059669);
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--shadow);
}

.logo-text h1 {
  font-size: 1.5rem;
  font-weight: bold;
  background: linear-gradient(135deg, var(--green), #059669);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.logo-text p {
  font-size: 0.875rem;
  color: var(--muted);
}

.btn-header {
  background: linear-gradient(135deg, var(--green), #059669);
  color: #ffffff;
  border: none;
  padding: 0.625rem 1.25rem;
  border-radius: 0;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  box-shadow: var(--shadow);
}

.btn-header:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

/* State Container */
.state-container {
  max-width: 600px;
  margin: 4rem auto;
  padding: 2rem;
}

.state-content {
  background: #ffffff;
  border-radius: 0;
  padding: 3rem;
  text-align: center;
  box-shadow: var(--shadow);
}

/* Loading State */
.loading-animation {
  display: flex;
  justify-content: center;
  gap: 0.75rem;
  margin-bottom: 2rem;
}

.loading-circle {
  width: 1rem;
  height: 1rem;
  background: var(--green);
  border-radius: 50%;
  animation: bounce 1.4s ease-in-out infinite;
}

.loading-circle:nth-child(1) {
  animation-delay: -0.32s;
}

.loading-circle:nth-child(2) {
  animation-delay: -0.16s;
}

@keyframes bounce {
  0%, 80%, 100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}

.progress-bar {
  width: 100%;
  height: 0.5rem;
  background: var(--line);
  border-radius: 0;
  overflow: hidden;
  margin-top: 2rem;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--green), #059669);
  border-radius: 0;
  animation: progress 2s ease-in-out infinite;
}

@keyframes progress {
  0% {
    width: 0%;
  }
  50% {
    width: 70%;
  }
  100% {
    width: 100%;
  }
}

.loading-state h2 {
  font-size: 1.75rem;
  color: var(--ink);
  margin-bottom: 0.75rem;
}

.loading-state p {
  color: var(--muted);
  font-size: 1rem;
}

/* Error State */
.error-animation {
  margin-bottom: 2rem;
}

.error-animation svg {
  color: #ef4444;
  animation: shake 0.5s ease-in-out;
}

@keyframes shake {
  0%, 100% {
    transform: translateX(0);
  }
  25% {
    transform: translateX(-10px);
  }
  75% {
    transform: translateX(10px);
  }
}

.error-state h2 {
  font-size: 1.75rem;
  color: var(--ink);
  margin-bottom: 1rem;
}

.error-message {
  color: #dc2626;
  font-size: 1rem;
  margin-bottom: 2rem;
}

.error-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

/* Success Container */
.success-container {
  max-width: 900px;
  margin: 2rem auto;
  padding: 0 1rem;
}

/* Success Banner */
.success-banner {
  background: linear-gradient(135deg, var(--green-mist) 0%, var(--green-mist) 100%);
  border-radius: 0;
  padding: 3rem 2rem;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.confetti {
  position: absolute;
  inset: 0;
  background-image: 
    radial-gradient(circle, var(--green) 2px, transparent 2px),
    radial-gradient(circle, #059669 2px, transparent 2px),
    radial-gradient(circle, var(--green-2) 2px, transparent 2px);
  background-size: 50px 50px, 80px 80px, 110px 110px;
  background-position: 0 0, 40px 60px, 130px 270px;
  opacity: 0.2;
  animation: confettiFall 20s linear infinite;
}

@keyframes confettiFall {
  0% {
    transform: translateY(-100%);
  }
  100% {
    transform: translateY(100%);
  }
}

.success-badge {
  position: relative;
  z-index: 1;
}

.badge-circle {
  width: 6rem;
  height: 6rem;
  background: linear-gradient(135deg, var(--green), #059669);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  box-shadow: var(--shadow);
  animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.badge-circle svg {
  color: #ffffff;
}

.success-badge h1 {
  font-size: 2.5rem;
  font-weight: bold;
  color: var(--green);
  margin-bottom: 0.75rem;
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

.success-badge p {
  font-size: 1.125rem;
  color: var(--green);
  animation: fadeInUp 0.6s ease-out 0.4s both;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Content Card */
.content-card {
  background: #ffffff;
  border-radius: 0;
  box-shadow: var(--shadow);
  overflow: hidden;
}

/* Matricule Highlight */
.matricule-highlight {
  background: linear-gradient(135deg, var(--green-mist), var(--green-mist));
  padding: 2rem;
  text-align: center;
  border-bottom: 1px solid var(--line);
}

.matricule-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--green);
  color: #ffffff;
  padding: 0.5rem 1.25rem;
  border-radius: 0;
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--shadow);
}

.matricule-box {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  background: #ffffff;
  padding: 1.5rem;
  border-radius: 0;
  border: 2px solid var(--green);
  box-shadow: var(--shadow);
}

.matricule-label {
  font-size: 0.875rem;
  color: #059669;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.matricule-value {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--green);
  font-family: 'Courier New', monospace;
}

/* Info Section */
.info-section {
  padding: 2rem;
  border-bottom: 1px solid var(--line);
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--ink);
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid var(--line);
}

.section-title svg {
  color: var(--green);
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.25rem;
  background: var(--surface);
  border-radius: 0;
  border: 1px solid var(--line);
  transition: all 0.3s ease;
}

.info-item:hover {
  border-color: var(--green);
  box-shadow: var(--shadow);
  transform: translateY(-2px);
}

.info-icon {
  width: 3rem;
  height: 3rem;
  border-radius: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  flex-shrink: 0;
}

.bg-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.bg-green { background: linear-gradient(135deg, var(--green), #059669); }
.bg-purple { background: linear-gradient(135deg, #a855f7, #9333ea); }
.bg-orange { background: linear-gradient(135deg, var(--gold), var(--gold)); }
.bg-teal { background: linear-gradient(135deg, #14b8a6, #0d9488); }
.bg-indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); }

.info-details {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  min-width: 0;
}

.info-label {
  font-size: 0.75rem;
  color: var(--muted);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.info-value {
  font-size: 0.9375rem;
  color: var(--ink);
  font-weight: 600;
  word-break: break-word;
}

/* Payment Section */
.payment-section {
  padding: 2rem;
  border-bottom: 1px solid var(--line);
}

.payment-card {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  padding: 2rem;
  background: linear-gradient(135deg, var(--green-mist), var(--green-mist));
  border-radius: 0;
  border: 2px solid var(--green);
}

.payment-icon {
  width: 4rem;
  height: 4rem;
  background: var(--green);
  color: #ffffff;
  border-radius: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.payment-details h4 {
  font-size: 1.125rem;
  color: var(--green);
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.payment-amount {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--green);
  margin-bottom: 0.5rem;
}

.status-badge {
  display: inline-block;
  background: var(--green);
  color: #ffffff;
  padding: 0.25rem 0.75rem;
  border-radius: 0;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.payment-ref {
  font-size: 0.75rem;
  color: #059669;
  margin-top: 0.5rem;
}

/* Main Action */
.main-action {
  padding: 2rem;
  border-bottom: 1px solid var(--line);
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 0;
  font-size: 0.9375rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  text-decoration: none;
}

.btn-large {
  width: 100%;
  padding: 1.25rem 2rem;
  font-size: 1.0625rem;
}

.btn-primary {
  background: linear-gradient(135deg, var(--green), #059669);
  color: #ffffff;
  box-shadow: var(--shadow);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

.btn-secondary {
  background: #f3f4f6;
  color: var(--ink-soft);
  border-color: #d1d5db;
}

.btn-secondary:hover {
  background: var(--line);
}

.btn-outline {
  background: transparent;
  color: var(--green);
  border-color: var(--green);
}

.btn-outline:hover {
  background: var(--green-mist);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.btn-spinner {
  width: 1.25rem;
  height: 1.25rem;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top: 3px solid #ffffff;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Notes Section */
.notes-section {
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  border-bottom: 1px solid var(--line);
}

.note-card {
  display: flex;
  align-items: start;
  gap: 1rem;
  padding: 1.25rem;
  border-radius: 0;
}

.success-note {
  background: linear-gradient(135deg, var(--green-mist), var(--green-mist));
  border: 1px solid var(--green-mist);
}

.info-note {
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  border: 1px solid #bfdbfe;
}

.note-card svg {
  flex-shrink: 0;
  margin-top: 0.125rem;
}

.success-note svg {
  color: var(--green);
}

.info-note svg {
  color: #3b82f6;
}

.note-card h4 {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.success-note h4 {
  color: var(--green);
}

.info-note h4 {
  color: #1e40af;
}

.note-card p {
  font-size: 0.875rem;
  line-height: 1.5;
}

.success-note p {
  color: var(--green);
}

.info-note p {
  color: #1e40af;
}

.note-card strong {
  font-weight: 700;
}

/* Secondary Actions */
.secondary-actions {
  padding: 1.5rem 2rem;
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

/* Hidden */
.hidden {
  display: none;
}

/* Responsive */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
  }

  .success-container {
    padding: 0 0.5rem;
  }

  .success-badge h1 {
    font-size: 2rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .payment-card {
    flex-direction: column;
    text-align: center;
  }

  .secondary-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }

  .note-card {
    flex-direction: column;
    text-align: center;
  }

  .error-actions {
    flex-direction: column;
  }
}

@media (max-width: 480px) {
  .app-header {
    position: relative;
  }

  .success-badge h1 {
    font-size: 1.75rem;
  }

  .matricule-value {
    font-size: 1.25rem;
  }

  .info-section,
  .payment-section,
  .main-action,
  .notes-section {
    padding: 1.25rem;
  }
}
</style>