<template>
    <div class="app">
      <div class="animated-background">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
        <div class="floating-shape shape-5"></div>
        <div class="floating-shape shape-6"></div>
      </div>
  
      <main class="main-container">
        <div class="results-card">
          <div class="card-header">
            <h1 class="main-title">SENAFOI 2025</h1>
            <p class="subtitle">Consultation de Résultat et Impression de Bulletin</p>
          </div>
  
          <form @submit.prevent="consultResults" class="results-form">
            <div class="form-group">
              <label for="matricule" class="form-label">Numéro de Matricule</label>
              <input
                type="text"
                id="matricule"
                v-model="matricule"
                @input="handleMatriculeInput"
                placeholder="Entrez votre numéro de matricule"
                class="form-input"
                :disabled="isLoading"
                required
              />
            </div>
  
            <button @click="printResult" :disabled="isLoading || !matricule.trim()" class="print-btn">
              <span v-if="printing" class="loading-spinner"></span>
              {{ printing ? 'Génération en cours...' : 'Rechercher' }}
            </button>
          </form>
  
          <div v-if="error" class="error-display">
            <p class="error-text">{{ error }}</p>
          </div>
  
          <div v-if="result" class="result-display">
            <div class="result-header">
              <div class="student-photo">
                <img 
                  :src="result.photo || '/placeholder.svg?height=120&width=120'" 
                  :alt="`Photo de ${result.nom} ${result.prenom}`"
                  class="photo-img"
                />
              </div>
              <div class="student-info">
                <h2>{{ result.nom }} {{ result.prenom }}</h2>
                <p class="matricule-display">Matricule: {{ result.matricule }}</p>
              </div>
            </div>
            
            <div class="result-details">
              <div class="detail-row">
                <strong>Niveau :</strong> 
                <span>{{ result.niveau_seminaire }}</span>
              </div>
              <div class="detail-row">
                <strong>Score Obtenu :</strong> 
                <span class="score">{{ result.score_obtenu }}</span>
              </div>
              <div class="detail-row" v-if="result.moyenne !== undefined">
                <strong>Moyenne :</strong> 
                <span class="moyenne">{{ result.moyenne }}/20</span>
              </div>
              <div class="detail-row" v-if="result.conduite !== undefined">
                <strong>Conduite :</strong> 
                <span>{{ result.conduite }}</span>
              </div>
              <div class="detail-row" v-if="result.detail">
                <strong>Détail :</strong> 
                <span>{{ result.detail }}</span>
              </div>
              <div class="detail-row status-row">
                <strong>Statut :</strong> 
                <span :class="['status-badge', getStatusClass()]">
                  {{ getStatusText() }}
                </span>
              </div>
            </div>
            
            <button @click="printResult" class="print-btn" :disabled="isPrintingPDF">
              <span v-if="isPrintingPDF" class="loading-spinner"></span>
              <span v-else class="print-icon">🖨️</span>
              {{ isPrintingPDF ? 'Génération du PDF...' : 'Imprimer le Bulletin' }}
            </button>
          </div>
  
          <div class="help-section">
            <p class="help-text">Vous n'avez pas votre numéro de matricule ?</p>
            <a href="/liste_resultat" class="help-link">
              Cliquez ici pour le récupérer
            </a>
          </div>
        </div>
  
        <div class="info-footer">
          <p class="info-text">
            Service de consultation des résultats disponible 24h/24
          </p>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { jsPDF } from 'jspdf'
  
  // Data
  const matricule = ref('')
  const isLoading = ref(false)
  const isPrintingPDF = ref(false)
  const printing = ref(false)
  const result = ref(null)
  const error = ref('')
  
  // Helper function to convert image URL to base64
  async function getBase64FromUrl(url) {
    try {
      const response = await fetch(url)
      const blob = await response.blob()
      return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onloadend = () => resolve(reader.result)
        reader.onerror = reject
        reader.readAsDataURL(blob)
      })
    } catch (error) {
      console.warn('Erreur lors de la conversion de l\'image:', error)
      return null
    }
  }
  
  // Methods
  const handleMatriculeInput = (event) => {
    matricule.value = event.target.value.toUpperCase()
  }
  
  const consultResults = async () => {
    if (!matricule.value.trim()) return
  
    isLoading.value = true
    result.value = null
    error.value = ''
  
    try {
      const response = await fetch('https://api.aeemci-ce.ci/get_resultat_final.php', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          action: 'get_seminarist',
          matricule: matricule.value.trim()
        })
      })
  
      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`)
      }
  
      const data = await response.json()
  
      if (data.success) {
        result.value = data.data
      } else {
        error.value = data.error || 'Aucun résultat trouvé pour ce matricule'
      }
  
    } catch (err) {
      console.error('Erreur API:', err)
      error.value = 'Erreur lors de la récupération du résultat. Veuillez réessayer.'
    } finally {
      isLoading.value = false
    }
  }
  
  const printResult = async () => {
  if (!result.value) return;
  printing.value = true;

  try {
    const { default: jsPDF } = await import('jspdf');
    const { default: QRCode } = await import('qrcode');

    const doc = new jsPDF('p', 'mm', 'a4');
    const today = new Date();
    const bulletinNumber = Math.floor(Math.random() * 99999)
      .toString()
      .padStart(5, '0');
    const qrText = `SENAFOI 2025 - ${result.value.matricule} - ${result.value.nom} ${result.value.prenom}`;

    const qrDataURL = await QRCode.toDataURL(qrText, { width: 120, margin: 1 });

    const emerald = [34, 94, 78];
    const gold = [212, 175, 55];

    // Filigrane
    doc.setTextColor(240, 240, 240);
    doc.setFontSize(60);
    doc.text('﷽', 105, 150, { align: 'center' });

    // Bandeau
    doc.setFillColor(...emerald);
    doc.rect(0, 0, 210, 35, 'F');

    // Logo
    try {
      const logoImg = new Image();
      logoImg.crossOrigin = 'anonymous';
      await new Promise((resolve, reject) => {
        logoImg.onload = resolve;
        logoImg.onerror = reject;
        logoImg.src = 'https://upload.wikimedia.org/wikipedia/fr/4/42/Logo_AEEMCI.jpeg';
      });
      doc.addImage(logoImg, 'JPEG', 10, 8, 28, 25);
    } catch {}

    // Titre
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(20);
    doc.setTextColor(255, 255, 255);
    doc.text('AEEMCI', 105, 15, { align: 'center' });

    doc.setFontSize(10);
    doc.text(
      "Association des Élèves et Étudiants Musulmans de Côte d'Ivoire",
      105,
      20,
      { align: 'center' }
    );

    doc.setFontSize(14);
    doc.setTextColor(...gold);
    doc.text('BULLETIN DE RÉSULTATS - SENAFOI 2025', 105, 28, { align: 'center' });

    // Infos générales
    let startY = 45;
    doc.setTextColor(0, 0, 0);
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(9);
    doc.text(`BULLETIN N° : ${bulletinNumber}`, 18, startY);
    doc.text(`Date : ${today.toLocaleDateString('fr-FR')}`, 165, startY);

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(12);
    doc.text(`MATRICULE: ${String(result.value.matricule)}`, 105, startY + 7, { align: 'center' });

    doc.setDrawColor(...gold);
    doc.setLineWidth(0.5);
    doc.line(15, startY + 12, 195, startY + 12);

    // Identification
    let yPos = startY + 20;
    doc.setFillColor(250, 250, 250);
    doc.rect(15, yPos - 5, 180, 8, 'F');
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.text('IDENTIFICATION DU SÉMINARISTE', 20, yPos);

    if (result.value.photo) {
      try {
        const photoImg = new Image();
        photoImg.crossOrigin = 'anonymous';
        await new Promise((resolve, reject) => {
          photoImg.onload = resolve;
          photoImg.onerror = reject;
          photoImg.src = result.value.photo;
        });
        doc.addImage(photoImg, 'JPEG', 155, yPos + 2, 30, 30);
      } catch {}
    }

    yPos += 10;
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text('Nom :', 20, yPos);
    doc.setFont('helvetica', 'bold');
    doc.text(String(result.value.nom || ''), 35, yPos);

    doc.setFont('helvetica', 'normal');
    doc.text('Prénom(s) :', 20, yPos + 6);
    doc.setFont('helvetica', 'bold');
    doc.text(String(result.value.prenom || ''), 40, yPos + 6);

    doc.setFont('helvetica', 'normal');
    doc.text('Niveau :', 20, yPos + 12);
    doc.setFont('helvetica', 'bold');
    doc.text(String(result.value.niveau_seminaire || ''), 35, yPos + 12);

    // Résultats
    yPos += 25;
    doc.setFillColor(250, 250, 250);
    doc.rect(15, yPos - 5, 180, 8, 'F');
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.text("RÉSULTATS DE L'ÉVALUATION", 20, yPos);

    yPos += 8;
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text('Score obtenu :', 20, yPos);
    doc.setFont('helvetica', 'bold');
    doc.text(String(result.value.score_obtenu || ''), 45, yPos);

    doc.setFont('helvetica', 'normal');
    doc.text('Moyenne :', 90, yPos);
    doc.setFont('helvetica', 'bold');
    doc.text(`${String(result.value.moyenne || '')}/20`, 110, yPos);

    doc.setFont('helvetica', 'normal');
    doc.text('Conduite :', 20, yPos + 6);
    doc.setFont('helvetica', 'bold');
    doc.text(`${String(result.value.conduite || '')}/20`, 45, yPos + 6);

    doc.setFont('helvetica', 'normal');
    doc.text('Statut :', 90, yPos + 6);
    doc.setFont('helvetica', 'bold');
    const statut = result.value.moyenne >= 10 ? 'ADMIS' : 'RECALÉ';
    doc.setTextColor(result.value.moyenne >= 10 ? 0 : 200, result.value.moyenne >= 10 ? 128 : 0, 0);
    doc.text(statut, 110, yPos + 6);
    doc.setTextColor(0, 0, 0);

    // Observations si nécessaire
    if (result.value.details || result.value.conduite == 14) {
      yPos += 15;
      doc.setFillColor(255, 253, 240);
      doc.rect(15, yPos - 5, 180, 8, 'F');
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(11);
      doc.setTextColor(...gold);
      doc.text('OBSERVATIONS', 20, yPos);

      yPos += 8;
      doc.setTextColor(0, 0, 0);
      doc.setFont('helvetica', 'normal');
      doc.setFontSize(9);
      const obsText = result.value.details
        ? String(result.value.details)
        : 'Remarque : Améliorer la conduite et le comportement.';
      const lines = doc.splitTextToSize(obsText, 170);
      lines.forEach((line, index) => {
        doc.text(line, 20, yPos + index * 4);
      });
      yPos += lines.length * 4;
    }

    // QR Code
    doc.addImage(qrDataURL, 'PNG', 180, yPos - 15, 25, 25);

    // Infos SENAFOI
    yPos += 20;
    doc.setFillColor(255, 253, 240);
    doc.rect(15, yPos - 5, 180, 8, 'F');
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(11);
    doc.setTextColor(...gold);
    doc.text('INFORMATIONS SENAFOI 2025', 20, yPos);

    yPos += 8;
    doc.setTextColor(0, 0, 0);
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(8);
    [
      '• Séminaire National de Formation Islamique et Managériale',
      '• Dates : du 03 au 10 Août 2025',
      '• Lieu : Lycée Mamie Adjoua de Yamoussoukro',
      "• Organisé par l'AEEMCI"
    ].forEach(info => {
      doc.text(info, 20, yPos);
      yPos += 4;
    });

    // Footer
    doc.setFillColor(...emerald);
    doc.rect(0, 275, 210, 22, 'F');
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(7);
    doc.setTextColor(255, 255, 255);
    doc.text(
      'Siège : Mosquée An-Nour de la Riviera II / 08 BP 2462 Abidjan 08',
      105,
      282,
      { align: 'center' }
    );
    doc.text('27 33 75 42 12 / aeemci@yahoo.fr / www.aeemci.ci', 105, 286, { align: 'center' });
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(9);
    doc.text('AEEMCI, pour une identité islamique !', 105, 292, { align: 'center' });

    doc.save(
      `bulletin_senafoi_2025_${result.value.matricule}_${today
        .toISOString()
        .split('T')[0]}.pdf`
    );
  } catch (e) {
    console.error(e);
    error.value = 'Erreur lors de la génération du PDF';
  } finally {
    printing.value = false;
  }
};


  
  const getStatusClass = () => {
    if (!result.value) return ''
    const moyenne = parseFloat(result.value.moyenne || result.value.score_obtenu || 0)
    return moyenne >= 10 ? 'admis' : 'recale'
  }
  
  const getStatusText = () => {
    if (!result.value) return ''
    const moyenne = parseFloat(result.value.moyenne || result.value.score_obtenu || 0)
    return moyenne >= 10 ? 'ADMIS' : 'RECALÉ'
  }
  </script>
  
  <style scoped>
  .app {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #4CAF50 0%, #FF9800 100%);
    font-family: var(--display);
  }
  
  .animated-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
  }
  
  .floating-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 20s infinite ease-in-out;
  }
  
  .shape-1 {
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, #4CAF50, #388E3C);
    top: 10%;
    left: 10%;
    animation-delay: 0s;
  }
  
  .shape-2 {
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, #FF9800, #F57C00);
    top: 60%;
    right: 15%;
    animation-delay: -5s;
  }
  
  .shape-3 {
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, #4CAF50, #2E7D32);
    bottom: 20%;
    left: 20%;
    animation-delay: -10s;
  }
  
  .shape-4 {
    width: 180px;
    height: 180px;
    background: radial-gradient(circle, #FF9800, #E65100);
    top: 30%;
    right: 30%;
    animation-delay: -15s;
  }
  
  .shape-5 {
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, #4CAF50, #1B5E20);
    bottom: 40%;
    right: 10%;
    animation-delay: -7s;
  }
  
  .shape-6 {
    width: 160px;
    height: 160px;
    background: radial-gradient(circle, #FF9800, #FF6F00);
    top: 70%;
    left: 50%;
    animation-delay: -12s;
  }
  
  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(-20px) rotate(90deg); }
    50% { transform: translateY(-40px) rotate(180deg); }
    75% { transform: translateY(-20px) rotate(270deg); }
  }
  
  .main-container {
    position: relative;
    z-index: 1;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  
  .results-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 0;
    padding: 40px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255, 255, 255, 0.2);
    max-width: 600px;
    width: 100%;
    text-align: center;
  }
  
  .card-header {
    margin-bottom: 30px;
  }
  
  .main-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .subtitle {
    font-size: 1.1rem;
    color: var(--ink-soft);
    margin: 0;
  }
  
  .results-form {
    margin-bottom: 30px;
  }
  
  .form-group {
    margin-bottom: 25px;
    text-align: left;
  }
  
  .form-label {
    display: block;
    font-weight: 600;
    color: var(--ink);
    margin-bottom: 8px;
    font-size: 1rem;
  }
  
  .form-input {
    width: 100%;
    padding: 15px;
    border: 2px solid var(--line);
    border-radius: 0;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--surface);
    text-transform: uppercase;
  }
  
  .form-input:focus {
    outline: none;
    border-color: #4CAF50;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
  }
  
  .form-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  
  .submit-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #4CAF50 0%, #FF9800 100%);
    color: white;
    border: none;
    border-radius: 0;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }
  
  .submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
  }
  
  .submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
  }
  
  .loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  .error-display {
    margin-top: 20px;
    padding: 15px;
    background: #fed7d7;
    border: 1px solid #feb2b2;
    border-radius: 0;
    text-align: center;
  }
  
  .error-text {
    color: #c53030;
    font-weight: 500;
    margin: 0;
  }
  
  .result-display {
    text-align: left;
    margin-top: 30px;
    padding: 25px;
    background: var(--surface);
    border-radius: 0;
    border: 1px solid var(--line);
  }
  
  .result-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--line);
  }
  
  .student-photo {
    flex-shrink: 0;
  }
  
  .photo-img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #4CAF50;
    box-shadow: var(--shadow);
  }
  
  .student-info h2 {
    margin: 0 0 8px 0;
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 700;
  }
  
  .matricule-display {
    margin: 0;
    color: var(--muted);
    font-size: 1rem;
    font-weight: 500;
  }
  
  .result-details {
    margin-bottom: 25px;
  }
  
  .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--line);
    font-size: 1rem;
  }
  
  .detail-row:last-child {
    border-bottom: none;
  }
  
  .detail-row strong {
    color: var(--ink-soft);
    font-weight: 600;
  }
  
  .score, .moyenne {
    font-weight: 700;
    color: #4CAF50;
    font-size: 1.1rem;
  }
  
  .status-row {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid var(--line);
  }
  
  .status-badge {
    padding: 8px 16px;
    border-radius: 0;
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .status-badge.admis {
    background: var(--green-mist);
    color: var(--green);
    border: 1px solid var(--green-2);
  }
  
  .status-badge.recale {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #f87171;
  }
  
  .print-btn {
    width: 100%;
    margin-top: 20px;
    padding: 15px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 0;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 1rem;
  }
  
  .print-btn:hover:not(:disabled) {
    background: #388E3C;
    transform: translateY(-1px);
    box-shadow: var(--shadow);
  }
  
  .print-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
  }
  
  .print-icon {
    font-size: 1.2rem;
  }
  
  .help-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--line);
  }
  
  .help-text {
    color: var(--ink-soft);
    margin-bottom: 10px;
    font-size: 0.95rem;
  }
  
  .help-link {
    color: #4CAF50;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
  }
  
  .help-link:hover {
    color: #388E3C;
    text-decoration: underline;
  }
  
  .info-footer {
    margin-top: 30px;
    text-align: center;
  }
  
  .info-text {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0;
  }
  
  @media (max-width: 768px) {
    .results-card {
      padding: 30px 20px;
      margin: 10px;
    }
    
    .main-title {
      font-size: 2rem;
    }
    
    .result-header {
      flex-direction: column;
      text-align: center;
      gap: 15px;
    }
    
    .photo-img {
      width: 80px;
      height: 80px;
    }
    
    .detail-row {
      flex-direction: column;
      align-items: flex-start;
      gap: 5px;
    }
    
    .floating-shape {
      opacity: 0.05;
    }
  }
  
  @media print {
    .app {
      background: white !important;
    }
    
    .animated-background,
    .submit-btn,
    .help-section,
    .info-footer,
    .print-btn {
      display: none !important;
    }
    
    .results-card {
      background: white !important;
      box-shadow: none !important;
      border: 1px solid #000 !important;
    }
    
    .result-header {
      border-bottom: 2px solid #000 !important;
    }
    
    .status-badge {
      border: 1px solid #000 !important;
    }
  }
  </style>
  