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
              <p>Confirmation d'Inscription au Séminaire Régional (SR)</p>
            </div>
          </div>
        </div>
        <div class="header-right">
          <button @click="nouvelleInscription" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            Nouvelle inscription SR
          </button>
        </div>
      </div>
    </header>

    <!-- Loading State -->
    <div v-if="isLoading" class="loading-container">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <h2>Validation du paiement SR...</h2>
        <p>Veuillez patienter pendant que nous validons votre paiement et récupérons vos informations d'inscription au séminaire régional</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="errorMessage" class="error-container">
      <div class="error-content">
        <div class="error-icon">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
        </div>
        <h2>Erreur</h2>
        <p>{{ errorMessage }}</p>
        <div class="error-actions">
          <button @click="retryValidation" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
            </svg>
            Réessayer
          </button>
          <button @click="nouvelleInscription" class="btn btn-secondary">
            Nouvelle inscription SR
          </button>
        </div>
      </div>
    </div>

    <!-- Success State -->
    <div v-else-if="participantSR" class="success-container">
      <!-- Page de félicitation -->
      <div class="congratulations-section">
        <div class="success-card">
          <div class="success-header">
            <div class="success-icon">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <h1>Félicitations !</h1>
            <p class="success-message">Votre inscription au séminaire régional (SR) a été validée avec succès</p>
            <div class="payment-confirmation">
              <div class="confirmation-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                <span>{{ participantSR.statut_paiement === 'GRATUIT' ? 'Inscription gratuite' : 'Paiement validé' }}</span>
              </div>
              <p class="matricule-info">
                Votre inscription au séminaire régional a été confirmée<br>
                <strong style="font-size: 25px; font-family: arial">Matricule: <span style="letter-spacing: 2px"> {{ participantSR.matricule_seminaire }}</span></strong>
              </p>
            </div>
          </div>

          <!-- Récapitulatif du participant SR -->
          <div class="member-summary">
            <h3>Récapitulatif de votre inscription SR</h3>
            <div class="summary-grid">
              <div class="summary-item">
                <div class="summary-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                </div>
                <div class="summary-content">
                  <span class="summary-label">Nom complet</span>
                  <span class="summary-value">{{ participantSR.prenom }} {{ participantSR.nom }}</span>
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
                  <span class="summary-value">{{ participantSR.contact }}</span>
                </div>
              </div>

              <div class="summary-item">
                <div class="summary-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                  </svg>
                </div>
                <div class="summary-content">
                  <span class="summary-label">Type de séminaire</span>
                  <span class="summary-value">Séminaire Régional (SR)</span>
                </div>
              </div>

              <div class="summary-item">
                <div class="summary-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <div class="summary-content">
                  <span class="summary-label" style="color: orange; font-weight: bold">Dortoir attribué</span>
                  <span class="summary-value">{{ getDortoirLabelSR(participantSR.dortoir) }}</span>
                </div>
              </div>

              <div class="summary-item">
                <div class="summary-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5 1.12 1.5 2.5-.67 2.5-1.5 2.5zM5 11l1.5-4.5h11L19 11H5z"/>
                  </svg>
                </div>
                <div class="summary-content">
                  <span class="summary-label" style="color: orange; font-weight: bold">Car de transport</span>
                  <span class="summary-value">{{ getCarLabel(participantSR.car_transport) }}</span>
                </div>
              </div>

              <div v-if="participantSR.secretariat_regional" class="summary-item">
                <div class="summary-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                  </svg>
                </div>
                <div class="summary-content">
                  <span class="summary-label">Secrétariat régional</span>
                  <span class="summary-value">{{ participantSR.secretariat_regional }}</span>
                </div>
              </div>

              <div class="summary-item">
                <div class="summary-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H6V9h14v11z"/>
                  </svg>
                </div>
                <div class="summary-content">
                  <span class="summary-label">Date d'inscription</span>
                  <span class="summary-value">{{ formatDate(participantSR.created_at) }}</span>
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
              {{ isGeneratingPDF ? 'Génération...' : 'Télécharger ma fiche d\'inscription SR PDF' }}
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
                <h4>Votre inscription SR est confirmée !</h4>
                <p>Conservez précieusement votre matricule <strong>{{ participantSR.matricule_seminaire }}</strong> et votre fiche d'inscription. Ils vous seront demandés lors du séminaire régional.</p>
                <p><strong>Dortoir:</strong> {{ getDortoirLabelSR(participantSR.dortoir) }} | <strong>Car:</strong> {{ getCarLabel(participantSR.car_transport) }}</p>
              </div>
            </div>

            <div class="info-card" :class="{ 'info-card-free': participantSR.statut_paiement === 'GRATUIT' }">
              <div class="info-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
              </div>
              <div class="info-content">
                <h4>{{ participantSR.statut_paiement === 'GRATUIT' ? 'Inscription gratuite' : 'Paiement validé' }}</h4>
                <p v-if="participantSR.statut_paiement === 'GRATUIT'">
                  Votre inscription est <strong>gratuite</strong> car vous avez choisi le transport personnel depuis l'intérieur.
                </p>
                <p v-else>
                  Votre paiement de <strong>{{participantSR.montant_paye_formate}}</strong> a été validé avec succès. Statut: <strong>{{ participantSR.statut_paiement }}</strong>
                </p>
                <p v-if="participantSR.ref_paiement"><small>Référence: {{ participantSR.ref_paiement }}</small></p>
              </div>
            </div>

            <div class="info-card sr-specific">
              <div class="info-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <div class="info-content">
                <h4>Séminaire Régional (SR)</h4>
                <p>Vous participez au séminaire régional AEEMCI. Ce programme est spécialement conçu pour les participants de votre région.</p>
                <p><strong>Transport choisi:</strong> {{ participantSR.transport === 'convoi' ? 'Transport organisé depuis Abidjan (9 000 FCFA)' : 'Transport personnel depuis l\'intérieur (gratuit)' }}</p>
              </div>
            </div>
          </div>

          <!-- Actions secondaires -->
          <div class="secondary-actions">
            <button @click="shareInscription" class="btn btn-outline">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
              </svg>
              Partager
            </button>

            <button @click="contactSupport" class="btn btn-outline">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
              </svg>
              Support
            </button>

            <button @click="viewSeminaireInfo" class="btn btn-outline">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
              </svg>
              <div class="info-content">
                <h4>Infos séminaire SR</h4>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import QRCode from 'qrcode';
import { PDFDocument } from 'pdf-lib';
import axios from 'axios'

// Route et état
const route = useRoute()

// État de l'application
const participantSR = ref(null)
const isLoading = ref(true)
const errorMessage = ref('')
const isGeneratingPDF = ref(false)

// Récupération du matricule depuis l'URL
const matricule = route.params.matricule || ''

// Validation et récupération des données du participant SR
const validateAndLoadSRData = async () => {
  if (!matricule) {
    errorMessage.value = 'Matricule manquant dans l\'URL'
    isLoading.value = false
    return
  }

  try {
    isLoading.value = true
    errorMessage.value = ''

    // Appel à l'API SR qui met à jour le statut ET récupère les données
    const response = await axios.post('https://sogetrag.com/api/seminaire_sr_paye.php', {
      matricule: matricule
    })
    
    if (response.data.success) {
      participantSR.value = response.data.participant_sr
      console.log('Paiement SR validé et données récupérées:', response.data)
    } else {
      // Gestion spéciale des erreurs de montant non conforme
      if (response.data.error_type === 'MONTANT_NON_CONFORME_SR') {
        errorMessage.value = `${response.data.message}. Montants autorisés: ${response.data.montants_autorises.join(', ')} FCFA`
      } else {
        errorMessage.value = response.data.message || 'Erreur lors de la validation du paiement SR'
      }
    }
  } catch (error) {
    console.error('Erreur lors de la validation SR:', error)
    if (error.response?.status === 404) {
      errorMessage.value = 'Aucun participant SR trouvé avec ce matricule'
    } else if (error.response?.status === 400) {
      // Gestion des erreurs de montant non conforme
      if (error.response.data.error_type === 'MONTANT_NON_CONFORME_SR') {
        errorMessage.value = `${error.response.data.message}. Montants autorisés: ${error.response.data.montants_autorises.join(', ')} FCFA`
      } else {
        errorMessage.value = error.response.data.message || 'Matricule SR invalide'
      }
    } else {
      errorMessage.value = 'Erreur lors de la validation du paiement SR. Veuillez réessayer.'
    }
  } finally {
    isLoading.value = false
  }
}

// Fonctions utilitaires
const formatDate = (dateString) => {
  if (!dateString) return 'Non renseigné'
  return new Date(dateString).toLocaleDateString('fr-FR')
}

const getDortoirLabelSR = (dortoir) => {
  const labels = {
    // Dortoirs SR Hommes
    'SR_DORTOIR_A': 'DORTOIR SR',
    'SR_DORTOIR_B': 'DORTOIR SR',
    'SR_DORTOIR_C': 'DORTOIR SR',
    
    // Dortoirs SR Femmes
    'SR_DORTOIR_D': 'DORTOIR SR',
    'SR_DORTOIR_E': 'DORTOIR SR',
    'SR_DORTOIR_F': 'DORTOIR SR',
    
    'SR_EXTERNE': 'Hébergement SR Externe',
    'SR_NON_ATTRIBUE': 'Dortoir SR non attribué'
  }
  return labels[dortoir] || (dortoir || 'Non assigné')
}

const getCarLabel = (car) => {
  const labels = {
    'CAR_01': 'Car SR',
    'CAR_02': 'Car SR',
    'CAR_03': 'Car SR',
    'CAR_04': 'Car SR',
    'CAR_05': 'Car SR',
    'CAR_06': 'Car SR',
    'CAR_07': 'Car SR',
    'CAR_08': 'Car SR',
    'CAR_09': 'Car SR',
    'CAR_10': 'Car SR',
    'CAR_11': 'Car SR',
    'TRANSPORT_PERSONNEL': 'Transport Personnel'
  }
  return labels[car] || (car || 'Non assigné')
}

// Actions
const generatePDF = async () => {
  isGeneratingPDF.value = true;
  try {
    const { default: jsPDF } = await import('jspdf');
    const doc = new jsPDF('p', 'mm', 'a4');
    const today = new Date();
    const receiptNumber = Math.floor(Math.random() * 99999).toString().padStart(5, '0');
    const qrText = `https://aeemci.sastock.com/seminaire_sr_paye/${participantSR.value.matricule_seminaire}`;
    const qrDataURL = await QRCode.toDataURL(qrText, {
      width: 120,
      margin: 1,
    });

    // Filigrane AEEMCI
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
    
    // Sous-titre SR
    doc.setFontSize(14);
    doc.text("FICHE D'INSCRIPTION - RECU SEMINAIRE REGIONAL (SR)", 105, 28, { align: 'center' });

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
      console.warn('Impossible de charger la bannière SR:', error);
    }

    let startY = 67;

    // Informations de base
    doc.setTextColor(0, 0, 0);
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(9);
    doc.text(`RECU SR N° : ${receiptNumber}`, 18, startY);
    doc.text(`Date : ${today.toLocaleDateString('fr-FR')}`, 165, startY);
    
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(12);
    doc.text(`MATRICULE SR: ${participantSR.value.matricule_seminaire}`, 105, startY + 7, { align: 'center' });

    // Ligne de séparation
    doc.setLineWidth(0.5);
    doc.setDrawColor(0, 128, 0);
    doc.line(15, startY + 12, 195, startY + 12);

    // Section IDENTIFICATION SR
    let yPos = startY + 20;
    doc.setFillColor(240, 248, 255);
    doc.rect(15, yPos - 5, 180, 8, 'F');
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.text("IDENTIFICATION DU PARTICIPANT SR", 20, yPos);

    // Photo (si disponible)
    if (participantSR.value.photo) {
      try {
        doc.addImage(participantSR.value.photo, 'JPEG', 155, yPos + 2, 30, 30);
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
    doc.text(participantSR.value.nom || '', 35, yPos);
    
    doc.setFont('helvetica', 'normal');
    doc.text('Prénom(s) :', 20, yPos + 6);
    doc.setFont('helvetica', 'bold');
    doc.text(participantSR.value.prenom || '', 40, yPos + 6);
    
    doc.setFont('helvetica', 'normal');
    doc.text('Contact :', 20, yPos + 12);
    doc.setFont('helvetica', 'bold');
    doc.text(participantSR.value.contact || '', 35, yPos + 12);
    
    doc.setFont('helvetica', 'normal');
    doc.text('Type :', 20, yPos + 18);
    doc.setFont('helvetica', 'bold');
    doc.text('Séminaire Régional (SR)', 35, yPos + 18);

    // Colonne 2
    doc.setFont('helvetica', 'normal');
    doc.text('Dortoir :', 90, yPos);
    doc.setFont('helvetica', 'bold');
    doc.text(getDortoirLabelSR(participantSR.value.dortoir) || 'Non assigné', 105, yPos);
    
    doc.setFont('helvetica', 'normal');
    doc.text('Transport :', 90, yPos + 6);
    doc.setFont('helvetica', 'bold');
    doc.text(getCarLabel(participantSR.value.car_transport) || 'Non assigné', 110, yPos + 6);
    
    if (participantSR.value.secretariat_regional) {
      doc.setFont('helvetica', 'normal');
      doc.text('Secrétariat :', 90, yPos + 12);
      doc.setFont('helvetica', 'bold');
      doc.text(participantSR.value.secretariat_regional, 110, yPos + 12);
    }

    // Section PAIEMENT SR
    yPos += 30;
    doc.setFillColor(240, 248, 255);
    doc.rect(15, yPos - 5, 180, 8, 'F');
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.text("REFERENCES DU PAIEMENT SR", 20, yPos);
    
    yPos += 8;
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text('Transaction :', 20, yPos);
    doc.setFont('helvetica', 'bold');
    doc.text(participantSR.value.ref_paiement || 'N/A', 45, yPos);
    
    doc.setFont('helvetica', 'normal');
    doc.text('Opérateur :', 90, yPos);
    doc.setFont('helvetica', 'bold');
    doc.text(participantSR.value.statut_paiement === 'GRATUIT' ? 'GRATUIT' : 'WAVE', 110, yPos);
    
    doc.setFont('helvetica', 'normal');
    doc.text('N° Wave :', 20, yPos + 6);
    doc.setFont('helvetica', 'bold');
    doc.text(participantSR.value.numero_wave || 'N/A', 40, yPos + 6);
    
    doc.setFont('helvetica', 'normal');
    doc.text('Date inscription :', 90, yPos + 6);
    doc.setFont('helvetica', 'bold');
    doc.text(formatDate(participantSR.value.created_at), 118, yPos + 6);

    doc.setFont('helvetica', 'normal');
    doc.text('Montant :', 20, yPos + 12);
    doc.setFont('helvetica', 'bold');
    doc.text(participantSR.value.montant_paye_formate || '0 FCFA', 40, yPos + 12);

    // QR Code
    doc.addImage(qrDataURL, 'PNG', 160, yPos - 15, 25, 25);

    // Section CONSIGNES IMPORTANTES SR
    yPos += 20;
    doc.setFillColor(255, 248, 220);
    doc.rect(15, yPos - 5, 180, 8, 'F');
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.setTextColor(204, 102, 0);
    doc.text("CONSIGNES IMPORTANTES - SEMINAIRE REGIONAL", 20, yPos);

    yPos += 8;
    doc.setTextColor(0, 0, 0);
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(8);
    doc.text("Le participant SR doit prévoir :", 20, yPos);
    
    yPos += 4;
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7);
    
    const consignesSR = [
      "• Des vêtements décents (couvrant tout le corps pour les filles et dépassant les genoux pour les garçons)",
      "• Sa literie (drap, couverture, moustiquaire)",
      "• Son équipement sportif (jogging de rigueur pour les sœurs)",
      "• Matériel d'étude adapté au niveau régional"
    ];
    
    consignesSR.forEach(consigne => {
      doc.text(consigne, 22, yPos);
      yPos += 3.5;
    });

    yPos += 2;
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(8);
    doc.text("NB : Le séminaire régional est un lieu d'apprentissage spécialisé :", 20, yPos);
    
    yPos += 4;
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7);
    
    const interdictionsSR = [
      "• Programme adapté aux spécificités régionales",
      "• Respect des normes islamiques (coiffures appropriées)",
      "• Éviter les objets de luxe ou de grande valeur",
      "• Chaque participant SR aura droit à un kit AEEMCI spécialisé"
    ];
    
    interdictionsSR.forEach(interdiction => {
      doc.text(interdiction, 22, yPos);
      yPos += 3.5;
    });

    yPos += 2;
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(8);
    doc.text("Contacts SR :", 20, yPos);
    
    yPos += 4;
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7);
    
    const contactsSR = [
      "• +225 40 65 56 51 (Coordination SR)",
      "• +225 44 39 75 79 (Support SR)",
      "• seminaire.sr@aeemci.ci"
    ];
    
    contactsSR.forEach(contact => {
      doc.text(contact, 22, yPos);
      yPos += 3.5;
    });

    // Pied de page
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

    // Téléchargement
    const filename = `fiche_sr_aeemci_${participantSR.value.matricule_seminaire}_${today.toISOString().split('T')[0]}.pdf`;
    doc.save(filename);

  } catch (error) {
    console.error('Erreur lors de la génération du PDF SR:', error);
    alert("Erreur lors de la génération du PDF SR. Veuillez réessayer.");
  } finally {
    isGeneratingPDF.value = false;
  }
}

const shareInscription = () => {
  if (navigator.share) {
    navigator.share({
      title: 'Mon inscription au séminaire régional AEEMCI',
      text: `Je me suis inscrit au séminaire régional (SR) ! Matricule: ${participantSR.value.matricule_seminaire}`,
      url: window.location.href
    })
  } else {
    const text = `Je me suis inscrit au séminaire régional (SR) ! Matricule: ${participantSR.value.matricule_seminaire}`
    navigator.clipboard.writeText(text).then(() => {
      alert('Texte copié dans le presse-papiers !')
    })
  }
}

const contactSupport = () => {
  window.open('mailto:support@aeemci.org?subject=Support - Inscription séminaire SR ' + participantSR.value.matricule_seminaire, '_blank')
}

const viewSeminaireInfo = () => {
  window.open('https://aeemci.sastock.com/seminaire-sr-info', '_blank')
}

const nouvelleInscription = () => {
  window.location.href = 'https://aeemci.sastock.com/seminaire-sr'
}

const retryValidation = () => {
  validateAndLoadSRData()
}

// Initialisation
onMounted(() => {
  validateAndLoadSRData()
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

/* Main Actions */
.main-actions {
  padding: 2rem;
  display: flex;
  gap: 1rem;
  justify-content: center;
  border-bottom: 1px solid #e5e7eb;
}

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

.info-card.sr-specific {
  background: #fef3c7;
  border-color: #fbbf24;
}

.info-card.info-card-free {
  background: #f0f9ff;
  border-color: #0ea5e9;
}

.info-card.info-card-free .info-icon {
  background: #0ea5e9;
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

.info-content small {
  font-size: 0.75rem;
  color: #059669;
}

/* Secondary Actions */
.secondary-actions {
  padding: 1.5rem 2rem;
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
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

  .success-container {
    padding: 1rem;
  }

  .success-header {
    padding: 2rem 1rem;
  }

  .success-header h1 {
    font-size: 2rem;
  }

  .member-summary {
    padding: 1.5rem;
  }

  .summary-grid {
    grid-template-columns: 1fr;
  }

  .main-actions {
    padding: 1.5rem;
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

  .error-actions {
    flex-direction: column;
  }
}

@media (max-width: 480px) {
  .success-header h1 {
    font-size: 1.75rem;
  }

  .success-message {
    font-size: 1.125rem;
  }

  .logo-text h1 {
    font-size: 1.25rem;
  }

  .logo-text p {
    font-size: 0.75rem;
  }

  .summary-item {
    flex-direction: column;
    text-align: center;
  }

  .info-card {
    padding: 1rem;
  }
}
</style>
