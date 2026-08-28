<template>
  <div class="eq-root">

    <!-- ══════════════ QR SCANNER ══════════════ -->
    <div v-if="showQRScanner" class="eq-screen eq-screen--scanner">
      <div class="eq-scanner-wrap">
        <div class="eq-brand">
          <span class="eq-brand__icon">◈</span>
          <span class="eq-brand__label">SÉNAFOI</span>
        </div>
        <h1 class="eq-scanner__title">Identification</h1>
        <p class="eq-scanner__sub">Scannez votre badge ou saisissez votre matricule</p>

        <div class="eq-video-frame">
          <video ref="qrVideo" class="eq-video" playsinline autoplay></video>
          <div class="eq-scan-overlay">
            <div class="eq-scan-corner tl"></div>
            <div class="eq-scan-corner tr"></div>
            <div class="eq-scan-corner bl"></div>
            <div class="eq-scan-corner br"></div>
            <div class="eq-scan-line" :class="{ active: scanningQR }"></div>
          </div>
        </div>

        <div v-if="qrError" class="eq-alert eq-alert--danger">
          <i class="eq-alert__icon">⚠</i> {{ qrError }}
        </div>
        <div v-if="scannedMatricule" class="eq-alert eq-alert--success">
          <i class="eq-alert__icon">✓</i>
          Matricule détecté : <strong>{{ scannedMatricule }}</strong>
          <button class="eq-btn eq-btn--sm eq-btn--ghost" @click="proceedWithMatricule">Continuer →</button>
        </div>

        <div class="eq-scanner-actions">
          <button v-if="!scanningQR" class="eq-btn eq-btn--primary" @click="startQRScanner">
            <span class="eq-btn__icon">📷</span> Activer la caméra
          </button>
          <button v-if="scanningQR" class="eq-btn eq-btn--danger" @click="stopQRScanner">
            <span class="eq-btn__icon">⏹</span> Arrêter
          </button>
          <button class="eq-btn eq-btn--outline" @click="showManualInput = true">
            <span class="eq-btn__icon">✏</span> Saisir Matricule
          </button>
        </div>
      </div>

      <!-- Modal saisie manuelle -->
      <div v-if="showManualInput" class="eq-modal-backdrop" @click.self="showManualInput = false">
        <div class="eq-modal">
          <div class="eq-modal__header">
            <h3>Saisie manuelle</h3>
            <button class="eq-modal__close" @click="showManualInput = false">✕</button>
          </div>
          <input
            v-model="manualMatricule"
            @input="manualMatricule = manualMatricule.toUpperCase()"
            type="text"
            placeholder="VOTRE MATRICULE"
            class="eq-input"
            autofocus
          />
          <div class="eq-modal__actions">
            <button class="eq-btn eq-btn--ghost" @click="showManualInput = false; manualMatricule = ''">Annuler</button>
            <button class="eq-btn eq-btn--primary" :disabled="!manualMatricule.trim()" @click="useManualMatricule">Valider</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════ SÉMINARISTE INFO ══════════════ -->
    <div v-if="showSeminaristInfo" class="eq-screen eq-screen--info">
      <div class="eq-info-wrap">
        <div class="eq-brand">
          <span class="eq-brand__icon">◈</span>
          <span class="eq-brand__label">SÉNAFOI</span>
        </div>
        <h2 class="eq-info__title">Bienvenue</h2>

        <div class="eq-profile-card">
          <div class="eq-avatar">
            <img v-if="seminaristeInfo.photo" :src="seminaristeInfo.photo" alt="Photo" class="eq-avatar__img" />
            <span v-else class="eq-avatar__initials">{{ getInitials() }}</span>
          </div>
          <div class="eq-profile-name">{{ seminaristeInfo.prenom }} {{ seminaristeInfo.nom }}</div>
          <div class="eq-profile-matricule">{{ seminaristeInfo.matricule_seminaire }}</div>

          <div class="eq-profile-details">
            <div v-if="seminaristeInfo.telephone" class="eq-detail-row">
              <span class="eq-detail-label">Tél.</span>
              <span class="eq-detail-value">{{ seminaristeInfo.telephone }}</span>
            </div>
            <div v-if="seminaristeInfo.email" class="eq-detail-row">
              <span class="eq-detail-label">Email</span>
              <span class="eq-detail-value">{{ seminaristeInfo.email }}</span>
            </div>
          </div>
        </div>

        <p class="eq-info__confirm">Ces informations sont-elles correctes ?</p>
        <div class="eq-info-actions">
          <button class="eq-btn eq-btn--ghost" @click="goBackToScanner">← Rescanner</button>
          <button
            class="eq-btn eq-btn--primary"
            :disabled="loadingSeminariste"
            @click="startEvaluationProcess"
          >
            {{ loadingSeminariste ? 'Chargement…' : 'Lancer l\'évaluation →' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ══════════════ LOADING ══════════════ -->
    <div v-if="loading" class="eq-screen eq-screen--center">
      <div class="eq-spinner"></div>
      <p class="eq-loading-text">Chargement de l'évaluation…</p>
    </div>

    <!-- ══════════════ ACCÈS REFUSÉ ══════════════ -->
    <div v-else-if="!hasAccess && !showQRScanner && !showSeminaristInfo && !loading" class="eq-screen eq-screen--center">
      <div class="eq-status-icon eq-status-icon--danger">✕</div>
      <h2 class="eq-status-title">Accès refusé</h2>
      <p class="eq-status-sub">Connectez votre badge pour accéder à l'évaluation.</p>
      <button class="eq-btn eq-btn--outline" @click="goBack">← Retour</button>
    </div>

    <!-- ══════════════ DÉJÀ COMPLÉTÉ ══════════════ -->
    <div v-else-if="alreadyCompleted && !showQRScanner && !showSeminaristInfo" class="eq-screen eq-screen--center">
      <div class="eq-status-icon eq-status-icon--warn">!</div>
      <h2 class="eq-status-title">Déjà terminée</h2>
      <p class="eq-status-sub">Vous avez déjà passé cette évaluation.</p>
      <button class="eq-btn eq-btn--outline" @click="goBack">← Retour</button>
    </div>

    <!-- ══════════════ PRÉ-DÉMARRAGE ══════════════ -->
    <div v-else-if="!sessionStarted && !loading && !showQRScanner && !showSeminaristInfo" class="eq-screen eq-screen--prestart">
      <div class="eq-prestart-wrap">
        <div class="eq-brand">
          <span class="eq-brand__icon">◈</span>
          <span class="eq-brand__label">SÉNAFOI</span>
        </div>
        <div class="eq-prestart-badge">ÉVALUATION</div>
        <h1 class="eq-prestart__title">{{ evaluation.titre }}</h1>
        <p class="eq-prestart__desc">{{ evaluation.description }}</p>

        <div class="eq-stats-row">
          <div class="eq-stat-pill">
            <span class="eq-stat-pill__value">{{ questions.length }}</span>
            <span class="eq-stat-pill__label">Questions</span>
          </div>
          <div class="eq-stat-pill">
            <span class="eq-stat-pill__value">{{ evaluation.duree }}</span>
            <span class="eq-stat-pill__label">Minutes</span>
          </div>
        </div>

        <div class="eq-rules">
          <div class="eq-rules__title">Instructions</div>
          <ul class="eq-rules__list">
            <li>Lisez attentivement chaque question</li>
            <li>Une seule réponse par question</li>
            <li>Navigation libre entre les questions</li>
            <li>Soumission automatique en fin de temps</li>
          </ul>
        </div>

        <button
          class="eq-btn eq-btn--primary eq-btn--lg eq-btn--full"
          :disabled="starting"
          @click="startSession"
        >
          {{ starting ? 'Démarrage…' : 'Commencer l\'évaluation' }}
        </button>
      </div>
    </div>

    <!-- ══════════════ QUESTIONNAIRE ══════════════ -->
    <div v-else-if="!showResults && sessionStarted" class="eq-quiz">

      <!-- Header sticky -->
      <header class="eq-quiz__header">
        <div class="eq-quiz__meta">
          <span class="eq-quiz__eval-name">{{ evaluation.titre }}</span>
          <div
            class="eq-timer"
            :class="{ 'eq-timer--urgent': timeRemaining < 300 }"
          >
            <span class="eq-timer__icon">⏱</span>
            {{ formatTimeRemaining(timeRemaining) }}
          </div>
        </div>
        <div class="eq-progress-wrap">
          <div class="eq-progress-track">
            <div
              class="eq-progress-fill"
              :style="`width: ${((currentQuestionIndex + 1) / questions.length) * 100}%`"
            ></div>
          </div>
          <span class="eq-progress-label">{{ currentQuestionIndex + 1 }} / {{ questions.length }}</span>
        </div>
      </header>

      <!-- Bandeau de reprise de session -->
      <div v-if="resumed" class="eq-resume-banner">
        <span class="eq-resume-banner__icon">↻</span>
        <div>
          <strong>Session reprise</strong>
          <p>Vos réponses et le temps restant ont été restaurés.</p>
        </div>
        <button class="eq-resume-banner__close" @click="resumed = false">✕</button>
      </div>

      <!-- Contenu de la question -->
      <main class="eq-quiz__body">
        <div class="eq-question-num">Question {{ currentQuestionIndex + 1 }}</div>
        <div class="eq-question-text">{{ questions[currentQuestionIndex]?.question }}</div>

        <div class="eq-options">
          <label
            v-for="option in ['A', 'B', 'C', 'D']"
            :key="option"
            class="eq-option"
            :class="{
              'eq-option--selected': answers[currentQuestionIndex] === option,
              'eq-option--filled': answers[currentQuestionIndex] !== null && answers[currentQuestionIndex] !== option
            }"
          >
            <input
              type="radio"
              :name="`q_${currentQuestionIndex}`"
              :value="option"
              v-model="answers[currentQuestionIndex]"
              class="eq-option__radio"
            />
            <span class="eq-option__letter">{{ option }}</span>
            <span class="eq-option__text">{{ questions[currentQuestionIndex]?.[`reponse_${option.toLowerCase()}`] }}</span>
            <span class="eq-option__check">✓</span>
          </label>
        </div>

        <!-- Pastilles de navigation rapide -->
        <div class="eq-qnav">
          <div class="eq-qnav__label">Navigation rapide</div>
          <div class="eq-qnav__grid">
            <button
              v-for="(_, idx) in questions"
              :key="idx"
              class="eq-qnav__dot"
              :class="{
                'eq-qnav__dot--current': idx === currentQuestionIndex,
                'eq-qnav__dot--answered': answers[idx] !== null && idx !== currentQuestionIndex
              }"
              @click="goToQuestion(idx)"
            >{{ idx + 1 }}</button>
          </div>
        </div>
      </main>

      <!-- Footer navigation FIXÉ -->
      <nav class="eq-quiz__footer">
        <button
          class="eq-nav-btn eq-nav-btn--prev"
          :disabled="currentQuestionIndex === 0"
          @click="previousQuestion"
        >
          <span>←</span> Précédent
        </button>

        <button
          v-if="currentQuestionIndex < questions.length - 1"
          class="eq-nav-btn eq-nav-btn--next"
          @click="nextQuestion"
        >
          Suivant <span>→</span>
        </button>
        <button
          v-else
          class="eq-nav-btn eq-nav-btn--finish"
          @click="confirmSubmit"
        >
          <span>✓</span> Terminer
        </button>
      </nav>
    </div>

    <!-- ══════════════ RÉSULTATS ══════════════ -->
    <div v-else-if="showResults" class="eq-screen eq-screen--results">
      <div class="eq-results-wrap">

        <div class="eq-brand">
          <span class="eq-brand__icon">◈</span>
          <span class="eq-brand__label">SÉNAFOI</span>
        </div>

        <!-- Score circulaire -->
        <div class="eq-score-ring-wrap">
          <svg class="eq-score-ring" viewBox="0 0 120 120">
            <circle class="eq-score-ring__bg" cx="60" cy="60" r="50" />
            <circle
              class="eq-score-ring__fill"
              cx="60" cy="60" r="50"
              :stroke-dasharray="`${scoreCircle} 314`"
              :class="getScoreRingClass(results.score_pourcentage)"
            />
          </svg>
          <div class="eq-score-center">
            <div class="eq-score-pct">{{ results.score_pourcentage }}<span class="eq-score-pct__sym">%</span></div>
            <div class="eq-score-label">Score</div>
          </div>
        </div>

        <!-- Niveau affecté - hero -->
        <div class="eq-niveau-badge" :class="getScoreClass(results.score_pourcentage)">
          <div class="eq-niveau-badge__label">Niveau affecté</div>
          <div class="eq-niveau-badge__value">{{ results.niveau_affecte }}</div>
          <div v-if="results.niveau_applique" class="eq-niveau-badge__note">Niveau enregistré dans votre dossier</div>
          <div v-else-if="results.niveau_message" class="eq-niveau-badge__note eq-niveau-badge__note--warn">{{ results.niveau_message }}</div>
        </div>

        <!-- Détails chiffrés -->
        <div class="eq-result-cards">
          <div class="eq-result-card">
            <div class="eq-result-card__value">{{ results.score_obtenu }}</div>
            <div class="eq-result-card__label">Score obtenu</div>
          </div>
          <div class="eq-result-card">
            <div class="eq-result-card__value">{{ results.score_total }}</div>
            <div class="eq-result-card__label">Total</div>
          </div>
          <div class="eq-result-card">
            <div class="eq-result-card__value">{{ formatTime(results.temps_ecoule) }}</div>
            <div class="eq-result-card__label">Temps</div>
          </div>
        </div>

        <!-- Message contextuel -->
        <div class="eq-result-msg" :class="getScoreClass(results.score_pourcentage)">
          <span v-if="results.score_pourcentage >= 80">🎉 Excellent travail !</span>
          <span v-else-if="results.score_pourcentage >= 60">👍 Bon résultat, continuez ainsi !</span>
          <span v-else>📚 Revoyez les fondamentaux pour progresser.</span>
        </div>

        <div class="eq-result-congrats">Merci pour votre participation. À bientôt !</div>

        <button class="eq-btn eq-btn--outline eq-btn--full" @click="goBack">← Retour à l'accueil</button>
      </div>
    </div>

    <!-- ══════════════ MODAL CONFIRMATION ══════════════ -->
    <div v-if="showConfirmModal" class="eq-modal-backdrop" @click.self="showConfirmModal = false">
      <div class="eq-modal eq-modal--confirm">
        <div class="eq-modal__icon">⚡</div>
        <h3 class="eq-modal__title">Terminer l'évaluation ?</h3>
        <p class="eq-modal__body">
          {{ unansweredCount > 0 ? `${unansweredCount} question(s) sans réponse. ` : '' }}Cette action est irréversible.
        </p>
        <div class="eq-modal__actions">
          <button class="eq-btn eq-btn--ghost" @click="showConfirmModal = false">Annuler</button>
          <button class="eq-btn eq-btn--primary" :disabled="submitting" @click="submitAnswers">
            {{ submitting ? 'Soumission…' : 'Confirmer' }}
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
    matricule: { type: String, required: true }
  },
  data() {
    return {
      evaluationId: null,
      showQRScanner: true,
      scanningQR: false,
      qrError: '',
      scannedMatricule: '',
      stream: null,
      showManualInput: false,
      manualMatricule: '',
      localMatricule: this.matricule,
      showSeminaristInfo: false,
      seminaristeInfo: null,
      loadingSeminariste: false,
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
      resumed: false,
      saveTick: 0,
    }
  },
  watch: {
    // Toute réponse cochée est immédiatement persistée
    answers: {
      deep: true,
      handler() { this.saveSessionState(); }
    }
  },
  computed: {
    scoreCircle() {
      const pct = this.results.score_pourcentage || 0;
      return Math.round((pct / 100) * 314);
    },
    unansweredCount() {
      return Object.values(this.answers).filter(a => a === null).length;
    }
  },
  mounted() {
    this.getEvaluationIdFromUrl();
    window.addEventListener('beforeunload', this.handlePageUnload);
    document.addEventListener('visibilitychange', this.handleVisibilityChange);
  },
  beforeUnmount() {
    // On sauvegarde avant de quitter pour pouvoir reprendre la session
    this.saveSessionState();
    if (this.timer) clearInterval(this.timer);
    if (this.qrDetectionInterval) clearInterval(this.qrDetectionInterval);
    this.stopQRScanner();
    window.removeEventListener('beforeunload', this.handlePageUnload);
    document.removeEventListener('visibilitychange', this.handleVisibilityChange);
  },
  methods: {
    // ─── Persistance de session ──────────────────────────────────────────
    // La copie en cours est conservée localement : si le séminariste ferme
    // la page ou perd la connexion, il retrouve ses réponses, la question
    // courante et le temps qu'il lui restait.
    sessionStorageKey() {
      const mat = this.localMatricule || this.scannedMatricule || 'anonyme';
      return `senafoi_eval_${this.evaluationId}_${mat}`;
    },
    saveSessionState() {
      if (!this.sessionStarted || this.showResults || !this.evaluationId) return;
      try {
        localStorage.setItem(this.sessionStorageKey(), JSON.stringify({
          sessionId: this.sessionId,
          matricule: this.localMatricule,
          answers: this.answers,
          currentQuestionIndex: this.currentQuestionIndex,
          timeRemaining: this.timeRemaining,
          savedAt: Date.now(),
        }));
      } catch (e) {
        // quota depasse ou mode prive : la session continue normalement
      }
    },
    loadSessionState() {
      try {
        const raw = localStorage.getItem(this.sessionStorageKey());
        return raw ? JSON.parse(raw) : null;
      } catch (e) {
        return null;
      }
    },
    clearSessionState() {
      try { localStorage.removeItem(this.sessionStorageKey()); } catch (e) { /* ignore */ }
    },
    // Restaure une copie en cours si elle existe (apres rechargement/reconnexion)
    resumeSessionIfAny() {
      const saved = this.loadSessionState();
      if (!saved || !saved.sessionId) return false;
      if (typeof saved.timeRemaining !== 'number' || saved.timeRemaining <= 0) {
        this.clearSessionState();
        return false;
      }

      this.sessionId = saved.sessionId;
      this.currentQuestionIndex = Math.min(saved.currentQuestionIndex || 0, Math.max(0, this.questions.length - 1));

      // On reprend les reponses deja donnees, en gardant la structure courante
      const restored = {};
      for (let i = 0; i < this.questions.length; i++) {
        const val = saved.answers ? saved.answers[i] : null;
        restored[i] = (val === undefined ? null : val);
      }
      this.answers = restored;

      this.sessionStarted = true;
      this.resumed = true;
      this.startTimer(saved.timeRemaining);
      return true;
    },
    handleVisibilityChange() {
      // Sauvegarde des que l'onglet passe en arriere-plan
      if (document.hidden) this.saveSessionState();
    },
    handlePageUnload(event) {
      // On ne soumet plus automatiquement : la copie est sauvegardee
      // localement pour permettre la reprise.
      if (this.sessionStarted && !this.showResults) {
        this.saveSessionState();
        event.preventDefault();
        event.returnValue = '';
      }
    },
    getInitials() {
      if (!this.seminaristeInfo) return '?';
      const n = (this.seminaristeInfo.prenom || '').charAt(0);
      const m = (this.seminaristeInfo.nom || '').charAt(0);
      return (n + m).toUpperCase();
    },
    getEvaluationIdFromUrl() {
      if (this.$route?.params?.id) { this.evaluationId = this.$route.params.id; return; }
      if (this.$route?.query?.id) { this.evaluationId = this.$route.query.id; return; }
      const parts = window.location.pathname.split('/');
      if (parts.length >= 3 && parts[1] === 'evaluation_question') { this.evaluationId = parts[2]; return; }
      this.evaluationId = new URLSearchParams(window.location.search).get('id');
      if (!this.evaluationId) this.showError('Aucun ID d\'évaluation trouvé dans l\'URL');
    },
    async checkAccess() {
      this.loading = true;
      try {
        const res = await fetch('https://api.aeemci-ce.ci/senafoi/evaluations-api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'check_access', evaluation_id: this.evaluationId, matricule: this.localMatricule })
        });
        const data = await res.json();
        if (data.success) {
          this.hasAccess = data.has_access;
          this.alreadyCompleted = data.already_completed;
          this.evaluation = data.evaluation;
          if (this.hasAccess && !this.alreadyCompleted) await this.loadQuestions();
        } else {
          this.showError('Erreur vérification: ' + data.error);
        }
      } catch (e) {
        this.showError('Erreur de connexion: ' + e.message);
      } finally {
        this.loading = false;
      }
    },
    async loadQuestions() {
      try {
        const res = await fetch('https://api.aeemci-ce.ci/senafoi/evaluations-api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'questions', evaluation_id: this.evaluationId })
        });
        const data = await res.json();
        if (data.success) {
          this.questions = data.data;
          this.initializeAnswers();
          // Reprise automatique d'une copie interrompue
          this.resumeSessionIfAny();
        }
        else this.showError('Erreur questions: ' + data.error);
      } catch (e) {
        this.showError('Erreur de connexion: ' + e.message);
      }
    },
    initializeAnswers() {
      const a = {};
      for (let i = 0; i < this.questions.length; i++) a[i] = null;
      this.answers = a;
    },
    async startSession() {
      this.starting = true;
      try {
        const res = await fetch('https://api.aeemci-ce.ci/senafoi/evaluation-session.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'start_session', evaluation_id: this.evaluationId, matricule: this.localMatricule })
        });
        const data = await res.json();
        if (data.success) {
          this.sessionId = data.session_id;
          this.sessionStarted = true;
          this.startTimer();
          this.saveSessionState();
        }
        else this.showError('Erreur démarrage: ' + data.error);
      } catch (e) {
        this.showError('Erreur de connexion: ' + e.message);
      } finally {
        this.starting = false;
      }
    },
    startTimer(secondesRestantes = null) {
      this.timeRemaining = secondesRestantes !== null
        ? secondesRestantes
        : this.evaluation.duree * 60;
      if (this.timer) clearInterval(this.timer);
      this.timer = setInterval(() => {
        this.timeRemaining--;
        // Sauvegarde reguliere (toutes les 3s) pour limiter les ecritures
        if (++this.saveTick % 3 === 0) this.saveSessionState();
        if (this.timeRemaining <= 0) this.timeUp();
      }, 1000);
    },
    timeUp() {
      clearInterval(this.timer);
      alert('Temps écoulé ! Soumission automatique.');
      setTimeout(() => this.submitAnswers(), 1500);
    },
    scrollToTop() { window.scrollTo({ top: 0, behavior: 'smooth' }); },
    nextQuestion() { if (this.currentQuestionIndex < this.questions.length - 1) { this.currentQuestionIndex++; this.scrollToTop(); } },
    previousQuestion() { if (this.currentQuestionIndex > 0) { this.currentQuestionIndex--; this.scrollToTop(); } },
    goToQuestion(i) { this.currentQuestionIndex = i; this.scrollToTop(); this.saveSessionState(); },
    confirmSubmit() { this.showConfirmModal = true; },
    async submitAnswers() {
      this.showConfirmModal = false;
      this.submitting = true;
      if (this.timer) clearInterval(this.timer);
      const tempsEcoule = (this.evaluation.duree * 60) - this.timeRemaining;
      try {
        const res = await fetch('https://api.aeemci-ce.ci/senafoi/evaluation-session.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'submit_answers', evaluation_id: this.evaluationId, matricule: this.localMatricule, reponses: Object.values(this.answers), temps_ecoule: tempsEcoule })
        });
        const data = await res.json();
        if (data.success) {
          this.results = data.resultats;
          this.showResults = true;
          // Copie terminee : on efface la sauvegarde locale
          this.clearSessionState();
        }
        else this.showError('Erreur soumission: ' + data.error);
      } catch (e) {
        this.showError('Erreur de connexion: ' + e.message);
      } finally {
        this.submitting = false;
      }
    },
    formatTimeRemaining(s) {
      return `${Math.floor(s/60).toString().padStart(2,'0')}:${(s%60).toString().padStart(2,'0')}`;
    },
    formatTime(s) {
      if (!s) return '—';
      const m = Math.floor(s / 60), sec = s % 60;
      return m > 0 ? `${m}m ${sec}s` : `${sec}s`;
    },
    getScoreClass(pct) {
      if (pct >= 80) return 'score--high';
      if (pct >= 60) return 'score--mid';
      return 'score--low';
    },
    getScoreRingClass(pct) {
      if (pct >= 80) return 'eq-score-ring__fill--high';
      if (pct >= 60) return 'eq-score-ring__fill--mid';
      return 'eq-score-ring__fill--low';
    },
    goBack() { window.history.back(); },
    showError(msg) { alert('⚠ ' + msg); },
    async startQRScanner() {
      try {
        this.qrError = '';
        this.scanningQR = true;
        this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } } });
        this.$refs.qrVideo.srcObject = this.stream;
        await this.$refs.qrVideo.play();
        this.startQRDetection();
      } catch (e) {
        this.qrError = 'Impossible d\'accéder à la caméra : ' + e.message;
        this.scanningQR = false;
      }
    },
    stopQRScanner() {
      if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null; }
      this.scanningQR = false;
      if (this.qrDetectionInterval) clearInterval(this.qrDetectionInterval);
    },
    startQRDetection() {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      this.qrDetectionInterval = setInterval(() => {
        const v = this.$refs.qrVideo;
        if (v && v.videoWidth > 0) {
          canvas.width = v.videoWidth; canvas.height = v.videoHeight;
          ctx.drawImage(v, 0, 0);
          // Intégrer jsQR ici pour la production
        }
      }, 500);
    },
    async proceedWithMatricule() {
      if (!this.scannedMatricule) return;
      this.loadingSeminariste = true;
      this.qrError = '';
      try {
        const res = await fetch('https://api.aeemci-ce.ci/senafoi/evaluations-api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'get_seminariste_info', matricule: this.scannedMatricule })
        });
        const data = await res.json();
        if (data.success && data.seminariste) {
          this.seminaristeInfo = data.seminariste;
          this.localMatricule = this.scannedMatricule;
          this.showQRScanner = false;
          this.showSeminaristInfo = true;
        } else {
          this.qrError = 'Matricule introuvable. Veuillez réessayer.';
          this.scannedMatricule = '';
        }
      } catch (e) {
        this.qrError = 'Erreur de recherche : ' + e.message;
        this.scannedMatricule = '';
      } finally {
        this.loadingSeminariste = false;
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
      this.loadingSeminariste = true;
      this.showSeminaristInfo = false;
      try { await this.checkAccess(); }
      catch (e) { this.showError('Erreur: ' + e.message); }
      finally { this.loadingSeminariste = false; }
    },
  }
}
</script>

<style scoped>
/* ─────────────────────────────────────────────
   RESET & ROOT
───────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* Bandeau de reprise de session */
.eq-resume-banner {
  display: flex; align-items: center; gap: 12px;
  margin: 12px auto 0; max-width: 760px; width: calc(100% - 32px);
  padding: 12px 14px; border-radius: 0;
  background: var(--green-mist); border: 1px solid var(--green-mist); color: var(--green);
}
.eq-resume-banner__icon { font-size: 20px; font-weight: 900; }
.eq-resume-banner strong { display: block; font-size: 14px; }
.eq-resume-banner p { margin: 2px 0 0; font-size: 12.5px; opacity: .85; }
.eq-resume-banner__close {
  margin-left: auto; border: 0; background: transparent; color: var(--green);
  font-size: 15px; cursor: pointer; padding: 4px 8px; border-radius: 0;
}
.eq-resume-banner__close:hover { background: rgba(6,95,70,.1); }

.eq-root {
  --c-ink: #0f1117;
  --c-ink-soft: #3a3d4a;
  --c-muted: #8a8fa8;
  --c-line: #e4e6f0;
  --c-bg: #f7f8fc;
  --c-surface: #ffffff;
  --c-accent: #1a3cff;
  --c-accent-light: #e8ecff;
  --c-accent-dark: #0f25c4;
  --c-success: #0e9f6e;
  --c-success-light: var(--green-mist);
  --c-warn: var(--gold);
  --c-warn-light: var(--gold-mist);
  --c-danger: #dc2626;
  --c-danger-light: #fee2e2;
  --c-gold: var(--gold);
  --c-gold-light: var(--gold-mist);
  --r: 14px;
  --r-sm: 8px;
  --r-xl: 24px;
  --shadow: 0 2px 16px rgba(15,17,35,0.08);
  --shadow-lg: 0 8px 40px rgba(15,17,35,0.14);
  font-family: var(--display);
  background: var(--c-bg);
  color: var(--c-ink);
  min-height: 100dvh;
  -webkit-font-smoothing: antialiased;
}

/* ─────────────────────────────────────────────
   BRAND
───────────────────────────────────────────── */
.eq-brand {
  display: flex; align-items: center; gap: 8px;
  justify-content: center;
  margin-bottom: 32px;
}
.eq-brand__icon {
  font-size: 22px; color: var(--c-accent);
  line-height: 1;
}
.eq-brand__label {
  font-size: 13px; font-weight: 700; letter-spacing: 0.18em;
  color: var(--c-ink-soft); text-transform: uppercase;
}

/* ─────────────────────────────────────────────
   SCREENS (wrapper générique)
───────────────────────────────────────────── */
.eq-screen {
  min-height: 100dvh;
  display: flex; flex-direction: column;
  background: var(--c-bg);
}
.eq-screen--center {
  align-items: center; justify-content: center;
  text-align: center; padding: 40px 24px; gap: 16px;
}
.eq-screen--results, .eq-screen--prestart, .eq-screen--info {
  overflow-y: auto;
}

/* ─────────────────────────────────────────────
   BUTTONS
───────────────────────────────────────────── */
.eq-btn {
  display: inline-flex; align-items: center; justify-content: center;
  gap: 8px; border: none; border-radius: var(--r-sm);
  padding: 12px 22px; font-size: 15px; font-weight: 600;
  cursor: pointer; transition: all 0.18s; line-height: 1;
  text-decoration: none;
}
.eq-btn:disabled { opacity: 0.45; cursor: not-allowed; }

.eq-btn--primary {
  background: var(--c-accent); color: #fff;
}
.eq-btn--primary:hover:not(:disabled) { background: var(--c-accent-dark); transform: translateY(-1px); }

.eq-btn--outline {
  background: transparent; color: var(--c-accent);
  border: 2px solid var(--c-accent);
}
.eq-btn--outline:hover:not(:disabled) { background: var(--c-accent-light); }

.eq-btn--ghost {
  background: transparent; color: var(--c-ink-soft);
  border: 1.5px solid var(--c-line);
}
.eq-btn--ghost:hover:not(:disabled) { background: var(--c-line); }

.eq-btn--danger {
  background: var(--c-danger); color: #fff;
}
.eq-btn--sm { padding: 8px 14px; font-size: 13px; }
.eq-btn--lg { padding: 16px 28px; font-size: 16px; }
.eq-btn--full { width: 100%; }

/* ─────────────────────────────────────────────
   INPUT
───────────────────────────────────────────── */
.eq-input {
  width: 100%; padding: 14px 16px;
  border: 2px solid var(--c-line); border-radius: var(--r-sm);
  font-size: 16px; font-weight: 600; letter-spacing: 0.06em;
  color: var(--c-ink); background: var(--c-bg);
  transition: border-color 0.18s; outline: none;
}
.eq-input:focus { border-color: var(--c-accent); }

/* ─────────────────────────────────────────────
   QR SCANNER SCREEN
───────────────────────────────────────────── */
.eq-screen--scanner {
  padding: 0;
}
.eq-scanner-wrap {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; padding: 48px 24px 32px;
  max-width: 480px; margin: 0 auto; width: 100%;
}
.eq-scanner__title {
  font-size: 28px; font-weight: 700; color: var(--c-ink);
  margin-bottom: 8px; text-align: center;
}
.eq-scanner__sub {
  font-size: 14px; color: var(--c-muted); text-align: center;
  margin-bottom: 28px; line-height: 1.6;
}

.eq-video-frame {
  position: relative; width: 100%; max-width: 320px;
  aspect-ratio: 1; margin-bottom: 24px;
  border-radius: var(--r); overflow: hidden;
  background: #0f1117;
}
.eq-video { width: 100%; height: 100%; object-fit: cover; display: block; }

.eq-scan-overlay {
  position: absolute; inset: 0; pointer-events: none;
}
.eq-scan-corner {
  position: absolute; width: 24px; height: 24px;
  border-color: var(--c-accent); border-style: solid; border-width: 0;
}
.eq-scan-corner.tl { top: 12px; left: 12px; border-top-width: 3px; border-left-width: 3px; border-radius: 0; }
.eq-scan-corner.tr { top: 12px; right: 12px; border-top-width: 3px; border-right-width: 3px; border-radius: 0; }
.eq-scan-corner.bl { bottom: 12px; left: 12px; border-bottom-width: 3px; border-left-width: 3px; border-radius: 0; }
.eq-scan-corner.br { bottom: 12px; right: 12px; border-bottom-width: 3px; border-right-width: 3px; border-radius: 0; }

.eq-scan-line {
  position: absolute; left: 12px; right: 12px; height: 2px;
  background: linear-gradient(90deg, transparent, var(--c-accent), transparent);
  top: 12px;
}
.eq-scan-line.active {
  animation: scanMove 2s ease-in-out infinite;
}
@keyframes scanMove {
  0%, 100% { top: 12px; }
  50% { top: calc(100% - 14px); }
}

.eq-scanner-actions {
  display: flex; flex-direction: column; gap: 12px; width: 100%; margin-top: 8px;
}

/* ─────────────────────────────────────────────
   ALERTS
───────────────────────────────────────────── */
.eq-alert {
  width: 100%; padding: 12px 16px; border-radius: var(--r-sm);
  font-size: 14px; font-weight: 500; margin-bottom: 16px;
  display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.eq-alert--danger { background: var(--c-danger-light); color: var(--c-danger); }
.eq-alert--success { background: var(--c-success-light); color: var(--c-success); }
.eq-alert__icon { font-style: normal; font-weight: 700; font-size: 16px; }

/* ─────────────────────────────────────────────
   MODAL
───────────────────────────────────────────── */
.eq-modal-backdrop {
  position: fixed; inset: 0; background: rgba(15,17,35,0.55);
  display: flex; align-items: flex-end;
  z-index: 9999;
  backdrop-filter: blur(4px);
}
@media (min-width: 480px) {
  .eq-modal-backdrop { align-items: center; justify-content: center; }
}
.eq-modal {
  background: var(--c-surface); border-radius: var(--r-xl) var(--r-xl) 0 0;
  padding: 28px 24px 36px; width: 100%; max-width: 420px;
  box-shadow: var(--shadow-lg);
  display: flex; flex-direction: column; gap: 16px;
}
@media (min-width: 480px) {
  .eq-modal { border-radius: var(--r-xl); }
}
.eq-modal__header {
  display: flex; align-items: center; justify-content: space-between;
}
.eq-modal__header h3 { font-size: 18px; font-weight: 700; color: var(--c-ink); }
.eq-modal__close {
  background: none; border: none; cursor: pointer;
  font-size: 20px; color: var(--c-muted); padding: 4px;
  line-height: 1;
}
.eq-modal__actions { display: flex; gap: 12px; justify-content: flex-end; }

.eq-modal--confirm { text-align: center; border-radius: var(--r-xl) var(--r-xl) 0 0; }
@media (min-width: 480px) { .eq-modal--confirm { border-radius: var(--r-xl); } }
.eq-modal__icon { font-size: 40px; }
.eq-modal__title { font-size: 20px; font-weight: 700; color: var(--c-ink); }
.eq-modal__body { font-size: 14px; color: var(--c-ink-soft); line-height: 1.6; }
.eq-modal--confirm .eq-modal__actions { justify-content: center; }

/* ─────────────────────────────────────────────
   SÉMINARISTE INFO
───────────────────────────────────────────── */
.eq-info-wrap {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; padding: 48px 24px 40px;
  max-width: 480px; margin: 0 auto; width: 100%;
}
.eq-info__title {
  font-size: 26px; font-weight: 700; color: var(--c-ink);
  margin-bottom: 28px; text-align: center;
}
.eq-profile-card {
  width: 100%; background: var(--c-surface);
  border-radius: var(--r); padding: 28px 24px;
  box-shadow: var(--shadow); text-align: center;
  margin-bottom: 28px;
}
.eq-avatar {
  width: 88px; height: 88px; border-radius: 50%;
  overflow: hidden; margin: 0 auto 16px;
  background: var(--c-accent-light);
  display: flex; align-items: center; justify-content: center;
  border: 3px solid var(--c-accent);
}
.eq-avatar__img { width: 100%; height: 100%; object-fit: cover; }
.eq-avatar__initials {
  font-size: 28px; font-weight: 700; color: var(--c-accent); line-height: 1;
}
.eq-profile-name { font-size: 20px; font-weight: 700; color: var(--c-ink); margin-bottom: 4px; }
.eq-profile-matricule {
  font-size: 13px; color: var(--c-accent); font-weight: 700;
  letter-spacing: 0.12em; margin-bottom: 20px;
}
.eq-profile-details { text-align: left; border-top: 1px solid var(--c-line); padding-top: 16px; }
.eq-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--c-line); }
.eq-detail-row:last-child { border-bottom: none; }
.eq-detail-label { font-size: 13px; color: var(--c-muted); font-weight: 500; }
.eq-detail-value { font-size: 13px; color: var(--c-ink); font-weight: 600; }

.eq-info__confirm { font-size: 14px; color: var(--c-muted); margin-bottom: 20px; text-align: center; }
.eq-info-actions { display: flex; gap: 12px; width: 100%; }
.eq-info-actions .eq-btn { flex: 1; }

/* ─────────────────────────────────────────────
   STATUS SCREENS
───────────────────────────────────────────── */
.eq-status-icon {
  width: 64px; height: 64px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 28px; font-weight: 700;
}
.eq-status-icon--danger { background: var(--c-danger-light); color: var(--c-danger); }
.eq-status-icon--warn { background: var(--c-warn-light); color: var(--c-warn); }
.eq-status-title { font-size: 22px; font-weight: 700; color: var(--c-ink); }
.eq-status-sub { font-size: 14px; color: var(--c-muted); max-width: 280px; line-height: 1.6; }

/* ─────────────────────────────────────────────
   SPINNER
───────────────────────────────────────────── */
.eq-spinner {
  width: 44px; height: 44px; border-radius: 50%;
  border: 3px solid var(--c-line);
  border-top-color: var(--c-accent);
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.eq-loading-text { font-size: 15px; color: var(--c-muted); }

/* ─────────────────────────────────────────────
   PRÉ-DÉMARRAGE
───────────────────────────────────────────── */
.eq-prestart-wrap {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; padding: 48px 24px 48px;
  max-width: 480px; margin: 0 auto; width: 100%;
}
.eq-prestart-badge {
  font-size: 11px; font-weight: 800; letter-spacing: 0.22em;
  color: var(--c-accent); background: var(--c-accent-light);
  padding: 5px 14px; border-radius: 0; margin-bottom: 18px;
}
.eq-prestart__title {
  font-size: 26px; font-weight: 800; color: var(--c-ink);
  text-align: center; margin-bottom: 12px; line-height: 1.3;
}
.eq-prestart__desc {
  font-size: 14px; color: var(--c-muted); text-align: center;
  line-height: 1.7; margin-bottom: 28px;
}
.eq-stats-row { display: flex; gap: 16px; margin-bottom: 32px; }
.eq-stat-pill {
  flex: 1; background: var(--c-surface); border-radius: var(--r-sm);
  padding: 18px 16px; text-align: center; box-shadow: var(--shadow);
}
.eq-stat-pill__value { display: block; font-size: 28px; font-weight: 800; color: var(--c-accent); }
.eq-stat-pill__label { display: block; font-size: 12px; color: var(--c-muted); margin-top: 4px; font-weight: 500; }

.eq-rules {
  width: 100%; background: var(--c-surface);
  border-radius: var(--r-sm); padding: 20px;
  box-shadow: var(--shadow); margin-bottom: 32px;
}
.eq-rules__title { font-size: 13px; font-weight: 700; color: var(--c-muted); letter-spacing: 0.1em; margin-bottom: 12px; text-transform: uppercase; }
.eq-rules__list { list-style: none; padding: 0; }
.eq-rules__list li {
  font-size: 14px; color: var(--c-ink-soft); padding: 8px 0;
  border-bottom: 1px solid var(--c-line); line-height: 1.5;
  padding-left: 20px; position: relative;
}
.eq-rules__list li:last-child { border-bottom: none; }
.eq-rules__list li::before {
  content: '—'; position: absolute; left: 0;
  color: var(--c-accent); font-weight: 700;
}

/* ─────────────────────────────────────────────
   QUIZ INTERFACE
───────────────────────────────────────────── */
.eq-quiz {
  display: flex; flex-direction: column;
  min-height: 100dvh;
  background: var(--c-bg);
}

/* Header sticky */
.eq-quiz__header {
  position: sticky; top: 0; z-index: 100;
  background: var(--c-surface);
  padding: 14px 20px 12px;
  box-shadow: var(--shadow);
}
.eq-quiz__meta {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 10px;
}
.eq-quiz__eval-name {
  font-size: 13px; font-weight: 700; color: var(--c-ink-soft);
  max-width: 60%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.eq-timer {
  font-size: 16px; font-weight: 800; color: var(--c-success);
  background: var(--c-success-light); padding: 5px 12px;
  border-radius: 0; display: flex; align-items: center; gap: 6px;
  transition: all 0.3s;
}
.eq-timer--urgent { color: var(--c-danger); background: var(--c-danger-light); animation: pulse 1s ease-in-out infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.7} }
.eq-timer__icon { font-style: normal; }

.eq-progress-wrap { display: flex; align-items: center; gap: 10px; }
.eq-progress-track {
  flex: 1; height: 6px; background: var(--c-line);
  border-radius: 0; overflow: hidden;
}
.eq-progress-fill {
  height: 100%; background: var(--c-accent);
  border-radius: 0; transition: width 0.4s ease;
}
.eq-progress-label { font-size: 12px; color: var(--c-muted); font-weight: 600; white-space: nowrap; }

/* Contenu */
.eq-quiz__body {
  flex: 1; overflow-y: auto;
  padding: 24px 20px 140px; /* espace pour le footer fixe */
}
.eq-question-num {
  font-size: 11px; font-weight: 800; letter-spacing: 0.18em;
  color: var(--c-accent); text-transform: uppercase; margin-bottom: 12px;
}
.eq-question-text {
  font-size: 18px; font-weight: 700; color: var(--c-ink);
  line-height: 1.5; margin-bottom: 28px;
}

/* Options */
.eq-options { display: flex; flex-direction: column; gap: 12px; margin-bottom: 36px; }
.eq-option {
  display: flex; align-items: flex-start; gap: 14px;
  background: var(--c-surface); border: 2px solid var(--c-line);
  border-radius: var(--r-sm); padding: 16px;
  cursor: pointer; transition: all 0.18s; position: relative;
}
.eq-option:active { transform: scale(0.99); }
.eq-option--selected {
  border-color: var(--c-accent); background: var(--c-accent-light);
}
.eq-option__radio { display: none; }
.eq-option__letter {
  width: 32px; height: 32px; border-radius: 50%;
  background: var(--c-line); color: var(--c-muted);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; flex-shrink: 0;
  transition: all 0.18s;
}
.eq-option--selected .eq-option__letter {
  background: var(--c-accent); color: #fff;
}
.eq-option__text {
  flex: 1; font-size: 15px; color: var(--c-ink-soft);
  line-height: 1.55; padding-top: 5px;
  transition: color 0.18s;
}
.eq-option--selected .eq-option__text { color: var(--c-ink); font-weight: 500; }
.eq-option__check {
  opacity: 0; width: 22px; height: 22px; border-radius: 50%;
  background: var(--c-accent); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; flex-shrink: 0; align-self: center;
  transition: opacity 0.18s;
}
.eq-option--selected .eq-option__check { opacity: 1; }

/* Navigation rapide */
.eq-qnav { }
.eq-qnav__label {
  font-size: 11px; font-weight: 800; letter-spacing: 0.14em;
  color: var(--c-muted); text-transform: uppercase; margin-bottom: 12px;
}
.eq-qnav__grid {
  display: flex; flex-wrap: wrap; gap: 8px;
}
.eq-qnav__dot {
  width: 36px; height: 36px; border-radius: 0;
  border: 2px solid var(--c-line); background: var(--c-surface);
  font-size: 12px; font-weight: 700; color: var(--c-muted);
  cursor: pointer; transition: all 0.15s;
  display: flex; align-items: center; justify-content: center;
}
.eq-qnav__dot--current {
  border-color: var(--c-accent); background: var(--c-accent); color: #fff;
}
.eq-qnav__dot--answered {
  border-color: var(--c-success); background: var(--c-success-light); color: var(--c-success);
}

/* Footer FIXE */
.eq-quiz__footer {
  position: fixed; bottom: 0; left: 0; right: 0; z-index: 200;
  background: var(--c-surface);
  border-top: 1px solid var(--c-line);
  padding: 12px 20px;
  display: flex; gap: 12px; align-items: center;
  padding-bottom: max(12px, env(safe-area-inset-bottom));
}

.eq-nav-btn {
  flex: 1; padding: 15px 12px;
  border-radius: var(--r-sm); border: none;
  font-size: 15px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: all 0.18s;
}
.eq-nav-btn:disabled { opacity: 0.35; cursor: not-allowed; }
.eq-nav-btn--prev {
  background: var(--c-bg); color: var(--c-ink-soft);
  border: 2px solid var(--c-line); flex: 0 0 auto; padding: 15px 20px;
}
.eq-nav-btn--prev:hover:not(:disabled) { background: var(--c-line); }
.eq-nav-btn--next {
  background: var(--c-accent); color: #fff;
}
.eq-nav-btn--next:hover:not(:disabled) { background: var(--c-accent-dark); transform: translateY(-1px); }
.eq-nav-btn--finish {
  background: var(--c-success); color: #fff;
}
.eq-nav-btn--finish:hover:not(:disabled) { opacity: 0.9; transform: translateY(-1px); }

/* ─────────────────────────────────────────────
   RÉSULTATS
───────────────────────────────────────────── */
.eq-results-wrap {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; padding: 48px 24px 56px;
  max-width: 480px; margin: 0 auto; width: 100%;
  gap: 0;
}

/* Score ring */
.eq-score-ring-wrap {
  position: relative; width: 160px; height: 160px; margin-bottom: 28px;
}
.eq-score-ring {
  width: 160px; height: 160px; transform: rotate(-90deg);
}
.eq-score-ring__bg { fill: none; stroke: var(--c-line); stroke-width: 10; }
.eq-score-ring__fill {
  fill: none; stroke-width: 10; stroke-linecap: round;
  transition: stroke-dasharray 1s ease;
  stroke-dashoffset: 0;
}
.eq-score-ring__fill--high { stroke: var(--c-success); }
.eq-score-ring__fill--mid { stroke: var(--c-gold); }
.eq-score-ring__fill--low { stroke: var(--c-danger); }

.eq-score-center {
  position: absolute; inset: 0;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
}
.eq-score-pct {
  font-size: 36px; font-weight: 800; color: var(--c-ink); line-height: 1;
}
.eq-score-pct__sym { font-size: 20px; font-weight: 700; }
.eq-score-label { font-size: 12px; color: var(--c-muted); font-weight: 600; margin-top: 2px; }

/* Niveau badge */
.eq-niveau-badge {
  width: 100%; border-radius: var(--r); padding: 20px 24px;
  margin-bottom: 20px; text-align: center;
}
.eq-niveau-badge.score--high { background: var(--c-success-light); border: 2px solid var(--c-success); }
.eq-niveau-badge.score--mid  { background: var(--c-gold-light);    border: 2px solid var(--c-gold); }
.eq-niveau-badge.score--low  { background: var(--c-danger-light);  border: 2px solid var(--c-danger); }
.eq-niveau-badge__label {
  font-size: 11px; font-weight: 800; letter-spacing: 0.16em;
  text-transform: uppercase; margin-bottom: 8px;
}
.score--high .eq-niveau-badge__label { color: var(--c-success); }
.score--mid  .eq-niveau-badge__label { color: var(--c-warn); }
.score--low  .eq-niveau-badge__label { color: var(--c-danger); }
.eq-niveau-badge__value {
  font-size: 26px; font-weight: 800; color: var(--c-ink);
}
.eq-niveau-badge__note {
  margin-top: 8px; font-size: 12px; font-weight: 700;
  color: var(--c-success); line-height: 1.4;
}
.eq-niveau-badge__note--warn { color: var(--c-warn); font-weight: 600; }

/* Cards résultats */
.eq-result-cards {
  display: flex; gap: 12px; width: 100%; margin-bottom: 20px;
}
.eq-result-card {
  flex: 1; background: var(--c-surface);
  border-radius: var(--r-sm); padding: 16px 12px;
  text-align: center; box-shadow: var(--shadow);
}
.eq-result-card__value {
  font-size: 22px; font-weight: 800; color: var(--c-ink); margin-bottom: 4px;
}
.eq-result-card__label { font-size: 11px; color: var(--c-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }

/* Message & congrats */
.eq-result-msg {
  width: 100%; text-align: center; padding: 14px 20px;
  border-radius: var(--r-sm); font-size: 15px; font-weight: 600;
  margin-bottom: 16px;
}
.eq-result-msg.score--high { background: var(--c-success-light); color: var(--c-success); }
.eq-result-msg.score--mid  { background: var(--c-gold-light); color: var(--c-warn); }
.eq-result-msg.score--low  { background: var(--c-danger-light); color: var(--c-danger); }
.eq-result-congrats {
  font-size: 14px; color: var(--c-muted); text-align: center;
  margin-bottom: 28px; line-height: 1.6;
}

/* ─────────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────────── */
@media (min-width: 600px) {
  .eq-quiz__body { padding-bottom: 100px; }
  .eq-quiz__footer { max-width: 600px; left: 50%; transform: translateX(-50%); border-radius: var(--r) var(--r) 0 0; border: 1px solid var(--c-line); border-bottom: none; }
}
</style>