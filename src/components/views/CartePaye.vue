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
                <p>Confirmation de Paiement</p>
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
  
      <!-- Loading State -->
      <div v-if="isLoading" class="loading-container">
        <div class="loading-content">
          <div class="loading-spinner"></div>
          <h2>Validation du paiement...</h2>
          <p>Veuillez patienter pendant que nous validons votre paiement et récupérons vos informations</p>
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
            <button @click="retryValidation" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
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
              <p class="success-message">Votre paiement a été validé avec succès</p>
              <div class="payment-confirmation">
                <div class="confirmation-badge">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  <span>Paiement validé</span>
                </div>
                <p class="matricule-info">
                  Votre carte de membre AEEMCI a été créée<br>
                  <strong>Matricule: {{ membre.matricule_gen }}</strong>
                </p>
              </div>
            </div>
  
            <!-- Récapitulatif du membre -->
            <div class="member-summary">
              <h3>Récapitulatif de votre inscription</h3>
              <div class="summary-grid">
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Nom complet</span>
                    <span class="summary-value">{{ membre.prenom }} {{ membre.nom }}</span>
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
                    <span class="summary-value">{{ membre.contact }}</span>
                  </div>
                </div>
  
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Secrétariat</span>
                    <span class="summary-value">{{ membre.region || membre.secretariat_debut }}</span>
                  </div>
                </div>
  
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Statut</span>
                    <span class="summary-value">{{ getStatutLabel(membre.je_suis) }}</span>
                  </div>
                </div>
  
                <div v-if="membre.qualite_membre" class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 17.06 7H16c-.8 0-1.54.37-2.01.99l-2.54 7.63H14v6h6z"/>
                    </svg>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Poste</span>
                    <span class="summary-value">{{ membre.qualite_membre }}</span>
                  </div>
                </div>
  
                <div class="summary-item">
                  <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H6V9h14v11z"/>
                    </svg>
                  </div>
                  <div class="summary-content">\
                    <span class="summary-label">Date d\'inscription</span>
                    <span class="summary-value\">{{ formatDate(membre.created_at) }}</span>
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
                {{ isGeneratingPDF ? 'Génération...' : 'Télécharger ma fiche PDF' }}
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
                  <h4>Votre carte est validée !</h4>
                  <p>Conservez précieusement votre matricule <strong>{{ membre.matricule_gen }}</strong> et votre fiche de membre. Ils vous seront demandés lors des activités AEEMCI.</p>
                </div>
              </div>
  
              <div class="info-card">
                <div class="info-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                </div>
                <div class="info-content">
                  <h4>Paiement validé</h4>
                  <p>Votre paiement de <strong>1 000 FCFA</strong> a été validé avec succès. Statut: <strong>{{ membre.statut_paiement }}</strong></p>
                  <p v-if="membre.ref_paiement"><small>Référence: {{ membre.ref_paiement }}</small></p>
                </div>
              </div>
            </div>
  
            <!-- Actions secondaires -->
            <div class="secondary-actions">
              <button @click="shareCard" class="btn btn-outline">
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
  
              <button @click="viewMemberArea" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                </svg>
                Espace membre
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Fiche de membre cachée pour PDF -->
      <div class="hidden">
        <FicheMembre v-if="membre" ref="ficheRef" :membre="membre" />
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import QRCode from 'qrcode';
  import axios from 'axios'
  
  // Composants
  import FicheMembre from './FicheMembre.vue'
  
  // Route et état
  const route = useRoute()
  const ficheRef = ref(null)
  
  // État de l'application
  const membre = ref(null)
  const isLoading = ref(true)
  const errorMessage = ref('')
  const isGeneratingPDF = ref(false)


  
  // Récupération du matricule depuis l'URL
  const matricule = route.params.matricule || ''
  
  // Validation et récupération des données du membre
  const validateAndLoadMemberData = async () => {
    if (!matricule) {
      errorMessage.value = 'Matricule manquant dans l\'URL'
      isLoading.value = false
      return
    }
  
    try {
      isLoading.value = true
      errorMessage.value = ''
  
      // Appel à l'API qui met à jour le statut ET récupère les données
      const response = await axios.post('https://sogetrag.com/api/update_payment_status.php', {
        matricule: matricule
      })
      
      if (response.data.success) {
        membre.value = response.data.membre
        console.log('Paiement validé et données récupérées:', response.data)
      } else {
        errorMessage.value = response.data.message || 'Erreur lors de la validation du paiement'
      }
    } catch (error) {
      console.error('Erreur lors de la validation:', error)
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
  
  // Fonctions utilitaires
  const formatDate = (dateString) => {
    if (!dateString) return 'Non renseigné'
    return new Date(dateString).toLocaleDateString('fr-FR')
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

  async function getBase64FromUrl(url) {
  const response = await fetch(url);
  const blob = await response.blob();
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(reader.result);
    reader.onerror = reject;
    reader.readAsDataURL(blob);
  });
}

  
  // Actions
  const generatePDF = async () => {
  isGeneratingPDF.value = true;

  try {
    const { default: jsPDF } = await import('jspdf');
    const doc = new jsPDF('p', 'mm', 'a4');

    const today = new Date();
    const receiptNumber = Math.floor(Math.random() * 99999).toString().padStart(5, '0');

    const qrText = `https://aeemci.sastock.com/carte_paye/${membre.value.matricule_gen}`;
  const qrDataURL = await QRCode.toDataURL(qrText, {
    width: 150,
    margin: 1,
  });

    // Ajouter le logo (x, y, width, height)
    doc.addImage('https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg', 'JPEG', 10, 8, 28, 25); // Ajustez les dimensions selon votre logo

    doc.setFont('helvetica', 'bold');
doc.setFontSize(10); // Texte plus petit
doc.setTextColor(230, 230, 230); // Gris très clair (presque invisible, style "sécurité")

for (let y = 20; y <= 280; y += 15) { // lignes du haut vers le bas
  for (let x = 10; x <= 200; x += 30) { // mots de gauche à droite
    doc.text('AEEMCI', x, y);
  }
}
    // === En-tête AEEMCI ===
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(24);
    doc.setTextColor(0, 128, 0);
    doc.text('AEEMCI', 85, 20);

    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.text("Association des Elèves et Etudiants Musulmans de Côte d'Ivoire", 105, 25, { align: 'center' });

    // === Titre principal ===
    const cardX = 50; // Centré horizontalement
    const cardY = 30;
    const cardWidth = 105;
    const cardHeight = 20;

    // Fond vert clair sans bordure avec coins arrondis
    doc.setFillColor(0, 200, 100); // Vert clair (modifiable)
    doc.roundedRect(cardX, cardY, cardWidth, cardHeight, 5, 5, 'F'); // rx=5mm, ry=5mm, F=fill only

    // Texte noir centré
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(0, 0, 0); // Texte noir
    doc.setFontSize(12);
    doc.text("RECU D’INSCRIPTION DE LA CARTE", 100, cardY + 8, { align: 'center' });
    doc.text("DE MEMBRE", 100, cardY + 14.5, { align: 'center' });

    doc.setLineWidth(0.2); // Épaisseur du trait
    doc.line(20, 60, 190, 60);

    // === Infos Reçu ===
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(10);
    doc.text(`Reçu N° : ${receiptNumber}`, 100, 70, { align: 'center' });

    // Texte "MATRICULE: ..." plus grand et gras
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(14);
    doc.text(`MATRICULE: ${membre.value.matricule_gen}`, 100, 76, { align: 'center' });

    doc.setLineWidth(0.2); // Épaisseur du trait
    doc.line(20, 85, 190, 85);

   

    // === IDENTIFICATION ===
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(12);
    doc.text("IDENTIFICATION", 20, 105);

    // Cadre photo
    
    doc.addImage(membre.value.photo_membre, 'JPEG', 145, 100, 40, 40);

    // Infos personnelles
    const infosY = 115;
    doc.setFontSize(10);

    doc.setFont('helvetica', 'normal'); doc.text('Nom :', 20, infosY);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.nom || '', 45, infosY);

    doc.setFont('helvetica', 'normal'); doc.text('Prénom(s) :', 20, infosY + 8);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.prenom || '', 45, infosY + 8);

    doc.setFont('helvetica', 'normal'); doc.text('Contact :', 20, infosY + 16);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.contact || '', 45, infosY + 16);

    doc.setFont('helvetica', 'normal'); doc.text('Secrétariat', 20, infosY + 24);
    doc.text('régional :', 20, infosY + 30);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.region || membre.value.secretariat_debut || '', 45, infosY + 27);

    doc.setFont('helvetica', 'normal'); doc.text('Sous-comité :', 20, infosY + 38);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.ville_ou_commune || membre.value.souscomite_debut || '', 45, infosY + 38);

    doc.setFont('helvetica', 'normal'); doc.text('Section :', 20, infosY + 46);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.section || '', 45, infosY + 46);

    // === REFERENCES DU PAIEMENT ===
    doc.setFontSize(12);
    doc.setFont('helvetica', 'bold');
    doc.text("REFERENCES DU PAIEMENT", 20, infosY + 66);

    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal'); doc.text('ID de la transaction :', 20, infosY + 78);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.transaction_id || 'N/A', 70, infosY + 78);

    doc.setFont('helvetica', 'normal'); doc.text('Opérateur de paiement :', 20, infosY + 86);
    doc.setFont('helvetica', 'bold'); doc.text('WAVE', 70, infosY + 86);

    doc.setFont('helvetica', 'normal'); doc.text('Numéro de la transaction :', 20, infosY + 94);
    doc.setFont('helvetica', 'bold'); doc.text(membre.value.numero_wave || 'N/A', 70, infosY + 94);

    doc.setFont('helvetica', 'normal'); doc.text('Date de paiement :', 20, infosY + 102);
    doc.setFont('helvetica', 'bold'); doc.text(formatDate(membre.value.created_at), 70, infosY + 102);

     // === Date et Lieu ===
     const dateStr = today.toLocaleDateString('fr-FR', {
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    const timeStr = today.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', hour12: false });
    const dateCode = today.toLocaleDateString('fr-FR').replace(/\//g, '') + '      ' + timeStr;

    doc.text(`Fait à Abidjan, le ${dateStr}`, 20, infosY + 115);
    doc.text(`Imprimé le : ${dateCode}`, 20, infosY +120);

    // QR Code (rectangle vide)
    doc.addImage(qrDataURL, 'PNG', 146, 185, 40, 40);

    // === PIED DE PAGE ===

    // === FILIGRANE "AEEMCI" EN FOND TRANSPARENT ===


    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.text("Siège Social : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08", 105, 275, { align: 'center' });
    doc.text("27 33 75 42 12 / 07 09 99 55 31 / 05 44 54 34 89 / aeemci@yahoo.fr / www.aeemci.ci", 105, 280, { align: 'center' });

    doc.setFont('helvetica', 'bold');
    doc.setTextColor(0, 128, 0);
    doc.text("AEEMCI, pour une identité islamique !", 105, 286, { align: 'center' });

    const filename = `recu_aeemci_${membre.value.matricule_gen}_${today.toISOString().split('T')[0]}.pdf`;
    doc.save(filename);

  } catch (error) {
    console.error('Erreur lors de la génération du PDF:', error);
    alert("Erreur lors de la génération du PDF. Veuillez réessayer.");
  } finally {
    isGeneratingPDF.value = false;
  }
}
  
  const printFiche = () => {
    if (ficheRef.value?.$el) {
      // Créer une nouvelle fenêtre pour l'impression
      const printWindow = window.open("", "_blank")
      if (printWindow) {
        // Récupérer tous les styles du composant FicheMembre
        const ficheStyles = `
          <style>
            /* Reset et base pour impression */
            * {
              margin: 0;
              padding: 0;
              box-sizing: border-box;
            }
  
            body {
              font-family: Arial, sans-serif;
              background: white;
              margin: 0;
              padding: 0;
            }
  
            /* Styles spécifiques pour l'impression */
            @media print {
              body {
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
              }
              
              .fiche-membre-aeemci {
                width: 100% !important;
                height: auto !important;
                padding: 10mm !important;
                margin: 0 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
              }
  
              .header-section {
                page-break-inside: avoid;
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
  
            /* Copie des styles du composant FicheMembre */
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
  
            .separator-line {
              height: 4px;
              background: #16a34a;
              margin: 20px 0;
            }
  
            .main-body {
              display: flex;
              flex-direction: column;
              gap: 20px;
            }
  
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
              font-size: 1.1em;
            }
  
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
              grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
              letter-spacing: 0.05em;
            }
  
            .info-value {
              font-size: 12px;
              color: #000000;
              font-weight: 600;
              padding: 5px 0;
              border-bottom: 1px solid #d1d5db;
              min-height: 20px;
            }
  
            .militantisme-section,
            .status-section {
              background: #ffffff;
              border: 1px solid #e5e7eb;
              border-radius: 8px;
              padding: 15px;
            }
  
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
  
            .qr-code-container img {
              width: 100%;
              height: 100%;
              object-fit: contain;
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
          </style>
        `
  
        printWindow.document.write(`
          <!DOCTYPE html>
  <html lang="fr">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Fiche de Membre AEEMCI - ${membre.value.matricule_gen}</title>
      ${ficheStyles}
    </head>
    <body>
      ${ficheRef.value.$el.outerHTML}
      <script>
        window.onload = function() {
          const images = document.querySelectorAll('img');
          let loadedImages = 0;
          const totalImages = images.length;

          function triggerPrint() {
            setTimeout(() => {
              window.print();
            }, 500);
          }

          if (totalImages === 0) {
            triggerPrint();
          } else {
            images.forEach(img => {
              if (img.complete) {
                loadedImages++;
              } else {
                img.onload = img.onerror = () => {
                  loadedImages++;
                  if (loadedImages === totalImages) triggerPrint();
                };
              }
            });

            if (loadedImages === totalImages) {
              triggerPrint();
            }
          }

          setTimeout(() => window.print(), 3000);
        };

        window.onafterprint = function () {
          window.close();
        };
      <\/script>
    </body>
  </html>
        `)
        printWindow.document.close()
      }
    }
  }
  
  const shareCard = () => {
    if (navigator.share) {
      navigator.share({
        title: 'Ma carte AEEMCI',
        text: `Je viens de recevoir ma carte de membre AEEMCI ! Matricule: ${membre.value.matricule_gen}`,
        url: window.location.href
      })
    } else {
      // Fallback pour les navigateurs qui ne supportent pas l'API Web Share
      const text = `Je viens de recevoir ma carte de membre AEEMCI ! Matricule: ${membre.value.matricule_gen}`
      navigator.clipboard.writeText(text).then(() => {
        alert('Texte copié dans le presse-papiers !')
      })
    }
  }
  
  const contactSupport = () => {
    window.open('mailto:support@aeemci.org?subject=Support - Carte membre ' + membre.value.matricule_gen, '_blank')
  }
  
  const viewMemberArea = () => {
    // Redirection vers l'espace membre
    window.open('https://aeemci.sastock.com/espace-membre', '_blank')
  }
  
  const nouvelleInscription = () => {
    window.location.href = 'https://aeemci.sastock.com/carte_membre'
  }
  
  const retryValidation = () => {
    validateAndLoadMemberData()
  }
  
  // Initialisation
  onMounted(() => {
    validateAndLoadMemberData()
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
  
  /* Hidden */
  .hidden {
    display: none;
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
  