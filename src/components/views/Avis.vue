<template>
    <div class="app-container">
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
                <p>Questionnaire d'Évaluation du Séminaire</p>
              </div>
            </div>
          </div>
          <div class="header-right" v-if="currentStep > 0">
            <p class="step-info">Étape {{ currentStep }} sur {{ totalSteps }}</p>
            <div class="progress-bar">
              <div
                class="progress-fill"
                :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
              ></div>
            </div>
          </div>
        </div>
      </header>
  

      <main class="main-content">
        <div class="form-container">
          <div v-if="currentStep === 0" class="step-content choice-section">
            <div class="choice-container">
              <h2 class="step-heading">
                <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                QUESTIONNAIRE D’EVALUATION DU SENAFOI YAKRO 2025
              </h2>
              <p class="subtitle">
                Nous vous prions de bien vouloir répondre à ce questionnaire pour nous permettre de mieux organiser votre
                SENAFOI et vous faire vivre une plus grande et plus belle expérience des séminaires.
              </p>
              <div class="choice-buttons">
                <button
                  @click="startQuestionnaire"
                  type="button"
                  class="choice-btn choice-btn-primary"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  <div class="btn-content">
                    <span class="btn-title">Commencer le questionnaire</span>
                    <span class="btn-subtitle">Vos retours sont précieux pour nous</span>
                  </div>
                </button>
              </div>
            </div>
          </div>
  
          <div v-if="currentStep > 0" class="steps-header">
            <div class="steps-nav">
              <div
                v-for="(step, index) in steps"
                :key="index"
                class="step-item"
                :class="{
                  'step-active': index + 1 === currentStep,
                  'step-completed': index + 1 < currentStep,
                  'step-disabled': index + 1 > currentStep
                }"
              >
                <div class="step-number">
                  <span v-if="index + 1 < currentStep">✓</span>
                  <span v-else>{{ index + 1 }}</span>
                </div>
                <span class="step-title">{{ step.title }}</span>
                <div
                  v-if="index < steps.length - 1"
                  class="step-connector"
                  :class="{ 'connector-active': index + 1 < currentStep }"
                ></div>
              </div>
            </div>
          </div>
  
          <form @submit.prevent="handleSubmit" v-if="currentStep > 0" class="form-content">
            <div v-if="currentStep === 1" class="step-section">
              <h2 class="step-heading">
                <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                FORMATION
              </h2>
              <div class="form-group">
                <label class="form-label">Avez-vous trouvé pertinent le thème central ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationPertinentTheme" type="radio" value="OUI" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">OUI</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationPertinentTheme" type="radio" value="NON" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">NON</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group">
                <label class="form-label">Quel est votre ressenti sur la qualité des thématiques ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationQualiteThematiques" type="radio" value="Très satisfait" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Très satisfait</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationQualiteThematiques" type="radio" value="Satisfait" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Satisfait</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationQualiteThematiques" type="radio" value="Moins satisfait" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Moins satisfait</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group">
                <label class="form-label">Quel est votre ressenti sur la qualité des formateurs ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationQualiteFormateurs" type="radio" value="Très satisfait" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Très satisfait</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationQualiteFormateurs" type="radio" value="Satisfait" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Satisfait</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.formationQualiteFormateurs" type="radio" value="Moins satisfait" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Moins satisfait</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group form-group-full">
                <label class="form-label">Suggestion :</label>
                <div class="input-container">
                  <textarea v-model="formData.formationSuggestion" class="form-input" rows="3" placeholder="Vos suggestions pour la formation..."></textarea>
                </div>
              </div>
            </div>
  
            <div v-if="currentStep === 2" class="step-section">
              <h2 class="step-heading">
                <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                RESTAURATION
              </h2>
              <div class="form-group">
                <label class="form-label">Comment avez-vous trouvé les horaires de restauration ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationHoraires" type="radio" value="Très bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Très bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationHoraires" type="radio" value="Bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationHoraires" type="radio" value="Moins bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Moins bonne</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group">
                <label class="form-label">Comment avez-vous trouvé la variété des menus ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationVarieteMenus" type="radio" value="Très bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Très bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationVarieteMenus" type="radio" value="Bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationVarieteMenus" type="radio" value="Moins bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Moins bonne</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group">
                <label class="form-label">Comment jugez-vous la propreté du réfectoire ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationPropreteRefectoire" type="radio" value="Très bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Très bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationPropreteRefectoire" type="radio" value="Bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.restaurationPropreteRefectoire" type="radio" value="Moins bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Moins bonne</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group form-group-full">
                <label class="form-label">Suggestion :</label>
                <div class="input-container">
                  <textarea v-model="formData.restaurationSuggestion" class="form-input" rows="3" placeholder="Vos suggestions pour la restauration..."></textarea>
                </div>
              </div>
            </div>
  
            <div v-if="currentStep === 3" class="step-section">
              <h2 class="step-heading">
                <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.58L19 8l-9 9z"/>
                </svg>
                SECURITE
              </h2>
              <div class="form-group">
                <label class="form-label">Comment avez-vous trouvé la sécurité sur le site ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.securiteQualite" type="radio" value="Très bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Très bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.securiteQualite" type="radio" value="Bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.securiteQualite" type="radio" value="Moins bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Moins bonne</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group form-group-full">
                <label class="form-label">Suggestion :</label>
                <div class="input-container">
                  <textarea v-model="formData.securiteSuggestion" class="form-input" rows="3" placeholder="Vos suggestions pour la sécurité..."></textarea>
                </div>
              </div>
            </div>
  
            <div v-if="currentStep === 4" class="step-section">
              <h2 class="step-heading">
                <svg class="heading-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                INNOVATION DU SEMINAIRE
              </h2>
              <div class="form-group">
                <label class="form-label">Comment jugez-vous les innovations pour ce SENAFOI ? <span class="required">*</span></label>
                <div class="radio-group radio-group-horizontal">
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.innovationQualite" type="radio" value="Très bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Très bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.innovationQualite" type="radio" value="Bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Bonne</span>
                  </label>
                  <label class="radio-item radio-item-compact">
                    <input v-model="formData.innovationQualite" type="radio" value="Moins bonne" class="radio-input" required />
                    <div class="radio-custom"></div>
                    <span class="radio-label">Moins bonne</span>
                  </label>
                </div>
              </div>
  
              <div class="form-group form-group-full">
                <label class="form-label">Suggestion :</label>
                <div class="input-container">
                  <textarea v-model="formData.innovationSuggestion" class="form-input" rows="3" placeholder="Vos suggestions pour les innovations..."></textarea>
                </div>
              </div>
            </div>
  
            <div class="form-navigation">
              <button
                v-if="currentStep > 1"
                @click="previousStep"
                type="button"
                class="btn btn-secondary"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Précédent
              </button>
              <div v-else></div>
  
              <button
                v-if="currentStep < totalSteps"
                @click="nextStep"
                type="button"
                class="btn btn-primary"
                :disabled="!canProceedToNextStep"
              >
                Suivant
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                </svg>
              </button>
  
              <button
                v-if="currentStep === totalSteps"
                type="submit"
                class="btn btn-primary"
                :disabled="isSubmitting || !canProceedToNextStep"
              >
                <div v-if="isSubmitting" class="loading-spinner"></div>
                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                {{ isSubmitting ? 'Envoi en cours...' : 'Soumettre le questionnaire' }}
              </button>
            </div>
          </form>
        </div>
      </main>
  
      <div v-if="showConfirmation" class="modal-overlay">
        <div class="modal-content">
          <div class="modal-header">
            <div class="success-icon">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
            </div>
            <h3>Questionnaire soumis !</h3>
            <p>Merci d'avoir pris le temps de remplir ce questionnaire. Vos retours sont précieux !</p>
          </div>
          <div class="modal-actions">
            <button @click="resetForm" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
              </svg>
              Remplir un nouveau questionnaire
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import axios from 'axios' // Importez axios
  
  // État du formulaire
  const formData = ref({
    formationPertinentTheme: '',
    formationQualiteThematiques: '',
    formationQualiteFormateurs: '',
    formationSuggestion: '',
    restaurationHoraires: '',
    restaurationVarieteMenus: '',
    restaurationPropreteRefectoire: '',
    restaurationSuggestion: '',
    securiteQualite: '',
    securiteSuggestion: '',
    innovationQualite: '',
    innovationSuggestion: '',
  })
  
  const currentStep = ref(0) // 0 for introduction, 1-4 for questionnaire sections
  const isSubmitting = ref(false)
  const showConfirmation = ref(false)
  
  const totalSteps = computed(() => 4) // Total number of questionnaire sections
  
  const steps = computed(() => [
    { title: 'Formation' },
    { title: 'Restauration' },
    { title: 'Sécurité' },
    { title: 'Innovation' },
  ])
  
  // Validation pour passer à l'étape suivante
  const canProceedToNextStep = computed(() => {
    switch (currentStep.value) {
      case 1: // FORMATION
        return formData.value.formationPertinentTheme &&
               formData.value.formationQualiteThematiques &&
               formData.value.formationQualiteFormateurs
      case 2: // RESTAURATION
        return formData.value.restaurationHoraires &&
               formData.value.restaurationVarieteMenus &&
               formData.value.restaurationPropreteRefectoire
      case 3: // SECURITE
        return formData.value.securiteQualite
      case 4: // INNOVATION DU SEMINAIRE (final step)
        return formData.value.innovationQualite
      default:
        return false
    }
  })
  
  // Fonctions de navigation
  const startQuestionnaire = () => {
    currentStep.value = 1
  }
  
  const nextStep = () => {
    if (currentStep.value < totalSteps.value && canProceedToNextStep.value) {
      currentStep.value++
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
  }
  
  const previousStep = () => {
    if (currentStep.value > 1) {
      currentStep.value--
    } else if (currentStep.value === 1) {
      currentStep.value = 0 // Go back to introduction
    }
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
  
  // Fonction de soumission finale
  const handleSubmit = async () => {
    if (!canProceedToNextStep.value) {
      alert("Veuillez répondre à toutes les questions obligatoires de cette section.")
      return
    }
  
    isSubmitting.value = true
    try {
      // REMPLACEZ 'http://localhost/api/submit_questionnaire.php' par l'URL réelle de votre backend PHP
      const response = await axios.post('https://sogetrag.com/api/submit_questionnaire.php', formData.value);
  
      if (response.data.success) {
        console.log('Questionnaire soumis avec succès !', response.data);
        showConfirmation.value = true;
      } else {
        console.error('Erreur lors de la soumission du questionnaire :', response.data.message);
        alert('Une erreur est survenue lors de la soumission : ' + (response.data.message || 'Veuillez réessayer.'));
      }
    } catch (error) {
      console.error('Erreur réseau ou serveur :', error);
      alert('Impossible de se connecter au serveur. Veuillez vérifier votre connexion ou réessayer plus tard.');
    } finally {
      isSubmitting.value = false;
    }
  }
  
  // Fonction pour réinitialiser le formulaire
  const resetForm = () => {
    Object.keys(formData.value).forEach(key => {
      formData.value[key] = ''
    })
    currentStep.value = 0 // Retour à l'étape d'introduction
    showConfirmation.value = false
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
    max-width: 1200px;
    margin: 0 auto;
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
  
  .header-right {
    text-align: right;
  }
  
  .step-info {
    font-size: 0.875rem;
    color: #4b5563;
    margin-bottom: 0.25rem;
  }
  
  .progress-bar {
    width: 8rem;
    height: 0.5rem;
    background: #e5e7eb;
    border-radius: 0.25rem;
    overflow: hidden;
  }
  
  .progress-fill {
    height: 100%;
    background: #16a34a;
    transition: width 0.3s ease;
  }
  
  /* Main Content */
  .main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
  }
  
  .form-container {
    background: #ffffff;
    border-radius: 0.75rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    overflow: hidden;
  }
  
  /* Styles pour les sections de choix (introduction) */
  .choice-section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 500px;
    padding: 2rem;
  }
  
  .choice-container {
    background: #f0fdf4;
    border: 2px solid #16a34a;
    border-radius: 1rem;
    padding: 3rem;
    text-align: center;
    max-width: 800px;
    width: 100%;
  }
  
  .choice-container h2 {
    font-size: 2rem;
    font-weight: bold;
    color: #15803d;
    margin-bottom: 1rem;
  }
  
  .subtitle {
    font-size: 1.125rem;
    color: #4b5563;
    margin-bottom: 3rem;
  }
  
  .choice-buttons {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    max-width: 600px;
    margin: 0 auto;
  }
  
  .choice-btn {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
    border: 2px solid;
    border-radius: 1rem;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
  }
  
  .choice-btn-primary {
    border-color: #16a34a;
    color: #15803d;
  }
  
  .choice-btn-primary:hover {
    background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(22, 163, 74, 0.2);
  }
  
  .choice-btn svg {
    flex-shrink: 0;
    width: 3rem;
    height: 3rem;
  }
  
  .btn-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .btn-title {
    font-size: 1.25rem;
    font-weight: 700;
  }
  
  .btn-subtitle {
    font-size: 0.875rem;
    opacity: 0.8;
    font-weight: 400;
  }
  
  /* Steps Header */
  .steps-header {
    background: #dcfce7;
    padding: 1.5rem 2rem;
  }
  
  .steps-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
  }
  
  .step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
  }
  
  .step-number {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border: 2px solid #d1d5db;
    background: #ffffff;
    color: #4b5563;
  }
  
  .step-item.step-active .step-number {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
  }
  
  .step-item.step-completed .step-number {
    background: #15803d;
    color: #ffffff;
    border-color: #15803d;
  }
  
  .step-title {
    font-size: 0.75rem;
    font-weight: 500;
    color: #374151;
    text-align: center;
  }
  
  .step-connector {
    position: absolute;
    top: 1rem;
    left: 60%;
    right: -40%;
    height: 2px;
    background: #d1d5db;
    z-index: 1;
  }
  
  .step-connector.connector-active {
    background: #16a34a;
  }
  
  /* Form Content */
  .form-content {
    padding: 2rem;
  }
  
  .step-section {
    min-height: 400px; /* Ensure enough space for content */
  }
  
  .step-heading {
    font-size: 1.75rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 2rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
  }
  
  .heading-icon {
    color: #16a34a;
  }
  
  /* Form Elements */
  .form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1.5rem; /* Space between form groups */
  }
  
  .form-group-full {
    width: 100%;
  }
  
  .form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .required {
    color: #dc2626;
  }
  
  .input-container {
    position: relative;
  }
  
  .form-input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: all 0.2s ease;
    background: #ffffff;
  }
  
  .form-input:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
  }
  
  /* Radio Group */
  .radio-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .radio-group-horizontal {
    flex-direction: row;
    flex-wrap: wrap;
    gap: 1.5rem;
  }
  
  .radio-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
  }
  
  .radio-item-compact {
    padding: 0.5rem 1rem;
    flex: none;
    width: auto;
  }
  
  .radio-item:hover {
    background: #f9fafb;
    border-color: #16a34a;
  }
  
  .radio-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
  }
  
  .radio-custom {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    margin-right: 0.75rem;
    position: relative;
    transition: all 0.2s ease;
    flex-shrink: 0;
  }
  
  .radio-input:checked + .radio-custom {
    border-color: #16a34a;
    background: #16a34a;
  }
  
  .radio-input:checked + .radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 0.5rem;
    height: 0.5rem;
    background: #ffffff;
    border-radius: 50%;
  }
  
  .radio-label {
    font-weight: 500;
    color: #374151;
  }
  
  /* Navigation Buttons */
  .form-navigation {
    display: flex;
    justify-content: space-between;
    padding: 1.5rem 2rem;
    border-top: 1px solid #e5e7eb;
    background: #f8fafc;
  }
  
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
  }
  
  .btn-primary {
    background: #16a34a;
    color: #ffffff;
  }
  
  .btn-primary:hover:not(:disabled) {
    background: #15803d;
  }
  
  .btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .btn-secondary {
    background: #f3f4f6;
    color: #374151;
  }
  
  .btn-secondary:hover {
    background: #e5e7eb;
  }
  
  .loading-spinner {
    width: 1rem;
    height: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  /* Modal de confirmation */
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
    padding: 1rem;
  }
  
  .modal-content {
    background: #ffffff;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
  }
  
  .modal-header {
    text-align: center;
    margin-bottom: 2rem;
  }
  
  .success-icon {
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
  
  .modal-header h3 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1rem;
  }
  
  .modal-header p {
    font-size: 1rem;
    color: #4b5563;
    margin-bottom: 0.5rem;
  }
  
  .modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    justify-content: center;
  }
  
  .modal-actions .btn {
    flex: 1;
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .header-content {
      flex-direction: column;
      gap: 1rem;
      text-align: center;
    }
  
    .form-content {
      padding: 1.5rem;
    }
  
    .choice-container {
      padding: 2rem;
    }
  
    .choice-buttons {
      flex-direction: column;
    }
  
    .choice-btn {
      padding: 1.5rem;
    }
  
    .radio-group-horizontal {
      flex-direction: column;
      gap: 0.75rem;
    }
  
    .form-navigation {
      padding: 1rem;
    }
  
    .modal-actions {
      flex-direction: column;
    }
  
    .steps-nav {
      overflow-x: auto;
      padding-bottom: 1rem;
    }
  
    .step-item {
      min-width: 100px;
    }
  
    .step-connector {
      width: 100%;
    }
  }
  
  @media (max-width: 480px) {
    .main-content {
      padding: 1rem;
    }
  
    .form-container {
      border-radius: 0.5rem;
    }
  
    .step-content {
      padding: 1.5rem;
    }
  
    .btn {
      padding: 0.75rem 1rem;
    }
  
    .modal-content {
      padding: 1.5rem;
    }
  }
  </style>
  