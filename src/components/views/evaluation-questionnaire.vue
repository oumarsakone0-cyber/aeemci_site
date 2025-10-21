<template>
  <div class="questionnaire-container" style="padding: 20px; min-height: 100vh; background: #f8f9fa;">
    <!-- QR Scanner Screen -->
    <div v-if="showQRScanner" class="qr-scanner-screen" style="padding: 20px; min-height: 100vh; background: #f8f9fa;">
      <div class="scanner-container" style="padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; text-align: center;">
        <video ref="qrVideo" style="width: 100%; max-width: 400px; border-radius: 8px; background: #333; margin-bottom: 20px;"></video>
        <div class="scanner-controls" style="padding: 20px 0;">
          <button
            v-if="!scanningQR"
            @click="startQRScanner"
            style="padding: 15px 30px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500; margin-right: 10px;"
          >
            📷 Activer la caméra
          </button>
          <button
            v-if="scanningQR"
            @click="stopQRScanner"
            style="padding: 15px 30px; background: #e74c3c; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500; margin-right: 10px;"
          >
            ⏹️ Arrêter le scan
          </button>
          <button
            @click="showManualInput = true"
            style="padding: 15px 30px; background: #3498db; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
          >
            ✏️ Saisir Votre Matricule
          </button>
        </div>
        <div v-if="qrError" class="error-message" style="padding: 15px; background: #fee; border: 1px solid #fcc; border-radius: 4px; color: #c33; margin: 20px 0;">
          {{ qrError }}
        </div>
        <div v-if="scannedMatricule" class="scanned-result" style="padding: 20px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724; margin: 20px 0;">
          <div style="font-weight: bold; margin-bottom: 10px;">✅ Matricule détecté :</div>
          <div style="font-size: 18px; font-family: monospace;">{{ scannedMatricule }}</div>
          <button
            @click="proceedWithMatricule"
            style="padding: 12px 24px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 15px;"
          >
            Continuer avec ce matricule
          </button>
        </div>
      </div>
      <!-- Modal de saisie manuelle -->
      <div v-if="showManualInput" class="modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px;">
        <div class="modal-content" style="background: white; border-radius: 8px; padding: 30px; max-width: 400px; width: 100%;">
          <h3 style="padding: 0 0 15px 0; margin: 0; color: #2c3e50;">Saisie manuelle du matricule</h3>
          <input
            v-model="manualMatricule"
            @input="manualMatricule = manualMatricule.toUpperCase()"
            type="text"
            placeholder="Entrez votre matricule"
            style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px; font-size: 16px; text-transform: uppercase;"
          />
          <div class="modal-actions" style="display: flex; gap: 15px; justify-content: flex-end;">
            <button
              @click="showManualInput = false; manualMatricule = ''"
              style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer;"
            >
              Annuler
            </button>
            <button
              @click="useManualMatricule"
              :disabled="!manualMatricule.trim()"
              style="padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer;"
              :style="!manualMatricule.trim() ? 'opacity: 0.5; cursor: not-allowed;' : ''"
            >
              Valider
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Seminariste Info Screen -->
    <div v-if="showSeminaristInfo" class="seminariste-info-screen" style="padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
      <div class="seminariste-header" style="padding: 0 0 30px 0; text-align: center;">
        <h1 style="padding: 0 0 15px 0; margin: 0; color: #2c3e50; font-size: 24px;">Informations du Séminariste</h1>
        <p style="padding: 0; margin: 0; color: #7f8c8d; line-height: 1.6;">
          Vérifiez vos informations avant de commencer l'évaluation
        </p>
      </div>
      <div class="seminariste-card" style="padding: 30px; background: #f8f9fa; border-radius: 8px; margin-bottom: 30px;">
        <div class="seminariste-content" style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
          <!-- Photo du séminariste -->
          <div class="seminariste-photo" style="flex-shrink: 0;">
            <div class="photo-container" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 4px solid #3498db; background: #ecf0f1; display: flex; align-items: center; justify-content: center;">
              <img
                v-if="seminaristeInfo.photo"
                :src="seminaristeInfo.photo"
                :alt="seminaristeInfo.nom + ' ' + seminaristeInfo.prenom"
                style="width: 100%; height: 100%; object-fit: cover;"
              />
              <div v-else style="font-size: 48px; color: #7f8c8d;">👤</div>
            </div>
          </div>
          <!-- Informations du séminariste -->
          <div class="seminariste-details" style="flex: 1; min-width: 250px;">
            <div class="info-row" style="padding: 8px 0; display: flex; align-items: center; gap: 15px;">
              <div class="info-label" style="font-weight: bold; color: #2c3e50; min-width: 80px;">Matricule:</div>
              <div class="info-value" style="color: #3498db; font-family: monospace; font-size: 16px; font-weight: bold;">{{ seminaristeInfo.matricule_seminaire }}</div>
            </div>
            <div class="info-row" style="padding: 8px 0; display: flex; align-items: center; gap: 15px;">
              <div class="info-label" style="font-weight: bold; color: #2c3e50; min-width: 80px;">Nom:</div>
              <div class="info-value" style="color: #2c3e50; font-size: 16px;">{{ seminaristeInfo.nom }}</div>
            </div>
            <div class="info-row" style="padding: 8px 0; display: flex; align-items: center; gap: 15px;">
              <div class="info-label" style="font-weight: bold; color: #2c3e50; min-width: 80px;">Prénoms:</div>
              <div class="info-value" style="color: #2c3e50; font-size: 16px;">{{ seminaristeInfo.prenom }}</div>
            </div>
            <div v-if="seminaristeInfo.telephone" class="info-row" style="padding: 8px 0; display: flex; align-items: center; gap: 15px;">
              <div class="info-label" style="font-weight: bold; color: #2c3e50; min-width: 80px;">Téléphone:</div>
              <div class="info-value" style="color: #2c3e50; font-size: 16px;">{{ seminaristeInfo.telephone }}</div>
            </div>
            <div v-if="seminaristeInfo.email" class="info-row" style="padding: 8px 0; display: flex; align-items: center; gap: 15px;">
              <div class="info-label" style="font-weight: bold; color: #2c3e50; min-width: 80px;">Email:</div>
              <div class="info-value" style="color: #2c3e50; font-size: 16px;">{{ seminaristeInfo.email }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="confirmation-section" style="padding: 20px 0; text-align: center; border-top: 1px solid #ecf0f1;">
        <p style="padding: 0 0 20px 0; margin: 0; color: #7f8c8d; font-size: 16px;">
          Ces informations sont-elles correctes ?
        </p>
        <div class="action-buttons" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
          <button
            @click="goBackToScanner"
            style="padding: 12px 24px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
          >
            🔄 Scanner à nouveau
          </button>
          <button
            @click="startEvaluationProcess"
            :disabled="loadingSeminariste"
            style="padding: 15px 40px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
            :style="loadingSeminariste ? 'opacity: 0.7; cursor: not-allowed;' : ''"
          >
            {{ loadingSeminariste ? 'Chargement...' : '🚀 Lancer l\'évaluation' }}
          </button>
        </div>
      </div>
    </div>
    <!-- Loading State -->
    <div v-if="loading" class="loading-screen" style="padding: 60px; text-align: center;">
      <div style="padding: 20px; font-size: 18px; color: #7f8c8d;">
        Chargement de l'évaluation...
      </div>
    </div>
    <!-- Access Denied -->
    <div v-else-if="!hasAccess && !showQRScanner && !showSeminaristInfo" class="access-denied" style="padding: 60px; text-align: center; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      <div style="padding: 20px; color: #e74c3c; font-size: 18px; font-weight: 500;">
        ❌ Accès refusé
      </div>
      <div style="padding: 10px; color: #7f8c8d;">
        Connecter votre Badge pour avoir accès.
      </div>
      <button
        @click="goBack"
        style="padding: 12px 24px; margin-top: 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;"
      >
        Retour
      </button>
    </div>
    <!-- Already Completed -->
    <div v-else-if="alreadyCompleted && !showQRScanner && !showSeminaristInfo" class="already-completed" style="padding: 60px; text-align: center; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      <div style="padding: 20px; color: #f39c12; font-size: 18px; font-weight: 500;">
        ⚠️ Évaluation déjà terminée
      </div>
      <div style="padding: 10px; color: #7f8c8d;">
        Vous avez déjà passé cette évaluation.
      </div>
      <button
        @click="goBack"
        style="padding: 12px 24px; margin-top: 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;"
      >
        Retour
      </button>
    </div>
    <!-- Pre-Start Screen -->
    <div v-else-if="!sessionStarted && !loading && !showQRScanner && !showSeminaristInfo" class="pre-start" style="padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
      <div class="evaluation-info" style="padding: 0 0 30px 0; text-align: center;">
        <h1 style="padding: 0 0 15px 0; margin: 0; color: #2c3e50; font-size: 24px;">{{ evaluation.titre }}</h1>
        <p style="padding: 0 0 20px 0; margin: 0; color: #7f8c8d; line-height: 1.6;">{{ evaluation.description }}</p>
        <div class="info-grid" style="padding: 20px 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
          <div class="info-item" style="padding: 15px; background: #f8f9fa; border-radius: 6px;">
            <div style="padding: 5px 0; font-size: 20px; font-weight: bold; color: #3498db;">{{ questions.length }}</div>
            <div style="padding: 2px 0; color: #7f8c8d; font-size: 14px;">Questions</div>
          </div>
          <div class="info-item" style="padding: 15px; background: #f8f9fa; border-radius: 6px;">
            <div style="padding: 5px 0; font-size: 20px; font-weight: bold; color: #e67e22;">{{ evaluation.duree }}</div>
            <div style="padding: 2px 0; color: #7f8c8d; font-size: 14px;">Minutes</div>
          </div>
        </div>
      </div>
      <div class="instructions" style="padding: 20px 0; border-top: 1px solid #ecf0f1; border-bottom: 1px solid #ecf0f1;">
        <h3 style="padding: 0 0 15px 0; margin: 0; color: #2c3e50; font-size: 16px;">Instructions :</h3>
        <ul style="padding: 0 0 0 20px; margin: 0; color: #7f8c8d; line-height: 1.6;">
          <li style="padding: 5px 0;">Lisez attentivement chaque question</li>
          <li style="padding: 5px 0;">Sélectionnez une seule réponse par question</li>
          <li style="padding: 5px 0;">Vous pouvez naviguer entre les questions</li>
          <li style="padding: 5px 0;">Le temps est limité à {{ evaluation.duree }} minutes</li>
          <li style="padding: 5px 0;">L'évaluation sera soumise automatiquement à la fin du temps</li>
        </ul>
      </div>
      <div class="start-section" style="padding: 30px 0 0 0; text-align: center;">
        <button
          @click="startSession"
          :disabled="starting"
          class="start-btn"
          style="padding: 15px 40px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
          :style="starting ? 'opacity: 0.7; cursor: not-allowed;' : ''"
        >
          {{ starting ? 'Démarrage...' : 'Commencer l\'évaluation' }}
        </button>
      </div>
    </div>
    <!-- Questionnaire Interface -->
    <div v-else-if="!showResults && sessionStarted" class="questionnaire-interface" style="padding: 0;">
      <!-- Header with Timer -->
      <div class="questionnaire-header" style="padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; position: sticky; top: 20px; z-index: 100;">
        <div class="header-content" style="padding: 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
          <div class="evaluation-title" style="padding: 0;">
            <h2 style="padding: 0; margin: 0; color: #2c3e50; font-size: 18px;">{{ evaluation.titre }}</h2>
          </div>
          <div class="progress-info" style="padding: 0; display: flex; align-items: center; gap: 20px;">
            <div class="question-progress" style="padding: 8px 16px; background: #f8f9fa; border-radius: 20px; font-size: 14px; color: #7f8c8d;">
              Question {{ currentQuestionIndex + 1 }} / {{ questions.length }}
            </div>
            <div class="timer" style="padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 16px;"
              :style="timeRemaining < 300 ? 'background: #e74c3c; color: white;' : 'background: #27ae60; color: white;'">
              ⏱️ {{ formatTimeRemaining(timeRemaining) }}
            </div>
          </div>
        </div>
        <!-- Progress Bar -->
        <div class="progress-bar-container" style="padding: 15px 0 0 0;">
          <div class="progress-bar" style="width: 100%; height: 6px; background: #ecf0f1; border-radius: 3px; overflow: hidden;">
            <div
              class="progress-fill"
              style="height: 100%; background: #3498db; border-radius: 3px; transition: width 0.3s ease;"
              :style="`width: ${((currentQuestionIndex + 1) / questions.length) * 100}%`"
            ></div>
          </div>
        </div>
      </div>
      <!-- Question Content -->
      <div class="question-content" style="padding: 0;">
        <div class="question-card" style="padding: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
          <div class="question-text" style="padding: 0 0 30px 0;">
            <h3 style="padding: 0; margin: 0; color: #2c3e50; font-size: 20px; line-height: 1.4;">
              {{ questions[currentQuestionIndex]?.question }}
            </h3>
          </div>
          <div class="answers-list" style="padding: 0;">
            <div
              v-for="option in ['A', 'B', 'C', 'D']"
              :key="option"
              class="answer-option"
              style="padding: 15px 0;"
            >
              <label
                class="answer-label"
                style="padding: 15px 20px; display: flex; align-items: flex-start; gap: 15px; border: 2px solid #ecf0f1; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;"
                :style="answers[currentQuestionIndex] === option ? 'border-color: #3498db; background: #f8f9fa;' : ''"
                @mouseover="$event.target.style.borderColor = '#3498db'"
                @mouseleave="$event.target.style.borderColor = answers[currentQuestionIndex] === option ? '#3498db' : '#ecf0f1'"
              >
                <input
                  type="radio"
                  :name="`question_${currentQuestionIndex}`"
                  :value="option"
                  v-model="answers[currentQuestionIndex]"
                  style="margin: 0; transform: scale(1.2);"
                />
                <div class="option-content" style="padding: 0; flex: 1;">
                  <div class="option-letter" style="padding: 0 0 5px 0; font-weight: bold; color: #3498db; font-size: 16px;">
                    {{ option }}.
                  </div>
                  <div class="option-text" style="padding: 0; color: #2c3e50; line-height: 1.4;">
                    {{ questions[currentQuestionIndex]?.[`reponse_${option.toLowerCase()}`] }}
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>
        <!-- Navigation -->
        <div class="navigation-section" style="padding: 0;">
          <div class="navigation-controls" style="padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <button
              @click="previousQuestion"
              :disabled="currentQuestionIndex === 0"
              style="padding: 12px 24px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;"
              :style="currentQuestionIndex === 0 ? 'opacity: 0.5; cursor: not-allowed;' : ''"
            >
              ← Précédent
            </button>
            <!-- Quick Navigation -->
            <div class="quick-nav" style="padding: 0; display: flex; gap: 8px; flex-wrap: wrap;">
              <button
                v-for="(question, index) in questions"
                :key="index"
                @click="goToQuestion(index)"
                class="quick-nav-btn"
                style="padding: 8px 12px; border: 2px solid #ecf0f1; background: white; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 500; min-width: 40px;"
                :style="getQuickNavStyle(index)"
              >
                {{ index + 1 }}
              </button>
            </div>
            <div class="right-controls" style="padding: 0; display: flex; gap: 10px;">
              <button
                v-if="currentQuestionIndex < questions.length - 1"
                @click="nextQuestion"
                style="padding: 12px 24px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;"
              >
                Suivant →
              </button>
              <button
                v-if="currentQuestionIndex === questions.length - 1"
                @click="confirmSubmit"
                style="padding: 12px 24px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;"
              >
                Terminer l'évaluation
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Results Screen -->
    <div v-else-if="showResults" class="results-screen" style="padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; text-align: center;">
      <div class="results-header" style="padding: 0 0 30px 0;">
        <div style="padding: 0 0 20px 0; font-size: 48px;">🎉</div>
        <h2 style="padding: 0 0 10px 0; margin: 0; color: #2c3e50; font-size: 24px;">Évaluation terminée !</h2>
        <p style="padding: 0; margin: 0; color: #7f8c8d;">Félicitations, vous avez terminé l'évaluation.</p>
      </div>
      <div class="results-summary" style="padding: 30px 0; border-top: 1px solid #ecf0f1; border-bottom: 1px solid #ecf0f1;">
        <div class="results-grid" style="padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 20px;">
          <div class="result-item" style="padding: 20px; background: #f8f9fa; border-radius: 8px; width: 210%;">
            <div style="padding: 5px 0; font-size: 30px; font-weight: bold; color: #1f2937; text-align: left">Merci et à bientôt</div>
          </div>
        </div>
      </div>
      <div class="results-actions" style="padding: 30px 0 0 0; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <button
          @click="goBack"
          style="padding: 15px 30px; background: #3498db; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;"
        >
          Retour à l'accueil
        </button>
      </div>
    </div>
    <!-- Confirmation Modal -->
    <div v-if="showConfirmModal" class="modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px;">
      <div class="modal-content" style="background: white; border-radius: 8px; padding: 30px; max-width: 400px; width: 100%; text-align: center;">
        <h3 style="padding: 0 0 15px 0; margin: 0; color: #2c3e50;">Confirmer la soumission</h3>
        <p style="padding: 0 0 25px 0; margin: 0; color: #7f8c8d; line-height: 1.4;">
          Êtes-vous sûr de vouloir terminer l'évaluation ? Cette action est irréversible.
        </p>
        <div class="modal-actions" style="padding: 0; display: flex; gap: 15px; justify-content: center;">
          <button
            @click="showConfirmModal = false"
            style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer;"
          >
            Annuler
          </button>
          <button
            @click="submitAnswers"
            :disabled="submitting"
            style="padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer;"
            :style="submitting ? 'opacity: 0.7; cursor: not-allowed;' : ''"
          >
            {{ submitting ? 'Soumission...' : 'Confirmer' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EvaluationQuestionnaire',
  props: {
    matricule: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      // ID d'évaluation récupéré depuis l'URL
      evaluationId: null,

      // Nouveaux états pour le scan QR
      showQRScanner: true,
      scanningQR: false,
      qrError: '',
      scannedMatricule: '',
      stream: null,

      showManualInput: false,
      manualMatricule: '',
      localMatricule: this.matricule,

      // Nouveau : informations du séminariste
      showSeminaristInfo: false,
      seminaristeInfo: null,
      loadingSeminariste: false,
      // États existants
      loading: false,
      starting: false,
      submitting: false,
      hasAccess: false,
      alreadyCompleted: false,
      sessionStarted: false,
      showResults: false,
      showConfirmModal: false,
      evaluation: {},
      questions: [],
      answers: {},
      currentQuestionIndex: 0,
      timeRemaining: 0,
      timer: null,
      results: {},
      sessionId: null,
      qrDetectionInterval: null,
    }
  },
  mounted() {
    this.getEvaluationIdFromUrl();
    window.addEventListener('beforeunload', this.handlePageUnload);
  },
  beforeUnmount() {
    // Nettoyer les timers et listeners
    if (this.timer) clearInterval(this.timer);
    if (this.qrDetectionInterval) clearInterval(this.qrDetectionInterval);
    this.stopQRScanner();
    // Très important : retirer l'écouteur d'événement
    window.removeEventListener('beforeunload', this.handlePageUnload);
  },
  methods: {
    handlePageUnload(event) {
      // Si la session est en cours et les résultats non affichés
      if (this.sessionStarted && !this.showResults) {
        console.log('Soumission automatique avant de quitter...');
        this.submitAnswers(true); // true = isAutomatic
      }
      // Pour certains navigateurs, il faut définir returnValue pour que l'événement se déclenche
      event.preventDefault();
      event.returnValue = '';
    },
    getEvaluationIdFromUrl() {
      // Méthode 1: Essayer de récupérer depuis les paramètres de route Vue.js
      if (this.$route && this.$route.params && this.$route.params.id) {
        this.evaluationId = this.$route.params.id;
        console.log('🔍 ID récupéré depuis $route.params:', this.evaluationId);
        return;
      }

      // Méthode 2: Essayer de récupérer depuis les query params
      if (this.$route && this.$route.query && this.$route.query.id) {
        this.evaluationId = this.$route.query.id;
        console.log('🔍 ID récupéré depuis $route.query:', this.evaluationId);
        return;
      }

      // Méthode 3: Parser manuellement l'URL
      const path = window.location.pathname;
      console.log('🔍 Chemin actuel:', path);

      // Pour une URL comme /evaluation_question/10
      const pathParts = path.split('/');
      console.log('🔍 Parties du chemin:', pathParts);

      if (pathParts.length >= 3 && pathParts[1] === 'evaluation_question') {
        this.evaluationId = pathParts[2];
        console.log('🔍 ID récupéré depuis le chemin:', this.evaluationId);
        return;
      }

      // Méthode 4: Essayer les query parameters classiques
      const params = new URLSearchParams(window.location.search);
      this.evaluationId = params.get('id');

      console.log('🔍 URL complète:', window.location.href);
      console.log('🔍 Paramètres URL:', window.location.search);
      console.log('🔍 ID d\'évaluation final:', this.evaluationId);

      if (!this.evaluationId) {
        console.error('❌ Aucun ID d\'évaluation trouvé dans l\'URL');
        this.showError('Aucun ID d\'évaluation trouvé dans l\'URL');
      }
    },

    async checkAccess() {
      console.log('🔍 Début checkAccess avec matricule:', this.localMatricule);
      console.log('🔍 ID d\'évaluation utilisé:', this.evaluationId);

      this.loading = true;
      try {
        const requestData = {
          action: 'check_access',
          evaluation_id: this.evaluationId,
          matricule: this.localMatricule
        };

        console.log('📤 Données envoyées:', requestData);

        const response = await fetch('https://sogetrag.com/api/evaluations-api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestData)
        });
        const data = await response.json();
        console.log('📋 Check access response:', data);

        if (data.success) {
          this.hasAccess = data.has_access;
          this.alreadyCompleted = data.already_completed;
          this.evaluation = data.evaluation;

          console.log('✅ Accès:', this.hasAccess, 'Déjà terminé:', this.alreadyCompleted);

          if (this.hasAccess && !this.alreadyCompleted) {
            await this.loadQuestions();
          }
        } else {
          console.error('❌ Erreur checkAccess:', data.error);
          this.showError('Erreur lors de la vérification d\'accès: ' + data.error);
        }
      } catch (error) {
        console.error('💥 Exception checkAccess:', error);
        this.showError('Erreur de connexion: ' + error.message);
      } finally {
        this.loading = false;
        console.log('🏁 Fin checkAccess, loading:', this.loading);
      }
    },

    async loadQuestions() {
      console.log('📚 Début loadQuestions');
      try {
        const response = await fetch('https://sogetrag.com/api/evaluations-api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'questions',
            evaluation_id: this.evaluationId,
          })
        });
        const data = await response.json();
        console.log('📝 Load questions response:', data);

        if (data.success) {
          this.questions = data.data;
          this.initializeAnswers();
          console.log('✅ Questions chargées:', this.questions.length);
        } else {
          console.error('❌ Erreur loadQuestions:', data.error);
          this.showError('Erreur lors du chargement des questions: ' + data.error);
        }
      } catch (error) {
        console.error('💥 Exception loadQuestions:', error);
        this.showError('Erreur de connexion: ' + error.message);
      }
    },

    initializeAnswers() {
      const answers = {};
      for (let i = 0; i < this.questions.length; i++) {
        answers[i] = null;
      }
      this.answers = answers;
    },

    async startSession() {
      this.starting = true;
      try {
        const response = await fetch('https://sogetrag.com/api/evaluation-session.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'start_session',
            evaluation_id: this.evaluationId,
            matricule: this.localMatricule
          })
        });
        const data = await response.json();
        console.log('🚀 Start session response:', data);

        if (data.success) {
          this.sessionId = data.session_id;
          this.sessionStarted = true;
          this.startTimer();
        } else {
          this.showError('Erreur lors du démarrage: ' + data.error);
        }
      } catch (error) {
        console.error('💥 Start session error:', error);
        this.showError('Erreur de connexion: ' + error.message);
      } finally {
        this.starting = false;
      }
    },

    startTimer() {
      this.timeRemaining = this.evaluation.duree * 60;
      this.timer = setInterval(() => {
        this.timeRemaining--;
        if (this.timeRemaining <= 0) {
          this.timeUp();
        }
      }, 1000);
    },

    timeUp() {
      clearInterval(this.timer);
      this.showError('Temps écoulé ! L\'évaluation va être soumise automatiquement.');
      setTimeout(() => {
        this.submitAnswers();
      }, 2000);
    },

    scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    },

    nextQuestion() {
      if (this.currentQuestionIndex < this.questions.length - 1) {
        this.currentQuestionIndex++;
        this.scrollToTop();
      }
    },

    previousQuestion() {
      if (this.currentQuestionIndex > 0) {
        this.currentQuestionIndex--;
        this.scrollToTop();
      }
    },

    goToQuestion(index) {
      this.currentQuestionIndex = index;
      this.scrollToTop();
    },

    confirmSubmit() {
      this.showConfirmModal = true;
    },

    async submitAnswers() {
      this.showConfirmModal = false;
      this.submitting = true;

      if (this.timer) {
        clearInterval(this.timer);
      }
      const tempsEcoule = (this.evaluation.duree * 60) - this.timeRemaining;
      try {
        const response = await fetch('https://sogetrag.com/api/evaluation-session.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'submit_answers',
            evaluation_id: this.evaluationId,
            matricule: this.localMatricule,
            reponses: Object.values(this.answers),
            temps_ecoule: tempsEcoule
          })
        });
        const data = await response.json();
        if (data.success) {
          this.results = data.resultats;
          this.showResults = true;
        } else {
          this.showError('Erreur lors de la soumission: ' + data.error);
        }
      } catch (error) {
        this.showError('Erreur de connexion: ' + error.message);
      } finally {
        this.submitting = false;
      }
    },

    getQuickNavStyle(index) {
      if (index === this.currentQuestionIndex) {
        return 'border-color: #3498db; background: #3498db; color: white;';
      } else if (this.answers[index] !== null) {
        return 'border-color: #27ae60; background: #27ae60; color: white;';
      } else {
        return 'border-color: #ecf0f1; background: white; color: #7f8c8d;';
      }
    },

    formatTimeRemaining(seconds) {
      const minutes = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    },

    formatTime(seconds) {
      const minutes = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${minutes}m ${secs}s`;
    },

    getScoreColor(percentage) {
      if (percentage >= 80) return 'color: #27ae60;';
      if (percentage >= 60) return 'color: #f39c12;';
      return 'color: #e74c3c;';
    },

    goBack() {
      window.history.back();
    },

    showError(message) {
      alert('❌ ' + message);
    },

    async startQRScanner() {
      try {
        this.qrError = '';
        this.scanningQR = true;

        const constraints = {
          video: {
            facingMode: 'environment',
            width: { ideal: 640 },
            height: { ideal: 480 }
          }
        };
        this.stream = await navigator.mediaDevices.getUserMedia(constraints);
        this.$refs.qrVideo.srcObject = this.stream;

        this.startQRDetection();
      } catch (error) {
        this.qrError = 'Impossible d\'accéder à la caméra: ' + error.message;
        this.scanningQR = false;
      }
    },

    stopQRScanner() {
      if (this.stream) {
        this.stream.getTracks().forEach(track => track.stop());
        this.stream = null;
      }
      this.scanningQR = false;
      if (this.qrDetectionInterval) {
        clearInterval(this.qrDetectionInterval);
      }
    },

    startQRDetection() {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');

      this.qrDetectionInterval = setInterval(() => {
        if (this.$refs.qrVideo && this.$refs.qrVideo.videoWidth > 0) {
          canvas.width = this.$refs.qrVideo.videoWidth;
          canvas.height = this.$refs.qrVideo.videoHeight;
          ctx.drawImage(this.$refs.qrVideo, 0, 0);

          try {
            // IMPORTANT: For a real project, you would use a library like jsQR here.
            // This is a placeholder for QR code detection logic.
            // Example with jsQR:
            // const code = jsQR(imageData.data, imageData.width, imageData.height, {
            //   inversionAttempts: "dontInvert",
            // });
            // if (code) {
            //   this.scannedMatricule = code.data;
            //   this.stopQRScanner();
            // }

            // Placeholder for demonstration:
            // Simulate a successful scan after a few seconds
            if (!this.scannedMatricule && Math.random() > 0.95) { // Simulate a 5% chance of scanning
                this.scannedMatricule = 'SIMULATED_MATRICULE_123';
                this.stopQRScanner();
            }

          } catch (error) {
            // Ignorer les erreurs de détection
          }
        }
      }, 500);
    },

    detectQRCode(imageData) {
      // This function is a placeholder. In a real application, you would integrate a QR code scanning library here.
      // For example, using jsQR:
      // import jsQR from 'jsqr';
      // const code = jsQR(imageData.data, imageData.width, imageData.height, {
      //   inversionAttempts: "dontInvert",
      // });
      // if (code) {
      //   return code.data;
      // }
      return null;
    },

    async proceedWithMatricule() {
      if (this.scannedMatricule) {
        this.loadingSeminariste = true;
        this.qrError = '';

        try {
          const response = await fetch('https://sogetrag.com/api/evaluations-api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              action: 'get_seminariste_info',
              matricule: this.scannedMatricule
            })
          });
          const data = await response.json();
          console.log('👤 Seminariste info response:', data);

          if (data.success && data.seminariste) {
            this.seminaristeInfo = data.seminariste;
            this.localMatricule = this.scannedMatricule;
            this.showQRScanner = false;
            this.showSeminaristInfo = true;
          } else {
            this.qrError = 'Matricule non trouvé dans la base de données. Veuillez réessayer.';
            this.scannedMatricule = '';
          }
        } catch (error) {
          console.error('💥 Seminariste info error:', error);
          this.qrError = 'Erreur lors de la recherche: ' + error.message;
          this.scannedMatricule = '';
        } finally {
          this.loadingSeminariste = false;
        }
      }
    },

    useManualMatricule() {
      if (this.manualMatricule.trim()) {
        this.scannedMatricule = this.manualMatricule.trim();
        this.showManualInput = false;
        this.manualMatricule = '';
        this.proceedWithMatricule();
      }
    },

    goBackToScanner() {
      this.showSeminaristInfo = false;
      this.showQRScanner = true;
      this.seminaristeInfo = null;
      this.scannedMatricule = '';
      this.qrError = '';
    },

    async startEvaluationProcess() {
      console.log('🚀 Début du processus d\'évaluation');
      this.loadingSeminariste = true;
      this.showSeminaristInfo = false;

      try {
        await this.checkAccess();
        console.log('✅ checkAccess terminé');
      } catch (error) {
        console.error('❌ Erreur dans checkAccess:', error);
        this.showError('Erreur lors du chargement: ' + error.message);
      } finally {
        this.loadingSeminariste = false;
      }
    },

    viewCorrection() {
      // Navigation vers la page de correction Vue.js
      this.$router.push({
        name: 'correction',
        query: {
          evaluation_id: this.evaluationId,
          matricule: this.localMatricule,
          session_id: this.sessionId
        }
      });
    }
  }
}
</script>

<style scoped>
/* Add your component-specific styles here */
</style>
