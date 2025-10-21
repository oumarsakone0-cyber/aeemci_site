<template>
    <div class="fiche-membre-aeemci">
      <!-- En-tête AEEMCI -->
      <div class="header-section">
        <div class="logo-section">
          <div class="logo-container">
            <!-- Logo AEEMCI -->
            <div class="logo-circle">
              <div class="logo-text">A</div>
            </div>
          </div>
          <div class="organization-info">
            <h1 class="org-title">AEEMCI</h1>
            <h2 class="org-subtitle">Association des Étudiants et Élèves Musulmans de Côte d'Ivoire</h2>
            <div class="contact-details">
              <p><strong>Siège Social :</strong> Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08</p>
              <p><strong>Contacts :</strong> 27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89</p>
              <p><strong>Email :</strong> aeemci@yahoo.fr | <strong>Site :</strong> www.aeemci.ci</p>
              <p class="slogan"><strong>AEEMCI, pour une identité islamique !</strong></p>
            </div>
          </div>
          <div class="header-right">
            <h2 class="document-title">FICHE DE MEMBRE</h2>
            <p class="year-info">Année {{ new Date().getFullYear() }}</p>
          </div>
        </div>
      </div>
  
      <!-- Ligne de séparation -->
      <div class="separator-line"></div>
  
      <!-- Corps principal -->
      <div class="main-body">
        <!-- Section avec photo et matricule -->
        <div class="top-section">
          <div class="photo-matricule-section">
            <!-- Photo -->
            <div class="photo-container">
              <img 
                v-if="membre.photo_membre" 
                :src="membre.photo_membre" 
                :alt="`Photo de ${membre.prenom} ${membre.nom}`"
                class="member-photo"
                @error="handleImageError"
              />
              <div v-else class="photo-placeholder">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
              </div>
            </div>
            
            <!-- Matricule -->
            <div class="matricule-section">
              <div class="matricule-box">
                <p class="matricule-label">MATRICULE</p>
                <p class="matricule-value">{{ membre.matricule_gen }}</p>
              </div>
            </div>
          </div>
  
          <!-- Informations personnelles -->
          <div class="personal-info-section">
            <h3 class="section-title">INFORMATIONS PERSONNELLES</h3>
            <div class="info-grid">
              <div class="info-row">
                <span class="info-label">Prénom</span>
                <span class="info-value">{{ membre.prenom }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Nom</span>
                <span class="info-value">{{ membre.nom }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Date de naissance</span>
                <span class="info-value">{{ formatDate(membre.date_naiss) }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Lieu de naissance</span>
                <span class="info-value">{{ membre.lieu_naiss }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Sexe</span>
                <span class="info-value">{{ membre.sexe === 'M' ? 'Masculin' : 'Féminin' }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Contact</span>
                <span class="info-value">{{ membre.contact }}</span>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Section militantisme -->
        <div class="militantisme-section">
          <h3 class="section-title">INFORMATIONS MILITANTISME</h3>
          <div class="info-grid-three">
            <div class="info-row">
              <span class="info-label">SR de début</span>
              <span class="info-value">{{ membre.secretariat_debut }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Sous-comité début</span>
              <span class="info-value">{{ membre.souscomite_debut }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Année de début</span>
              <span class="info-value">{{ membre.annee_debut }}</span>
            </div>
          </div>
        </div>
  
        <!-- Section statut et poste actuel -->
        <div class="status-section">
          <h3 class="section-title">STATUT ET POSTE ACTUEL</h3>
          <div class="info-grid-two">
            <div class="info-row">
              <span class="info-label">Statut</span>
              <span class="info-value">{{ getStatutLabel(membre.je_suis) }}</span>
            </div>
            <div class="info-row" v-if="membre.qualite_membre">
              <span class="info-label">Poste</span>
              <span class="info-value">{{ membre.qualite_membre }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Secrétariat actuel</span>
              <span class="info-value">{{ membre.region || membre.secretariat_debut }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Commune/Ville</span>
              <span class="info-value">{{ membre.ville_ou_commune || membre.souscomite_debut }}</span>
            </div>
            <div class="info-row" v-if="membre.section">
              <span class="info-label">Section</span>
              <span class="info-value">{{ membre.section }}</span>
            </div>
          </div>
        </div>
  
        <!-- Section informations d'inscription -->
        <div class="inscription-section">
          <div class="inscription-box">
            <div class="inscription-grid">
              <div class="inscription-item">
                <span class="inscription-label">Date d'inscription</span>
                <span class="inscription-value">{{ formatDate(membre.created_at) }}</span>
              </div>
              <div class="inscription-item">
                <span class="inscription-label">Validité</span>
                <span class="inscription-value">Année académique {{ new Date().getFullYear() }}-{{ new Date().getFullYear() + 1 }}</span>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Section signatures -->
        <div class="signatures-section">
          <div class="signature-left">
            <div class="signature-line"></div>
            <p class="signature-label">Signature du membre</p>
          </div>
          <div class="signature-right">
            <div class="signature-line"></div>
            <p class="signature-label">Cachet et signature AEEMCI</p>
          </div>
        </div>
  
        <!-- QR Code et informations finales -->
        <div class="footer-section">
          <div class="qr-section">
            <div ref="qrCodeContainer" class="qr-code-container">
              <!-- QR Code sera généré ici -->
            </div>
          </div>
          <div class="footer-info">
            <p class="footer-title"><strong>AEEMCI</strong> - Association des Étudiants et Élèves Musulmans de Côte d'Ivoire</p>
            <p class="footer-contact">Siège Social: Abidjan, Côte d'Ivoire | Email: contact@aeemci.org | Tél: +225 XX XX XX XX XX</p>
            <p class="footer-validity">Cette fiche est valable pour l'année académique en cours et doit être présentée lors des activités AEEMCI</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, ref, onMounted, nextTick } from 'vue'
  
  const props = defineProps({
    membre: {
      type: Object,
      required: true
    }
  })
  
  const qrCodeContainer = ref(null)
  
  // Fonctions utilitaires
  const formatDate = (dateString) => {
    if (!dateString) return 'Non renseigné'
    return new Date(dateString).toLocaleDateString('fr-FR')
  }
  
  const formatDateLong = (date) => {
    const options = { 
      weekday: 'long', 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    }
    return date.toLocaleDateString('fr-FR', options)
  }
  
  const formatDateShort = (date) => {
    return date.toLocaleDateString('fr-FR').replace(/\//g, '')
  }
  
  const formatTime = (date) => {
    return date.toLocaleTimeString('fr-FR', { 
      hour: '2-digit', 
      minute: '2-digit',
      hour12: false 
    })
  }
  
  const getStatutLabel = (statut) => {
    const labels = {
      'SR': 'Secrétaire Régional',
      'SOUS_COMITE': 'Membre du Sous-comité',
      'BUREAU_SECTION': 'Membre du bureau de Section',
      'MEMBRE_SIMPLE': 'Membre simple'
    }
    return labels[statut] || statut
  }
  
  const getStatusClass = (statut) => {
    switch (statut) {
      case 'VALIDE':
      case 'PAYE': 
        return 'status-valid'
      case 'EN_ATTENTE': 
        return 'status-pending'
      case 'ECHEC': 
        return 'status-failed'
      default: 
        return 'status-unknown'
    }
  }
  
  const generateReceiptNumber = () => {
    const date = new Date()
    const year = date.getFullYear().toString().slice(-2)
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const day = date.getDate().toString().padStart(2, '0')
    const random = Math.floor(Math.random() * 9999).toString().padStart(4, '0')
    return `${year}${month}${day}${random}`
  }
  
  const generateVerificationCode = () => {
    return Math.random().toString(36).substr(2, 12).toUpperCase()
  }
  
  const handleImageError = (event) => {
    event.target.style.display = 'none'
    const placeholder = event.target.nextElementSibling
    if (placeholder) {
      placeholder.style.display = 'flex'
    }
  }
  
  // Génération du QR Code avec API externe
  const generateQRCode = async () => {
    try {
      if (qrCodeContainer.value && props.membre?.matricule_gen) {
        const qrUrl = `https://aeemci.sastock.com/carte_paye/${props.membre.matricule_gen}`
        
        // Utiliser l'API QR Server pour générer le QR code
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${encodeURIComponent(qrUrl)}`
        
        // Créer l'image QR code
        const img = document.createElement('img')
        img.src = qrApiUrl
        img.alt = 'QR Code de vérification'
        img.style.width = '100%'
        img.style.height = '100%'
        img.style.objectFit = 'contain'
        
        // Gérer les erreurs de chargement
        img.onerror = () => {
          console.error('Erreur lors du chargement du QR code')
          // Fallback avec un placeholder
          qrCodeContainer.value.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #666; flex-direction: column; font-size: 10px;">
              <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zM3 21h8v-8H3v8zm2-6h4v4H5v-4zM13 3v8h8V3h-8zm6 6h-4V5h4v4zM19 13h2v2h-2zM13 13h2v2h-2zM15 15h2v2h-2zM13 17h2v2h-2zM15 19h2v2h-2zM17 17h2v2h-2zM17 19h2v2h-2zM19 17h2v2h-2z"/>
              </svg>
              <span>QR Code</span>
            </div>
          `
        }
        
        // Remplacer le contenu du container
        qrCodeContainer.value.innerHTML = ''
        qrCodeContainer.value.appendChild(img)
      }
    } catch (error) {
      console.error('Erreur lors de la génération du QR code:', error)
      // Fallback avec un placeholder
      if (qrCodeContainer.value) {
        qrCodeContainer.value.innerHTML = `
          <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #666; flex-direction: column; font-size: 10px;">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zM3 21h8v-8H3v8zm2-6h4v4H5v-4zM13 3v8h8V3h-8zm6 6h-4V5h4v4zM19 13h2v2h-2zM13 13h2v2h-2zM15 15h2v2h-2zM13 17h2v2h-2zM15 19h2v2h-2zM17 17h2v2h-2zM17 19h2v2h-2zM19 17h2v2h-2z"/>
            </svg>
            <span>QR Code</span>
          </div>
        `
      }
    }
  }
  
  onMounted(async () => {
    await nextTick()
    generateQRCode()
  })
  </script>
  
  <style scoped>
  .fiche-membre-aeemci {
    width: 210mm;
    min-height: 297mm;
    background: #ffffff;
    font-family: Arial, sans-serif;
    color: #000000;
    padding: 15mm;
    box-sizing: border-box;
    position: relative;
    font-size: 14px;
    line-height: 1.4;
  }
  
  /* En-tête */
  .header-section {
    margin-bottom: 20px;
  }
  
  .logo-section {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  
  .logo-container {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .logo-circle {
    width: 60px;
    height: 60px;
    background: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .logo-text {
    color: #ffffff;
    font-size: 24px;
    font-weight: bold;
  }
  
  .organization-info {
    flex: 1;
    margin-left: 15px;
  }
  
  .org-title {
    font-size: 24px;
    font-weight: bold;
    margin: 0 0 5px 0;
    color: #16a34a;
    letter-spacing: 1px;
  }
  
  .org-subtitle {
    font-size: 14px;
    font-weight: bold;
    margin: 0 0 10px 0;
    color: #000000;
    line-height: 1.2;
  }
  
  .contact-details {
    font-size: 10px;
    line-height: 1.3;
  }
  
  .contact-details p {
    margin: 1px 0;
  }
  
  .slogan {
    margin-top: 5px;
    font-style: italic;
    color: #16a34a;
  }
  
  .header-right {
    text-align: right;
    flex-shrink: 0;
  }
  
  .document-title {
    font-size: 20px;
    font-weight: bold;
    color: #000000;
    margin: 0 0 5px 0;
  }
  
  .year-info {
    font-size: 12px;
    color: #666666;
    margin: 0;
  }
  
  /* Ligne de séparation */
  .separator-line {
    height: 4px;
    background: #16a34a;
    margin: 20px 0;
  }
  
  /* Corps principal */
  .main-body {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  /* Section du haut avec photo et infos personnelles */
  .top-section {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
  }
  
  .photo-matricule-section {
    flex-shrink: 0;
    width: 150px;
    text-align: center;
  }
  
  .photo-container {
    margin-bottom: 15px;
  }
  
  .member-photo {
    width: 120px;
    height: 150px;
    object-fit: cover;
    border: 4px solid #16a34a;
    border-radius: 8px;
  }
  
  .photo-placeholder {
    width: 120px;
    height: 150px;
    border: 4px solid #16a34a;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    color: #999999;
  }
  
  .matricule-section {
    margin-top: 15px;
  }
  
  .matricule-box {
    background: #f0fdf4;
    border: 2px solid #16a34a;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
  }
  
  .matricule-label {
    font-size: 10px;
    color: #15803d;
    font-weight: bold;
    margin: 0 0 5px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  
  .matricule-value {
    font-size: 14px;
    font-weight: bold;
    color: #15803d;
    margin: 0;
    font-family: 'Courier New', monospace;
  }
  
  /* Informations personnelles */
  .personal-info-section {
    flex: 1;
  }
  
  .section-title {
    font-size: 16px;
    font-weight: bold;
    color: #000000;
    margin: 0 0 15px 0;
    padding-bottom: 5px;
    border-bottom: 2px solid #e5e7eb;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }
  
  .info-grid-two {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }
  
  .info-grid-three {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
  }
  
  .info-row {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  
  .info-label {
    font-size: 10px;
    color: #666666;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .info-value {
    font-size: 12px;
    color: #000000;
    font-weight: 600;
    padding: 5px 0;
    border-bottom: 1px solid #d1d5db;
    min-height: 20px;
  }
  
  /* Sections */
  .militantisme-section,
  .status-section {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 15px;
  }
  
  /* Section inscription */
  .inscription-section {
    margin: 20px 0;
  }
  
  .inscription-box {
    background: #f0fdf4;
    border: 2px solid #16a34a;
    border-radius: 8px;
    padding: 15px;
  }
  
  .inscription-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }
  
  .inscription-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }
  
  .inscription-label {
    font-size: 10px;
    color: #15803d;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .inscription-value {
    font-size: 12px;
    color: #15803d;
    font-weight: bold;
  }
  
  /* Signatures */
  .signatures-section {
    display: flex;
    justify-content: space-between;
    margin: 40px 0 30px 0;
  }
  
  .signature-left,
  .signature-right {
    width: 45%;
    text-align: center;
  }
  
  .signature-line {
    width: 100%;
    height: 60px;
    border-bottom: 2px solid #000000;
    margin-bottom: 10px;
  }
  
  .signature-label {
    font-size: 12px;
    font-weight: bold;
    margin: 0;
    color: #000000;
  }
  
  /* Footer avec QR code */
  .footer-section {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #16a34a;
  }
  
  .qr-section {
    flex-shrink: 0;
  }
  
  .qr-code-container {
    width: 80px;
    height: 80px;
    border: 2px solid #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
  }
  
  .footer-info {
    flex: 1;
    text-align: center;
  }
  
  .footer-title {
    font-size: 12px;
    font-weight: bold;
    color: #000000;
    margin: 0 0 5px 0;
  }
  
  .footer-contact {
    font-size: 10px;
    color: #666666;
    margin: 0 0 10px 0;
  }
  
  .footer-validity {
    font-size: 10px;
    color: #666666;
    margin: 0;
    font-style: italic;
  }
  
  /* Responsive pour impression */
  @media print {
    .fiche-membre-aeemci {
      width: 100% !important;
      height: 100vh !important;
      padding: 10mm !important;
      margin: 0 !important;
      box-shadow: none !important;
    }
    
    .top-section {
      page-break-inside: avoid;
    }
    
    .signatures-section {
      page-break-inside: avoid;
    }
    
    .footer-section {
      page-break-inside: avoid;
    }
  }
  
  /* Améliorations visuelles pour l'écran */
  @media screen {
    .fiche-membre-aeemci {
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      margin: 20px auto;
    }
  }
  </style>
  