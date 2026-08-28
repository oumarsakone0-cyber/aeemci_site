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
              <p>Confirmation d'Inscription {{ inscriptionType }}</p>
            </div>
          </div>
        </div>
        <button
          @click="nouvelleInscription"
          class="btn btn-secondary"
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          Nouvelle inscription
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <div class="form-container">
        <!-- Loading State -->
        <div v-if="isLoading" class="loading-state">
          <div class="loading-spinner-large"></div>
          <h2>Validation en cours...</h2>
          <p v-if="inscriptionType === 'CIMA'">Vérification de votre paiement</p>
          <p v-else>Récupération de vos informations</p>
          <p class="matricule-info">Matricule: {{ matricule }}</p>
        </div>

        <!-- Error State -->
        <div v-else-if="errorMessage" class="error-state">
          <div class="error-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h2>Erreur</h2>
          <p>{{ errorMessage }}</p>
          <div class="error-actions">
            <button @click="retryValidation" class="btn btn-primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              Réessayer
            </button>
            <button @click="nouvelleInscription" class="btn btn-secondary">
              Nouvelle inscription
            </button>
          </div>
        </div>

        <!-- Success State -->
        <div v-else-if="inscription" class="success-content">
          <!-- Success Header -->
          <div class="success-header">
            <div class="success-icon-wrapper">
              <div class="success-icon-animated">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
              </div>
            </div>
            <h1>Félicitations !</h1>
            <p class="success-subtitle">
              Votre inscription {{ inscriptionType === 'CIMA' ? 'CIMA' : 'à l\'Assemblée Générale' }} a été validée avec succès
            </p>
            
            <div class="matricule-badge">
              <div class="badge-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ inscriptionType === 'CIMA' ? 'Paiement validé' : 'Inscription confirmée' }}
              </div>
              <p class="badge-label">Votre matricule d'inscription</p>
              <p class="badge-matricule">{{ inscription.matricule_membre }}</p>
            </div>
          </div>

          <!-- Récapitulatif -->
          <div class="recap-section">
            <h3 class="section-title">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Récapitulatif de votre inscription
            </h3>
            
            <div class="info-grid">
              <!-- Nom complet -->
              <div class="info-card">
                <div class="info-icon bg-emerald">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <p class="info-label">Nom complet</p>
                  <p class="info-value">{{ inscription.prenom }} {{ inscription.nom }}</p>
                </div>
              </div>

              <!-- Contact -->
              <div class="info-card">
                <div class="info-icon bg-blue">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <p class="info-label">Téléphone</p>
                  <p class="info-value">{{ inscription.telephone || 'Non renseigné' }}</p>
                </div>
              </div>

              <!-- Qualité membre -->
              <div class="info-card">
                <div class="info-icon bg-indigo">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <p class="info-label">Qualité/Rôle</p>
                  <p class="info-value">{{ inscription.qualite_membre || 'Non renseigné' }}</p>
                </div>
              </div>

              <!-- Secrétariat -->
              <div class="info-card">
                <div class="info-icon bg-orange">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <p class="info-label">Secrétariat</p>
                  <p class="info-value">{{ inscription.ville_ou_commune || 'Non renseigné' }}</p>
                </div>
              </div>

              <!-- Sous-comité (si présent) -->
              <div v-if="inscription.sc && inscription.sc.length >= 3" class="info-card">
                <div class="info-icon bg-purple">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <p class="info-label">Sous-comité</p>
                  <p class="info-value">{{ inscription.sc }}</p>
                </div>
              </div>

              <!-- Thème (pour CIMA uniquement) -->
              <div v-if="inscriptionType === 'CIMA' && inscription.theme" class="info-card info-card-full">
                <div class="info-icon bg-teal">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <p class="info-label">Thème attribué</p>
                  <p class="info-value">{{ inscription.theme }}</p>
                </div>
              </div>

              <!-- Montant (pour CIMA uniquement) -->
              <div v-if="inscriptionType === 'CIMA'" class="info-card">
                <div class="info-icon bg-green">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <p class="info-label">Montant payé</p>
                  <p class="info-value amount">{{ inscription.somme_payee || inscription.montant_inscription || '2000' }} F CFA</p>
                </div>
              </div>
            </div>

            <!-- Action principale - Télécharger PDF -->
            <div class="main-action">
              <button
                @click="generatePDF"
                :disabled="isGeneratingPDF"
                class="btn btn-primary btn-large"
              >
                <div v-if="isGeneratingPDF" class="loading-spinner"></div>
                <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ isGeneratingPDF ? 'Génération en cours...' : 'Télécharger mon reçu PDF' }}
              </button>
            </div>

            <!-- Informations importantes -->
            <div class="info-messages">
              <!-- Message de confirmation -->
              <div class="info-message success-message">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                  <h4>{{ inscriptionType === 'CIMA' ? 'Votre inscription CIMA est confirmée !' : 'Votre inscription à l\'AG est confirmée !' }}</h4>
                  <p>
                    Conservez précieusement votre matricule <strong>{{ inscription.matricule_membre }}</strong> et votre reçu d'inscription.
                  </p>
                </div>
              </div>

              <!-- Message de paiement (CIMA uniquement) -->
              <div v-if="inscriptionType === 'CIMA'" class="info-message payment-message">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <div>
                  <h4>Paiement validé</h4>
                  <p>
                    Votre paiement de <strong>{{ inscription.somme_payee || inscription.montant_inscription || '2000' }} F CFA</strong> a été validé avec succès.
                  </p>
                  <p v-if="inscription.reference_paiement || inscription.ref_paiement" class="small-info">
                    Référence: {{ inscription.reference_paiement || inscription.ref_paiement }}
                  </p>
                </div>
              </div>

              <!-- Message gratuit (AG uniquement) -->
              <div v-else class="info-message free-message">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                  <h4>Inscription gratuite</h4>
                  <p>
                    Votre inscription à l'Assemblée Générale est gratuite. Aucun paiement n'est requis.
                  </p>
                </div>
              </div>
            </div>

            <!-- Actions secondaires -->
            <div class="secondary-actions">
              <button @click="shareInscription" class="btn btn-outline">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                Partager
              </button>

              <button @click="contactSupport" class="btn btn-outline">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Support
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import QRCode from 'qrcode'

const API_BASE_URL = 'https://api.aeemci-ce.ci/verifier_cima.php'

// État
const inscription = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')
const isGeneratingPDF = ref(false)
const matricule = ref('')

// Type d'inscription (CIMA ou AG)
const inscriptionType = computed(() => {
  if (!inscription.value) return ''
  return inscription.value.cima === 'oui' ? 'CIMA' : 'AG'
})

onMounted(() => {
  const pathParts = window.location.pathname.split('/')
  const matriculeFromUrl = pathParts[pathParts.length - 1]
  
  if (matriculeFromUrl) {
    matricule.value = matriculeFromUrl
    validateAndLoadInscriptionData()
  } else {
    errorMessage.value = 'Matricule invalide dans l\'URL'
  }
})

const validateAndLoadInscriptionData = async () => {
  if (!matricule.value.trim()) {
    errorMessage.value = 'Matricule manquant'
    return
  }

  try {
    isLoading.value = true
    errorMessage.value = ''

    const response = await fetch(API_BASE_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        matricule: matricule.value.trim()
      })
    })

    const data = await response.json()
    console.log('📦 API Response:', data)

    if (data.success) {
      inscription.value = data.membre
      console.log('✅ Inscription loaded:', {
        type: inscriptionType.value,
        statut: inscription.value.statut_paiement,
        cima: inscription.value.cima
      })
    } else {
      errorMessage.value = data.message || 'Erreur lors de la validation'
      console.error('❌ API Error:', data)
    }
  } catch (error) {
    console.error('❌ Fetch Error:', error)
    errorMessage.value = 'Erreur lors de la validation. Veuillez réessayer.'
  } finally {
    isLoading.value = false
  }
}

const generatePDF = async () => {
  isGeneratingPDF.value = true

  try {
    const { default: jsPDF } = await import('jspdf')
    const doc = new jsPDF('p', 'mm', 'a4')
    const today = new Date()
    const receiptNumber = Math.floor(Math.random() * 99999).toString().padStart(5, '0')
    const qrText = `${window.location.origin}/verification_cima_2025/${inscription.value.matricule_membre}`
    const qrDataURL = await QRCode.toDataURL(qrText, {
      width: 120,
      margin: 1,
    })

    const typeEvent = inscriptionType.value
    const montant = typeEvent === 'CIMA' ? (inscription.value.somme_payee || inscription.value.montant_inscription || '2000') : '0'

    // Watermark
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(8)
    doc.setTextColor(248, 248, 248)
    for (let y = 20; y <= 280; y += 20) {
      for (let x = 10; x <= 200; x += 40) {
        doc.text('AEEMCI', x, y, { angle: 45 })
      }
    }

    // En-tête avec dégradé
    for (let i = 0; i < 35; i++) {
      const greenValue = Math.floor(128 + (i * 2))
      doc.setFillColor(0, Math.min(greenValue, 160), Math.floor(greenValue * 0.6))
      doc.rect(0, i, 210, 1, 'F')
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
    
    // Titre
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(24)
    doc.setTextColor(255, 255, 255)
    doc.text('AEEMCI', 105, 15, { align: 'center' })
    
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    doc.text("Association des Eleves et Etudiants Musulmans de Cote d'Ivoire", 105, 21, { align: 'center' })
    
    // Badge type inscription
    doc.setFillColor(255, 255, 255)
    doc.roundedRect(45, 24, 120, 8, 2, 2, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(14)
    doc.setTextColor(0, 128, 0)
    doc.text(`REÇU D'INSCRIPTION ${typeEvent}`, 105, 29, { align: 'center' })

    // Informations du reçu
    let startY = 45

    doc.setFillColor(240, 248, 255)
    doc.roundedRect(15, startY - 4, 70, 8, 2, 2, 'F')
    doc.setTextColor(0, 100, 0)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.text(`REÇU N° : ${receiptNumber}`, 18, startY)
    
    doc.setFillColor(240, 248, 255)
    doc.roundedRect(125, startY - 4, 70, 8, 2, 2, 'F')
    doc.text(`Date : ${today.toLocaleDateString('fr-FR')}`, 128, startY)
    
    // Matricule
    doc.setFillColor(255, 248, 220)
    doc.roundedRect(45, startY + 5, 120, 10, 3, 3, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(14)
    doc.setTextColor(0, 128, 0)
    doc.text(`${inscription.value.matricule_membre}`, 105, startY + 11, { align: 'center' })

    doc.setLineWidth(0.8)
    doc.setDrawColor(0, 128, 0)
    doc.line(15, startY + 18, 195, startY + 18)

    // Section identification
    let yPos = startY + 28
    
    doc.setFillColor(0, 128, 0)
    doc.roundedRect(15, yPos - 5, 180, 10, 2, 2, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.setTextColor(255, 255, 255)
    doc.text("IDENTIFICATION DU MEMBRE", 20, yPos)

    if (inscription.value.photo_membre) {
      try {
        doc.addImage(inscription.value.photo_membre, 'JPEG', 155, yPos + 3, 32, 32)
        doc.setDrawColor(0, 128, 0)
        doc.setLineWidth(1)
        doc.roundedRect(155, yPos + 3, 32, 32, 3, 3, 'S')
      } catch (e) {
        console.warn('Photo non ajoutée')
      }
    }

    yPos += 12
    doc.setFontSize(9)
    doc.setTextColor(0, 0, 0)
    
    const addField = (label, value, x, y) => {
      doc.setFont('helvetica', 'normal')
      doc.setTextColor(80, 80, 80)
      doc.text(label, x, y)
      doc.setFont('helvetica', 'bold')
      doc.setTextColor(0, 0, 0)
      const labelWidth = doc.getTextWidth(label)
      doc.text(value || 'N/A', x + labelWidth + 2, y)
    }

    addField('Nom :', inscription.value.nom, 20, yPos)
    addField('Prénom(s) :', inscription.value.prenom, 20, yPos + 6)
    addField('Matricule :', inscription.value.matricule_membre, 20, yPos + 12)
    addField('Téléphone :', inscription.value.telephone || 'Non renseigne', 20, yPos + 18)

    addField('Qualité :', inscription.value.qualite_membre, 90, yPos)
    addField('Secrétariat :', inscription.value.ville_ou_commune, 90, yPos + 6)

    if (inscription.value.sc && inscription.value.sc.length > 2) {
      addField('Sous-comité :', inscription.value.sc, 90, yPos + 12)
    }

    // Section paiement
    yPos += 40
    
    doc.setFillColor(0, 128, 0)
    doc.roundedRect(15, yPos - 5, 180, 10, 2, 2, 'F')
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(12)
    doc.setTextColor(255, 255, 255)
    doc.text(typeEvent === 'CIMA' ? "INFORMATIONS DE PAIEMENT" : "INFORMATIONS D'INSCRIPTION", 20, yPos)
    
    yPos += 10
    doc.setFontSize(9)
    doc.setTextColor(0, 0, 0)
    
    if (typeEvent === 'CIMA') {
      addField('Référence :', inscription.value.ref_paiement || inscription.value.reference_paiement || 'En attente', 20, yPos)
      
      const isPaye = inscription.value.statut_paiement === 'paye'
      doc.setFont('helvetica', 'normal')
      doc.setTextColor(80, 80, 80)
      doc.text('Statut :', 90, yPos)
      
      doc.setFillColor(isPaye ? 220 : 255, isPaye ? 255 : 248, isPaye ? 220 : 220)
      doc.roundedRect(103, yPos - 3.5, 25, 5, 1, 1, 'F')
      doc.setFont('helvetica', 'bold')
      doc.setTextColor(isPaye ? 0 : 200, isPaye ? 128 : 100, 0)
      doc.text(isPaye ? 'PAYE' : 'EN ATTENTE', 105, yPos)
      doc.setTextColor(0, 0, 0)
      
      addField('Contact paiement :', inscription.value.contact_paiement || 'N/A', 20, yPos + 6)
      
      doc.setFillColor(240, 255, 240)
      doc.roundedRect(18, yPos + 10, 60, 8, 2, 2, 'F')
      doc.setFont('helvetica', 'normal')
      doc.setTextColor(80, 80, 80)
      doc.text('Montant :', 20, yPos + 15)
      
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(12)
      doc.setTextColor(0, 128, 0)
      doc.text(`${montant} F CFA`, 45, yPos + 15)
    } else {
      doc.setFillColor(220, 255, 220)
      doc.roundedRect(18, yPos, 170, 8, 2, 2, 'F')
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(11)
      doc.setTextColor(0, 128, 0)
      doc.text('INSCRIPTION GRATUITE - Aucun paiement requis', 20, yPos + 5)
    }
    
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(9)

    // QR Code
    doc.addImage(qrDataURL, 'PNG', 158, yPos - 16, 28, 28)
    doc.setDrawColor(0, 128, 0)
    doc.setLineWidth(0.5)
    doc.roundedRect(158, yPos - 16, 28, 28, 2, 2, 'S')
    doc.setFontSize(6)
    doc.setFont('helvetica', 'normal')
    doc.text('Scan pour verifier', 172, yPos + 14, { align: 'center' })

    // Thème (CIMA uniquement)
    if (typeEvent === 'CIMA' && inscription.value.theme) {
      yPos += 30
      doc.setFillColor(240, 248, 255)
      doc.roundedRect(15, yPos - 5, 180, 20, 3, 3, 'F')
      doc.setFont('helvetica', 'bold')
      doc.setFontSize(10)
      doc.setTextColor(0, 100, 150)
      doc.text("THÈME ATTRIBUÉ", 20, yPos)
      yPos += 6
      doc.setFont('helvetica', 'normal')
      doc.setFontSize(8)
      doc.setTextColor(0, 0, 0)
      const themeLines = doc.splitTextToSize(inscription.value.theme, 170)
      doc.text(themeLines, 20, yPos)
      yPos += (themeLines.length * 4)
    }

    // Tampon de validation
    yPos += 30
    
    doc.setFillColor(220, 220, 220)
    doc.roundedRect(17, yPos + 2, 180, 30, 5, 5, 'F')
    
    doc.setFillColor(240, 255, 240)
    doc.roundedRect(15, yPos, 180, 30, 5, 5, 'F')
    doc.setDrawColor(0, 128, 0)
    doc.setLineWidth(2)
    doc.roundedRect(15, yPos, 180, 30, 5, 5, 'S')
    
    doc.setFillColor(0, 128, 0)
    doc.circle(20, yPos + 5, 2, 'F')
    doc.circle(190, yPos + 5, 2, 'F')
    doc.circle(20, yPos + 25, 2, 'F')
    doc.circle(190, yPos + 25, 2, 'F')
    
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(16)
    doc.setTextColor(0, 128, 0)
    doc.text(typeEvent === 'CIMA' ? "RDV le 30 Déc 2025" : "RDV le 31 Déc 2025 à 12h00", 105, yPos + 13, { align: 'center' })
    doc.setFontSize(10)
    doc.setFont('helvetica', 'normal')
    doc.text(`Reçu d'inscription ${typeEvent} ${new Date().getFullYear()}`, 105, yPos + 21, { align: 'center' })

    // Note importante
    yPos += 40
    
    doc.setFillColor(255, 248, 220)
    doc.roundedRect(15, yPos - 5, 180, 20, 3, 3, 'F')
    doc.setDrawColor(255, 165, 0)
    doc.setLineWidth(0.5)
    doc.roundedRect(15, yPos - 5, 180, 20, 3, 3, 'S')
    
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(10)
    doc.setTextColor(204, 102, 0)
    doc.text("NOTE IMPORTANTE", 20, yPos)

    yPos += 6
    doc.setTextColor(0, 0, 0)
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(8)
    doc.text("- Ce reçu atteste de votre inscription. Conservez-le precieusement.", 22, yPos)
    yPos += 4
    doc.text("- Il pourra vous être demandé lors des activités AEEMCI.", 22, yPos)

    // Pied de page
    for (let i = 0; i < 27; i++) {
      const greenValue = Math.floor(160 - (i * 2))
      doc.setFillColor(0, Math.max(greenValue, 128), Math.floor(greenValue * 0.6))
      doc.rect(0, 270 + i, 210, 1, 'F')
    }
    
    doc.setFont('helvetica', 'normal')
    doc.setFontSize(7)
    doc.setTextColor(255, 255, 255)
    doc.text("Siege Social : Mosquee An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 278, { align: 'center' })
    doc.text("Tel: 27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 | Email: aeemci@yahoo.fr | Web: www.aeemci-ce.ci", 105, 283, { align: 'center' })
    
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9)
    doc.text("AEEMCI, pour une identite islamique !", 105, 290, { align: 'center' })

    const filename = `REÇU_${typeEvent}_${inscription.value.matricule_membre}_${today.toISOString().split('T')[0]}.pdf`
    doc.save(filename)

  } catch (error) {
    console.error('Erreur PDF:', error)
    alert("Erreur lors de la génération du PDF")
  } finally {
    isGeneratingPDF.value = false
  }
}

const shareInscription = () => {
  const text = `Je me suis inscrit ${inscriptionType.value === 'CIMA' ? 'au CIMA' : 'à l\'AG'} ! Matricule: ${inscription.value.matricule_membre}`
  if (navigator.share) {
    navigator.share({
      title: `Mon inscription ${inscriptionType.value}`,
      text: text,
      url: window.location.href
    })
  } else {
    navigator.clipboard.writeText(text).then(() => {
      alert('Texte copié !')
    })
  }
}

const contactSupport = () => {
  window.open(`mailto:aeemci@yahoo.fr?subject=Support - Inscription ${inscriptionType.value} ${inscription.value.matricule_membre}`, '_blank')
}

const nouvelleInscription = () => {
  window.location.href = '/participant_cima_2025'
}

const retryValidation = () => {
  errorMessage.value = ''
  validateAndLoadInscriptionData()
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
  background: linear-gradient(135deg, var(--green-mist) 0%, var(--surface) 100%);
  font-family: var(--display);
}

/* Header */
.app-header {
  background: #ffffff;
  border-bottom: 1px solid var(--line);
  box-shadow: var(--shadow);
  position: sticky;
  top: 0;
  z-index: 50;
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
  width: 3rem;
  height: 3rem;
  background: var(--green);
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-text h1 {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--green);
  margin: 0;
}

.logo-text p {
  font-size: 0.875rem;
  color: var(--ink-soft);
  margin: 0;
}

/* Main Content */
.main-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.form-container {
  background: #ffffff;
  border-radius: 0;
  box-shadow: var(--shadow);
  overflow: hidden;
}

/* Loading State */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 500px;
  padding: 3rem;
  text-align: center;
}

.loading-spinner-large {
  width: 4rem;
  height: 4rem;
  border: 4px solid var(--green-mist);
  border-top: 4px solid var(--green);
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1.5rem;
}

.loading-state h2 {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--ink);
  margin-bottom: 0.5rem;
}

.loading-state p {
  color: var(--muted);
  margin-bottom: 0.25rem;
}

.matricule-info {
  font-size: 0.875rem;
  color: var(--ink-soft);
  font-weight: 500;
}

/* Error State */
.error-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 3rem;
  text-align: center;
  min-height: 500px;
  justify-content: center;
}

.error-icon {
  width: 5rem;
  height: 5rem;
  background: #fee2e2;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.error-icon svg {
  color: #dc2626;
}

.error-state h2 {
  font-size: 1.75rem;
  font-weight: bold;
  color: var(--ink);
  margin-bottom: 0.75rem;
}

.error-state p {
  color: var(--muted);
  margin-bottom: 2rem;
  max-width: 400px;
}

.error-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  justify-content: center;
}

/* Success Content */
.success-content {
  min-height: 500px;
}

.success-header {
  background: linear-gradient(135deg, var(--green) 0%, #059669 100%);
  padding: 3rem 2rem;
  text-align: center;
  color: white;
  position: relative;
  overflow: hidden;
}

.success-header::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.3;
}

.success-icon-wrapper {
  position: relative;
  z-index: 1;
  margin-bottom: 1.5rem;
}

.success-icon-animated {
  width: 6rem;
  height: 6rem;
  background: white;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--shadow);
  animation: bounce 1s ease-in-out infinite;
}

.success-icon-animated svg {
  color: var(--green);
}

.success-header h1 {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 0.75rem;
  position: relative;
  z-index: 1;
}

.success-subtitle {
  font-size: 1.125rem;
  margin-bottom: 2rem;
  position: relative;
  z-index: 1;
}

.matricule-badge {
  background: white;
  border-radius: 0;
  padding: 1.5rem;
  max-width: 400px;
  margin: 0 auto;
  box-shadow: var(--shadow);
  position: relative;
  z-index: 1;
}

.badge-header {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--green-mist);
  color: var(--green);
  padding: 0.5rem 1rem;
  border-radius: 0;
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 1rem;
}

.badge-header svg {
  color: #059669;
}

.badge-label {
  color: var(--muted);
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.badge-matricule {
  color: var(--green);
  font-size: 2rem;
  font-weight: bold;
  letter-spacing: 0.05em;
}

/* Récapitulatif Section */
.recap-section {
  padding: 2rem;
}

.section-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--ink);
  margin-bottom: 1.5rem;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.section-title svg {
  color: var(--green);
}

/* Info Grid */
.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.info-card {
  display: flex;
  gap: 1rem;
  padding: 1.25rem;
  background: linear-gradient(135deg, var(--surface) 0%, #f3f4f6 100%);
  border-radius: 0;
  border: 1px solid var(--line);
  transition: all 0.2s ease;
}

.info-card:hover {
  box-shadow: var(--shadow);
  transform: translateY(-2px);
}

.info-card-full {
  grid-column: 1 / -1;
}

.info-icon {
  width: 3rem;
  height: 3rem;
  border-radius: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: white;
}

.bg-emerald { background: var(--green-2); }
.bg-blue { background: #3b82f6; }
.bg-indigo { background: #6366f1; }
.bg-orange { background: var(--gold); }
.bg-purple { background: #a855f7; }
.bg-teal { background: #14b8a6; }
.bg-green { background: var(--green); }

.info-content {
  flex: 1;
  min-width: 0;
}

.info-label {
  font-size: 0.75rem;
  color: var(--muted);
  text-transform: uppercase;
  font-weight: 600;
  margin-bottom: 0.25rem;
  letter-spacing: 0.05em;
}

.info-value {
  font-size: 1rem;
  color: var(--ink);
  font-weight: bold;
  word-break: break-word;
}

.info-value.amount {
  color: var(--green);
  font-size: 1.25rem;
}

/* Main Action */
.main-action {
  margin-bottom: 2rem;
}

/* Info Messages */
.info-messages {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
}

.info-message {
  display: flex;
  gap: 1rem;
  padding: 1.25rem;
  border-radius: 0;
  border: 2px solid;
}

.info-message svg {
  flex-shrink: 0;
}

.info-message h4 {
  font-size: 0.875rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.info-message p {
  font-size: 0.875rem;
  line-height: 1.5;
}

.small-info {
  font-size: 0.75rem !important;
  margin-top: 0.5rem !important;
  font-weight: 500;
}

.success-message {
  background: var(--green-mist);
  border-color: #6ee7b7;
  color: var(--green);
}

.success-message svg {
  color: #059669;
}

.payment-message {
  background: #dbeafe;
  border-color: #93c5fd;
  color: #1e40af;
}

.payment-message svg {
  color: #2563eb;
}

.free-message {
  background: var(--green-mist);
  border-color: #6ee7b7;
  color: var(--green);
}

.free-message svg {
  color: #059669;
}

/* Secondary Actions */
.secondary-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: center;
  flex-wrap: wrap;
  padding-top: 1.5rem;
  border-top: 1px solid var(--line);
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-primary {
  background: var(--green);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: var(--green);
  box-shadow: var(--shadow);
}

.btn-large {
  width: 100%;
  padding: 1.25rem 2rem;
  font-size: 1rem;
  border-radius: 0;
}

.btn-secondary {
  background: #f3f4f6;
  color: var(--ink-soft);
}

.btn-secondary:hover {
  background: var(--line);
}

.btn-outline {
  background: white;
  color: var(--green);
  border: 2px solid var(--green);
}

.btn-outline:hover {
  background: var(--green-mist);
}

.loading-spinner {
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
  }

  .success-header {
    padding: 2rem 1rem;
  }

  .success-header h1 {
    font-size: 2rem;
  }

  .badge-matricule {
    font-size: 1.5rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .secondary-actions {
    flex-direction: column;
  }

  .btn-outline {
    width: 100%;
  }
}
</style>